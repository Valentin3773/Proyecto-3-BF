<?php

include('../extractor.php');

session_start();
reloadSession();

if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['codigo'])) comprobarcodigo();

else exit();

function comprobarcodigo() {

    global $pdo;

    $respuesta = [];

    $codigo = sanitizar($_POST['codigo']);
    $con = sanitizar($_POST['contrasenia']);
    $recon = sanitizar($_POST['recontrasenia']);
    $email = $_SESSION['email'];
    $scupass = sanitizar($_POST['secp']);

    if((hash_equals($scupass, hash_hmac('sha256', $codigo, 'sosunalokita2025')))) {

        if ($con === null || $con === '') $respuesta['error'] = "Contraseña no proporcionada";
        else if ($recon === null || $recon === '') $respuesta['error'] = "Confirmación de contraseña no proporcionada";
        else if ($con !== $recon) $respuesta['error'] = "Las contraseñas no coinciden";
        else if(strlen($con) > 24) $respuesta['error'] = "La contraseña es demasiado larga, debe tener entre 8 y 24 caracteres";
        else if(strlen($con) <= 7) $respuesta['error'] = "La contraseña es demasiado corta, debe tener entre 8 y 24 caracteres";
        else if (!preg_match('/[A-Z]/', $con)) $respuesta['error'] = "La contraseña debe incluir al menos una letra mayúscula (A-Z)";
        else if (!preg_match('/[a-z]/', $con)) $respuesta['error'] = "La contraseña debe incluir al menos una letra minúscula (a-z)";
        else if (!preg_match('/[0-9]/', $con)) $respuesta['error'] = "La contraseña debe incluir al menos un número (0-9)";
        else if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $con)) $respuesta['error'] = "La contraseña debe incluir al menos un carácter especial";   

        else {
            try {

                $contrasenia = password_hash($con, PASSWORD_BCRYPT);
                $chekeo = "UPDATE paciente SET contrasenia = :contra WHERE email = :email";
                $stmt = $pdo->prepare($chekeo);
                $stmt->bindParam(':contra', $contrasenia);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $respuesta['exito'] = "Se actualizó la contraseña";
            } 
            catch(Exception $e) { 

                $respuesta['error'] = "Error:" . $e;
            }
        } 
    } 
    else $respuesta['error'] = "Código inválido";

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

?>