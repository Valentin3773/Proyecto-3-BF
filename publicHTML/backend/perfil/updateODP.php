<?php

include("../conexion.php");
include("../extractor.php");

session_start();
reloadSession();

if(!isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) header('Location: index.php');

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['odontologo']) && !isset($_SESSION['paciente'])) UpdateProfileOdontologo($pdo);

else {

    echo "FUERA";
    header('Location: index.php');
    exit();
}

function UpdateProfileOdontologo() {

    global $pdo;

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    $respuesta = array();

    if(!$data) exit();

    $ido = $_SESSION['odontologo']['idodontologo'];

    $name = sanitizar($data['name']);
    $value = sanitizar($data['value']);
    $oldvalue = sanitizar($data['oldvalue']);
    $nameROW = sanitizar($data['name']);

    $datosModificables = ['nombre', 'apellido', 'email', 'telefono', 'direccion'];

    if(!in_array($name, $datosModificables) || !in_array($nameROW, $datosModificables)) return;

    try {

        $consulta = "UPDATE odontologo SET {$name} = :val1 WHERE {$nameROW} = :val2 and idodontologo = :ido";
        
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam(':val1', $value);
        $stmt->bindParam(':val2', $oldvalue);
        $stmt->bindParam(':ido', $ido);

        if($stmt->execute()) {

            $respuesta['enviar'] = "Datos actualizados";
            reloadSession();
        }
        else $respuesta['error'] = "Ha ocurrido un error";
    } 
    catch (PDOException $e) {

        $respuesta['error'] = "UPDATE odontologo SET {$name} = {$value} WHERE {$nameROW} = {$oldvalue} and idodontologo = {$ido}";
    }
    
    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}
?>