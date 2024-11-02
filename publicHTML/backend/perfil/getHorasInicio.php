<?php

include('../conexion.php');
include('../extractor.php');

session_start();
reloadSession();

if($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_SESSION['odontologo'])) exit();

$json = file_get_contents('php://input');

$data = json_decode($json, true);

$respuesta = array();

if($data) {

    $dia = intval(sanitizar($data['dia']));

    $ido = $_SESSION['odontologo']['idodontologo'];

    $respuesta['horasInicio'] = getHorasInicioHorario($dia, $ido);

    $respuesta['horasInicioComun'] = formatDateTimeArray(getHorasInicioHorario($dia, $ido), 'H:i:s', 'H:i');
}

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>