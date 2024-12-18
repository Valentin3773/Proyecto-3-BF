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

    $hora = formatDateTime($hora,'H:i', 'H:i:s');
    $horaV = formatDateTime($horaV,'H:i', 'H:i:s');
    
    if (empty($asunto) || $asunto == null) {

        echo "El campo 'Asunto' esta vacio. Por favor, ingresa un valor";
        exit();
    }
    else if(!preg_match("/^[a-zA-ZÀ-ÿ\s'’`´,.-]+$/u", $asunto)) echo "El formato del asunto no es válido";
    else if(strlen($asunto) > 55) echo "El asunto es demasiado largo";
    else if(strlen($asunto) <= 4) echo "El asunto es demasiado corto, debe tener al menos 5 caracteres";

    else if($resumen != null && $resumen != '' && !preg_match("/^[a-zA-ZÀ-ÿ\s'’`´,.¿?¡!-]+$/u", $resumen)) echo "El formato del resumen no es válido";
    else if($resumen != null && $resumen != '' && strlen($resumen) > 2500) echo "El resumen es demasiado largo";
    else if($resumen != null && $resumen != '' && strlen($resumen) <= 9) echo "El resumen es demasiado corto, debe tener al menos 10 caracteres";
    
    elseif (empty($hora) || strcasecmp($hora, "No hay horarios disponibles") === 0 || $hora == null) {

        echo "Debes seleccionar una hora.";
        exit();
    } 
    elseif (empty($duracion) || $duracion == 0 || $duracion == null) {

        echo "El campo 'Duración' no puede ser 0 o vacío. Por favor, ingresa un valor";
        exit();
    } 
    elseif (empty($fecha) || strcasecmp($fecha, "No hay fecha disponibles") === 0 || $fecha == null) {

        echo "Debes seleccionar una fecha.";
        exit();
    } 
    else {

        if ($fecha == "Elija una fecha") $fecha = $fechaV;
        if ($hora == "Elija una hora") $hora = $horaV;

        $fechadatetime = DateTime::createFromFormat('Y-m-d', $fecha);
        if(in_array(formatDateTime($hora, 'H:i:s', 'H:i'), horasDisponibles($fecha, $ido)) || $hora == $horaV){

            if (fechaDisponible($fecha, $ido) || $fecha == $fechaV) {

                if (duracionesDisponibles($fechadatetime, $hora, $ido)) {

                    $disponibles = duracionesDisponibles($fechadatetime, $hora, $ido);
                    $estado = false;
                    
                    foreach ($disponibles as $duracionD) {

                        if ($duracion == $duracionD) {

                            $estado = true;
                        }
                    }
                    
                    if ($estado) {

                        try {

                            $consulta = 'UPDATE consulta SET fecha = :fecha, hora = :hora, asunto = :asunto, resumen = :resumen, duracion = :duracion WHERE idodontologo = :idodontologo AND hora = :horaV AND fecha = :fechaV AND vigente = "vigente"';
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

                            echo $th->getMessage();
                        }
                    } 
                    else  echo "Uno de los datos no está disponible";
                } 
                else echo "No disponible";
            } 
            else echo "Fecha y/u hora no disponible";
        } 
        else echo "Horario no disponible";
    }
    exit();
}