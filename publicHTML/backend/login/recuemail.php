<?php 

include ('../conexion.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') recuperaremail();

else exit();

function recuperaremail() {

    global $pdo;

    $respuesta = [];

    $nombre = htmlspecialchars(strip_tags($_POST['nombre']));
    $apellido = htmlspecialchars(strip_tags($_POST['apellido']));
    $documento = htmlspecialchars(strip_tags($_POST['documento']));

    if(!is_string($nombre) || $nombre == null || $nombre == "") $respuesta['error'] = "Los datos ingresados no son correctos";
    
    else if(!is_string($apellido) || $apellido == null || $apellido == "") $respuesta['error'] = "Los datos ingresados no son correctos";

    //ctype_digit porque is_int no anda porque el formulario es string cuando se envia
    else if(!ctype_digit($documento) || $documento == null || $documento == "") $respuesta['error'] = "Los datos ingresados no son correctos";
    
    else {

        $chekeo = "SELECT email FROM paciente WHERE nombre = :nombre AND apellido = :apellido AND documento = :documento";
        $stmt = $pdo->prepare($chekeo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':documento', $documento);

        if ($stmt->execute() && $stmt->rowCount() > 0) {

            $tupla = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $respuesta['exito'] = "Se comprobó su indentidad, su email es:<br>" . $tupla['email'];
        } 
        else $respuesta['error'] = "No se pudo encontrar un usuario con los datos ingresados";
    }

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}
?>