<?php 
include ("../conexion.php");
session_start();
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    eleminarConsulta($pdo);    
} else {
    echo "Error: Acceso no autorizado";
}

function eleminarConsulta($pdo){
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    $fecha = $data['fechaV'];
    $hora = $data['horaV'];
    $ido = $_SESSION['odontologo']['idodontologo'];

    
    try {

        $consulta = 'DELETE FROM consulta WHERE fecha = :fecha AND hora = :hora AND idodontologo = :idodontologo;';

        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':idodontologo', $ido);

        if ($stmt->execute()) {
            $response['enviar'] = "Datos válidos. Consulta Eliminada, con Fecha:".$fecha."| Hora: ".$hora;
        } else {
            $response['error'] = "Error al ejecutar la consulta";
        }
    } catch (Throwable $th) {
        $response['error'] = $th->getMessage()." | ".$asunto." | ".$hora." | ".$duracion." | ".$fecha." | ".$resumen." | ".$ido;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>