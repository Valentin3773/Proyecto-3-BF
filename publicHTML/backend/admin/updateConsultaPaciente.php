<?php

include("../conexion.php");
include("../extractor.php");

session_start();
reloadSession();

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['odontologo'])) updateConsulta();

else exit();

function updateConsulta()
{

    global $pdo;

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $asunto = isset($data['asunto']) ? sanitizar($data['asunto']) : null;
    $hora = isset($data['hora']) ? sanitizar($data['hora']) : null;
    $duracion = isset($data['duracion']) ? sanitizar($data['duracion']) : null;
    $fecha = isset($data['fecha']) ? sanitizar($data['fecha']) : null;
    $resumen = isset($data['resumen']) ? sanitizar($data['resumen']) : null;
    $ido = $_SESSION['odontologo']['idodontologo'];
    $fechaV = isset($data['fechaV']) ? sanitizar($data['fechaV']) : null;
    $horaV = isset($data['horaV']) ? sanitizar($data['horaV']) : null;

    $hora = formatDateTime($hora, 'H:i', 'H:i:s');
    $fecha = formatDateTime($hora, 'Y-m-d', 'd/m/Y');
    
    if (empty($asunto) || $asunto == null) {

        echo "El campo 'Asunto' esta vacio. Por favor, ingrese un valor";
        exit();
    } 
    else if (empty($hora) || strcasecmp($hora, "No hay horarios disponibles") === 0 || $hora == null) {

        echo "Debes seleccionar una hora";
        exit();
    } 
    else if (empty($duracion) || $duracion == 0 || $duracion == null) {

        echo "El campo 'Duracion' no puede estar vacio. Por favor, ingrese un valor";
        exit();
    } 
    else if (empty($fecha) || strcasecmp($fecha, "No hay fecha disponibles") === 0 || $fecha == null) {

        echo "Debes seleccionar una fecha";
        exit();
    }
    else {

        if ($fecha == "Elija una fecha") $fecha = $fechaV;
        
        if ($hora == "Elija una hora") $hora = $horaV;

        $fechadatetime = Datetime::createFromFormat('Y-m-d', $fecha);

        if(!(fechaDisponible($fecha, $ido) && in_array($hora, horasDisponibles($fecha, $ido)) && in_array($duracion, duracionesDisponibles($fechadatetime, $hora, $ido)))) {

            echo "Lo sentimos, la fecha, la hora o la duración no están disponibles";
            exit();
        }

        try {

            $consulta = 'UPDATE consulta SET fecha = :fecha, hora = :hora, asunto = :asunto, resumen = :resumen, duracion = :duracion WHERE idodontologo = :idodontologo AND hora = :horaV AND fecha = :fechaV';

            $stmt = $pdo->prepare($consulta);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora', $hora);
            $stmt->bindParam(':asunto', $asunto);
            $stmt->bindParam(':resumen', $resumen);
            $stmt->bindParam(':duracion', $duracion);
            $stmt->bindParam(':idodontologo', $ido);
            $stmt->bindParam(':fechaV', $fechaV);
            $stmt->bindParam(':horaV', $horaV);

            if ($stmt->execute()) echo "Se ha modificado la consulta";
            
            else echo "Ha ocurrido un error al modificar la consulta";
        } 
        catch (Throwable $th) {

            echo  "$th->getMessage()";
        }
        
    }
    exit();

}
