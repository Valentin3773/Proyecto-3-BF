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

if ($data && !isset($data['clinica'])) {

    $respuesta = [];

    $fecha = sanitizar($data['fecha']);
    $hora = sanitizar($data['hora']);
    $ido = intval(sanitizar($data['ido']));
    $asunto = sanitizar($data['asunto']);
    $mensaje = sanitizar($data['mensaje']);
    $puntaje = intval(sanitizar($data['puntaje']));

    $tiempoconsulta = DateTime::createFromFormat("Y-m-d H:i:s", "{$fecha} {$hora}");
    $tiempoactual = DateTime::createFromFormat("Y-m-d H:i:s", getTiempoActual());

    $booleanos = obtenerNotificacionesConsulta($fecha, $hora, $ido)['booleanos'];

    $sql = "SELECT o.email AS emailo, p.nombre AS nombrep, p.apellido AS apellidop, c.duracion AS duracion FROM (consulta c JOIN paciente p ON c.idpaciente = p.idpaciente) JOIN odontologo o ON c.idodontologo = o.idodontologo WHERE c.fecha = :fecha AND c.hora = :hora AND c.idodontologo = :ido AND c.vigente = 'vigente'";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':hora', $hora);
    $stmt->bindParam(':ido', $ido);

    if ($stmt->execute() && $stmt->rowCount() == 1) {

        $tupla = $stmt->fetch(PDO::FETCH_ASSOC);

        $duracion = intval($tupla['duracion']);
        $tiempoconsulta->modify("+{$duracion} minutes");

        if ($booleanos[2] || $tiempoconsulta >= $tiempoactual) exit();
        else $booleanos[2] = true;

        $nombrep = $tupla['nombrep'];
        $apellidop = $tupla['apellidop'];
        $emailo = $tupla['emailo'];

        if (enviarEmailCalificador(formatDateTime($fecha, 'Y-m-d', 'd/m/Y'), formatDateTime($hora, 'H:i:s', 'H:i'), $asunto, $mensaje, $puntaje, $nombrep, $apellidop, $emailo) && modificarBooleanosNotificacionesConsulta($fecha, $hora, $ido, $booleanos)) {

            $respuesta['exito'] = "Se ha enviado la reseña al odontólogo, gracias por su tiempo";
        } 
        else $respuesta['error'] = "Ha ocurrido un error al enviar la reseña";
    }
    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
} 
else if ($data && isset($data['clinica']) && $data['clinica']) {

    $respuesta = [];

    $idp = $_SESSION['paciente']['idpaciente'];
    $clinicacalificada = $_SESSION['paciente']['calificado'];

    $asunto = sanitizar($data['asunto']);
    $mensaje = sanitizar($data['mensaje']);
    $puntaje = intval(sanitizar($data['puntaje']));
    $nombrep = $_SESSION['paciente']['nombre'];
    $apellidop = $_SESSION['paciente']['apellido'];

    $consultas = obtenerNotificacionesPaciente($idp);

    if ($clinicacalificada || sizeof($consultas) < 5) exit();

    $sql = "UPDATE paciente SET calificado = true WHERE idpaciente = :idp";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idp', $idp);

    if (enviarEmailCalificadorClinica($mensaje, $puntaje, $nombrep, $apellidop) && $stmt->execute()) {

        $respuesta['exito'] = "Se ha enviado la reseña a la clínica, gracias por su tiempo";
    } 
    else $respuesta['error'] = "Ha ocurrido un error al enviar la reseña";

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
} 
else exit();

function enviarEmailCalificador(string $fecha, string $hora, string $asuntoc, string $mensajec, int $puntaje, string $nombrep, string $apellidop, string $emailo): bool
{

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

        //Destinatario
        $destino = $emailo;
        $mail->isHTML(true);

        // Asunto del correo
        $asunto = "Reseña de consulta de {$nombrep} {$apellidop}";

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
                  h1, h2 {
                      color: #ffffff;
                      text-shadow: 1px 1px 10px rgba(0, 255, 55, 0.5);
                      font-size: 35px;
                  }
                  h2 {
                      font-size: 30px;
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
                      <h1>Reseña de Consulta</h1>
                      <p class='texto'>
                          El paciente {$nombrep} {$apellidop} ha calificado su consulta.
                      </p>
                      <h2>Asunto:</h2>
                      <p class='texto'>{$asuntoc}</p>
                      <h2>Fecha:</h2>
                      <p class='texto'>{$fecha}</p>
                      <h2>Hora:</h2>
                      <p class='texto'>{$hora}</p>
                      <h2>Calificación: {$puntaje} / 5 ⭐</h2>
                      <h2>Mensaje:</h2>
                      <p class='texto'>
                          {$mensajec}
                      </p>
                  </header>
                  <p class='footer'>Este es un mensaje automático, por favor no responda directamente a este correo.</p>
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

        if ($mail->send()) return true;

        else return false;
    } 
    catch (Exception $e) {

        return false;
    }
}

function enviarEmailCalificadorClinica(string $mensajec, int $puntaje, string $nombrep, string $apellidop): bool
{

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

        //Destinatario
        $destino = $defaults['emailclinica'];
        $mail->isHTML(true);

        // Asunto del correo
        $asunto = "Reseña de la clínica de {$nombrep} {$apellidop}";

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
                  h1, h2 {
                      color: #ffffff;
                      text-shadow: 1px 1px 10px rgba(0, 255, 55, 0.5);
                      font-size: 35px;
                  }
                  h2 {
                      font-size: 30px;
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
                      <h1>Reseña de la Clínica</h1>
                      <p class='texto'>
                          El paciente {$nombrep} {$apellidop} ha calificado la clínica.
                      </p>
                      <h2>Calificación: {$puntaje} / 5 ⭐</h2>
                      <h2>Mensaje:</h2>
                      <p class='texto'>
                          {$mensajec}
                      </p>
                  </header>
                  <p class='footer'>Este es un mensaje automático, por favor no responda directamente a este correo.</p>
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

        if ($mail->send()) return true;

        else return false;
    } 
    catch (Exception $e) {

        return false;
    }
}
