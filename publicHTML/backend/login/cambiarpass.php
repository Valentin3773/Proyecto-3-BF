<?php 

include('../extractor.php');
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// uso require_once porque si no da conflicto con el namespace del phpmailer
require_once '../../lib/PHPMailer/PHPMailer.php';
require_once '../../lib/PHPMailer/Exception.php';
require_once '../../lib/PHPMailer/SMTP.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') cambiarcontra();

else exit();

function cambiarcontra() {

    global $pdo;

    $email = htmlspecialchars(strip_tags($_POST['email']));

    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $chekeo = "SELECT * FROM paciente WHERE email = :email";
        $stmt = $pdo->prepare($chekeo);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {

            $codigo = generateToken("Pepe");
            $data = array ('codigo' => $codigo[1],'email' => $email); 
            $_SESSION = $data;
            //No mover, demora mucho el phpmailer
            enviarEmail($codigo, $email);
        } 
        else {

            $respuesta = array('noexiste' => "No existe ningun usuario registrado con el email seleccionado");
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit();
        }
    }
    else {

        $respuesta = array('noexiste' => "Correo ingresado no válido");
        header('Content-Type: application/json');
        echo json_encode($respuesta);
        exit();
    }
}

function enviarEmail($codigo, $email) {

    // Configuración de PHPMailer

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'laprogramarmy@gmail.com';
    $mail->Password = 'khpr cean piib ssiu';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    try {

            //Destinatario
            $destino = $email;
            $mail->isHTML(true);

            // Asunto del correo
            $asunto = "Nueva solicitud de cambio de contraseña";

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
                                    <h1>Código de cambio de contraseña</h1>
                                    <h3>$codigo[1]</h3>
                                    <p class='footer'>Este es un mensaje automático, por favor no responda directamente a este correo.</p>
                                    </div>
                                </body>
                            </html>
                    ";


        // Cabeceras del correo
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";

        // Enviar correo con PHPMailer
        $mail->addAddress($destino);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        $mail->send(); // Enviar correo

        $datos = [

            "respuesta" => "Correo enviado con exito",
            "codigo" => $codigo[0]
        ];
    } 
    catch (Exception $e) {

        $datos['error'] = "Oh!! Ha ocurrido un error $mail->ErrorInfo}";
    }
    header('Content-Type: application/json');
    echo json_encode($datos);
    exit();
}

?>