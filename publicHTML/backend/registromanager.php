<?php

include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrarse'])) {

    if (empty(trim($_POST["jejeje"]))) {

        processRegisterForm($pdo);
    } 
    else {

        echo "Fuera bot hijueputa!!!";
    }
}

function processRegisterForm($pdo) {

    $nombre = trim($_POST["nombre"]);
    $apellidos = trim($_POST["apellido"]);
    $documento = trim($_POST["documento"]);
    $telefono = trim($_POST["telefono"]);
    $direccion = trim($_POST["direccion"]);
    $email = trim($_POST["email"]);
    $contrasenia = trim($_POST["contrasenia"]);
    $concontrasenia = trim($_POST["concontrasenia"]);

    if ($contrasenia == $concontrasenia) {

        $chekeo = "SELECT documento FROM paciente WHERE documento = :documento OR email = :email";
        $stmt = $pdo->prepare($chekeo);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                echo "Usuario ya registrado";
            } 
            else {

                $sql = "INSERT INTO paciente (documento, nombre, apellido, email, contrasenia, direccion, telefono) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);

                if ($stmt->execute([$documento, $nombre, $apellidos, $email, $contrasenia, $direccion, $telefono])) {

                    header("Location: ../login.html");
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