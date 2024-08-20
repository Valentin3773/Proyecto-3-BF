<?php
include("../conexion.php");
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST"){
    UPDATEPROFILE_ODONTOLOGO();
} else {
    echo "FUERA";
    exit();
}

function UPDATEPROFILE_ODONTOLOGO() {
    $respuesta = array();
    $respuesta['error'] = "Odontologo.";
    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}
?>