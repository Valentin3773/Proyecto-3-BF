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
    $respuesta['error'] = "Puede ser que sí ".$_POST['name'];
    // Se descodifica el objeto JSON para poder utilizar su contenido
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    //Id del paciente
    $idp = $_SESSION['paciente']['idpaciente'];

    // Variables
    $name = $data['name'];
    $value = $data['value'];
    $oldvalue = $data['oldvalue'];
    $nameROW = $data['name'];

    //Consulta para modificar paciente
    $consulta = "UPDATE paciente SET $name = :val1 WHERE $nameROW = :val2";
    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':val1', $value);
    $stmt->bindParam(':val2', $oldvalue);

    try {
        $stmt->execute();
        $respuesta['enviar'] = "Datos Actualizados";
    } catch (Throwable $th) {
        $respuesta['error'] = "Ha ocurrido un error: " . $th->getMessage();
    }
    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}
?>