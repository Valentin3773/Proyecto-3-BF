<?php

session_start();

include("../../backend/extractor.php");

reloadSession();

if(isset($_SESSION['odontologo'])):

    $ido = $_SESSION['odontologo']['idodontologo'] ?? null;

    $sql = "SELECT i.idinactividad, i.fechainicio, i.fechafinalizacion, i.tiempoinicio, i.tiempofinalizacion FROM odontologo_inactividad oi JOIN inactividad i ON oi.idinactividad = i.idinactividad WHERE oi.idodontologo = :ido ORDER BY fechainicio ASC, tiempoinicio ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $ido);
    
    if($stmt->execute() && $stmt->rowCount() > 0) $inactividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <h1 class="subtitulo">Mis Inactividades</h1>

    <div id="leyendas">
        <span class="desde">Desde</span>
        <span class="hasta">Hasta</span>
    </div>

    <div id="continactividades">

    <?php 
    
    if(!empty($inactividades)) foreach($inactividades as $inactividad) {

        $horainicio = new DateTime($inactividad['tiempoinicio']);
        $horainicio = $horainicio->format('H:i');
        $horafinalizacion = new DateTime($inactividad['tiempofinalizacion']);
        $horafinalizacion = $horafinalizacion->format('H:i');
        $fechainicio = new DateTime($inactividad['fechainicio']);
        $fechainicio = $fechainicio->format('d/m/Y');
        $fechafinalizacion = new DateTime($inactividad['fechafinalizacion']);
        $fechafinalizacion = $fechafinalizacion->format('d/m/Y');
        
        echo "<div id='{$inactividad["idinactividad"]}' class='inactividad'>

                <div class='desde'>
                    <span>{$fechainicio}</span><hr><span>{$horainicio}</span>
                </div>

                <div class='hasta'>
                    <span>{$fechafinalizacion}</span><hr><span>{$horafinalizacion}</span>
                </div>

              </div>
        ";
    }
    ?>

    </div>

    <div id="agregarinactividadcont" class="d-flex justify-content-center align-items-center mt-4">

        <div id="agregarinactividad"><div class="mas"></div></div>

    </div>

<?php endif; ?>