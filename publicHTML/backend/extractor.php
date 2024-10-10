<?php

// Extractor es un modulo que incluye funciones que mejoran la calidad de vida de los programadores.

$dir = "{$_SERVER['DOCUMENT_ROOT']}/Proyecto-3-BF/publicHTML/";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $dir . "lib/PHPMailer/PHPMailer.php";
require $dir . "lib/PHPMailer/Exception.php";
require $dir . "lib/PHPMailer/SMTP.php";

include('conexion.php');

$defaults = [

    'horaminima' => '01:00:00', // Hora minima posible para un horario
    'horamaxima' => '23:30:00', // Hora máxima posible para un horario
    'duracionmaxima' => 180 // Duración máxima (en minutos) de una consulta
];

function getDatesFromRange(string $fechainicio, string $fechafin): array
{

    $fechas = [];
    $fechaactual = strtotime($fechainicio);
    $fechafin = strtotime($fechafin);

    while ($fechaactual <= $fechafin) {

        $fechas[] = date('Y-m-d', $fechaactual);
        $fechaactual = strtotime('+1 day', $fechaactual);
    }
    return $fechas;
}

function getHoursFromRange(string $hora_inicio, string $hora_fin): array
{

    $horas = [];
    $hora_actual = strtotime($hora_inicio);
    $hora_fin = strtotime($hora_fin);

    while ($hora_actual <= $hora_fin) {

        $horas[] = date('H:i:s', $hora_actual);

        $hora_actual += 30 * 60;

        $minutos_restantes = date('i', $hora_actual) % 30;

        if ($minutos_restantes > 0) $hora_actual += (30 - $minutos_restantes) * 60;
    }

    return $horas;
}

function getDefaultHours(): array
{

    global $defaults;

    $minimo = $defaults['horaminima'];
    $maximo = $defaults['horamaxima'];

    return getHoursFromRange($minimo, $maximo);
}

function getFechaActual(): string
{

    global $pdo;

    $sql = "SELECT CURDATE() as fecha";
    $resultado = $pdo->query($sql)->fetch();

    return $resultado['fecha'];
}

function getHoraActual(): string
{

    global $pdo;

    $sql = "SELECT CURTIME() as hora";
    $resultado = $pdo->query($sql)->fetch();

    return $resultado['hora'];
}

function sumarFecha(string $fecha, string $intervalo, int $cantidad): string
{

    global $pdo;

    switch ($intervalo) {

        case "dia":
            $intervalont = "DAY";
            break;

        case "mes":
            $intervalont = "MONTH";
            break;

        case "año":
            $intervalont = "YEAR";
            break;
    }

    $sql = "SELECT DATE_ADD(:fecha, INTERVAL :cantidad {$intervalont}) AS sumafecha";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':cantidad', $cantidad);

    $stmt->execute();
    $resultado = $stmt->fetch();

    return $resultado['sumafecha'];
}

function fechaMayor(string $fecha1, string $fecha2): bool
{

    $fechona1 = new DateTime($fecha1);
    $fechona2 = new DateTime($fecha2);

    if ($fechona1 > $fechona2) return true;

    else return false;
}

function fechaMayorOIgual(string $fecha1, string $fecha2): bool
{

    $fechona1 = new DateTime($fecha1);
    $fechona2 = new DateTime($fecha2);

    if ($fechona1 >= $fechona2) return true;

    else return false;
}

function fechaMenorOIgual(string $fecha1, string $fecha2): bool
{

    $fechona1 = new DateTime($fecha1);
    $fechona2 = new DateTime($fecha2);

    if ($fechona1 <= $fechona2) return true;

    else return false;
}

function fechaMenor(string $fecha1, string $fecha2): bool
{

    $fechona1 = new DateTime($fecha1);
    $fechona2 = new DateTime($fecha2);

    if ($fechona1 < $fechona2) return true;

    else return false;
}

function fechaIgual(string $fecha1, string $fecha2): bool
{

    $fechona1 = new DateTime($fecha1);
    $fechona2 = new DateTime($fecha2);

    if ($fechona1 == $fechona2) return true;

    else return false;
}

