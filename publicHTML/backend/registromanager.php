<?php

include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        processRegisterForm($pdo);
        echo "adasd";
}

function processRegisterForm($pdo) {

    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : null;
    $apellidos = isset($_POST['apellido']) ? trim($_POST['apellido']) : null;
    $documento = isset($_POST['documento']) ? trim($_POST['documento']) : null;
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : null;
    $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $contrasenia = isset($_POST['contrasenia']) ? trim($_POST['contrasenia']) : null;
    $concontrasenia = isset($_POST['concontrasenia']) ? trim($_POST['concontrasenia']) : null;

    if ($contrasenia == $concontrasenia) {

        $chekeo = "SELECT documento FROM paciente WHERE documento = :documento OR email = :email";
        $stmt = $pdo->prepare($chekeo);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                echo '<script type="text/javascript">';
                echo 'alert("Usuario ya registrado");';
                echo 'window.location.href = "../login.php";';
                echo '</script>';
            } 
            else {
                
                $hashedPassword = password_hash($contrasenia,PASSWORD_BCRYPT);

                $sql = "INSERT INTO paciente (documento, nombre, apellido, email, contrasenia, direccion, telefono) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);

                if ($stmt->execute([$documento, $nombre, $apellidos, $email, $hashedPassword, $direccion, $telefono])) {

                    header("Location: ../login.php");
                    exit();
                } 
                else {

                    echo "Lo siento! Se ha presentado un error.";
                }
            }
        } 
        else {

            echo "Error al ejecutar la consulta.";
        }
    } 
    else {

        echo "Ingrese correctamente las contraseñas";
    }
    unset($stmt);
    unset($pdo);
}