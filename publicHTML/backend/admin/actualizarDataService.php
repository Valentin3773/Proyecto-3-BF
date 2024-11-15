<?php

include("../conexion.php");
include("../extractor.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    subirCambios();
    exit();
} 
else {

    echo "Parece que estas intentando acceder desde algo no esperado :3";
    exit();
}

function subirCambios() {

    global $pdo;

    $respuesta = array();

    $desc = null;
    $titulo = null;
    $id = sanitizar($_POST['id']);

    if (!empty($_POST['descripcion'])) $desc = sanitizar($_POST['descripcion']);

    else if (!empty($_POST['titulo'])) $titulo = sanitizar($_POST['titulo']);

    try {

        if ($titulo != null) {

            if(!preg_match("/^[a-zA-ZÀ-ÿ\s'’`´,-]+$/u", $titulo)) $respuesta["error"] = "El formato del nombre del servicio no es válido";
            else if(strlen($titulo) > 70) $respuesta["error"] = "El nombre del servicio es demasiado largo";
            else if(strlen($titulo) <= 3) $respuesta["error"] = "El nombre del servicio es demasiado corto, debe tener al menos 4 caracteres";

            else {

                $consulta = "UPDATE servicio SET nombre = :titulo WHERE numero = :num";
                $stmt = $pdo->prepare($consulta);
                $stmt->bindParam(':titulo', $titulo);
                $stmt->bindParam(':num', $id);
                $stmt->execute();
                $respuesta["exito"] = "Se actualizó el nombre del servicio";
            }
        }
        else if ($desc != null) {

            if(!preg_match("/^[a-zA-ZÀ-ÿ\s'’`´,.¿?¡!-]+$/u", $desc)) $respuesta["error"] = "El formato de la descripción del servicio no es válido";
            else if(strlen($desc) > 2500) $respuesta["error"] = "La descripción del servicio es demasiado larga";
            else if(strlen($desc) < 20) $respuesta["error"] = "La descripción del servicio es demasiado corta, debe tener al menos 20 caracteres";

            else {

                $consulta = "UPDATE servicio SET descripcion = :descri WHERE numero = :num";
                $stmt = $pdo->prepare($consulta);
                $stmt->bindParam(':descri', $desc);
                $stmt->bindParam(':num', $id);
                $stmt->execute();
                $respuesta["exito"] = "Se actualizó la descripción del servicio";
            }
        }
        else $respuesta["error"] = "Ha ocurrido un error al actualizar el servicio";
    } 
    catch (Exception $e) {

        error_log("Excepción: $e");
    }

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

?>