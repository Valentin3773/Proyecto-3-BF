<?php

include("../conexion.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    loginCheckAdmin($pdo);
}

function loginCheckAdmin($pdo) {

    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $contrasenia = isset($_POST['contrasenia']) ? $_POST['contrasenia'] : null;

    $datos = array();
    
    if ($email === null || $email === '') {
    
        $datos['error'] = "Email no proporcionado.";
    } 
    else if ($contrasenia === null || $contrasenia === '') {
    
        $datos['error'] = "Contraseña no proporcionada.";
    } 
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $datos['error'] = "Correo en formato no reconocible";
    }   
    else {

        $chekeo = "SELECT * FROM odontologo WHERE email = :email";
        $stmt = $pdo->prepare($chekeo);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
 
        if ($stmt->execute() && $stmt->rowCount() > 0) {

            $tupla = $stmt->fetch(PDO::FETCH_ASSOC);

            $hashedPassword = $tupla['contrasenia'];

            if (password_verify($contrasenia, $hashedPassword)) {
                    
                    $_SESSION = array();
                    unset($tupla['contrasenia']);
                    $_SESSION['odontologo'] = $tupla;
                    $datos = $tupla;

                    $datos['admin'] = "Iniciando Sesion";
                } 
                else {
                    
                    $datos['error'] = "Credenciales Incorrectas";
                }
            } 
            else {

                $datos['error'] = "Usuario no encontrado";
            }  
    }
    header('Content-Type: application/json');
    echo json_encode($datos);
    exit();
}

?>