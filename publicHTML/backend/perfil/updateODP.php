<?php
include("../conexion.php");
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST"){
    UPDATEPROFILE_ODONTOLOGO(global $pdo);
} else {
    echo "FUERA";
    exit();
}

function UPDATEPROFILE_ODONTOLOGO($pdo) {
    $respuesta = array();
    $respuesta['error'] = $_POST['name'];

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}
?>