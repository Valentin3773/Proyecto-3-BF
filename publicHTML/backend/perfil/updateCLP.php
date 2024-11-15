<?php

include("../conexion.php");
include("../extractor.php");

session_start();
reloadSession();

if(!isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) header('Location: index.php');

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) UpdateProfilePaciente();

else {

    echo "FUERA";
    exit();
}

function UpdateProfilePaciente() {

    global $pdo;

    $respuesta = array();

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if(!$data) exit();

    $idp = $_SESSION['paciente']['idpaciente'];

    $name = sanitizar($data['name']);
    $value = sanitizar($data['value']);
    $oldvalue = sanitizar($data['oldvalue']);
    $nameROW = sanitizar($data['name']);

    $datosModificables = ['nombre', 'apellido', 'email', 'telefono', 'direccion'];

    if(!in_array($name, $datosModificables) || !in_array($nameROW, $datosModificables)) return;

    $novalidos = false;

    if($name == 'nombre') {

        if(!preg_match("/^[a-zA-ZÀ-ÿ\s'’`´-]+$/u", $value)) {
            
            $novalidos = true;
            $respuesta['error'] = "El nombre no tiene el formato adecuado";
        }
        else if(strlen($value) > 50) {
            
            $novalidos = true;
            $respuesta['error'] = "El nombre es demasiado largo";
        }
        else if(strlen($value) <= 2) {
            
            $novalidos = true;
            $respuesta['error'] = "El nombre es demasiado corto";
        }
    }
    else if($name == 'apellido') {

        if(!preg_match("/^[a-zA-ZÀ-ÿ\s'’`´-]+$/u", $value)) {
            
            $novalidos = true;
            $respuesta['error'] = "El apellido no tiene el formato adecuado";
        }
        else if(strlen($value) > 50) {
            
            $novalidos = true;
            $respuesta['error'] = "El apellido es demasiado largo";
        }
        else if(strlen($value) <= 2) {
            
            $novalidos = true;
            $respuesta['error'] = "El apellido es demasiado corto";
        }
    }
    else if($name == 'telefono') {

        if($value != null && $value != '' && !preg_match("/^\+?\d{0,3}[-. (]*\d{1,4}[-. )]*\d{1,4}[-. ]*\d{1,4}[-. ]*\d{0,9}$/", $value)) {
            
            $novalidos = true;
            $respuesta['error'] = "El formato del número de teléfono no es válido";
        }
        else if($value != null && $value != '' && strlen($value) > 30) {
            
            $novalidos = true;
            $respuesta['error'] = "El número de teléfono es demasiado largo";
        }
        else if($value != null && $value != '' && strlen($value) <= 4) {
            
            $novalidos = true;
            $respuesta['error'] = "El número de teléfono es demasiado corto, debe tener al menos 5 digitos";
        }
    }
    else if($name == 'direccion') {

        if($value != null && $value != '' && !preg_match("/^[a-zA-Z0-9À-ÿ\s,.-]+$/u", $value)) {
            
            $novalidos = true;
            $respuesta['error'] = "El formato de la dirección no es válido";
        }
        else if($value != null && $value != '' && strlen($value) > 200) {
            
            $novalidos = true;
            $respuesta['error'] = "La dirección es demasiado larga";
        }
        else if($value != null && $value != '' && strlen($value) <= 5) {
            
            $novalidos = true;
            $respuesta['error'] = "La dirección es demasiado corta, debe tener al menos 6 caracteres";
        }
    }
    else if($name == 'email') {

        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            
            $novalidos = true;
            $respuesta['error'] = "Correo electrónico en formato no reconocible";
        }
        else if(strlen($value) > 250) {
            
            $novalidos = true;
            $respuesta['error'] = "El correo electrónico es demasiado largo";
        }
        else if(strlen($value) <= 5) {
            
            $novalidos = true;
            $respuesta['error'] = "El correo electrónico es demasiado corto";
        }
    }

    if($novalidos) {

        header('Content-Type: application/json');
        echo json_encode($respuesta);
        exit();
    }

    try {
        
        $consulta = "UPDATE paciente SET {$name} = :val1 WHERE {$nameROW} = :val2 and idpaciente = :idp";
        
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam(':val1', $value);
        $stmt->bindParam(':val2', $oldvalue);
        $stmt->bindParam(':idp', $idp);

        if($stmt->execute()) {

            $respuesta['enviar'] = "Datos actualizados";
            reloadSession();
        }

        if($name == 'email') {

            $sql = "UPDATE paciente SET verificador = '' WHERE idpaciente = :idp";
            $stmt2 = $pdo->prepare($sql);
            $stmt2->bindParam(':idp', $idp);

            if($stmt2->execute()) reloadSession();
        }
    } 
    catch (PDOException $e) {
        
        $respuesta['error'] = "Ha ocurrido un error: " . $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

?>
