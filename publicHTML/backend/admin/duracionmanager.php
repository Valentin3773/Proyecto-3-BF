<?php

include('../extractor.php');

session_start();
reloadSession();

if(!isset($_SESSION['odontologo'])) exit();

if($_SERVER['REQUEST_METHOD'] != 'POST') {

    header('Location: ../../index.php');
    exit();
}

$json = file_get_contents('php://input');

$data = json_decode($json, true);

if($data) {

    $ido = $_SESSION['odontologo']['idodontologo'];

    $data['dia'] = sanitizar($data['dia']);
    $data['mes'] = sanitizar($data['mes']);
    $data['anio'] = sanitizar($data['anio']);

    $fecha = DateTime::createFromFormat('d-m-Y', "{$data["dia"]}-{$data["mes"]}-{$data["anio"]}");
    $hora = sanitizar($data["hora"]);

    $respuesta["duracionesDisponibles"] = duracionesDisponibles($fecha, $hora, $ido); 

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

?>