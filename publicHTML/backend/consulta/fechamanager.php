<?php

include('../conexion.php');
include('../extractor.php');

if($_SERVER['REQUEST_METHOD'] != 'POST') {

    header('Location: ../../index.php');
    exit();
}

$json = file_get_contents('php://input');

$data = json_decode($json, true);

if($data) {

    $ido = $data['odontologo'];

    $fechaActual = getFechaActual();

    $fechaFinal = new DateTime(sumarFecha($fechaActual, 'mes', 3));
    $fechaFinal->modify('last day of this month');
    $fechaFinal = $fechaFinal->format('Y-m-d');

    $fechas = getDatesFromRange($fechaActual, $fechaFinal);

    $fechasDisponibles = array();

    foreach($fechas as $fecha) if(fechaDisponible($fecha, $ido)) $fechasDisponibles[] = $fecha;

    $respuesta = [

        'fechaActual' => $fechaActual,
        'fechasDisponibles' => $fechasDisponibles
    ];

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

?>