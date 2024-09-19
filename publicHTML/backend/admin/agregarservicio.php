<?php

include('../conexion.php');
include('../extractor.php');

session_start();

if($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_SESSION['odontologo'])) exit();

$json = file_get_contents('php://input');

$data = json_decode($json, true);

$respuesta = array();

if($data) {

    $nombre = $data["nombre"];
    $descripcion = $data["descripcion"];

    $sql = "INSERT INTO servicio (nombre, descripcion, icono, imagen) VALUES (:nombre, :descripcion, null, null)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);

    if($stmt->execute()) $respuesta["exito"] = "Se ha agregado el servicio y ahora es visible para los pacientes";

    else $respuesta["error"] = "Ha ocurrido un error al agregar el servicio";
}

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>