function horaMayor(string $hora1, string $hora2): bool
{

    $horona1 = DateTime::createFromFormat('H:i:s', $hora1);
    $horona2 = DateTime::createFromFormat('H:i:s', $hora2);

    if ($horona1 > $horona2) return true;

    else return false;
}

function horaMenor(string $hora1, string $hora2): bool
{

    $horona1 = DateTime::createFromFormat('H:i:s', $hora1);
    $horona2 = DateTime::createFromFormat('H:i:s', $hora2);

    if ($horona1 < $horona2) return true;

    else return false;
}

function horaMayorOIgual(string $hora1, string $hora2): bool
{

    $horona1 = DateTime::createFromFormat('H:i:s', $hora1);
    $horona2 = DateTime::createFromFormat('H:i:s', $hora2);

    if ($horona1 >= $horona2) return true;

    else return false;
}

function horaMenorOIgual(string $hora1, string $hora2): bool
{

    $horona1 = DateTime::createFromFormat('H:i:s', $hora1);
    $horona2 = DateTime::createFromFormat('H:i:s', $hora2);

    if ($horona1 <= $horona2) return true;

    else return false;
}

function horaIgual(string $hora1, string $hora2): bool
{

    $horona1 = DateTime::createFromFormat('H:i:s', $hora1);
    $horona2 = DateTime::createFromFormat('H:i:s', $hora2);

    if ($horona1 == $horona2) return true;

    else return false;
}

function fechaDisponible(string $fecha, int $idodontologo): bool
{

    if (!empty(horasDisponibles($fecha, $idodontologo))) return true;

    else return false;
}

function horasDisponibles(string $fecha, int $idodontologo): array
{

    global $pdo;

    $fechaActual = getFechaActual();
    $horaActual = getHoraActual();

    $dayOfWeek = date('N', strtotime($fecha)); // Día de la semana (1 = Lunes, 7 = Domingo)

    // 1) Obtener horarios

    $sql = "SELECT horainicio, horafinalizacion FROM horario WHERE idodontologo = :ido AND dia = :dia";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $idodontologo);
    $stmt->bindParam(':dia', $dayOfWeek);

    if ($stmt->execute()) {

        $horarios = $stmt->fetchAll();

        if (empty($horarios)) return [];
    }

    // 2) Obtener inactividades

    $sql = "SELECT tiempoinicio, tiempofinalizacion FROM inactividad WHERE idodontologo = :ido AND (:fecha BETWEEN DATE(tiempoinicio) AND DATE(tiempofinalizacion))";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $idodontologo);
    $stmt->bindParam(':fecha', $fecha);

    if ($stmt->execute()) $inactividades = $stmt->fetchAll();

    // 3) Obtener consultas

    $sql = "SELECT hora, duracion FROM consulta WHERE idodontologo = :ido AND fecha = :fecha";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $idodontologo);
    $stmt->bindParam(':fecha', $fecha);

    if ($stmt->execute()) $consultas = $stmt->fetchAll();

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
            $horafecha = strtotime($fecha . ' ' . $hora);
            $disponible = true;

            // Verificar si la hora está dentro de alguna inactividad

            foreach ($inactividades as $inactividad) {

                $inactividadInicio = strtotime($inactividad['tiempoinicio']);
                $inactividadFin = strtotime($inactividad['tiempofinalizacion']);

                if ($horafecha >= $inactividadInicio && $horafecha <= $inactividadFin) {

                    $disponible = false;
                    break;
                }
            }

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

function duracionesDisponibles(DateTime $fecha, string $hora, int $idodontologo): array
{

    global $defaults;

    $extendedHours = [];

    foreach (horasDisponibles($fecha->format('Y-m-d'), $idodontologo) as $horon) $horasLuego[] = $horon . ':00';

    $horasLuego = array_values(array_diff($horasLuego, getHoursFromRange($defaults["horaminima"], $hora)));
    $horasLuego[] = $hora;

    if (sizeof($horasLuego) <= $defaults["duracionmaxima"] / 30) for ($i = 1; $i <= sizeof($horasLuego); $i++) $extendedHours[] = $i * 30;

    else for ($i = 1; $i <= $defaults["duracionmaxima"] / 30; $i++) $extendedHours[] = $i * 30;

    return $extendedHours;
}

