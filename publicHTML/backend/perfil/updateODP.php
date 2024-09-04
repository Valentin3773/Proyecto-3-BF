<?php

include("../conexion.php");
include("../extractor.php");

session_start();

if(!isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) header('Location: index.php');

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['odontologo']) && !isset($_SESSION['paciente'])) UpdateProfileOdontologo($pdo);

else {

    echo "FUERA";
    header('Location: index.php');
    exit();
}

function UpdateProfileOdontologo($pdo) {

    // Se descodifica el objeto JSON para poder utilizar su contenido
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    $respuesta = array();

    // Id del paciente
    $ido = $_SESSION['odontologo']['idodontologo'];

    // Variables
    $name = $data['name'];
    $value = $data['value'];
    $oldvalue = $data['oldvalue'];
    $nameROW = $data['name'];

    try {

        // Consulta para modificar odontólogo
        $consulta = "UPDATE odontologo SET $name = :val1 WHERE $nameROW = :val2 and idodontologo = :ido";
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam(':val1', $value);
        $stmt->bindParam(':val2', $oldvalue);
        $stmt->bindParam(':ido', $ido);
        $stmt->execute();
        $respuesta['enviar'] = "Datos actualizados";
        reloadSession();
    } 
    catch (PDOException $e) {

        $respuesta['error'] = "Ha ocurrido un error: " . $e->getMessage();
    }
    
    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}
?>