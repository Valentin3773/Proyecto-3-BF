<?php

include('../extractor.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../../lib/PHPMailer/PHPMailer.php';
require_once '../../lib/PHPMailer/Exception.php';
require_once '../../lib/PHPMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") sendMail();

function sendMail() {

    global $defaults;

    // Configuración de PHPMailer

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

        $email = isset($_POST['email']) ? sanitizar($_POST['email']) : null;
        $nombre = isset($_POST['nombre']) ? sanitizar($_POST['nombre']) : null;
        $telefono = isset($_POST['telefono']) ? sanitizar($_POST['telefono']) : null;
        $mensajec = isset($_POST['mensaje']) ? sanitizar($_POST['mensaje']) : null;

        $datos = array();

        if ($nombre === null || $nombre === '') {

            $datos['error'] = "Nombre no proporcionado.";
        } 
        else if ($email === null || $email === '') {

            $datos['error'] = "Email no proporcionado.";
        } 
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $datos['error'] = "Correo en formato no reconocible";
        } 
        else if ($telefono === null || $telefono === '') {

            $datos['error'] = "Teléfono no proporcionado.";
        }
        //Verifica que lo ingresado sean numeros
        else if (!ctype_digit($telefono)) {

            $datos['error'] = "El teléfono debe ser ingresado solo con números y sin espacios.";
        }
        //Verifica que si el largo del numero de telefono no es igual a 9 digitos
        else if (strlen($telefono) != 9) {

            $datos['error'] = "El teléfono debe tener exactamente 9 dígitos.";
        } 
        else if ($mensajec === null || $mensajec === '') {

            $datos['error'] = "Mensaje no proporcionado.";
        } 
        else {
          
            //Destinatario
            $destino = $defaults['emailclinica'];
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
                                        <h3>De: $nombre</h3>
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

            $mail->send(); // Enviar correo

            $datos['enviar'] = "Correo enviado con exito";
        }
    } 
    catch (Exception $e) {

        $datos['error'] = "Oh!! Ha ocurrido un error $mail->ErrorInfo}";
    }
    header('Content-Type: application/json');
    echo json_encode($datos);
    exit();
}
