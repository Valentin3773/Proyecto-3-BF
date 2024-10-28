<?php
include_once("../extractor.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    duraciones();
} else {
    exit();
}

function duraciones() {
    $data = json_decode(file_get_contents('php://input'), true);
    $fecha = $data['fecha'];
    $hora= $data['hora'];
    $ido = $_SESSION['odontologo']['idodontologo'];

    $fechatiempoString = $fecha . ' ' . $hora;
    $fecha = DateTime::createFromFormat('d/m/Y H:i', $fechatiempoString);

    $conjDuraciones = duracionesDisponibles($fecha,$hora,$ido);
    echo json_encode($conjDuraciones);
}
?>
