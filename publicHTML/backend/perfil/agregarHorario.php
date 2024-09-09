<?php

include('../conexion.php');
include('../extractor.php');

session_start();

if($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_SESSION['odontologo'])) exit();

$json = file_get_contents('php://input');

$data = json_decode($json, true);

$respuesta = array();

if($data) {

    $ido = $_SESSION['odontologo']['idodontologo'];

    $dia = $data['dia'];
    $horainicio = $data['horainicio'];
    $horafinalizacion = $data['horafinalizacion'];

    if(in_array($horainicio, getHorasInicioHorario($dia, $ido)) && in_array($horafinalizacion, getHorasFinalizacionHorario($dia, $horainicio, $ido))) {

        $sql = "INSERT INTO horario (horainicio, horafinalizacion, dia, idodontologo) VALUES (:horainicio, :horafinalizacion, :dia, :ido)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':horainicio', $horainicio);
        $stmt->bindParam(':horafinalizacion', $horafinalizacion);
        $stmt->bindParam(':dia', $dia);
        $stmt->bindParam(':ido', $ido);

        if($stmt->execute()) $respuesta['exito'] = "Se agregó el horario";

        else $respuesta['error'] = "Algo falló al agregar el horario";
    }
    else $respuesta['error'] = "Las horas seleccionadas no son válidas";
}

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>