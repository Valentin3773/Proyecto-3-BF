<?php

include('../conexion.php');

session_start();

if($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_SESSION['odontologo'])) exit();

$json = file_get_contents('php://input');

$data = json_decode($json, true);

$respuesta = array();

if($data) {

    $idi = $data["inactividad"];

    $sql = "DELETE FROM inactividad WHERE idinactividad = :idi";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idi', $idi);

    if($stmt->execute()) $respuesta["exito"] = "Se ha eliminado la inactividad";

    else $respuesta["error"] = "Ha ocurrido un error al eliminar la inactividad";
}

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>