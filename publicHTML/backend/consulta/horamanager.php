<?php

include('../conexion.php');
include('../extractor.php');

$json = file_get_contents('php://input');

$data = json_decode($json, true);

if($data) {

    $ido = $data['odontologo'];

    $fecha = $data['fecha'];

    $sql = 'SELECT horainicio, horafinalizacion, dia FROM horario h JOIN odontologo_horario oh ON h.idhorario = oh.idhorario WHERE oh.idodontologo = :ido ORDER BY dia ASC, horainicio ASC';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $ido);

    $horarios = array();

    if($stmt->execute() && $stmt->rowCount() > 0) {

        while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $horarios[] = $tupla;
        }
    }

    $horasDisponibles = horasDisponibles($fecha, $ido);

    $respuesta = [

        'horasDisponibles' => $horasDisponibles,
        'horarios' => $horarios
    ];

    echo json_encode($respuesta);
}

?>