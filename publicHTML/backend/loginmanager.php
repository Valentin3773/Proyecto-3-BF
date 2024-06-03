<?php

include("conexion.php");

// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ingresar'])) {
    
    // Verifica si el campo 'jejeje' no está vacío
    if (empty(trim($_POST["jejeje"]))) {

        processCheckUser($pdo);
    } 
    else {

        echo "Fuera bot hijueputa!!!";
    }
}

function processCheckUser($pdo) {

    $email = trim($_POST["email"]);
    $contrasenia = trim($_POST["contrasenia"]);

    // Prepara la consulta SQL para verificar si el usuario existe
    $chekeo = "SELECT email, contrasenia FROM paciente WHERE email = :email AND contrasenia = :contrasenia";
    $stmt = $pdo->prepare($chekeo);

    // Prepara la variable stmt para ejecutar, con el agregado que el PDO trate el dato como String
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':contrasenia', $contrasenia, PDO::PARAM_STR);

    // Ejecuta la consulta
    if ($stmt->execute()) {

        if ($stmt->rowCount() > 0) {

            echo "Usuario existente";
            header("location:../index.html");
            exit();
        } 
        else {

            echo "Usuario no existente";
        }
    } 
    else {

        echo "Error al ejecutar la consulta";
    }
    // Cierra la sentencia y la conexión
    unset($stmt);
    unset($pdo);
}
