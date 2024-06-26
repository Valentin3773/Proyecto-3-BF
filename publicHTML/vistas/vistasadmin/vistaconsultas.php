<?php

include("../../backend/conexion.php");

session_start();

$id = $_SESSION['odontologo']['idodontologo'];

$sql = 'SELECT fecha, hora, asunto FROM consulta WHERE fecha > CURDATE() AND hora > CURTIME() AND idodontologo = :ido';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':ido', $id);

if ($stmt->execute() && $stmt->rowCount() > 0) {

    while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $consultasFuturas[] = $tupla;
    }
}
$sql = 'SELECT fecha, hora, asunto FROM consulta WHERE fecha < SELECT CURDATE() AND hora < CURTIME() AND idodontologo = :ido';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':ido', $id);
if ($stmt->execute() && $stmt->rowCount() > 0) {

    while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $consultasPrevias[] = $tupla;
    }
}
$sql = 'SELECT hora, asunto FROM consulta WHERE fecha = CURDATE() AND hora > CURTIME() AND idodontologo = :ido';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':ido', $id);

if ($stmt->execute() && $stmt->rowCount() > 0) {

    while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $consultasActuales[] = $tupla;
    }
}

?>

<div id="tituconsultascontainer" class="d-flex justify-content-center gx-0 mt-3">

    <h1 id="tituloconsultas" class="text-center">Consultas</h1>

</div>

<div class="separador my-3">

    <hr>
    <span>EN CURSO</span>
    <hr>

</div>

<div id="consultasencurso">

    <?php

    foreach ($consultasActuales as $consultaActual) {

        $asuntoA = $consultaActual['asunto'];
        $horaA = $consultaActual ['hora'];

        echo '

        <div id="consultasencurso">

            <div class="consulta">

                <div class="horacontainer">

                    <span>Hasta:</span>
                    <span>'.$horaA.'</span>

                </div>

                <div class="asuntocontainer">

                    <span>'.$asuntoA.'</span>

                </div>

            </div>

        </div>

        ';

    }
    ?>

</div>

<div class="separador my-3">

    <hr>
    <span>FUTURAS</span>
    <hr>

</div>

<div id="consultasfuturas">

    <?php

    foreach ($consultasFuturas as $consultasFutura) {
        
        $asuntof = $consultasFutura['asunto'];
        $fechaf = $consultasFutura['fecha'];
        $horaf = $consultasFutura ['hora'];

    echo '
        <div class="consulta">

            <div class="fechacontainer">

                <span>Fecha:</span>
                <span>'.$fechaf.'</span>

            </div>

            <div class="horacontainer">

                <span>Hora:</span>
                <span>'.$horaf.'</span>

            </div>

            <div class="asuntocontainer">

                <span>'.$asuntof.'</span>

            </div>

        </div>

    ';

    }

?>

</div>

<div class="separador my-3">

    <hr>
    <span>PREVIAS</span>
    <hr>

</div>

<div id="consultasprevias">

    <?php

    foreach ($consultasPrevias as $consultaPrevia) {
        
        $asuntoP = $consultaPrevia['asunto'];
        $horaP = $consultaPrevia['hora'];
        $fechaP = $consultaPrevia['fecha'];

        echo '
        <div class="consulta">
            
            <div class="fechacontainer">

                <span>Fecha:</span>
                <span>'.$fechaP.'</span>

            </div>

            <div class="horacontainer">

                <span>Hasta:</span>
                <span>' . $horaP . '</span>

            </div>

            <div class="asuntocontainer">

                <span>' . $asuntoP . '</span>

            </div>

        </div>';
    }

    ?>

</div>