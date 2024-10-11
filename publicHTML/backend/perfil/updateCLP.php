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

    $datosModificables = ['nombre', 'apellido', 'email', 'telefono', 'direccion'];

    if(!in_array($name, $datosModificables) || !in_array($nameROW, $datosModificables)) return;

    try {
        
        $consulta = "UPDATE paciente SET {$name} = :val1 WHERE {$nameROW} = :val2 and idpaciente = :idp";
        
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam(':val1', $value);
        $stmt->bindParam(':val2', $oldvalue);
        $stmt->bindParam(':idp', $idp);

        if($stmt->execute()) {

            $respuesta['enviar'] = "Datos actualizados";
            reloadSession();
        }

        if($name == 'email') {

            $sql = "UPDATE paciente SET verificador = '' WHERE idpaciente = :idp";
            $stmt2 = $pdo->prepare($sql);
            $stmt2->bindParam(':idp', $idp);

            if($stmt2->execute()) reloadSession();
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
