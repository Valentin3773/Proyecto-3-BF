<?php

include_once("../extractor.php");

session_start();
reloadSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['odontologo'])) horarios();

else exit();

function horarios() {

    $data = json_decode(file_get_contents('php://input'), true);
    $fecha = sanitizar($data['fecha']);
    $ido = $_SESSION['odontologo']['idodontologo'];
    $conjHoras = horasDisponibles($fecha, $ido);
    error_log(json_encode($conjHoras));
    echo json_encode($conjHoras);
}

?>
