<?php

include("../conexion.php");
include("../extractor.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") processRegisterForm();

function processRegisterForm() : void {

    global $pdo;

    $nombre = isset($_POST['nombre']) ? sanitizar($_POST['nombre']) : null;
    $apellido = isset($_POST['apellido']) ? sanitizar($_POST['apellido']) : null;
    $documento = isset($_POST['documento']) ? sanitizar($_POST['documento']) : null;
    $telefono = isset($_POST['telefono']) ? sanitizar($_POST['telefono']) : null;
    $direccion = isset($_POST['direccion']) ? sanitizar($_POST['direccion']) : null;
    $email = isset($_POST['email']) ? sanitizar($_POST['email']) : null;
    $contrasenia = isset($_POST['contrasenia']) ? sanitizar($_POST['contrasenia']) : null;
    $concontrasenia = isset($_POST['concontrasenia']) ? sanitizar($_POST['concontrasenia'])  : null;

    $datos = array();

    if ($nombre === null || $nombre === '') $datos['error'] = "Nombre no proporcionado";
    else if(!preg_match("/^[a-zA-ZÀ-ÿ\s'’`´-]+$/u", $nombre)) $datos['error'] = "El nombre no tiene el formato adecuado";
    else if(strlen($nombre) > 50) $datos['error'] = "El nombre es demasiado largo";
    else if(strlen($nombre) <= 2) $datos['error'] = "El nombre es demasiado corto";
    
    else if ($apellido === null || $apellido === '') $datos['error'] = "Apellido no proporcionado";
    else if(!preg_match("/^[a-zA-ZÀ-ÿ\s'’`´-]+$/u", $apellido)) $datos['error'] = "El apellido no tiene el formato adecuado";
    else if(strlen($apellido) > 50) $datos['error'] = "El apellido es demasiado largo";
    else if(strlen($apellido) <= 2) $datos['error'] = "El apellido es demasiado corto";

    else if ($telefono != null && $telefono != '' && !preg_match("/^\+?\d{0,3}[-. (]*\d{1,4}[-. )]*\d{1,4}[-. ]*\d{1,4}[-. ]*\d{0,9}$/", $telefono)) $datos['error'] = "El formato del número de teléfono no es válido";
    else if ($telefono != null && $telefono != '' && strlen($telefono) > 30) $datos['error'] = "El número de teléfono es demasiado largo";
    else if ($telefono != null && $telefono != '' && strlen($telefono) <= 4) $datos['error'] = "El número de teléfono es demasiado corto, debe tener al menos 5 digitos";

    else if ($direccion != null && $direccion != '' && !preg_match("/^[a-zA-Z0-9À-ÿ\s,.-]+$/u", $direccion)) $datos['error'] = "El formato de la dirección no es válido";
    else if ($direccion != null && $direccion != '' && strlen($direccion) > 200) $datos['error'] = "La dirección es demasiado larga";
    else if ($direccion != null && $direccion != '' && strlen($direccion) <= 5) $datos['error'] = "La dirección es demasiado corta, debe tener al menos 6 caracteres";

    else if ($documento === null || $documento === '') $datos['error'] = "Documento no proporcionado";
    else if (!ctype_digit($documento)) $datos['error'] = "El documento debe ser ingresado solo con números y sin puntos ni guiones";
    else if (strlen($documento) > 20) $datos['error'] = "El documento es demasiado largo";
    else if (strlen($documento) <= 3) $datos['error'] = "El documento es demasiado corto";
    
    else if ($email === null || $email === '') $datos['error'] = "Email no proporcionado";
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $datos['error'] = "Correo electrónico en formato no reconocible";
    else if (strlen($email) > 250) $datos['error'] = "El correo electrónico es demasiado largo";
    else if (strlen($email) <= 5) $datos['error'] = "El correo electrónico es demasiado corto";
    
    else if ($contrasenia === null || $contrasenia === '') $datos['error'] = "Contraseña no proporcionada";
    else if ($concontrasenia === null || $concontrasenia === '') $datos['error'] = "Confirmación de contraseña no proporcionada";
    else if ($contrasenia !== $concontrasenia) $datos['error'] = "Las contraseñas no coinciden";
    else if (strlen($contrasenia) > 24) $datos['error'] = "La contraseña es demasiado larga (debe tener entre 8 y 24 caracteres)";
    else if (strlen($contrasenia) <= 7) $datos['error'] = "La contraseña es demasiado corta (debe tener al menos 8 caracteres)";
    else if (!preg_match('/[A-Z]/', $contrasenia)) $datos['error'] = "La contraseña debe incluir al menos una letra mayúscula (A-Z)";
    else if (!preg_match('/[a-z]/', $contrasenia)) $datos['error'] = "La contraseña debe incluir al menos una letra minúscula (a-z)";
    else if (!preg_match('/[0-9]/', $contrasenia)) $datos['error'] = "La contraseña debe incluir al menos un número (0-9)";
    else if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $contrasenia)) $datos['error'] = "La contraseña debe incluir al menos un carácter especial";   
    
    else {

        $chekeo = "SELECT documento FROM paciente WHERE documento = :documento OR email = :email";
        $stmt = $pdo->prepare($chekeo);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) $datos['error'] = "El email o el documento ya se encuentran registrados";
            
            else {

                $hashedPassword = password_hash($contrasenia, PASSWORD_BCRYPT);

                $sql = "INSERT INTO paciente (documento, nombre, apellido, email, contrasenia, direccion, telefono) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);

                if ($stmt->execute([$documento, $nombre, $apellido, $email, $hashedPassword, $direccion, $telefono])) {

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
