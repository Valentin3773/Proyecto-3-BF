<?php

include("conexion.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    loginCheckUser($pdo);
}

function loginCheckUser($pdo) {

    // Funciona como operador ternario, osea verifica que si no esta definida sea null
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $contrasenia = isset($_POST['contrasenia']) ? $_POST['contrasenia'] : null;


    $datos = array();

    //chequeos adicionales
    if ($email === null || $email === '') {

        $datos['error'] = "Email no proporcionado.";

    } elseif ($contrasenia === null || $contrasenia === '') {

        $datos['error'] = "Contraseña no proporcionada.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        $datos['error'] = "Correo en formato no reconocible";
    }   
    else {
        $chekeo = "SELECT * FROM paciente WHERE email = :email";
        $stmt = $pdo->prepare($chekeo);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
 

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $tupla = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $tupla['contrasenia'];

            if (password_verify($contrasenia, $hashedPassword)) {

                    //Se quita la contraseña de la variable tupla para evitar fuga de datos
                    unset($tupla['contrasenia']);
                    $_SESSION['paciente'] = $tupla;
                    $datos = $tupla;

                } else {
                    
                    $datos['error'] = "Credenciales Incorrectas";
                }
            } else {

                $datos['error'] = "Usuario no encontrado.";
            
            }

        
    }

    header('Content-Type: application/json');
    echo json_encode($datos);
    exit();
}


?>
