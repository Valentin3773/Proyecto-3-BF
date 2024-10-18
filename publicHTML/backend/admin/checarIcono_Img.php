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
                $estado['ok'] = true;
            }

        } 
        else if ($tipo == 'imagen') {

            $consulta = "SELECT imagen FROM servicio WHERE numero = :num";
            $stmt = $pdo->prepare($consulta);
            $stmt->bindParam(':num', $id);

            if ($stmt->execute() && $stmt->rowCount() > 0) {

                $estado['nombre'] = $stmt->fetch(PDO::FETCH_ASSOC)['imagen'];
                $estado['ok'] = true;
            }
        }
    } 
    catch (Exception $e) {

        $estado['nombre'] = $e;
    }
    return $estado;
}
?>