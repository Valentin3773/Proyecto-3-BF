<?php

include('../extractor.php');

session_start();
reloadSession();

if(!isset($_SESSION['odontologo']) || $_SERVER['REQUEST_METHOD'] != 'POST') exit();

$data = json_decode(file_get_contents('php://input'), true);

$respuesta = array();

if($data) {

    $fechainicio = DateTime::createFromFormat('d/m/Y', $data['fechainicio']);
    $fechainicio = $fechainicio->format('Y-m-d');

    $horainicio = DateTime::createFromFormat('H:i', $data['horainicio']);
    $horainicio = $horainicio->format('H:i:s');

    $ido = $_SESSION['odontologo']['idodontologo'];

    $fechaActual = getFechaActual();

    $fechaFinal = new DateTime(sumarFecha($fechaActual, 'mes', 5));
    $fechaFinal->modify('last day of this month');
    $fechaFinal = $fechaFinal->format('Y-m-d');

    $fechas = getDatesFromRange($fechaActual, $fechaFinal);

    $fechasDisponibles = array();

    foreach ($fechas as $fecha) {
        
        $fechona = new DateTime($fecha);
        if(fechaFinalizacionInactividadDisponible($fechainicio, $horainicio, $fecha, $ido)) $fechasDisponibles[] = $fechona->format('d-m-Y');
    }

    $respuesta = [

        'fechaActual' => $fechaActual,
        'fechasDisponibles' => $fechasDisponibles
    ];
}

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>