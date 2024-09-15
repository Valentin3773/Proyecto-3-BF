<?php

include('../conexion.php');
include('../extractor.php');

session_start();

if($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_SESSION['paciente'])) exit();

$json = file_get_contents('php://input');

$data = json_decode($json, true);

$respuesta = array();

if($data) {

    $nom = $data['nomolestar'];
    
    $idp = $_SESSION['paciente']['idpaciente'];

    $sql = "UPDATE paciente SET nomolestar = :nom WHERE idpaciente = :idp";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idp', $idp);
    $stmt->bindParam(':nom', $nom);

    if($stmt->execute()) {
        
        if($nom) $respuesta['exito'] = "Se actualizaron tus preferencias, ya no recibiras notificaciones";

        else $respuesta['exito'] = "Se actualizaron tus preferencias, ahora recibiras notificaciones";
    }
    else $respuesta['error'] = "Ha ocurrido un error al cambiar su configuración de privacidad";
}

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>