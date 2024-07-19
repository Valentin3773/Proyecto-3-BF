<?php

// Extractor es un modulo que incluye funciones que mejoran la calidad de vida de los programadores.

include('conexion.php');

function isDateAvailable($idodontologo, $fecha) {

    global $pdo;

    $dayOfWeek = date('N', strtotime($fecha)); // Día de la semana (1 = Lunes, 7 = Domingo)

    // 1) Obtener horarios

    $sql = "SELECT h.horainicio, h.horafinalizacion FROM horario h JOIN odontologo_horario oh ON h.idhorario = oh.idhorario WHERE oh.idodontologo = :ido AND h.dia = :dia";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $idodontologo);
    $stmt->bindParam(':dia', $dayOfWeek);
    if($stmt->execute()) {

        $horarios = $stmt->fetchAll();

        if (empty($horarios)) return false;
    }

    // 2) Obtener inactividades

    $sql = "SELECT fechainicio, tiempoinicio, fechafinalizacion, tiempofinalizacion FROM inactividad i JOIN odontologo_inactividad oi ON i.idinactividad = oi.idinactividad WHERE oi.idodontologo = :ido AND (:fecha BETWEEN fechainicio AND fechafinalizacion)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $idodontologo);
    $stmt->bindParam(':fecha', $fecha);

    if($stmt->execute()) {

        $inactividades = $stmt->fetchAll();
    }

    // 3) Obtener consultas

    $sql = "SELECT hora, duracion FROM consulta WHERE idodontologo = :ido AND fecha = :fecha";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $idodontologo);
    $stmt->bindParam(':fecha', $fecha);

    if($stmt->execute()) {

        $consultas = $stmt->fetchAll();
    }

    // Crear un array de horas ocupadas

    $horasOcupadas = [];

    foreach ($consultas as $consulta) {

        $start = strtotime($consulta['hora']);
        $end = $start + ($consulta['duracion'] * 60);

        while ($start < $end) {

            $horasOcupadas[] = date('H:i', $start);
            $start += 1800; // Incrementar por intervalos de 30 minutos
        }
    }

    // 4) Verificar disponibilidad en los horarios del odontólogo

    foreach ($horarios as $horario) {

        $horainicio = strtotime($horario['horainicio']);
        $horafinalizacion = strtotime($horario['horafinalizacion']);

        while ($horainicio < $horafinalizacion) {

            $hora = date('H:i', $horainicio);
            $disponible = true;

            // Verificar si la hora está dentro de alguna inactividad

            foreach ($inactividades as $inactividad) {

                $inactividadInicio = strtotime($inactividad['fechainicio'] . ' ' . $inactividad['tiempoinicio']);
                $inactividadFin = strtotime($inactividad['fechafinalizacion'] . ' ' . $inactividad['tiempofinalizacion']);
                if ($horainicio >= $inactividadInicio && $horainicio < $inactividadFin) {

                    $disponible = false;
                    break;
                }
            }
            // Verificar si la hora está ocupada por otra consulta
            
            if (in_array($hora, $horasOcupadas)) $disponible = false;

            if ($disponible) return true;

            $horainicio += 1800; // Incrementar por intervalos de 30 minutos
        }
    }
    return false;
}

function getDatesFromRange($fechainicio, $fechafin) {

    $fechas = [];
    $fechaactual = strtotime($fechainicio);
    $fechafin = strtotime($fechafin);

    while ($fechaactual <= $fechafin) {

        $fechas[] = date('Y-m-d', $fechaactual);
        $fechaactual = strtotime('+1 day', $fechaactual);
    }
    return $fechas;
}

