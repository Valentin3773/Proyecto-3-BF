<?php

include('../conexion.php');
include('../extractor.php');

session_start();
reloadSession();

if(!isset($_SESSION['odontologo'])) exit();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {  

    header('Location: ../../index.php');
    exit();
}

$respuesta = array();

$ido = $_SESSION['odontologo']['idodontologo'];

$idp = $_POST["paciente"];
$fecha = DateTime::createFromFormat('d/m/Y', $_POST["fecha"]);
$hora = $_POST["hora"];
$duracion = intval($_POST["duracion"]);
$asunto = $_POST["asunto"];

if(fechaDisponible($fecha->format('Y-m-d'), $ido) && in_array($hora, horasDisponibles($fecha->format('Y-m-d'), $ido)) && in_array($duracion, duracionesDisponibles($fecha, $hora, $ido))) {

    if(strlen($asunto) >= 6) {

        $fecha = $fecha->format('Y-m-d');
        $hora = $hora . ':00';
        
        $sql = "INSERT INTO consulta (fecha, hora, idodontologo, idpaciente, duracion, asunto) VALUES (:fecha, :hora, :ido, :idp, :duracion, :asunto)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':ido', $ido);
        $stmt->bindParam(':idp', $idp);
        $stmt->bindParam(':duracion', $duracion);
        $stmt->bindParam(':asunto', $asunto);

        if($stmt->execute()) $respuesta["exito"] = "Se ha agendado la consulta, se ha enviado un email al paciente";

        else $respuesta["error"] = "Ha ocurrido un error al agendar la consulta";
    }

    else $respuesta["error"] = "Asunto no válido";
}
else $respuesta["error"] = "La fecha, hora y/o duración no son válidas";

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>