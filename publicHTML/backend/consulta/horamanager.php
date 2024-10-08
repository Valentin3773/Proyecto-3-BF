<?php

include('../conexion.php');
include('../extractor.php');

if($_SERVER['REQUEST_METHOD'] != 'POST') {

    header('Location: ../../index.php');
    exit();
}

$json = file_get_contents('php://input');

$data = json_decode($json, true);

if($data) {

    $ido = $data['odontologo'];

    $fecha = new DateTime($data['fecha']);
    $fecha = $fecha->format('Y-m-d');

    $sql = 'SELECT horainicio, horafinalizacion, dia FROM horario WHERE idodontologo = :ido ORDER BY dia ASC, horainicio ASC';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $ido);

    $horarios = array();

    if($stmt->execute() && $stmt->rowCount() > 0) while($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) $horarios[] = $tupla;

    $horasDisponibles = horasDisponibles($fecha, $ido);

    $respuesta = [

        'horasDisponibles' => $horasDisponibles,
        'horarios' => $horarios,
        'fecha' => $fecha
    ];

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

?>