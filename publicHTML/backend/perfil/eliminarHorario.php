<?php

include('../conexion.php');
include('../extractor.php');

session_start();
reloadSession();

if($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_SESSION['odontologo'])) exit();

$json = file_get_contents('php://input');

$data = json_decode($json, true);

$respuesta = array();

if($data) {

    $idh = $data["horario"];

    $sql = "SELECT * FROM horario WHERE idhorario = :idh";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idh', $idh);

    if($stmt->execute() && $stmt->rowCount() == 1) $horario = $stmt->fetch(PDO::FETCH_ASSOC);

    else {

        $respuesta["error"] = "Ha ocurrido un error al eliminar el horario";
        exit();
    }

    $ido = $_SESSION['odontologo']['idodontologo'];

    $sql1 = "DELETE FROM horario WHERE idhorario = :idh AND idodontologo = :ido";

    $stmt1 = $pdo->prepare($sql1);
    $stmt1->bindParam(':idh', $idh);
    $stmt1->bindParam(':ido', $ido);

    $sql2 = "DELETE FROM consulta WHERE idodontologo = :ido AND ((fecha > CURDATE()) OR (fecha = CURDATE() AND CURTIME() < hora)) AND DAYOFWEEK(fecha) = :dia AND ((hora BETWEEN :horainicio AND :horafinalizacion) OR (ADDTIME(hora, SEC_TO_TIME(duracion * 60)) BETWEEN :horainicio AND :horafinalizacion) OR (:horainicio BETWEEN hora AND ADDTIME(hora, SEC_TO_TIME(duracion * 60)) AND :horafinalizacion BETWEEN hora AND ADDTIME(hora, SEC_TO_TIME(duracion * 60))))";

    $dia = $horario['dia'] == 7 ? 0 : $horario['dia'] + 1;

    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':ido', $ido);
    $stmt2->bindParam(':dia', $dia);
    $stmt2->bindParam(':horainicio', $horario['horainicio']);
    $stmt2->bindParam(':horafinalizacion', $horario['horafinalizacion']);

    $sql3 = "SELECT p.email, c.asunto, c.fecha, c.hora FROM consulta c JOIN paciente p ON c.idpaciente = p.idpaciente WHERE idodontologo = :ido AND ((fecha > CURDATE()) OR (fecha = CURDATE() AND CURTIME() < hora)) AND DAYOFWEEK(fecha) = :dia AND ((hora BETWEEN :horainicio AND :horafinalizacion) OR (ADDTIME(hora, SEC_TO_TIME(duracion * 60)) BETWEEN :horainicio AND :horafinalizacion) OR (:horainicio BETWEEN hora AND ADDTIME(hora, SEC_TO_TIME(duracion * 60)) AND :horafinalizacion BETWEEN hora AND ADDTIME(hora, SEC_TO_TIME(duracion * 60))))";

    $stmt3 = $pdo->prepare($sql3);
    $stmt3->bindParam(':ido', $ido);
    $stmt3->bindParam(':dia', $dia);
    $stmt3->bindParam(':horainicio', $horario['horainicio']);
    $stmt3->bindParam(':horafinalizacion', $horario['horafinalizacion']);

    if($stmt1->execute() && $stmt3->execute() && $stmt2->execute() && $stmt3->rowCount() > 0) {
        
        $respuesta["exito"] = "Se ha eliminado el horario";

        $emailsaenviar = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        foreach($emailsaenviar as $emailaenviar) {
            
            $fechaenviar = DateTime::createFromFormat('Y-m-d', $emailaenviar['fecha']);
            $fechaenviar = $fechaenviar->format('d/m/Y');

            $horaenviar = DateTime::createFromFormat('H:i:s', $emailaenviar['hora']);
            $horaenviar = $horaenviar->format('H:i');

            enviarEmailCancelador($emailaenviar['email'], $emailaenviar['asunto'], $fechaenviar, $horaenviar);
        }
    }

    else $respuesta["error"] = "Ha ocurrido un error al eliminar el horario";
}

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>