<?php 
include ("../conexion.php");
include ("../extractor.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    eleminarConsulta($pdo);    
} else {
    echo "Error: Acceso no autorizado";
}

function eleminarConsulta($pdo){
    $response = array();
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    $fecha = $data['fechaV'];
    $hora = $data['horaV'];
    $ido = $_SESSION['odontologo']['idodontologo'];

    
    try {
        archivarConsulta($fecha,$hora,$ido);
    } catch (Throwable $th) {
        $response['error'] = $th->getMessage()." | ".$asunto." | ".$hora." | ".$duracion." | ".$fecha." | ".$resumen." | ".$ido;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>