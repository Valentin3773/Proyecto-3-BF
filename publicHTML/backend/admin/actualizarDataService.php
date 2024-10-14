<?php 
include("../conexion.php");
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
        subirCambios();
        exit();
    } else {
        echo "Parece que estas intentando acceder desde algo no esperado :3";
        exit();
    }

    function subirCambios(){
        global $pdo;
        $desc = null;
        $titulo = null;
        $id = htmlspecialchars(strip_tags($_POST['id']));

        if(!empty($_POST['descripcion'])){
            $desc = htmlspecialchars(strip_tags($_POST['descripcion']));
        } else if (!empty($_POST['titulo'])) {
            $titulo = htmlspecialchars(strip_tags($_POST['titulo']));
        }
        try {
            if($titulo != null){
                $consulta = "UPDATE servicio SET nombre = :titulo WHERE numero = :num";
                $stmt = $pdo->prepare($consulta);
                $stmt->bindParam(':titulo', $titulo);
                $stmt->bindParam(':num', $id);
                $stmt->execute();
                echo "Se actualizo el titulo";
            } else if ($desc != null) {
                $consulta = "UPDATE servicio SET descripcion = :descri WHERE numero = :num";
                $stmt = $pdo->prepare($consulta);
                $stmt->bindParam(':descri', $desc);
                $stmt->bindParam(':num', $id);
                $stmt->execute();
                echo "Se actualizo la descripción";            
            } else {
                echo "Uhmm parece que sucede algo inesperado ;(";
            }
        } catch (Exception $e){
            echo "Excepcion: $e";
        }
    }
?>