<?php

session_start();

include("../../backend/extractor.php");

reloadSession();

if(isset($_SESSION['odontologo'])):

    $ido = $_SESSION['odontologo']['idodontologo'] ?? null;

    $sql = "SELECT idinactividad, tiempoinicio, tiempofinalizacion FROM inactividad WHERE idodontologo = :ido ORDER BY tiempoinicio ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $ido);
    
    if($stmt->execute() && $stmt->rowCount() > 0) $inactividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($inactividades)) $sizeinactividades = sizeof($inactividades);
    else $sizeinactividades = 0;
?>

    <h1 class="subtitulo" data-cantidad="<?= $sizeinactividades ?>">Mis Inactividades</h1>

    <?php
    
    if(!empty($inactividades)) {

        echo "
            <div id='leyendas'>
                <span class='desde'>Desde</span>
                <span class='hasta'>Hasta</span>
            </div>
            <div id='continactividades'>
        ";
        
        foreach($inactividades as $inactividad) {

            $tiempoinicio = new DateTime($inactividad['tiempoinicio']);
            $tiempofinalizacion = new DateTime($inactividad['tiempofinalizacion']);
            $fechainicio = $tiempoinicio->format('d/m/Y');
            $horainicio = $tiempoinicio->format('H:i');
            $fechafinalizacion = $tiempofinalizacion->format('d/m/Y');
            $horafinalizacion = $tiempofinalizacion->format('H:i');
            
            echo "<div id='{$inactividad["idinactividad"]}' class='inactividad'>

                    <div class='desde'>
                        <span>{$fechainicio}</span><hr><span>{$horainicio}</span>
                    </div>

                    <div class='hasta'>
                        <span>{$fechafinalizacion}</span><hr><span>{$horafinalizacion}</span>
                    </div>
                    
                    <div class='tachito invisible' data-inactividad='{$inactividad["idinactividad"]}'><i class='fas fa-trash-alt' style='color: #ffffff;'></i></div>

                </div>
            ";
        }
        echo "</div>";
    }
    else echo "<h3 id='noinactividades'>No tienes ninguna inactividad</h3>";
    ?>

    <div id="agregarinactividadcont" class="d-flex justify-content-center align-items-center mt-4 p-3">

        <div id="agregarinactividad"><div class="mas"></div></div>
        
        <div id="eliminarinactividad" class="invisible"><i class="fas fa-trash-alt" style="color: #ffffff;"></i></div>

    </div>

<?php endif; ?>