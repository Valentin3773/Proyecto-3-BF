<?php

include('../conexion.php');
include('../extractor.php');

session_start();
reloadSession();

if(!isset($_SESSION['odontologo']) || $_SERVER['REQUEST_METHOD'] != 'POST') exit();

$data = json_decode(file_get_contents('php://input'), true);

if($data) {

    $respuesta = [];

    $numservicio = intval(sanitizar($data['numero']));
    
    $sql = "DELETE FROM servicio WHERE numero = :num";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':num', $numservicio);

    if($stmt->execute()) $respuesta['exito'] = "Se ha eliminado el servicio";

    else $respuesta['error'] = "Ha ocurrido un error al eliminar el servicio";

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}
else exit();

?>