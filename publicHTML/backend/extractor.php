<?php

// Extractor es un modulo que incluye funciones que mejoran la calidad de vida de los programadores.

include('conexion.php');

function getDatesFromRange($fechainicio, $fechafin) {

    $fechas = [];
    $fechaactual = strtotime($fechainicio);
    $fechafin = strtotime($fechafin);

    while ($fechaactual <= $fechafin) {

        $fechas[] = date('Y-m-d', $fechaactual);
        $fechaactual = strtotime('+1 day', $fechaactual);
    }
    return $fechas;
}

function getFechaActual() {

    global $pdo;

    $sql = "SELECT CURDATE() as fecha";
    $resultado = $pdo->query($sql)->fetch();

    return $resultado['fecha'];
}

function getHoraActual() {

    global $pdo;

    $sql = "SELECT CURTIME() as hora";
    $resultado = $pdo->query($sql)->fetch();

    return $resultado['hora'];
}

?>