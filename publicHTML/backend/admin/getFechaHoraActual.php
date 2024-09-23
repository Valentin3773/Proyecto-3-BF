<?php

include('../extractor.php');
include('../conexion.php');

session_start();

if(!isset($_SESSION['odontologo'])) exit();

$ido = $_SESSION['odontologo']['idodontologo'];

$sql = "SELECT fecha FROM consulta WHERE idodontologo = :ido";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':ido', $ido);

if($stmt->execute()) $fechasDisponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$respuesta = [

    "fechaActual" => getFechaActual(),
    "horaActual" => getHoraActual(),
    "fechasDisponibles" => array_values($fechasDisponibles)
];

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>