function getAdjustedHoursFromRange($hora_inicio, $hora_fin) {

    $horas = [];
    $hora_actual = strtotime($hora_inicio);
    $hora_fin = strtotime($hora_fin);

    while ($hora_actual <= $hora_fin) {

        $hora_actual += 30 * 60; 

        $minutos_restantes = date('i', $hora_actual) % 30;
        
        if ($minutos_restantes > 0) {

            $hora_actual += (30 - $minutos_restantes) * 60;
        }

        $horas[] = date('H:i:s', $hora_actual);
    }

    return $horas;
}

function getFechaActual() {

    global $pdo;

    $sql = "SELECT CURDATE() as fecha";
    $resultado = $pdo->query($sql)->fetch();

    return $resultado['fecha'];
}

function getHoraActual() {

    global $pdo;

    $sql = "SELECT CURTIME() as hora";
    $resultado = $pdo->query($sql)->fetch();

    return $resultado['hora'];
}

function sumarFecha($fecha, $intervalo, $cantidad) {

    global $pdo;

    switch($intervalo) {
        
        case "dia": $intervalont = "DAY"; break;

        case "mes": $intervalont = "MONTH"; break;

        case "año": $intervalont = "YEAR"; break;
    }

    $sql = "SELECT DATE_ADD(:fecha, INTERVAL :cantidad " . $intervalont . ") AS sumafecha";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':cantidad', $cantidad);

    $stmt->execute();
    $resultado = $stmt->fetch();

    return $resultado['sumafecha'];
}

function fechaDisponible($fecha, $idodontologo) {

    global $pdo;

    $dayOfWeek = date('N', strtotime($fecha)); // Día de la semana (1 = Lunes, 7 = Domingo)

    // 1) Obtener horarios

    $sql = "SELECT h.horainicio, h.horafinalizacion FROM horario h JOIN odontologo_horario oh ON h.idhorario = oh.idhorario WHERE oh.idodontologo = :ido AND h.dia = :dia";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $idodontologo);
    $stmt->bindParam(':dia', $dayOfWeek);
    if($stmt->execute()) {

        $horarios = $stmt->fetchAll();

        if (empty($horarios)) return false;
    }

    // 2) Obtener inactividades

    $sql = "SELECT fechainicio, tiempoinicio, fechafinalizacion, tiempofinalizacion FROM inactividad i JOIN odontologo_inactividad oi ON i.idinactividad = oi.idinactividad WHERE oi.idodontologo = :ido AND (:fecha BETWEEN fechainicio AND fechafinalizacion)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $idodontologo);
    $stmt->bindParam(':fecha', $fecha);

    if($stmt->execute()) {

        $inactividades = $stmt->fetchAll();
    }

    // 3) Obtener consultas

    $sql = "SELECT hora, duracion FROM consulta WHERE idodontologo = :ido AND fecha = :fecha";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $idodontologo);
    $stmt->bindParam(':fecha', $fecha);

    if($stmt->execute()) {

        $consultas = $stmt->fetchAll();
    }

    // Crear un array de horas ocupadas

    $horasOcupadas = [];

    foreach ($consultas as $consulta) {

        $start = strtotime($consulta['hora']);
        $end = $start + ($consulta['duracion'] * 60);

        while ($start < $end) {

            $horasOcupadas[] = date('H:i', $start);
            $start += 1800; // Incrementar por intervalos de 30 minutos
        }
    }

    // 4) Verificar disponibilidad en los horarios del odontólogo

    foreach ($horarios as $horario) {

        $horainicio = strtotime($horario['horainicio']);
        $horafinalizacion = strtotime($horario['horafinalizacion']);

        while ($horainicio < $horafinalizacion) {

            $hora = date('H:i', $horainicio);
            $disponible = true;

            // Verificar si la hora está dentro de alguna inactividad

            foreach ($inactividades as $inactividad) {

                $inactividadInicio = strtotime($inactividad['fechainicio'] . ' ' . $inactividad['tiempoinicio']);
                $inactividadFin = strtotime($inactividad['fechafinalizacion'] . ' ' . $inactividad['tiempofinalizacion']);
                if ($horainicio >= $inactividadInicio && $horainicio < $inactividadFin) {

                    $disponible = false;
                    break;
                }
            }
            // Verificar si la hora está ocupada por otra consulta
            
            if (in_array($hora, $horasOcupadas)) $disponible = false;

            if ($disponible) return true;

            $horainicio += 1800; // Incrementar por intervalos de 30 minutos
        }
    }
    return false;
}

