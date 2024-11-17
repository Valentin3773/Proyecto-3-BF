<?php 

function checarIMGServicio($tipo, $id) {

    global $pdo;

    $estado = array(

        'ok' => false,
        'nombre' => ''
    );

    try {

        if($tipo == 'icono') {

            $consulta = "SELECT icono FROM servicio WHERE numero = :num";
            $stmt = $pdo->prepare($consulta);
            $stmt->bindParam(':num', $id);

            if ($stmt->execute() && $stmt->rowCount() > 0) {

                $estado['nombre'] = $stmt->fetch(PDO::FETCH_ASSOC)['icono'];
                if(isset($estado['nombre'])) $estado['ok'] = true;
                else $estado['ok'] = false;
            }

        } 
        else if ($tipo == 'imagen') {

            $consulta = "SELECT imagen FROM servicio WHERE numero = :num";
            $stmt = $pdo->prepare($consulta);
            $stmt->bindParam(':num', $id);

            if ($stmt->execute() && $stmt->rowCount() > 0) {

                $estado['nombre'] = $stmt->fetch(PDO::FETCH_ASSOC)['imagen'];
                if(isset($estado['nombre'])) $estado['ok'] = true;
                else $estado['ok'] = false;
            }
        }
    } 
    catch (Exception $e) {

        $estado['nombre'] = $e;
    }
    return $estado;
}
?>