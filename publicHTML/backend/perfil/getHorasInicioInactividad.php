<?php

include('../extractor.php');

session_start();
reloadSession();

if(!isset($_SESSION['odontologo']) || $_SERVER['REQUEST_METHOD'] != 'POST') exit();

$json = file_get_contents('php://input');

$data = json_decode($json, true);

if($data) {

    $data["dia"] = sanitizar($data["dia"]);
    $data["mes"] = sanitizar($data["mes"]);
    $data["anio"] = sanitizar($data["anio"]);

    $ido = $_SESSION['odontologo']['idodontologo'];

    $fecha = DateTime::createFromFormat('d-m-Y', "{$data["dia"]}-{$data["mes"]}-{$data["anio"]}");
    $fecha = $fecha->format('Y-m-d');

    $horasDisponibles = [];

    foreach(getHorasInicioInactividad($fecha, $ido) as $hora) {

        $horona = new DateTime($hora);
        $horasDisponibles[] = $horona->format('H:i');
    }

    $respuesta = [

        'horasDisponibles' => $horasDisponibles,
        'fecha' => $fecha
    ];

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

?>