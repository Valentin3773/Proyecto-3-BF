<?php

session_start();

include("../../backend/extractor.php");
include("../../backend/conexion.php");

reloadSession();

if(isset($_SESSION['odontologo'])):

    $ido = $_SESSION['odontologo']['idodontologo'] ?? null;

    $sql = "SELECT h.idhorario, h.horainicio, h.horafinalizacion, h.dia FROM odontologo_horario oh JOIN horario h ON oh.idhorario = h.idhorario WHERE oh.idodontologo = :ido ORDER BY h.dia ASC, h.horainicio ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $ido);
    
    if($stmt->execute() && $stmt->rowCount() > 0) $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
?>

    <h1 class="subtitulo">Mis Horarios</h1>

    <div id="conthorarios">

    <?php 
    
    if(!empty($horarios)) foreach($horarios as $horario) {
        
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

    ?>

    </div>

    <div id="agregarhorariocont" class="d-flex justify-content-center align-items-center mt-4">

        <div id="agregarhorario"><div class="mas"></div></div>

    </div>

<?php endif; ?>