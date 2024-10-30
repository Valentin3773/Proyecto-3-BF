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
    $asunto = sanitizar($data['asunto']);
    $ido = $_SESSION['odontologo']['idodontologo'];

    $fechatiempoString = $fecha . ' ' . $hora;
    $formato = DateTime::createFromFormat('d/m/Y H:i', $fechatiempoString);
    $Fechaensql = $formato->format('Y-m-d');
    $Tiempoensql = $formato->format('H:i:s');

    try {

        if(!archivarConsulta($Fechaensql, $Tiempoensql, $ido)) $response['error'] = "Ha ocurrido un error al eliminar la consulta";
        
        else {
            global $pdo;
            $consulta = "SELECT p.email FROM paciente p JOIN consulta c ON p.idpaciente = c.idpaciente WHERE c.idodontologo = :ido AND c.vigente = 'vigente'";
            $stmt = $pdo->prepare($consulta);
            $stmt->bindParam(':ido', $ido);            
            $tupla=null;

            if($stmt->execute() && $stmt->rowCount() > 0) $tupla = $stmt->fetch(PDO::FETCH_ASSOC);
            $response['enviar'] = "Se borro la consulta".$tupla['email'];
            enviarEmailCancelador($tupla['email'], $asunto, $Fechaensql, $Tiempoensql);
        }
    } 
    catch (Throwable $th) {

        $response['error'] = "Ha ocurrido un error al eliminar la consulta";
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

?>