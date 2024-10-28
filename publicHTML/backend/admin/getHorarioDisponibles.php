<?php
include_once("../extractor.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    horarios();
} else {
    exit();
}

function horarios() {
    $data = json_decode(file_get_contents('php://input'), true);
    $fecha = $data['fecha'];
    $ido = $_SESSION['odontologo']['idodontologo'];
    $conjHoras = horasDisponibles($fecha, $ido);
    echo json_encode($conjHoras);
}
?>
