<?php

include_once("../extractor.php");

session_start();
reloadSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['odontologo'])) duraciones();

else  exit();

function duraciones() {

    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['fecha']) && isset($data['hora'])) {

        $fecha = DateTime::createFromFormat('Y-m-d', sanitizar($data['fecha']));
        $hora = sanitizar($data['hora']);
        $ido = $_SESSION['odontologo']['idodontologo'];

        if ($fecha) {

            $conjDuraciones = duracionesDisponibles($fecha, $hora, $ido);
            
            echo json_encode($conjDuraciones);
        } 
    }
}

?>