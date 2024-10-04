<?php

include('extractor.php');

echo json_encode(getDefaultHours());

// $ido = 1;

// $fechaActual = getFechaActual();

// $fechaFinal = new DateTime(sumarFecha($fechaActual, 'mes', 5));
// $fechaFinal->modify('last day of this month');
// $fechaFinal = $fechaFinal->format('Y-m-d');

// $fechas = getDatesFromRange($fechaActual, $fechaFinal);

// $fechasDisponibles = array();

// foreach ($fechas as $fecha) if (fechaInicioInactividadDisponible($fecha, $ido)) $fechasDisponibles[] = $fecha;

// $respuesta = [

//     'fechaActual' => $fechaActual,
//     'fechasDisponibles' => $fechasDisponibles
// ];

// header('Content-Type: application/json');
// echo json_encode($respuesta);

?>