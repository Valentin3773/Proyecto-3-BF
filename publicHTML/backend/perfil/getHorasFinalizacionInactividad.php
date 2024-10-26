<?php

include('../extractor.php');

session_start();
reloadSession();

if(!isset($_SESSION['odontologo']) || $_SERVER['REQUEST_METHOD'] != 'POST') exit();

$json = file_get_contents('php://input');

$data = json_decode($json, true);

if($data) {

    $fechainicio = DateTime::createFromFormat('d/m/Y', sanitizar($data['fechainicio']));
    $fechainicio = $fechainicio->format('Y-m-d');

    $horainicio = DateTime::createFromFormat('H:i', sanitizar($data['horainicio']));
    $horainicio = $horainicio->format('H:i:s');

    $fechafinalizacion = DateTime::createFromFormat('d/m/Y', sanitizar($data['fechafinalizacion']));
    $fechafinalizacion = $fechafinalizacion->format('Y-m-d');

    $ido = $_SESSION['odontologo']['idodontologo'];

    $horasDisponibles = [];

    foreach(getHorasFinalizacionInactividad($fechainicio, $horainicio, $fechafinalizacion, $ido) as $hora) {

        $horona = new DateTime($hora);
        $horasDisponibles[] = $horona->format('H:i');
    }

    $respuesta['horasDisponibles'] = $horasDisponibles;

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

?>