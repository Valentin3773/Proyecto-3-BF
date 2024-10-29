<?php

include_once("../extractor.php");

session_start();
reloadSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST') duraciones();

else exit();

function duraciones() {

    $data = json_decode(file_get_contents('php://input'), true);
    $fecha = sanitizar($data['fecha']);
    $hora = sanitizar($data['hora']);
    $ido = $_SESSION['odontologo']['idodontologo'];

    error_log($fecha . ' ' . $hora);    

    $fechatiempoString = $fecha . ' ' . $hora;
    $fecha = DateTime::createFromFormat('d/m/Y H:i', $fechatiempoString);

    $conjDuraciones = duracionesDisponibles($fecha,$hora,$ido);
    echo json_encode($conjDuraciones);
}
?>
