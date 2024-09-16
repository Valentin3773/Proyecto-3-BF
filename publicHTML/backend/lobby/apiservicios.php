<?php

include('../conexion.php');

$sql = "SELECT * FROM servicio ORDER BY nombre ASC";

$stmt = $pdo->prepare($sql);

$servicios = array();

if($stmt->execute()) $servicios = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($servicios);
exit();

?>