function reloadSession(): int
{

    global $pdo;

    if (isset($_SESSION['paciente'])) {

        $idp = $_SESSION['paciente']['idpaciente'];

        $sql = "SELECT * FROM paciente WHERE idpaciente = :idp";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idp', $idp);

        if ($stmt->execute() && $stmt->rowCount() == 1) {

            $tupla = $stmt->fetch();
            unset($tupla['contrasenia']);

            $_SESSION['paciente'] = $tupla;
            return 1;
        }
    } else if (isset($_SESSION['odontologo'])) {

        $ido = $_SESSION['odontologo']['idodontologo'];

        $sql = "SELECT * FROM odontologo WHERE idodontologo = :ido";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ido', $ido);

        if ($stmt->execute() && $stmt->rowCount() == 1) {

            $tupla = $stmt->fetch();
            unset($tupla['contrasenia']);

            $_SESSION['odontologo'] = $tupla;
            return 2;
        }
    }
    return 3;
}

function generateToken(string $key, int $length = 32): array
{

    $randomNumber = random_int(100000, 999999);
    return [hash_hmac('sha256', $randomNumber, $key), $randomNumber];
}

function enviarEmailVerificador(string $destino, int $idp, string $verificador): bool
{

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'laprogramarmy@gmail.com';
    $mail->Password = 'khpr cean piib ssiu';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);

    $mensaje = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        background-color: #000000;
                        width: 80%;
                        margin: 20px auto;
                        padding: 20px;
                        border-radius: 10px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }
                    h1 {
                        color: #ffffff;
                        text-align: center;
                    }
                    h3,
                    p {
                        font-size: 16px;
                        line-height: 1.5;
                        color: #666666;
                        text-align: center;
                    }
                    .footer {
                        text-align: center;
                        margin-top: 20px;
                        font-size: 12px;
                        color: #aaaaaa;
                    }
                    #linkcontainer {

                        width: 100%;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }
                    /* From Uiverse.io by adamgiebl */
                    a {
                        position: relative;
                        display: inline-block;
                        margin: 15px;
                        padding: 15px 30px;
                        text-align: center;
                        font-size: 18px;
                        letter-spacing: 1px;
                        text-decoration: none;
                        color: #725AC1;
                        background: transparent;
                        cursor: pointer;
                        transition: ease-out 0.5s;
                        border: 2px solid #725AC1;
                        border-radius: 10px;
                        box-shadow: inset 0 0 0 0 #725AC1;
                    }
                    a:hover {
                        color: white;
                        box-shadow: inset 0 -100px 0 0 #725AC1;
                    }
                    a:active {
                        transform: scale(0.9);
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h1>Verifica tu cuenta de la clínica</h1>
                    <div id='linkcontainer'>
                        <a href='localhost/Proyecto-3-BF/publicHTML/backend/login/verificaremail.php?idp={$idp}&verificador={$verificador}'>Verificar cuenta</a>
                    </div>
                    <p class='footer'>Este es un mensaje automático, por favor no responda directamente a este correo.</p>
                </div>
            </body>
            </html>
        ";

    // Cabeceras del correo
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";

    // Enviar correo con PHPMailer
    $mail->setFrom('clinicasaludbucal@gmail.com', 'Clinica Salud Bucal', $headers);
    $mail->addAddress($destino);
    $mail->Subject = 'Verifica tu cuenta';
    $mail->Body    = $mensaje;

    if ($mail->send()) return true;

    else return false;
}

