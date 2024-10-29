<?php
include_once("../extractor.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    duraciones();
} else {
    exit();
}

function duraciones() {

    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['fecha']) && isset($data['hora'])) {

        $fecha = DateTime::createFromFormat('Y-m-d', $data['fecha']);
        $hora = $data['hora'];
        $ido = $_SESSION['odontologo']['idodontologo'];

        if ($fecha) {

            $conjDuraciones = duracionesDisponibles($fecha, $hora, $ido);
            
            echo json_encode($conjDuraciones);
        } 
    }
}

?>
