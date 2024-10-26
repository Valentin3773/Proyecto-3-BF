<?php

include('../conexion.php');
include('../extractor.php');

session_start();
reloadSession();

if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_SESSION['odontologo'])) exit();

$respuesta = array();

function crearServicio() {

    global $pdo;
    global $respuesta;

    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];

    $icono = isset($_FILES['file1']) ? $_FILES['file1'] : null;
    $imagen = isset($_FILES['file2']) ? $_FILES['file2'] : null;    
    $iconoName = icono($icono);
    $imagenName = imagen($imagen);

    if (($iconoName && $imagenName) || ($iconoName && $imagenName == null) || ($iconoName == null && $imagenName)) {
        
        $sql = "INSERT INTO servicio (nombre, descripcion, icono, imagen) VALUES (:nombre, :descripcion, :icono, :img)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':icono', $iconoName);
        $stmt->bindParam(':img', $imagenName);

        if($stmt->execute()) $respuesta["exito"] = "Se ha agregado el servicio y ahora es visible para los pacientes";
        
        else $respuesta["error"] = "Ha ocurrido un error al agregar el servicio";
    } 
    else {

        $sql = "INSERT INTO servicio (nombre, descripcion, icono, imagen) VALUES (:nombre, :descripcion, null, null)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);

        if($stmt->execute()) $respuesta["exito"] = "Se ha agregado el servicio y ahora es visible para los pacientes";
        
        else $respuesta["error"] = "Ha ocurrido un error al agregar el servicio";
    }

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

function icono($icon) {

    global $respuesta;

    if($icon != null) {
        
        $ruta_carpeta = $_SERVER['DOCUMENT_ROOT'] . "/Proyecto-3-BF/publicHTML/backend/almacenamiento/iconservice/";
        $extension = pathinfo($icon['name'], PATHINFO_EXTENSION);
        $nuevo_nombre_archivo = uniqid('img_', true) . '.' . $extension;
        $ruta_guardar_archivo = $ruta_carpeta . $nuevo_nombre_archivo;

        try {

            if (move_uploaded_file($icon['tmp_name'], $ruta_guardar_archivo)) return $nuevo_nombre_archivo;
            
            else return false;  
        } 
        catch (Exception $e) {

            $respuesta['error'] = "Excepción: " . $e->getMessage();
            return false;
        }
    }
}

function imagen($img) {

    global $respuesta;

    if($img != null) {

        $ruta_carpeta = $_SERVER['DOCUMENT_ROOT'] . "/Proyecto-3-BF/publicHTML/backend/almacenamiento/imgservice/";
        $extension = pathinfo($img['name'], PATHINFO_EXTENSION);
        $nuevo_nombre_archivo = uniqid('img_', true) . '.' . $extension;
        $ruta_guardar_archivo = $ruta_carpeta . $nuevo_nombre_archivo;

        try {

            if (move_uploaded_file($img['tmp_name'], $ruta_guardar_archivo)) return $nuevo_nombre_archivo;
            else return false;
        } 
        catch (Exception $e) {

            $respuesta['error'] = "Excepción: " . $e->getMessage();
            return false;
        }
    }
}

crearServicio($pdo);

?>
