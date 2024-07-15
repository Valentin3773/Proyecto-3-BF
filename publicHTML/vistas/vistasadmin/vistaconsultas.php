<?php

include("../../backend/conexion.php");

session_start();

if(!isset($_SESSION['odontologo'])) header('Location: ../../index.php');

$consultasFuturas = array();
$consultasPrevias = array();
$consultasActuales = array();

if(isset($_GET['idpaciente'])) {

    $nompaciente = "";
    
    $ido = $_SESSION['odontologo']['idodontologo'];
    $idp = isset($_GET['idpaciente']) ? $_GET['idpaciente'] : null;

    $consulta = "SELECT fecha, hora, asunto FROM consulta WHERE idpaciente = :idp AND idodontologo = :ido AND ((fecha > CURDATE()) OR (fecha = CURDATE() AND CURTIME() < hora)) ORDER BY fecha ASC, hora ASC";
    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':idp', $idp);
    $stmt->bindParam(':ido', $ido);
    
    if ($stmt->execute() && $stmt->rowCount() > 0) {

        while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $consultasFuturas[] = $tupla;
        }
    }

    $consulta = "SELECT fecha, hora, asunto FROM consulta WHERE idpaciente = :idp AND idodontologo = :ido AND ((fecha < CURDATE()) OR (fecha = CURDATE() AND ADDTIME(hora, SEC_TO_TIME(duracion * 60)) < CURTIME())) ORDER BY fecha ASC, hora ASC";

    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':ido', $ido);
    $stmt->bindParam(':idp', $idp);

    if ($stmt->execute() && $stmt->rowCount() > 0) {

        while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $consultasPrevias[] = $tupla;
        }
    }

    $consulta = "SELECT ADDTIME(hora, SEC_TO_TIME(duracion * 60)) as horafinalizacion, asunto FROM consulta WHERE idpaciente = :idp AND idodontologo = :ido AND CURDATE() = fecha AND CURTIME() BETWEEN hora AND ADDTIME(hora, SEC_TO_TIME(duracion * 60)) ORDER BY fecha ASC, hora ASC";
    
    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':ido', $ido);
    $stmt->bindParam(':idp', $idp);

    if ($stmt->execute() && $stmt->rowCount() > 0) {

        while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $consultasActuales[] = $tupla;
        }
    }

    $consulta = "SELECT nombre, apellido FROM paciente WHERE idpaciente = :idp";

    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':idp', $idp);
    
    if ($stmt->execute() && $stmt->rowCount() > 0) {

        $tupla = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $nompaciente = $tupla['nombre'] . " " . $tupla['apellido'];
    }
}

?>

<div id="tituconsultascontainer" class="d-flex justify-content-center gx-0 mt-3">

    <h1 id="tituloconsultas" class="text-center">Consultas <?php if(!empty($nompaciente)) echo " de " . $nompaciente; ?> </h1>

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
        $horaA = $consultaActual ['horafinalizacion'];

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

                <span>Hora:</span>
                <span>' . $horaP . '</span>

            </div>

            <div class="asuntocontainer">

                <span>' . $asuntoP . '</span>

            </div>

        </div>';
    }

    ?>

</div>