function verificarCuentaActivada(string $destino, int $idp): bool
{

    global $pdo;

    $sql = "SELECT verificador FROM paciente WHERE idpaciente = :idp";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idp', $idp);

    if ($stmt->execute() && $stmt->rowCount() == 1) {

        if ($stmt->fetch()['verificador'] == 'verificado') return false;

        $verificador = generateToken('tremendaclinica2024');

        $sql = "UPDATE paciente SET verificador = :verificador WHERE idpaciente = :idp";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':verificador', $verificador[0]);
        $stmt->bindParam(':idp', $idp);

        if ($stmt->execute() && enviarEmailVerificador($destino, $idp, $verificador[1])) return true;

        else return false;
    } else return false;
}

function getHorasInicioHorario(int $dia, int $ido): array
{

    global $pdo;
    global $defaults;

    $sql = "SELECT * FROM horario WHERE dia = :dia AND idodontologo = :ido";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dia', $dia);
    $stmt->bindParam(':ido', $ido);

    if ($stmt->execute()) $horarios = $stmt->fetchAll();

    if (!empty($horarios)) {

        $horas = getDefaultHours();

        foreach ($horarios as $horario) {

            $adjustedhorainicio = new DateTime($horario['horainicio']);
            $adjustedhorainicio->sub(new DateInterval('PT30M'));
            $adjustedhorainicio = $adjustedhorainicio->format('H:i');
            $adjustedhorafinalizacion = new DateTime($horario['horafinalizacion']);
            $adjustedhorafinalizacion = $adjustedhorafinalizacion->format('H:i');

            $horas = array_diff($horas, getHoursFromRange($adjustedhorainicio, $adjustedhorafinalizacion));
        }

        $horas = array_diff($horas, [$defaults['horamaxima']]);

        return $horas;
    } else return array_diff(getDefaultHours(), [$defaults['horamaxima']]);
}

function getHorasFinalizacionHorario(int $dia, string $horaini, int $ido): array
{

    global $pdo;
    global $defaults;

    $sql = "SELECT * FROM horario WHERE dia = :dia AND idodontologo = :ido";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dia', $dia);
    $stmt->bindParam(':ido', $ido);

    if ($stmt->execute()) $horarios = $stmt->fetchAll();

    $horainicio = new DateTime($horaini);
    $horainicio = $horainicio->format('H:i');

    if (!empty($horarios)) {

        $horas = getDefaultHours();

        $horas = array_diff($horas, getHoursFromRange($defaults['horaminima'], $horainicio));

        foreach ($horarios as $horario) {

            $adjustedhorainicio = new DateTime($horario['horainicio']);
            $adjustedhorainicio = $adjustedhorainicio->format('H:i');
            $adjustedhorafinalizacion = new DateTime($horario['horafinalizacion']);
            $adjustedhorafinalizacion = $adjustedhorafinalizacion->format('H:i');

            if ($adjustedhorainicio > $horainicio) $horas = array_diff($horas, getHoursFromRange($adjustedhorainicio, $defaults['horamaxima']));

            else $horas = array_diff($horas, getHoursFromRange($adjustedhorainicio, $adjustedhorafinalizacion));
        }
        return $horas;
    } else return array_diff(getDefaultHours(), getHoursFromRange($defaults['horaminima'], $horainicio));
}

function fechaInicioInactividadDisponible(string $fecha, int $ido): bool
{

    if (!empty(getHorasInicioInactividad($fecha, $ido))) return true;

    else return false;
}

