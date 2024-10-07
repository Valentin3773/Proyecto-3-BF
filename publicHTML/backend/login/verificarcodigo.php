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
    $scupass = $_POST['secp'];

    if(password_verify($codigo, $scupass)){
        if($con === $recon){
            $hashedPassword = password_hash($con, PASSWORD_DEFAULT);
            echo "SIIIIIIIIIIIIII";
        } else {
            echo "NOOOOOOOOOOOOOOO";
        }
    } else {
        echo "Codigo invalido".$scupass;
    }
}
?>
