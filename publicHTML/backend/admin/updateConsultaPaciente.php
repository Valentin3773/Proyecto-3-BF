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
        echo "El campo 'Asunto' esta vacio. Por favor, ingresa un valor."; exit();
    } elseif (empty($hora) || strcasecmp($hora, "No hay horarios disponibles") === 0) {
        echo "Debes seleccionar una hora."; exit();
    } elseif (empty($duracion) || $duracion == 0) {
        echo "El campo 'Duracion' no puede ser 0 o vacio    . Por favor, ingresa un valor."; exit();
    } elseif (empty($fecha) || strcasecmp($fecha, "No hay fecha disponibles") === 0) {
        echo "Debes seleccionar una fecha."; exit();
    } elseif (empty($resumen)) {
        echo "El campo 'Resumen' está vacío. Por favor, ingrese algun contenido."; exit();
    } else {
            if($fecha == "Elija una fecha"){
                $fecha = $fechaV;
            }
            if($hora == "Elija una hora"){
                $hora = $horaV;
            }
        try {

            $consulta = 'UPDATE consulta
            SET fecha = :fecha,
                hora = :hora,
                asunto = :asunto,
                resumen = :resumen,
                duracion = :duracion
            WHERE idodontologo = :idodontologo
              AND hora = :horaV
              AND fecha = :fechaV';

            $stmt = $pdo->prepare($consulta);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora', $hora);
            $stmt->bindParam(':asunto', $asunto);
            $stmt->bindParam(':resumen', $resumen);
            $stmt->bindParam(':duracion', $duracion);
            $stmt->bindParam(':idodontologo', $ido);
            $stmt->bindParam(':fechaV', $fechaV);
            $stmt->bindParam(':horaV', $horaV);


            if ($stmt->execute()) {
                echo "Datos válidos. Procesamiento en curso... ";
            } else {
                echo "Error al ejecutar la consulta";
            }
        } catch (Throwable $th) {
            echo  "$th->getMessage()";
        }

    }
    exit();
}
?>