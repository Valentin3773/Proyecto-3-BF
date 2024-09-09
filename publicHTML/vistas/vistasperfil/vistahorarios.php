<?php

session_start();

include("../../backend/extractor.php");
include("../../backend/conexion.php");

reloadSession();

if(isset($_SESSION['odontologo'])):

    $ido = $_SESSION['odontologo']['idodontologo'] ?? null;
    
    $sql = "SELECT idhorario, horainicio, horafinalizacion, dia FROM horario WHERE idodontologo = :ido ORDER BY dia ASC, horainicio ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $ido);
    
    if($stmt->execute() && $stmt->rowCount() > 0) $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
?>
    
    <h1 class="subtitulo">Mis Horarios</h1>
    
    <?php 
    
    if(!empty($horarios)) {
        
        echo '
            <div id="leyendas">
                <span class="horas">Horas</span>
                <span class="dia">Día</span>
            </div>
            <div id="conthorarios">
        ';

        foreach($horarios as $horario) {
        
            $horainicio = new DateTime($horario['horainicio']);
            $horainicio = $horainicio->format('H:i');
            $horafinalizacion = new DateTime($horario['horafinalizacion']);
            $horafinalizacion = $horafinalizacion->format('H:i');

            echo "<div id='{$horario["idhorario"]}' class='horario'>

                    <div class='horas'>
                        <span>{$horainicio} - {$horafinalizacion}</span>
                    </div>

                    <div class='dia'>
                        <span>{$semana[$horario["dia"] - 1]}</span>
                    </div>

                </div>
            ";
        }
        echo "</div>";
    }
    else echo "<h3 id='nohorarios'>Aún no tienes ningún horario</h3>";

    ?>

    <div id="agregarhorariocont" class="d-flex justify-content-center align-items-center mt-4">

        <div id="agregarhorario"><div class="mas"></div></div>

    </div>

<?php endif; ?>