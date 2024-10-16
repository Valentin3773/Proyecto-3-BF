<?php

include('conexion.php');
include('extractor.php');

session_start();
reloadSession();

if (!isset($_SESSION['odontologo']) || $_SERVER['REQUEST_METHOD'] != 'POST') exit();

function checarIMG(int $idp)
{

    global $pdo;

    $estado = array(

        'ok' => false,
        'nombre' => ''
    );

    if ($idp) {

        $consulta = "SELECT foto FROM paciente WHERE idpaciente = :idp";
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam(':idp', $idp);

        if ($stmt->execute() && $stmt->rowCount() > 0) {

            $estado['nombre'] = $stmt->fetch(PDO::FETCH_ASSOC)['foto'];
            $estado['ok'] = true;
        }
    }
    return $estado;
}

$respuesta = [];

$file = $_FILES['file'];

$idp = $_POST['idpaciente'];

$ruta_carpeta = $_SERVER['DOCUMENT_ROOT'] . "/Proyecto-3-BF/publicHTML/backend/almacenamiento/fotosdeperfil/";

$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$nuevo_nombre_archivo = uniqid('img_', true) . '.' . $extension;
$ruta_guardar_archivo = $ruta_carpeta . $nuevo_nombre_archivo;

try {

    if (move_uploaded_file($file['tmp_name'], $ruta_guardar_archivo)) {

        try {

            $estadoIMG = checarIMG();

            if ($estadoIMG['ok'] && file_exists($ruta_carpeta . $estadoIMG['nombre'])) unlink($ruta_carpeta . $estadoIMG['nombre']);

            $consulta = "UPDATE paciente SET foto = :img WHERE idpaciente = :idp";
            $stmt = $pdo->prepare($consulta);
            $stmt->bindParam(':img', $nuevo_nombre_archivo);
            $stmt->bindParam(':idp', $idp);

            if ($stmt->execute()) $respuesta['exito'] = "Se ha actualizado tu foto de perfil";

            else $respuesta['error'] = "Ha ocurrido un error al intentar actualizar tu foto de perfil";

            reloadSession();
        } 
        catch (PDOException $e) {

            $respuesta['error'] = "Ha ocurrido un error: " . $e->getMessage();
        }
    } 
    else $respuesta['error'] = "Error al mover el archivo. Verifica los permisos de la carpeta.";
} 
catch (Exception $e) {

    $respuesta['error'] = "Excepción: " . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>