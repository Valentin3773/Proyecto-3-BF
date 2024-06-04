<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    // Incluye los archivos de PHPMailer
    require 'PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enviar'])) {

        // Verifica si el campo 'jejeje' está vacío
        if (empty(trim($_POST["jejeje"]))) {
    
            sendMail();
        } 
        else {
    
            echo "Fuera bot hijueputa!!!";
        }
    }

    function sendMail() {

        // Configuración de PHPMailer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'laprogramarmy@gmail.com'; // Reemplaza con tu dirección de correo
        $mail->Password   = 'khpr cean piib ssiu'; // Reemplaza con tu contraseña de applicacion de google
        $mail->SMTPSecure = 'tls'; // Puedes cambiar a 'ssl' si es necesario
        $mail->Port       = 587; // Puedes cambiar a 465 si usas SSL
        $mail->CharSet = 'UTF-8';

        try {
            if (isset($_POST['enviar'])) {
                if (!empty($_POST['nombre']) && !empty($_POST['telefono']) && !empty($_POST['email']) && !empty($_POST['mensaje'])) {
                    
                    //Destinatario
                    $destino = "laprogramarmy@gmail.com";         

                    $nombre = $_POST["nombre"];
                    $telefono = $_POST["telefono"];
                    $email = $_POST["email"];
                    $mensajec = $_POST['mensaje'];

                        //avisa que mail va ser tratado como un HTML
                        $mail->isHTML(true);

                        // Asunto del correo
                        $asunto = "Nueva Solicitud de Contacto: $nombre";

                        //Aca va el cuerpo del mensaje
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
                                        background-color: #ffffff;
                                        width: 80%;
                                        margin: 20px auto;
                                        padding: 20px;
                                        border-radius: 10px;
                                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                                      }
                                      h1 {
                                        color: #333333;
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
                                        <h3>Numero de Telefono: $telefono</h3>
                                        <h3>Correo Electronico: $email</h3>

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
                        $headers .= "From: $nombre <$email>\r\n";
                        $headers .= "Return-path: $destino\r\n";

                        // Enviar correo con PHPMailer
                        $mail->setFrom($email, $nombre);
                        $mail->addAddress($destino);
                        $mail->Subject = $asunto;
                        $mail->Body    = $mensaje;
                        
                        //Enviar correo
                        $mail->send();
                    
                        echo '
                        <!DOCTYPE html>
                        <html lang="es">
                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        background-color: #f4f4f4;
                                        margin: 0;
                                        padding: 0;
                                      }
                                      .container {
                                        background-color: #ffffff;
                                        width: 80%;
                                        margin: 20px auto;
                                        padding: 20px;
                                        border-radius: 10px;
                                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                                      }
                                      h1 {
                                        color: #333333;
                                      }  
                                </style>
                            </head>
                            <body>
                                <div class="container">
                                    <h1>Email enviado correctamente</h1>                        
                                    <a href="../index.html" class="button">Volver</a>
                                </div>
                            </body>
                        </html>
                        ';

                    } else {
                        echo '
                        <!DOCTYPE html>
                        <html lang="es">
                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        background-color: #f4f4f4;
                                        margin: 0;
                                        padding: 0;
                                      }
                                      .container {
                                        background-color: #ffffff;
                                        width: 80%;
                                        margin: 20px auto;
                                        padding: 20px;
                                        border-radius: 10px;
                                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                                      }
                                      h1 {
                                        color: #333333;
                                      }     
                                </style>
                            </head>
                            <body>
                                <div class="container">
                                    <h1>Porfavor llene todos los campos</h1>                        
                                    <a href="../index.html" class="button">Volver</a>
                                </div>
                            </body>
                        </html>
                        ';
                    }
                }
        } catch (Exception $e) {
                echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }


    }

?>