function getHorasInicioInactividad(string $fecha, int $ido): array
{

    global $pdo;
    global $defaults;

    $fechaActual = getFechaActual();

    if (fechaMenor($fecha, $fechaActual)) return [];

    $sql = "SELECT * FROM inactividad WHERE idodontologo = :ido";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $ido);

    if ($stmt->execute() && $stmt->rowCount() > 0) {

        $inactividades = $stmt->fetchAll();

        $horasDisponibles = getDefaultHours();

        foreach ($inactividades as $inactividad) {

            $tiempoinicioinactividad = new DateTime($inactividad['tiempoinicio']);
            $tiempofinalizacioninactividad = new DateTime($inactividad['tiempofinalizacion']);

            $fechainicioinactividad = $tiempoinicioinactividad->format('Y-m-d');
            $adjustedhorainicio = new DateTime($tiempoinicioinactividad->format('H:i:s'));
            $adjustedhorainicio->sub(new DateInterval('PT30M'));
            $adjustedhorainicio = $adjustedhorainicio->format('H:i:s');

            $fechafinalizacioninactividad = $tiempofinalizacioninactividad->format('Y-m-d');
            $adjustedhorafinalizacion = $tiempoinicioinactividad->format('H:i:s');

            if (fechaIgual($fecha, $fechainicioinactividad) && fechaIgual($fecha, $fechafinalizacioninactividad)) $horasDisponibles = array_diff($horasDisponibles, getHoursFromRange($adjustedhorainicio, $adjustedhorafinalizacion));

            else if (fechaIgual($fecha, $fechainicioinactividad) && fechaMenor($fecha, $fechafinalizacioninactividad)) $horasDisponibles = array_diff($horasDisponibles, getHoursFromRange($adjustedhorainicio, $defaults['horamaxima']));

            else if (fechaMayor($fecha, $fechainicioinactividad) && fechaIgual($fecha, $fechafinalizacioninactividad)) $horasDisponibles = array_diff($horasDisponibles, getHoursFromRange($defaults['horaminima'], $adjustedhorafinalizacion));

            else if (fechaMayor($fecha, $fechainicioinactividad) && fechaMenor($fecha, $fechafinalizacioninactividad)) return [];
        }
        return array_values($horasDisponibles);
    } else return getDefaultHours();
}

function fechaFinalizacionInactividadDisponible(string $fechainicio, string $horainicio, string $fechafinalizacion, int $ido): bool
{

    if (!empty(getHorasFinalizacionInactividad($fechainicio, $horainicio, $fechafinalizacion, $ido))) return true;

    else return false;
}

function getHorasFinalizacionInactividad(string $fechainicio, string $horainicio, string $fechafinalizacion, int $ido): array
{

    global $pdo;
    global $defaults;

    if (fechaMayor($fechainicio, $fechafinalizacion)) return [];

    $inactividadtiempoinicio = new DateTime("{$fechainicio} {$horainicio}");

    $sql = "SELECT * FROM inactividad WHERE idodontologo = :ido";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $ido);

    if ($stmt->execute() && $stmt->rowCount() > 0) {

        $inactividades = $stmt->fetchAll();

        $horasDisponibles = getDefaultHours();

        if (fechaIgual($fechainicio, $fechafinalizacion)) $horasDisponibles = array_values(array_diff($horasDisponibles, getHoursFromRange($defaults['horaminima'], $horainicio)));

        foreach ($inactividades as $inactividad) {

            $otrainactividadtiempoinicio = new DateTime($inactividad['tiempoinicio']);
            $otrainactividadtiempofinalizacion = new DateTime($inactividad['tiempofinalizacion']);

            foreach (getDefaultHours() as $hora) {

                $inactividadtiempofinalizacion = new DateTime("{$fechafinalizacion} {$hora}");

                if ($otrainactividadtiempoinicio <= $inactividadtiempofinalizacion && $inactividadtiempofinalizacion <= $otrainactividadtiempofinalizacion) {

                    $horasDisponibles = array_values(array_diff($horasDisponibles, [$hora]));
                }

                if ($inactividadtiempoinicio <= $otrainactividadtiempofinalizacion && $otrainactividadtiempofinalizacion <= new DateTime("{$fechafinalizacion} {$hora}")) {

                    $horasDisponibles = array_values(array_diff($horasDisponibles, getHoursFromRange($hora, $defaults['horamaxima'])));
                }
            }
        }
        return $horasDisponibles;
    } 
    else return getDefaultHours();
}

