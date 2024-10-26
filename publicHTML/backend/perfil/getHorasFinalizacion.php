<?php

include('../conexion.php');
include('../extractor.php');

session_start();

if($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_SESSION['odontologo'])) exit();

$data = json_decode(file_get_contents('php://input'), true);

$respuesta = array();

if($data) {

    $dia = intval(sanitizar($data['dia']));

    $ido = $_SESSION['odontologo']['idodontologo'];

    $horainicio = sanitizar($data['horainicio']);
    
    $respuesta['horasFinalizacion'] = getHorasFinalizacionHorario($dia, $horainicio, $ido);
}

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>