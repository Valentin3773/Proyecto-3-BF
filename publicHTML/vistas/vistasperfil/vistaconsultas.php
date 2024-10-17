<?php

include("../../backend/conexion.php");

session_start();

include("../../backend/extractor.php");

reloadSession();

if (!isset($_SESSION['paciente'])) header('Location: ../../index.php');

$consultasFuturas = array();
$consultasPrevias = array();
$consultasActuales = array();

if (isset($_SESSION['paciente']['idpaciente'])) {

    $nompaciente = "";
    $idp = $_SESSION['paciente']['idpaciente'] ?? null;

    $consulta = "SELECT fecha, hora, asunto FROM consulta WHERE idpaciente = :idp AND vigente = 'vigente' AND ((fecha > CURDATE()) OR (fecha = CURDATE() AND CURTIME() < hora)) ORDER BY fecha ASC, hora ASC";

    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':idp', $idp);

    if ($stmt->execute() && $stmt->rowCount() > 0) {

        while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $consultasFuturas[] = $tupla;
        }
    }

    $consulta = "SELECT fecha, hora, asunto FROM consulta WHERE idpaciente = :idp AND vigente = 'vigente' AND ((fecha < CURDATE()) OR (fecha = CURDATE() AND ADDTIME(hora, SEC_TO_TIME(duracion * 60)) < CURTIME())) ORDER BY fecha ASC, hora ASC";

    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':idp', $idp);

    if ($stmt->execute() && $stmt->rowCount() > 0) {

        while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $consultasPrevias[] = $tupla;
        }
    }

    $consulta = "SELECT ADDTIME(hora, SEC_TO_TIME(duracion * 60)) as horafinalizacion, asunto, fecha, hora FROM consulta WHERE idpaciente = :idp AND vigente = 'vigente' AND CURDATE() = fecha AND CURTIME() BETWEEN hora AND ADDTIME(hora, SEC_TO_TIME(duracion * 60)) ORDER BY fecha ASC, hora ASC";

    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':idp', $idp);

    if ($stmt->execute() && $stmt->rowCount() > 0) {

        while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $consultasActuales[] = $tupla;
        }
    }

    $consulta = "SELECT nombre, apellido FROM paciente WHERE idpaciente = :idp AND vigente = 'vigente'";

    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':idp', $idp);

    if ($stmt->execute() && $stmt->rowCount() > 0) {

        $tupla = $stmt->fetch(PDO::FETCH_ASSOC);

        $nompaciente = "{$tupla['nombre']} {$tupla['apellido']}";
    }

    foreach($consultasActuales as &$consultaActual) {

        $consultaActual['fecha'] = formatDateTime($consultaActual['fecha'], 'Y-m-d', 'd/m/Y');
        $consultaActual['hora'] = formatDateTime($consultaActual['hora'], 'H:i:s', 'H:i');
    }

    foreach($consultasFuturas as &$consultaFutura) {

        $consultaFutura['fecha'] = formatDateTime($consultaFutura['fecha'], 'Y-m-d', 'd/m/Y');
        $consultaFutura['hora'] = formatDateTime($consultaFutura['hora'], 'H:i:s', 'H:i');
    }

    foreach($consultasPrevias as &$consultaPrevia) {

        $consultaPrevia['fecha'] = formatDateTime($consultaPrevia['fecha'], 'Y-m-d', 'd/m/Y');
        $consultaPrevia['hora'] = formatDateTime($consultaPrevia['hora'], 'H:i:s', 'H:i');
    }
}

?>

<link rel="stylesheet" href="css/administrador/consultas.css">

<section id="misconsultas">

    <div id="tituconsultascontainer" class="d-flex justify-content-center gx-0">

        <h1 id="tituloconsultas" class="text-center subtitulo">Mis Consultas</h1>

    </div>

    <?php if (!empty($consultasActuales)): ?>

        <div class="separador my-3">

            <hr>
            <span>EN CURSO</span>
            <hr>

        </div>

        <div id="consultasencurso">

            <?php

            foreach ($consultasActuales as $consultaActual) {

                $asuntoA = $consultaActual['asunto'];
                $horaA = $consultaActual['horafinalizacion'];
                $hora = $consultaActual['hora'];
                $fechaA = $consultaActual['fecha'];
                echo '

                    <div class="consulta" data-fecha="' . $fechaA . '" data-hora="' . $hora . '">
                    
                        <div class="asuntocontainer">

                            <span id="asunto">' . $asuntoA . '</span>

                        </div>
                        <div class="horacontainer">

                            <span id="hora">' . $horaA . '</span>

                        </div>

                    </div>

                ';
            }
            ?>

        </div>

    <?php

    endif;
    if (!empty($consultasFuturas)):

    ?>

        <div class="separador my-3">

            <hr>
            <?php 
            if(sizeof($consultasFuturas) > 1) echo "<span>FUTURAS</span>";
            else echo "<span>FUTURA</span>";
            ?>
            <hr>

        </div>

        <div id="consultasfuturas">

            <?php

            foreach ($consultasFuturas as $consultasFutura) {

                $asuntof = $consultasFutura['asunto'];
                $fechaf = $consultasFutura['fecha'];
                $horaf = $consultasFutura['hora'];

                echo '
                    <div class="consulta" data-fecha="' . $fechaf . '" data-hora="' . $horaf . '">
                        <div class="asuntocontainer">
                            <span id="asunto">' . $asuntof . '</span>
                        </div>
                        <div class="fechahorawrapper">
                            <div class="fechacontainer">
                                <span id="fecha">' . $fechaf . '</span>
                            </div>
                            <div class="horacontainer">
                                <span id="hora">' . $horaf . '</span>
                            </div>
                        </div>
                    </div>
                ';
            }
            ?>

        </div>

    <?php

    endif;
    if (!empty($consultasPrevias)):

    ?>

        <div class="separador my-3">

            <hr>
            <?php 
            if(sizeof($consultasPrevias) > 1) echo "<span>PREVIAS</span>";
            else echo "<span>PREVIA</span>";
            ?>
            <hr>

        </div>

        <div id="consultasprevias">

            <?php

            foreach ($consultasPrevias as $consultaPrevia) {

                $asuntoP = $consultaPrevia['asunto'];
                $horaP = $consultaPrevia['hora'];
                $fechaP = $consultaPrevia['fecha'];

                echo '
                    <div class="consulta" data-fecha=' . $fechaP . ' data-hora=' . $horaP . '>
                        
                        <div class="asuntocontainer">
                            <span id="asunto">' . $asuntof . '</span>
                        </div>
                        <div class="fechahorawrapper">
                            <div class="fechacontainer">
                                <span id="fecha">' . $fechaf . '</span>
                            </div>
                            <div class="horacontainer">
                                <span id="hora">' . $horaf . '</span>
                            </div>
                        </div>

                    </div>
                ';
            }

            ?>

        </div>

    <?php 

    endif; 
    if(empty($consultasActuales) && empty($consultasPrevias) && empty($consultasFuturas)):

    ?>
        <div id="noconsultascontainer">
            <div id="noconsultas">

                <h3>Aún no tienes ninguna consulta</h3>

                <a href="reservarconsulta.php" id="btnreservar" title="Reservar una consulta">Reservar<br>Consulta</a>
                
            </div>
        </div>

    <?php endif; ?>

</section>