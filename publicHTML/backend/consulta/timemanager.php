<?php

include("../conexion.php");

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

$sql = "SELECT CURDATE() as fecha, DATE_ADD(CURDATE(), INTERVAL 90 DAY) as fecha_resultado";
$stmta = $pdo->prepare($sql);

if($stmta->execute()) {

    $tuplas = $stmta->fetchAll();

    foreach($tuplas as $tupla) {

        $fechas = getDatesFromRange($tupla['fecha'], $tupla['fecha_resultado']);

        foreach($fechas as $fecha) {

            echo $fecha.'<br>';
        }
    }
}

?>