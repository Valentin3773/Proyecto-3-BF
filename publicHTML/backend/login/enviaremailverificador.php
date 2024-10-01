<?php 

include('../extractor.php');

session_start();
reloadSession();

if(!isset($_SESSION['paciente'])) exit();

$respuesta = [];

$idp = $_SESSION['paciente']['idpaciente'];
$email = $_SESSION['paciente']['email'];

if (verificarCuentaActivada($email, $idp)) $respuesta['exito'] = "Se ha enviado un mensaje de verificación a su casilla de correo";

else $respuesta['error'] = "Ha ocurrido un error al enviar el mensaje de verificación";

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>