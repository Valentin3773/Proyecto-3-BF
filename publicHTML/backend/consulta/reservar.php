<?php

include('../conexion.php');
include('../extractor.php');

session_start();

if($_SERVER['REQUEST_METHOD'] != 'POST') {

    header('Location: ../../index.php');
    exit();
}

$json = file_get_contents('php://input');

$data = json_decode($json, true);

$respuesta = array();

if($data && isset($_SESSION['paciente'])) {

    $ido = $data['odontologo'];
    $idp = $_SESSION['paciente']['idpaciente'];

    $fecha = new DateTime($data['fecha']);
    $fecha = $fecha->format('Y-m-d');

    $hora = new DateTime();
    $hora->setTime($data['hora']['hora'], $data['hora']['minuto']);

    $asunto = $data['asunto'];

    if(fechaDisponible($fecha, $ido) && in_array($hora->format('H:i'), horasDisponibles($fecha, $ido))) {

        $sql = 'INSERT INTO consulta (fecha, hora, idodontologo, idpaciente, asunto) VALUES (:fecha, :hora, :ido, :idp, :asunto)';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':fecha', $hora->format('H:i:s'));
        $stmt->bindParam(':ido', $ido);
        $stmt->bindParam(':idp', $idp); 
        $stmt->bindParam(':asunto', $asunto);

        if($stmt->execute()) $respuesta['exito'] = 'Su consulta se ha reservado correctamente';
    }
    else $respuesta['error'] = "Ha ocurrido un error, la fecha y hora no están disponibles";
}
else $respuesta['error'] = 'Ha ocurrido un error de sesión';

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>