function horasDisponibles($fecha, $idodontologo) {

    global $pdo;

    $fechaActual = getFechaActual();
    $horaActual = getHoraActual();

    $dayOfWeek = date('N', strtotime($fecha)); // Día de la semana (1 = Lunes, 7 = Domingo)

    // 1) Obtener horarios

    $sql = "SELECT h.horainicio, h.horafinalizacion FROM horario h JOIN odontologo_horario oh ON h.idhorario = oh.idhorario WHERE oh.idodontologo = :ido AND h.dia = :dia";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $idodontologo);
    $stmt->bindParam(':dia', $dayOfWeek);
    if($stmt->execute()) {

        $horarios = $stmt->fetchAll();

        if (empty($horarios)) return false;
    }

    // 2) Obtener inactividades

    $sql = "SELECT fechainicio, tiempoinicio, fechafinalizacion, tiempofinalizacion FROM inactividad i JOIN odontologo_inactividad oi ON i.idinactividad = oi.idinactividad WHERE oi.idodontologo = :ido AND (:fecha BETWEEN fechainicio AND fechafinalizacion)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $idodontologo);
    $stmt->bindParam(':fecha', $fecha);

    if($stmt->execute()) {

        $inactividades = $stmt->fetchAll();
    }

    // 3) Obtener consultas

    $sql = "SELECT hora, duracion FROM consulta WHERE idodontologo = :ido AND fecha = :fecha";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $idodontologo);
    $stmt->bindParam(':fecha', $fecha);

    if($stmt->execute()) {

        $consultas = $stmt->fetchAll();
    }

    // Crear un array de horas ocupadas

    $horasOcupadas = [];

    foreach ($consultas as $consulta) {

        $start = strtotime($consulta['hora']);
        $end = $start + ($consulta['duracion'] * 60);

        while ($start < $end) {

            $horasOcupadas[] = date('H:i', $start);
            $start += 1800; // Incrementar por intervalos de 30 minutos
        }
    }

    // 4) Verificar disponibilidad en los horarios del odontólogo

    $horasDisponibles = [];
    foreach ($horarios as $horario) {

        $horainicio = strtotime($horario['horainicio']);
        $horafinalizacion = strtotime($horario['horafinalizacion']);

        while ($horainicio < $horafinalizacion) {

            $hora = date('H:i', $horainicio);
            $disponible = true;

            // Verificar si la hora está dentro de alguna inactividad

            foreach ($inactividades as $inactividad) {

                $inactividadInicio = strtotime($inactividad['fechainicio'] . ' ' . $inactividad['tiempoinicio']);
                $inactividadFin = strtotime($inactividad['fechafinalizacion'] . ' ' . $inactividad['tiempofinalizacion']);
                if ($horainicio >= $inactividadInicio && $horainicio < $inactividadFin) {

                    $disponible = false;
                    break;
                }
            }
            // Verificar si la hora está ocupada por otra consulta
            
            if (in_array($hora, $horasOcupadas)) $disponible = false;

            // Si la fecha es hoy, verificar que la hora de inicio sea al menos 1 hora después de la hora actual
            if ($fecha == $fechaActual)
                if (strtotime($fecha . ' ' . $hora) <= strtotime('+1 hour', strtotime($fechaActual . ' ' . $horaActual))) $disponible = false;

            if ($disponible) $horasDisponibles[] = $hora;

            $horainicio += 1800; // Incrementar por intervalos de 30 minutos
        }
    }
    return $horasDisponibles;
}

?>