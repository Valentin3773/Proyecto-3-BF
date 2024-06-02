<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    sendMail();
}

function sendMail() {
    
    $nombre = $_POST['nombre'];
    $email = trim($_POST['email']);
    $telefono = $_POST['telefono'];
    $mensajec = $_POST['mensaje'];

    if(!empty(trim($nombre)) && !empty($email) && !empty(trim($telefono)) && !empty(trim($mensajec))) {

        $header = "From: remitente@example.com\r\nReply-To: remitente@example.com\r\nX-Mailer: PHP/" . phpversion();
        
        $mensaje = "Mensaje recibido desde la aplicación web\r\nRemitente: $email\r\nNombre: $nombre\r\nTeléfono: $telefono\r\nFecha: " . date('d/m/y', time()) . "Mensaje:\r\n$mensajec";

        $destinatario = "themystymysty@gmail.com";
        $asunto = "elpepe";

        if(mail($destinatario, $asunto, $mensaje, $header)) {

            header("Location: index.html?exitomail='exito'");
        }
        else {

            header("Location: index.html?exitomail='fallo'");
        }
    }
    else {

        header("Location: index.html?exitomail='vacio'");
    }
}

?>