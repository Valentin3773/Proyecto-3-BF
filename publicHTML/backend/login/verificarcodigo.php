<?php

include('../extractor.php');

session_start();
reloadSession();

if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['codigo'])) comprobarcodigo();

else exit();

function comprobarcodigo() {

    global $pdo;

    $respuesta = [];

    $codigo = htmlspecialchars(strip_tags($_POST['codigo']));
    $con = htmlspecialchars(strip_tags($_POST['contraseña']));
    $recon = htmlspecialchars(strip_tags($_POST['recontraseña']));
    $email = $_SESSION['email'];
    $scupass = $_POST['secp'];

    if($con === $recon) {

        if((hash_equals($scupass, hash_hmac('sha256', $codigo, 'Pepe')))) {

            if($con === $recon) {

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
            else $respuesta['error'] = "NOOOOOOOOOOOOOOO";
        } 
        else $respuesta['error'] = "Código inválido" . $scupass;
    } 
    else $respuesta['error'] = "Las contraseñas no coinciden";

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

?>