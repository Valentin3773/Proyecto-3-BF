<?php

session_start();

$respuesta = array();

if(isset($_SESSION['paciente']) || isset($_SESSION['odontologo'])) {

    $_SESSION = array();
    $respuesta['exito'] = "Has cerrado sesión";
}
else $respuesta['error'] = "Ha ocurrido un error, no hay sesiones iniciadas";

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>