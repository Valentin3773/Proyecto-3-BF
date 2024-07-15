<?php

include('../conexion.php');
include('../extractor.php');

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

    echo json_encode($respuesta);
}

?>