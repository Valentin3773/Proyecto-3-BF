<?php

include('../conexion.php');

session_start();

if($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_SESSION['odontologo'])) exit();

$json = file_get_contents('php://input');

$data = json_decode($json, true);

$respuesta = array();

if($data) {

    $idh = $data["horario"];

    $sql = "DELETE FROM horario WHERE idhorario = :idh";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idh', $idh);

    if($stmt->execute()) $respuesta["exito"] = "Se ha eliminado el horario";

    else $respuesta["error"] = "Ha ocurrido un error al eliminar el horario";
}

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>