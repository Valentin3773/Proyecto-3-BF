<?php 

include('../conexion.php');
include('../extractor.php');

session_start();
reloadSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) subirPacienteIMG();
    
    else if (isset($_SESSION['odontologo']) && !isset($_SESSION['paciente'])) subirOdontologoIMG();
    
    else echo "Fuera";
    
    exit();
} 
else exit();

function checarIMG() {

    global $pdo;

    $estado = array(

        'ok' => false,
        'nombre' => ''
    );

    if (isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) {

        $consulta = "SELECT foto FROM paciente WHERE idpaciente = :idp";
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam(':idp', $_SESSION['paciente']['idpaciente']);
        if ($stmt->execute() && $stmt->rowCount() > 0) {

            $estado['nombre'] = $stmt->fetch(PDO::FETCH_ASSOC)['foto'];

            if($estado['nombre'] != null) $estado['ok'] = true;
        }
    } 
    else if (isset($_SESSION['odontologo']) && !isset($_SESSION['paciente'])) {

        $consulta = "SELECT foto FROM odontologo WHERE idodontologo = :ido";
        $stmt = $pdo->prepare($consulta);
        $stmt->bindParam(':ido', $_SESSION['odontologo']['idodontologo']);
        if ($stmt->execute() && $stmt->rowCount() > 0) {

            $estado['nombre'] = $stmt->fetch(PDO::FETCH_ASSOC)['foto'];
            if($estado['nombre'] != null) $estado['ok'] = true;
        }
    }
    else return false;

    return $estado;
}

function subirPacienteIMG() {

    global $pdo;

    $respuesta = array();

    // Obtengo la imagen del ajax
    $file = $_FILES['file'] ?? null;
    $ruta_carpeta = $_SERVER['DOCUMENT_ROOT'] . "/Proyecto-3-BF/publicHTML/backend/almacenamiento/fotosdeperfil/";
    
    if(!isset($file)) return;

    if($file['size'] > 700 * 1024) {

        $respuesta['error'] = "La foto es demasiado grande, debe tener un tamaño menor a 700KB";
        header('Content-Type: application/json');
        echo json_encode($respuesta);
        exit();
    }

    // Genera un nombre único para que la imagen no se encuentre repetida
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nuevo_nombre_archivo = uniqid('img_', true) . '.' . $extension;
    $ruta_guardar_archivo = $ruta_carpeta . $nuevo_nombre_archivo;

    // Intenta guardar la imagen
    try {

        if (move_uploaded_file($file['tmp_name'], $ruta_guardar_archivo)) {

            // $respuesta['enviar'] = "Se cambió su perfil";

            try {

                // Borro la imagen vieja del usuario
                $estadoIMG = checarIMG();
                if ($estadoIMG['ok'] && file_exists($ruta_carpeta . $estadoIMG['nombre'])) unlink($ruta_carpeta . $estadoIMG['nombre']);

                // Consulta para modificar paciente
                $consulta = "UPDATE paciente SET foto = :img WHERE idpaciente = :idp";
                $stmt = $pdo->prepare($consulta);
                $stmt->bindParam(':img', $nuevo_nombre_archivo);
                $stmt->bindParam(':idp', $_SESSION['paciente']['idpaciente']);

                if($stmt->execute()) $respuesta['exito'] = "Se ha actualizado tu foto de perfil";

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
}

function subirOdontologoIMG() {

    global $pdo;

    $respuesta = array();

    // Obtengo la imagen del ajax
    $file = $_FILES['file'] ?? null;
    $ruta_carpeta = $_SERVER['DOCUMENT_ROOT'] . "/Proyecto-3-BF/publicHTML/backend/almacenamiento/fotosdeperfil/";
    
    if(!isset($file)) return;

    if($file['size'] > 700 * 1024) {

        $respuesta['error'] = "La foto es demasiado grande, debe tener un tamaño menor a 700KB";
        header('Content-Type: application/json');
        echo json_encode($respuesta);
        exit();
    }

    // Genera un nombre único para que la imagen no se encuentre repetida
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nuevo_nombre_archivo = uniqid('img_', true) . '.' . $extension;
    $ruta_guardar_archivo = $ruta_carpeta . $nuevo_nombre_archivo;

    // Intenta guardar la imagen
    try {

        if (move_uploaded_file($file['tmp_name'], $ruta_guardar_archivo)) {

            // $respuesta['enviar'] = "Se cambió su perfil";

            try {
                
                //Borro la imagen vieja del usuario
                $estadoIMG = checarIMG();
                if ($estadoIMG['ok'] && file_exists($ruta_carpeta . $estadoIMG['nombre'])) unlink($ruta_carpeta . $estadoIMG['nombre']);

                // Consulta para modificar paciente
                $consulta = "UPDATE odontologo SET foto = :img WHERE idodontologo = :ido";
                $stmt = $pdo->prepare($consulta);
                $stmt->bindParam(':img', $nuevo_nombre_archivo);
                $stmt->bindParam(':ido', $_SESSION['odontologo']['idodontologo']);

                if($stmt->execute()) $respuesta['exito'] = "Se ha actualizado tu foto de perfil";

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
}

?>