<?php

include("../../backend/conexion.php");

session_start();

if(isset($_GET['idpaciente'])) {

    $ido = $_SESSION['odontologo']['idodontologo'];
    $idp = $_GET['idpaciente'];

    $consultasPrevias = array();
    $consultasFuturas = array();
    $consultasActuales = array();

    $sql = 'SELECT c.fecha, c.hora, c.asunto FROM consulta_paciente cp JOIN consulta c ON c.fecha = cp.fecha AND c.hora = cp.hora AND c.idodontologo = cp.idodontologo WHERE c.idodontologo = :ido AND cp.idpaciente = :idp AND (c.fecha > CURDATE() OR (c.fecha = CURDATE() AND CURTIME() < c.hora))';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $ido);
    $stmt->bindParam(':idp', $idp);

    if ($stmt->execute() && $stmt->rowCount() > 0) {

        while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $consultasFuturas[] = $tupla;
        }
    }
    $sql = 'SELECT c.fecha, c.hora, c.asunto FROM consulta_paciente cp JOIN consulta c ON c.fecha = cp.fecha AND c.hora = cp.hora AND c.idodontologo = cp.idodontologo WHERE c.idodontologo = :ido AND cp.idpaciente = :idp AND (c.fecha < CURDATE() OR (c.fecha = CURDATE() AND ADDTIME(c.hora, SEC_TO_TIME(c.duracion * 60)) < CURTIME()));';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $ido);
    $stmt->bindParam(':idp', $idp);
    if ($stmt->execute() && $stmt->rowCount() > 0) {

        while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $consultasPrevias[] = $tupla;
        }
    }
    $sql = 'SELECT c.hora, c.asunto FROM consulta_paciente cp JOIN consulta c ON c.fecha = cp.fecha AND c.hora = cp.hora AND c.idodontologo = cp.idodontologo WHERE c.idodontologo = :ido AND cp.idpaciente = :idp AND ((CURDATE() = c.fecha AND CURTIME() BETWEEN c.hora AND ADDTIME(c.hora, SEC_TO_TIME(c.duracion * 60))) OR (CURDATE() = DATE_ADD(c.fecha, INTERVAL 1 DAY) AND CURTIME() < ADDTIME(c.hora, SEC_TO_TIME(c.duracion * 60)) AND ADDTIME(c.hora,SEC_TO_TIME(c.duracion * 60)) > "24:00:00"))';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ido', $ido);
    $stmt->bindParam(':idp', $idp);

    if ($stmt->execute() && $stmt->rowCount() > 0) {

        while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $consultasActuales[] = $tupla;
        }
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