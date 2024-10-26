<?php 

include ("../conexion.php");
include ("../extractor.php");

session_start();
reloadSession();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['odontologo'])) eliminarConsulta();

else exit();

function eliminarConsulta() {

    $response = array();
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    $fecha = sanitizar($data['fechaV']);
    $hora = sanitizar($data['horaV']);
    $ido = $_SESSION['odontologo']['idodontologo'];

    try {

        if(!archivarConsulta($fecha, $hora, $ido)) $response['error'] = "Ha ocurrido un error al eliminar la consulta";
    } 
    catch (Throwable $th) {

        $response['error'] = "Ha ocurrido un error al eliminar la consulta";
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

?>