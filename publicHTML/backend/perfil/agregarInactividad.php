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

    $sql2 = "SELECT p.email, c.asunto, c.fecha, c.hora FROM consulta c JOIN paciente p ON c.idpaciente = p.idpaciente WHERE idodontologo = :ido AND ((fecha > CURDATE()) OR (fecha = CURDATE() AND CURTIME() < hora)) AND ((CONCAT(fecha, ' ', hora) BETWEEN :tiempoinicio1 AND :tiempofinalizacion1) OR (ADDTIME(CONCAT(fecha, ' ', hora), SEC_TO_TIME(duracion * 60)) BETWEEN :tiempoinicio2 AND :tiempofinalizacion2) OR (:tiempoinicio3 BETWEEN CONCAT(fecha, ' ', hora) AND ADDTIME(CONCAT(fecha, ' ', hora), SEC_TO_TIME(duracion * 60)) AND :tiempofinalizacion3 BETWEEN CONCAT(fecha, ' ', hora) AND ADDTIME(CONCAT(fecha, ' ', hora), SEC_TO_TIME(duracion * 60))))";

    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':tiempoinicio1', $tiempoinicio);
    $stmt2->bindParam(':tiempoinicio2', $tiempoinicio);
    $stmt2->bindParam(':tiempoinicio3', $tiempoinicio);
    $stmt2->bindParam(':tiempofinalizacion1', $tiempofinalizacion);
    $stmt2->bindParam(':tiempofinalizacion2', $tiempofinalizacion);
    $stmt2->bindParam(':tiempofinalizacion3', $tiempofinalizacion);
    $stmt2->bindParam(':ido', $ido);

    if($stmt->execute() && $stmt2->execute() && $stmt2->rowCount() > 0) {

        $respuesta['exito'] = "Se ha agregado la inactividad";

        $emailsaenviar = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        foreach($emailsaenviar as $emailaenviar) archivarConsulta($emailaenviar['fecha'], $emailaenviar['hora'], $ido);

        foreach($emailsaenviar as $emailaenviar) {
            
            $fechaenviar = DateTime::createFromFormat('Y-m-d', $emailaenviar['fecha']);
            $fechaenviar = $fechaenviar->format('d/m/Y');

            $horaenviar = DateTime::createFromFormat('H:i:s', $emailaenviar['hora']);
            $horaenviar = $horaenviar->format('H:i');

            enviarEmailCancelador($emailaenviar['email'], $emailaenviar['asunto'], $fechaenviar, $horaenviar);
        }
    }
    else $respuesta['error'] = "Ha ocurrido un error al agregar la inactividad";
}
else $respuesta['error'] = "Ha ocurrido un error al agregar la inactividad, las fechas y/u horas no son válidas";

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>