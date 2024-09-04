<?php

include("../conexion.php");
include("../extractor.php");

session_start();

if(!isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) header('Location: index.php');

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) UpdateProfilePaciente();

else {

    echo "FUERA";
    exit();
}

function UpdateProfilePaciente() {

    global $pdo;

    // Se descodifica el objeto JSON para poder utilizar su contenido
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // ID del paciente
    $idp = $_SESSION['paciente']['idpaciente'];

    // Variables
    $name = $data['name'];
    $value = $data['value'];
    $oldvalue = $data['oldvalue'];
    $nameROW = $data['name'];

    try {
        
        // Consulta para modificar paciente
        $consulta = "UPDATE paciente SET $name = :val1 WHERE $nameROW = :val2 and idpaciente = :idp";
        
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam(':val1', $value);
        $stmt->bindParam(':val2', $oldvalue);
        $stmt->bindParam(':idp', $idp);

        if($stmt->execute()) {

            $respuesta['enviar'] = "Datos actualizados";
            reloadSession();
        }
    } 
    catch (PDOException $e) {
        
        $respuesta['error'] = "Ha ocurrido un error: " . $e->getMessage();
    }

    // Respuesta
    header('Content-Type: application/json');
    echo json_encode($respuesta);
}
?>