function enviarEmailCancelador($emailp, $asunto, $fecha, $hora): bool {

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'laprogramarmy@gmail.com';
    $mail->Password = 'khpr cean piib ssiu';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);

    $mensaje = "
            <html lang='es'>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            margin: 0;
                            padding: 0;
                        }
                        .container {
                            background-color: #000000;
                            width: 80%;
                            margin: 20px auto;
                            padding: 20px;
                            border-radius: 10px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
                        span, h1 {
                            color: #ffffff;
                            text-align: left;
                            font-size: 1.5rem;
                        }
                        h1.losentimos {

                            text-align: left;
                            font-size: 1.8rem;
                        }
                        h3,
                        p {
                            font-size: 16px;
                            line-height: 1.5;
                            color: #666666;
                            text-align: center;
                        }
                        .footer {
                            text-align: center;
                            margin-top: 20px;
                            font-size: 12px;
                            color: #aaaaaa;
                        }
                        #linkcontainer {

                            width: 100%;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }
                        /* From Uiverse.io by adamgiebl */
                        a {
                            position: relative;
                            display: inline-block;
                            margin: 15px;
                            padding: 15px 30px;
                            text-align: center;
                            font-size: 18px;
                            letter-spacing: 1px;
                            text-decoration: none;
                            color: #725AC1;
                            background: transparent;
                            cursor: pointer;
                            transition: ease-out 0.5s;
                            border: 2px solid #725AC1;
                            border-radius: 10px;
                            box-shadow: inset 0 0 0 0 #725AC1;
                        }
                        a:hover {
                            color: white;
                            box-shadow: inset 0 -100px 0 0 #725AC1;
                        }
                        a:active {
                            transform: scale(0.9);
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <h1 class='losentimos'>Lo sentimos</h1>
                        <span>Su consulta por asunto '{$asunto}', con fecha {$fecha} y hora {$hora} ha sido cancelada</span>
                        <br><br><br>
                        <span>Contacte a la clínica para más información <a href='https://api.whatsapp.com/send/?phone=598091814295' target='_blank'>WhattsApp</a></span>
                        <p class='footer'>Este es un mensaje automático, por favor no responda directamente a este correo.</p>
                    </div>
                </body>
            </html>
        ";

    // Cabeceras del correo
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";

    // Enviar correo con PHPMailer
    $mail->setFrom('clinicasaludbucal@gmail.com', 'Clinica Salud Bucal', $headers);
    $mail->addAddress($emailp);
    $mail->Subject = 'Cancelación de consulta';
    $mail->Body    = $mensaje;

    if ($mail->send()) return true;

    else return false;
}

function getHorasConsulta(string $hora, int $duracion): array {

    $horainicio = DateTime::createFromFormat('H:i:s', $hora);
    $horainicio = $horainicio->format('H:i:s');

    $horafinalizacion = DateTime::createFromFormat('H:i:s', $hora);
    $horafinalizacion->modify("+{$duracion} minutes");
    $horafinalizacion = $horafinalizacion->format('H:i:s');

    $horasConsulta = array_values(array_diff(getHoursFromRange($horainicio, $horafinalizacion), [$horafinalizacion]));

    return $horasConsulta;
}

function getFechaHorasConsulta(string $fecha, string $hora, int $duracion): array {

    $fechahoras = [];

    foreach(getHorasConsulta($hora, $duracion) as $horaconsulta) $fechahoras[] = "{$fecha} {$horaconsulta}";

    return $fechahoras;
}

function formatDateTime(string $tiempo, string $formatoinicial, string $formatofinal): string {

    $tiempoFormateado = DateTime::createFromFormat($formatoinicial, $tiempo);

    $tiempoFormateado = $tiempoFormateado->format($formatofinal);

    return $tiempoFormateado;
}

function formatDateTimeArray(array $tiempos, string $formatoinicial, string $formatofinal): array {

    $tiemposFormateados = [];

    foreach($tiempos as $tiempo) {

        $tiempoFormateado = DateTime::createFromFormat($formatoinicial, $tiempo);

        $tiemposFormateados[] = $tiempoFormateado->format($formatofinal);
    }

    return $tiemposFormateados;
}