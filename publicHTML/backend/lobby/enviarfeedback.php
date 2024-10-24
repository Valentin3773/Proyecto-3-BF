<?php

include('../conexion.php');
include('../extractor.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../../lib/PHPMailer/PHPMailer.php';
require_once '../../lib/PHPMailer/Exception.php';
require_once '../../lib/PHPMailer/SMTP.php';

session_start();
reloadSession();

if (!isset($_SESSION['paciente']) || $_SERVER['REQUEST_METHOD'] != 'POST') exit();

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {

    $respuesta = [];

    $fecha = strip_tags($data['fecha']);
    $hora = strip_tags($data['hora']);
    $ido = intval(strip_tags($data['ido']));
    $asunto = strip_tags($data['asunto']);
    $mensaje = strip_tags($data['mensaje']);
    $puntaje = intval(strip_tags($data['puntaje']));

    $booleanos = obtenerNotificacionesConsulta($fecha, $hora, $ido)['booleanos'];

    if ($booleanos[2]) exit();
    else $booleanos[2] = true;

    if (enviarEmailCalificador($fecha, $hora, $ido, $asunto, $mensaje, $puntaje) && modificarBooleanosNotificacionesConsulta($fecha, $hora, $ido, $booleanos)) {

        $respuesta['exito'] = "Se ha enviado la reseña al odontólogo, gracias por tu tiempo";
    } else $respuesta['error'] = "Ha ocurrido un error al enviar la reseña";

    header('Content-Type: application/json');
    echo $respuesta;
    exit();
} 
else exit();

function enviarEmailCalificador(string $fecha, string $hora, int $ido, string $asunto, string $mensaje, int $puntaje): bool
{
    /*
    global $defaults;

    // Configuración de PHPMailer

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'laprogramarmy@gmail.com';
    $mail->Password = $defaults['passemail'];
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    try {

        //Destinatario
        $destino = "laprogramarmy@gmail.com";
        $mail->isHTML(true);

        // Asunto del correo
        $asunto = "Nueva Solicitud de Contacto: $nombre";

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
                                      }
                                      h3, p {
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
                                    </style>
                                    </head>
                                    <body>
                                      <div class='container'>
                                        <h1>Consulta</h1>
                                        <h3>De: $nombre</ph3>
                                        <h3>Teléfono: $telefono</h3>
                                        <h3>Correo Electrónico: $email</h3>

                                        <h2>Mensaje</h2>
                                        <p>$mensajec</p>
                                        <p class='footer'>Este es un mensaje automático, por favor no responda directamente a este correo.</p>
                                      </div>
                                    </body>
                                </html>
                        ";

        // Cabeceras del correo
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";

        // Enviar correo con PHPMailer
        $mail->setFrom($email, $nombre, $headers);
        $mail->addAddress($destino);
        $mail->Subject = $asunto;
        $mail->Body    = $mensaje;

        return $mail->send();
    } 
    catch (Exception $e) {

        return false;
    }
    */

    return false;
}