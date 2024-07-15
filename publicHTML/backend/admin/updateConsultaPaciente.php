<?php
include("../conexion.php");
session_start();
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    updateConsulta($pdo);
}

function updateConsulta($pdo){

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $asunto = $data['asunto'];
    $hora = $data['hora'];
    $duracion = $data['duracion'];
    $fecha = $data['fecha'];
    $resumen = $data['resumen'];
    $ido = $_SESSION['odontologo']['idodontologo'];
    $fechaV = $data['fechaV'];
    $horaV = $data['horaV'];

    if (empty($asunto)) {
        $response['error'] = "El campo 'Asunto' esta vacio. Por favor, ingresa un valor.";
    } elseif (empty($hora) || strcasecmp($hora, "Elija un horario") === 0) {
        $response['error'] = "Debes seleccionar una hora.";
    } elseif (empty($duracion) || $duracion == 0) {
        $response['error'] = "El campo 'Duracion' no puede ser 0. Por favor, ingresa un valor.";
    } elseif (empty($fecha) || strcasecmp($fecha, "Elija una fecha") === 0) {
        $response['error'] = "Debes seleccionar una fecha.";
    } elseif (empty($resumen)) {
        $response['error'] = "El campo 'Resumen' está vacío. Por favor, ingrese algun contenido.";
    } else {

        try {

            $consulta = 'UPDATE consulta
            SET fecha = :fecha,
                hora = :hora,
                asunto = :asunto,
                resumen = :resumen
            WHERE idodontologo = :idodontologo
              AND hora = :horaV
              AND fecha = :fechaV';

            $stmt = $pdo->prepare($consulta);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora', $hora);
            $stmt->bindParam(':asunto', $asunto);
            $stmt->bindParam(':resumen', $resumen);
            $stmt->bindParam(':idodontologo', $ido);
            $stmt->bindParam(':fechaV', $fechaV);
            $stmt->bindParam(':horaV', $horaV);


            if ($stmt->execute()) {
                $response['enviar'] = "Datos válidos. Procesamiento en curso...";
            } else {
                $response['error'] = "Error al ejecutar la consulta";
            }
        } catch (Throwable $th) {
            $response['error'] = $th->getMessage()." | ".$asunto." | ".$hora." | ".$duracion." | ".$fecha." | ".$resumen." | ".$ido;
        }

    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>