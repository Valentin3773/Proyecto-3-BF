<?php
    include("conexion.php");

    // Verifica si el formulario ha sido enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrarse'])) {
        
        // Verifica si el campo 'jejeje' está vacío
        if (empty(trim($_POST["jejeje"]))) {
            processRegisterForm($pdo);
        } else {
            echo "Fuera Hacker!!!";
        }
    }

    function processRegisterForm($pdo)
    {
        // Obtiene los datos del formulario
        $nombre = trim($_POST["nombre"]);
        $apellidos = trim($_POST["apellido"]);
        $documento = trim($_POST["documento"]);
        $telefono = trim($_POST["telefono"]);
        $direccion = trim($_POST["direccion"]);
        $email = trim($_POST["email"]);
        $contrasenia = trim($_POST["contrasenia"]);
        $concontrasenia = trim($_POST["concontrasenia"]);

        // Comprueba si las contraseñas coinciden
        if ($contrasenia == $concontrasenia) {

            // Prepara la consulta SQL para verificar si el documento ya existe
            $chekeo = "SELECT documento FROM paciente WHERE documento = :documento";
            $stmt = $pdo->prepare($chekeo);
            $stmt->bindParam(':documento', $documento);

            // Ejecuta la consulta
            if ($stmt->execute()) {
                
                // Verifica si se encontró algún registro
                if ($stmt->rowCount() > 0) {
                    echo "Usuario ya registrado";
                } else {

                    // Prepara la consulta SQL para insertar el nuevo registro
                    $sql = "INSERT INTO paciente (documento, nombre, apellido, email, contrasenia, direccion, telefono) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);

                    // Ejecuta la consulta de inserción
                    if ($stmt->execute([$documento, $nombre, $apellidos, $email, $contrasenia, $direccion, $telefono])) {
                        
                        //Esto redirige al URL especificado
                        header("Location: https://youtu.be/mCdA4bJAGGk?si=T0061-A3bbfF476Z");
                        exit();
                    } else {
                        echo "Lo siento! Se ha presentado un error.";
                    }
                }
            } else {
                echo "Error al ejecutar la consulta.";
            }
        } else {
            echo "Ingrese correctamente las contraseñas";
        }

        // Cierra la sentencia y la conexión
        unset($stmt);
        unset($pdo);
    }
?>