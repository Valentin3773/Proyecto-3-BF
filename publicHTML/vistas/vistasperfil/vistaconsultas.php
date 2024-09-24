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

    $consulta = "SELECT fecha, hora, asunto FROM consulta WHERE idpaciente = :idp AND ((fecha > CURDATE()) OR (fecha = CURDATE() AND CURTIME() < hora)) ORDER BY fecha ASC, hora ASC";

    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':idp', $idp);

    if ($stmt->execute() && $stmt->rowCount() > 0) {

        while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $consultasFuturas[] = $tupla;
        }
    }

    $consulta = "SELECT fecha, hora, asunto FROM consulta WHERE idpaciente = :idp AND ((fecha < CURDATE()) OR (fecha = CURDATE() AND ADDTIME(hora, SEC_TO_TIME(duracion * 60)) < CURTIME())) ORDER BY fecha ASC, hora ASC";

    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':idp', $idp);

    if ($stmt->execute() && $stmt->rowCount() > 0) {

        while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $consultasPrevias[] = $tupla;
        }
    }

    $consulta = "SELECT ADDTIME(hora, SEC_TO_TIME(duracion * 60)) as horafinalizacion, asunto, fecha, hora FROM consulta WHERE idpaciente = :idp AND CURDATE() = fecha AND CURTIME() BETWEEN hora AND ADDTIME(hora, SEC_TO_TIME(duracion * 60)) ORDER BY fecha ASC, hora ASC";

    $stmt = $pdo->prepare($consulta);
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

        $nompaciente = "{$tupla['nombre']} {$tupla['apellido']}";
    }
}

?>

<link rel="stylesheet" href="css/administrador/consultas.css">

<section id="misconsultas">

    <div id="tituconsultascontainer" class="d-flex justify-content-center gx-0">

        <h1 id="tituloconsultas" class="text-center">Mis Consultas</h1>

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

                        <div class="horacontainer">

                            <span>Hasta:</span>
                            <span id="hora">' . $horaA . '</span>

                        </div>

                        <div class="asuntocontainer">

                            <span id="asunto">' . $asuntoA . '</span>

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
                        <div class="fechacontainer">
                            <span>Fecha:</span>
                            <span id="fecha">' . $fechaf . '</span>
                        </div>
                        <div class="horacontainer">
                            <span>Hora:</span>
                            <span id="hora">' . $horaf . '</span>
                        </div>
                        <div class="asuntocontainer">
                            <span id="asunto">' . $asuntof . '</span>
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
                        
                        <div class="fechacontainer">

                            <span>Fecha:</span>
                            <span id="fecha">' . $fechaP . '</span>

                        </div>

                        <div class="horacontainer">

                            <span>Hora:</span>
                            <span id="hora">' . $horaP . '</span>

                        </div>

                        <div class="asuntocontainer">

                            <span id="asunto">' . $asuntoP . '</span>

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