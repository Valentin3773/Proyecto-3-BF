<?php

// Este script debe ser ejecutado por una tarea cron.

$ip = $_SERVER['REMOTE_ADDR'];

if ($ip !== '127.0.0.1' && $ip !== '::1' && $ip !== 'localhost') {
    
    http_response_code(403);
    die('Acceso no autorizado');
}

include('conexion.php');
include('extractor.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../lib/PHPMailer/PHPMailer.php';
require_once '../lib/PHPMailer/Exception.php';
require_once '../lib/PHPMailer/SMTP.php';

$pacientes = $pdo->query("SELECT idpaciente, nombre, apellido, nomolestar FROM paciente");

foreach ($pacientes as $paciente) {
    
    $idp = $paciente['idpaciente'];
    $nombre = $paciente['nombre'];
    $apellido = $paciente['apellido'];
    $nomolestar = $paciente['nomolestar'];

    $consultaspaciente = obtenerNotificacionesPaciente($idp);

    if (!$nomolestar) foreach ($consultaspaciente as $consultapaciente) {

        $fechaActual = getFechaActual();
        $horaActual = getHoraActual();

        $fecha = $consultapaciente['fecha'];
        $hora = $consultapaciente['hora'];
        $ido = $consultapaciente['idodontologo'];

        // var_dump(diferenciaFechas($fecha, $fechaActual));

        if (!$consultapaciente['booleanos'][0] &&
        ( diferenciaFechas($fecha, $fechaActual) == 1 || ( diferenciaFechas($fecha, $fechaActual) == 0 && horaMenor($horaActual, $hora) ) ) 
        && horaMayor($horaActual, '08:00:00') && horaMenor($horaActual, '23:00:00')) {

            if (enviarEmailNotificador($fecha, $hora, $ido)) {

                $consultapaciente['booleanos'][0] = true;

                modificarBooleanosNotificacionesConsulta($fecha, $hora, $ido, $consultapaciente['booleanos']);
            }
        }
    }
}

function enviarEmailNotificador(string $fecha, string $hora, int $ido): bool {

    global $pdo;
    global $defaults;

    $sql = "SELECT p.email AS emailp, p.nombre AS nombrep, p.apellido AS apellidop, o.nombre AS nombreo, o.apellido AS apellidoo, c.asunto AS asunto FROM (consulta c JOIN paciente p ON c.idpaciente = p.idpaciente) JOIN odontologo o ON c.idodontologo = o.idodontologo WHERE c.fecha = :fecha AND c.hora = :hora AND c.idodontologo = :ido AND c.vigente = 'vigente'";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':hora', $hora);
    $stmt->bindParam(':ido', $ido);

    if ($stmt->execute() && $stmt->rowCount() == 1) {

        $tupla = $stmt->fetch(PDO::FETCH_ASSOC);

        $nombrep = $tupla['nombrep'];
        $apellidop = $tupla['apellidop'];
        $nombreo = $tupla['nombreo'];
        $apellidoo = $tupla['apellidoo'];
        $emailp = $tupla['emailp'];
        $asuntoc = $tupla['asunto'];

        $meses = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "setiembre", "octubre", "noviembre", "diciembre"];

        $fechahora = Datetime::createFromFormat("Y-m-d H:i:s", "{$fecha} {$hora}");
        $dia = $fechahora->format('d');
        $mes = $meses[intval($fechahora->format('m')) - 1];
        $anio = $fechahora->format('Y');
        $fechaformateada = "{$dia} de {$mes} de {$anio}";
        $horaformateada = $fechahora->format('H:i');

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $defaults['emailclinica'];
        $mail->Password = $defaults['passemail'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        try {

            //Destinatario
            $destino = $emailp;
            $mail->isHTML(true);

            // Asunto del correo
            $asunto = "Recordatorio de Consulta";

            $mensaje = "
                            
                        <!DOCTYPE html>
                        <html>

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
                                    text-shadow: 1px 1px 10px rgba(0, 255, 55, 0.5);
                                }

                                h3,
                                p {
                                    font-size: 16px;
                                    line-height: 1.5;
                                    color: #666666;
                                }

                                .footer {
                                    text-align: center;
                                    margin-top: 20px;
                                    font-size: 12px;
                                    color: #aaaaaa;
                                }
                                .texto {

                                    color: #ffffff;
                                    font-size: 22px;
                                    text-shadow: 1px 1px 10px rgba(0, 255, 55, 0.3);
                                }
                                a {

                                    color: green;
                                }
                            </style>
                        </head>

                        <body>
                            <div class='container'>
                                <header>
                                    <h1>Recordatorio de Consulta</h1>
                                    <br>
                                    <p class='texto'>
                                        Estimado {$nombrep} {$apellidop}.
                                    </p>
                                    <p class='texto'>
                                        Te recordamos que tienes una consulta agendada para el día {$fechaformateada} a la hora {$horaformateada}, con el odontólogo {$nombreo} {$apellidoo} por asunto de: {$asuntoc}.
                                    </p>
                                    <p class='texto'>
                                        Para más información o en caso de que desees cancelar la consulta, por favor contactanos mediante
                                        <a href='https://api.whatsapp.com/send/?phone=598091814295' target='_blank'>WhattsApp</a>
                                    </p>
                                    <p class='footer'>Este es un mensaje automático, por favor no responda directamente a este correo.</p>
                                </header>
                            </div>
                        </body>

                        </html>
                       ";

            // Cabeceras del correo
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";

            // Enviar correo con PHPMailer
            $mail->setFrom($destino, 'Clínica Salud Bucal', $headers);
            $mail->addAddress($destino);
            $mail->Subject = $asunto;
            $mail->Body    = $mensaje;

            if($mail->send()) return true;

            else return false;
        } 
        catch (Exception $e) {

            return false;
        }
    } 
    else return false;
}