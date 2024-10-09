<?php
session_start();
include('../extractor.php');

if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['codigo'])){
    comprobarcodigo();
} else {
    exit();
}

function comprobarcodigo(){
    $codigo = htmlspecialchars(strip_tags($_POST['codigo']));
    $con = htmlspecialchars(strip_tags($_POST['contraseña']));
    $recon = htmlspecialchars(strip_tags($_POST['recontraseña']));
    $email = $_SESSION['email'];
    $scupass = $_POST['secp'];
    if($con === $recon){
        if((hash_equals($scupass, hash_hmac('sha256', $codigo, 'Pepe')))){
            if($con === $recon){
                try{
                    $contrasenia = password_hash($con, PASSWORD_BCRYPT);
                    global $pdo;
                    $chekeo = "UPDATE paciente SET contrasenia = :contra WHERE email = :email";
                    $stmt = $pdo->prepare($chekeo);
                    $stmt->bindParam(':contra', $contrasenia);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();
                    echo "Se actualizo la contraseña";
                    exit();
                } catch(Exception $e){
                    echo "Error:".$e;
                    exit();
                }
            } else {
                echo "NOOOOOOOOOOOOOOO";
            }
        } else {
            echo "Codigo invalido".$scupass;
        }
    } else {
        echo "Las contraseñas no son iguales";
        exit();
    }
}
?>
