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

function UpdateProfileOdontologo() {

    global $pdo;

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    $respuesta = array();

    $ido = $_SESSION['odontologo']['idodontologo'];

    $name = $data['name'];
    $value = $data['value'];
    $oldvalue = $data['oldvalue'];
    $nameROW = $data['name'];

    if($name == 'documento' || $name == 'contrasenia' || $name == 'verificador') return;

    try {

        $consulta = "UPDATE odontologo SET :namee = :val1 WHERE :namerow = :val2 and idodontologo = :ido";
        
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam('namee', $name);
        $stmt->bindParam(':namerow', $nameROW);
        $stmt->bindParam(':val1', $value);
        $stmt->bindParam(':val2', $oldvalue);
        $stmt->bindParam(':ido', $ido);

        if($stmt->execute()) {

            $respuesta['enviar'] = "Datos actualizados";
            reloadSession();
        }
        else $respuesta['error'] = "Ha ocurrido un error";
    } 
    catch (PDOException $e) {

        $respuesta['error'] = "Ha ocurrido un error: " . $e->getMessage();
    }
    
    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}
?>