<?php 

session_start();

include("../conexion.php");
include("../extractor.php");
include("checarIcono_Img.php");

if  ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['odontologo']){
    
    actualizarIcono();
    exit();
}

function actualizarIcono() {

    $respuestas = array();
    $file = $_FILES['file'] ?? null;
    $id = isset($_POST['id']) ? intval(sanitizar($_POST['id'])) : null;

    if($id == null || $file == null || $id == 0) exit();

    $ruta_carpeta = $_SERVER['DOCUMENT_ROOT'] . "/Proyecto-3-BF/publicHTML/backend/almacenamiento/iconservice/";

    if($file['size'] > 1024 * 1024) {

        $respuestas['error'] = "El icono es demasiado grande, debe tener un tamaño menor a 1MB";

        header('Content-Type: application/json');
        echo json_encode($respuestas);
        exit();
    }
    
    // Genera un nombre único para que el icono no se encuentre repetida
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nuevo_nombre_archivo = uniqid('img_', true) . '.' . $extension;
    $ruta_guardar_archivo = $ruta_carpeta . $nuevo_nombre_archivo;

        // Intenta guardar el icono
        try {

            if (move_uploaded_file($file['tmp_name'], $ruta_guardar_archivo)) {
    
                try {

                    global $pdo;
    
                    // Borro el icono viejo del servicio
                    $estadoIMG = checarIMGServicio('icono', $id);
                    if ($estadoIMG['ok'] && file_exists($ruta_carpeta . $estadoIMG['nombre'])) unlink($ruta_carpeta . $estadoIMG['nombre']);
                    
                    // Consulta para modificar servicio
                    $consulta = "UPDATE servicio SET icono = :icon WHERE numero = :num";
                    $stmt = $pdo->prepare($consulta);
                    $stmt->bindParam(':icon', $nuevo_nombre_archivo);
                    $stmt->bindParam(':num', $id);
                    $stmt->execute();
                    $respuestas['enviar'] = "Se actualizó el icono";
                } 
                catch (PDOException $e) {
    
                    $respuestas['error'] = "Ha ocurrido un error: " . $e->getMessage();
                }
            } 
            else $respuestas['error'] = "Error al mover el archivo. Verifica los permisos de la carpeta";
        } 
        catch (Exception $e) {
    
            $respuestas['error'] = "Excepción: " . $e->getMessage();
        }

    header('Content-Type: application/json');
    echo json_encode($respuestas);
    exit();
}
?>