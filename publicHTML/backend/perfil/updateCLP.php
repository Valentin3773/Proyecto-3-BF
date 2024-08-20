<?php 
include("../conexion.php");
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST"){
    UPDATEPROFILE_PACIENTE();
} else {
    echo "FUERA";
    exit();
}

function UPDATEPROFILE_PACIENTE() {
    $respuesta = array();
    $respuesta['error'] = "Paciente.";
    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}
?>