<?php 
session_start();
include("../conexion.php");
include("checarIcono_Img.php");

if  ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['odontologo']){
    
    actualizarImagen();
    exit();
}

function actualizarImagen(){
    $respuestas = array();
    $file = $_FILES['file'];
    $id = $_POST['id'];

    $ruta_carpeta = $_SERVER['DOCUMENT_ROOT'] . "/Proyecto-3-BF/publicHTML/backend/almacenamiento/imgservice/";
    
    // Genera un nombre único para que la imagen no se encuentre repetida
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nuevo_nombre_archivo = uniqid('img_', true) . '.' . $extension;
    $ruta_guardar_archivo = $ruta_carpeta . $nuevo_nombre_archivo;

        // Intenta guardar el icono
        try {
           
            if (move_uploaded_file($file['tmp_name'], $ruta_guardar_archivo)) {
    
                try {
                    
                    global $pdo;
    
                    //Borro el icono vieja del servicio
                    $estadoIMG = checarIMGServicio('imagen',$id);
                    if ($estadoIMG['ok'] && file_exists($ruta_carpeta . $estadoIMG['nombre'])) unlink($ruta_carpeta . $estadoIMG['nombre']);
                    
                    // Consulta para modificar servicio
                    $consulta = "UPDATE servicio SET imagen = :img WHERE numero = :num";
                    $stmt = $pdo->prepare($consulta);
                    $stmt->bindParam(':img', $nuevo_nombre_archivo);
                    $stmt->bindParam(':num', $id);
                    $stmt->execute();
                    $respuestas['enviar'] = "Se actualizo el Imagen";
                } 
                catch (PDOException $e) {
    
                    $respuestas['error'] = "Ha ocurrido un error: " . $e->getMessage();
                }
            } else $respuestas['error'] = "Error al mover el archivo. Verifica los permisos de la carpeta.";
        } 
        catch (Exception $e) {
    
            $respuestas['error'] = "Excepción: " . $e->getMessage();
        }

    header('Content-Type: application/json');
    echo json_encode($respuestas);
}
?>