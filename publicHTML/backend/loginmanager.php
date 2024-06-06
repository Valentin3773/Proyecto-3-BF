<?php

include("conexion.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ingresar'])) {
    
    if (empty(trim($_POST["jejeje"]))) {

        loginCheckUser($pdo);
    } 
    else {

        echo "Fuera bot hijueputa!!!";
    }
}

function loginCheckUser($pdo) {

    $email = trim($_POST["email"]);
    $contrasenia = trim($_POST["contrasenia"]);

    $chekeo = "SELECT * FROM paciente WHERE email = :email AND contrasenia = :contrasenia";
    $stmt = $pdo->prepare($chekeo);

    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':contrasenia', $contrasenia, PDO::PARAM_STR);

    if ($stmt->execute()) {

        if ($stmt->rowCount() > 0) {

            $_SESSION['paciente'] = array();

            $tupla = $stmt->fetchAll()[0];

            $_SESSION['paciente']['nombre'] = $tupla['nombre'];
            $_SESSION['paciente']['apellido'] = $tupla['apellido'];
            $_SESSION['paciente']['documento'] = $tupla['documento'];
            $_SESSION['paciente']['telefono'] = $tupla['telefono'];
            $_SESSION['paciente']['direccion'] = $tupla['direccion'];
            $_SESSION['paciente']['email'] = $email;

            header("location:../index.php?iniciado=1");
            exit();
        } 
        else {

            echo "Usuario no existente";
        }
    } 
    else {

        echo "Error al ejecutar la consulta";
    }

    unset($stmt);
    unset($pdo);
}
