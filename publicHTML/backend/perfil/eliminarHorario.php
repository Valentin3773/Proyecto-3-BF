<?php

include('../conexion.php');
include('../extractor.php');

session_start();
reloadSession();

if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_SESSION['odontologo'])) exit();

$json = file_get_contents('php://input');

$data = json_decode($json, true);

$respuesta = array();

if ($data) {

    $idh = $data["horario"];

    $sql = "SELECT * FROM horario WHERE idhorario = :idh";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idh', $idh);

    if ($stmt->execute() && $stmt->rowCount() == 1) $horario = $stmt->fetch(PDO::FETCH_ASSOC);

    else {

        $respuesta["error"] = "Ha ocurrido un error al eliminar el horario";
        exit();
    }

    $ido = $_SESSION['odontologo']['idodontologo'];

    $sql1 = "DELETE FROM horario WHERE idhorario = :idh AND idodontologo = :ido";

    $stmt1 = $pdo->prepare($sql1);
    $stmt1->bindParam(':idh', $idh);
    $stmt1->bindParam(':ido', $ido);

    $dia = $horario['dia'] == 7 ? 0 : $horario['dia'] + 1;

    $sql2 = "SELECT p.email, c.asunto, c.fecha, c.hora FROM consulta c JOIN paciente p ON c.idpaciente = p.idpaciente WHERE idodontologo = :ido AND vigente = 'vigente' AND ((fecha > CURDATE()) OR (fecha = CURDATE() AND CURTIME() < hora)) AND DAYOFWEEK(fecha) = :dia AND ((hora BETWEEN :horainicio1 AND :horafinalizacion1) OR (ADDTIME(hora, SEC_TO_TIME(duracion * 60)) BETWEEN :horainicio2 AND :horafinalizacion2) OR (:horainicio3 BETWEEN hora AND ADDTIME(hora, SEC_TO_TIME(duracion * 60)) AND :horafinalizacion3 BETWEEN hora AND ADDTIME(hora, SEC_TO_TIME(duracion * 60))))";

    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':ido', $ido);
    $stmt2->bindParam(':dia', $dia);
    $stmt2->bindParam(':horainicio1', $horario['horainicio']);
    $stmt2->bindParam(':horainicio2', $horario['horainicio']);
    $stmt2->bindParam(':horainicio3', $horario['horainicio']);
    $stmt2->bindParam(':horafinalizacion1', $horario['horafinalizacion']);
    $stmt2->bindParam(':horafinalizacion2', $horario['horafinalizacion']);
    $stmt2->bindParam(':horafinalizacion3', $horario['horafinalizacion']);

    if ($stmt1->execute() && $stmt2->execute()) {

        $respuesta["exito"] = "{$ido} {$dia} {$horario['horainicio']} {$horario['horafinalizacion']}";

        if($stmt2->rowCount() > 0) {

            $consultasaarchivar = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            foreach ($consultasaarchivar as $consulta) archivarConsulta($consulta['fecha'], $consulta['hora'], $ido);

            foreach ($consultasaarchivar as $consulta) {

                $fechaenviar = DateTime::createFromFormat('Y-m-d', $consulta['fecha']);
                $fechaenviar = $fechaenviar->format('d/m/Y');

                $horaenviar = DateTime::createFromFormat('H:i:s', $consulta['hora']);
                $horaenviar = $horaenviar->format('H:i');

                enviarEmailCancelador($consulta['email'], $consulta['asunto'], $fechaenviar, $horaenviar);
            }
        }
    }
    else $respuesta["error"] = "Ha ocurrido un error al eliminar el horario";
}

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();
