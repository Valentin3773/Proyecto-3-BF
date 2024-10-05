<?php

include('../extractor.php');
include('../conexion.php');

session_start();
reloadSession();

if(!isset($_SESSION['odontologo']) || $_SERVER['REQUEST_METHOD'] != 'POST') exit();

$respuesta = [];

$ido = $_SESSION['odontologo']['idodontologo'];

$fechainicio = DateTime::createFromFormat('d/m/Y', $_POST['fechainicio']);
$fechainicio = $fechainicio->format('Y-m-d');

$horainicio = DateTime::createFromFormat('H:i', $_POST['horainicio']);
$horainicio = $horainicio->format('H:i:s');

$fechafinalizacion = DateTime::createFromFormat('d/m/Y', $_POST['fechafinalizacion']);
$fechafinalizacion = $fechafinalizacion->format('Y-m-d');

$horafinalizacion = DateTime::createFromFormat('H:i', $_POST['horafinalizacion']);
$horafinalizacion = $horafinalizacion->format('H:i:s');

if(fechaInicioInactividadDisponible($fechainicio, $ido) && 
in_array($horainicio, getHorasInicioInactividad($fechainicio, $ido)) && 
fechaFinalizacionInactividadDisponible($fechainicio, $horainicio, $fechafinalizacion, $ido) && 
in_array($horafinalizacion, getHorasFinalizacionInactividad($fechainicio, $horainicio, $fechafinalizacion, $ido))) {

    $tiempoinicio = new DateTime("{$fechainicio} {$horainicio}");
    $tiempoinicio = $tiempoinicio->format('Y-m-d H:i:s');
    
    $tiempofinalizacion = new DateTime("{$fechafinalizacion} {$horafinalizacion}");
    $tiempofinalizacion = $tiempofinalizacion->format('Y-m-d H:i:s');

    $sql = "INSERT INTO inactividad (tiempoinicio, tiempofinalizacion, idodontologo) VALUES (:tiempoinicio, :tiempofinalizacion, :ido)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tiempoinicio', $tiempoinicio);
    $stmt->bindParam(':tiempofinalizacion', $tiempofinalizacion);
    $stmt->bindParam(':ido', $ido);

    if($stmt->execute()) $respuesta['exito'] = "Se ha agregado la inactividad";

    else $respuesta['error'] = "Ha ocurrido un error al agregar la inactividad";
}
else $respuesta['error'] = "Ha ocurrido un error al agregar la inactividad, las fechas y/u horas no son válidas";

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>