<?php

include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    processRegisterForm($pdo);
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

    $datos = array();

    if ($nombre === null || $nombre === '') {
        $datos['error'] = "Nombre no proporcionado.";
    } 
    else if ($apellidos === null || $apellidos === '') {
        $datos['error'] = "Apellidos no proporcionados.";
    } 
    else if ($documento === null || $documento === '') {
        $datos['error'] = "Documento no proporcionado.";
    } 
    else if (!ctype_digit($documento)) {
        $datos['error'] = "El documento debe ser ingresado solo con números y sin guines.";
    }
    else if (strlen($documento) != 8) {
        $datos['error'] = "El documento debe tener exactamente 9 dígitos.";
    }
    else if ($email === null || $email === '') {
        $datos['error'] = "Email no proporcionado.";
    } 
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $datos['error'] = "Correo en formato no reconocible";
    } 
    else if ($contrasenia === null || $contrasenia === '') {
        $datos['error'] = "Contraseña no proporcionada.";
    } 
    else if ($concontrasenia === null || $concontrasenia === '') {
        $datos['error'] = "Confirmación de contraseña no proporcionada.";
    } 
    else if ($contrasenia !== $concontrasenia) {
        $datos['error'] = "Las contraseñas no coinciden.";
    } else {

            $chekeo = "SELECT documento FROM paciente WHERE documento = :documento OR email = :email";
            $stmt = $pdo->prepare($chekeo);
            $stmt->bindParam(':documento', $documento);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {

                if ($stmt->rowCount() > 0) {

                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    $datos['error'] = "Usuario existente: $email";

                } 
                else {
                    
                    $hashedPassword = password_hash($contrasenia,PASSWORD_BCRYPT);

                    $sql = "INSERT INTO paciente (documento, nombre, apellido, email, contrasenia, direccion, telefono) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);

                    if ($stmt->execute([$documento, $nombre, $apellidos, $email, $hashedPassword, $direccion, $telefono])) {

                        $datos['registrado'] = "Usuario Registrado";

                    } 
                    else {
                        $datos['error'] = "Lo siento! Se ha presentado un error.";
                    }
                }
            }
    }
       
    header('Content-Type: application/json');
    echo json_encode($datos);
    exit();

} 

?>