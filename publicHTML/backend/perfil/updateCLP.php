<?php

include("../conexion.php");
include("../extractor.php");

session_start();
reloadSession();

if(!isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) header('Location: index.php');

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) UpdateProfilePaciente();

else {

    echo "FUERA";
    exit();
}

function UpdateProfilePaciente() {

    global $pdo;

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $idp = $_SESSION['paciente']['idpaciente'];

    $name = $data['name'];
    $value = $data['value'];
    $oldvalue = $data['oldvalue'];
    $nameROW = $data['name'];

    if($name == 'documento' || $name == 'contrasenia' || $name == 'verificador') return;

    try {
        
        $consulta = "UPDATE paciente SET :namee = :val1 WHERE :namerow = :val2 and idpaciente = :idp";
        
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam(':namee', $name);
        $stmt->bindParam(':namerow', $nameROW);
        $stmt->bindParam(':val1', $value);
        $stmt->bindParam(':val2', $oldvalue);
        $stmt->bindParam(':idp', $idp);

        if($stmt->execute()) {

            $respuesta['enviar'] = "Datos actualizados";
            reloadSession();
        }

        if($name == 'email') {

            $consulta = "UPDATE paciente SET verificador = '' WHERE idpaciente = :idp";
            $stmt->bindParam(':idp', $idp);

            if($stmt->execute()) reloadSession();
        }
    } 
    catch (PDOException $e) {
        
        $respuesta['error'] = "Ha ocurrido un error: " . $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}
?>
