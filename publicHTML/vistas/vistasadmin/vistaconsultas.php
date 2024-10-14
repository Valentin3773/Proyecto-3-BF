<?php

include("../../backend/conexion.php");
include("../../backend/extractor.php");

session_start();

if (!isset($_SESSION['odontologo'])) header('Location: ../../index.php');

$consultasFuturas = array();
$consultasPrevias = array();
$consultasActuales = array();

if (isset($_GET['idpaciente'])) {

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

    $consulta = "SELECT ADDTIME(hora, SEC_TO_TIME(duracion * 60)) as horafinalizacion, asunto, fecha, hora FROM consulta WHERE idpaciente = :idp AND idodontologo = :ido AND CURDATE() = fecha AND CURTIME() BETWEEN hora AND ADDTIME(hora, SEC_TO_TIME(duracion * 60)) ORDER BY fecha ASC, hora ASC";

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
} else if (isset($_GET['anio']) && isset($_GET['mes']) && isset($_GET['dia'])) {

    $fecha = DateTime::createFromFormat('Y-m-d', "{$_GET['anio']}-{$_GET['mes']}-{$_GET['dia']}");
    $fechastring = $fecha->format('Y-m-d');
    $ido = $_SESSION['odontologo']['idodontologo'];

    $consulta = "SELECT fecha, hora, asunto FROM consulta WHERE fecha = :fechaelegida AND idodontologo = :ido AND ((fecha > CURDATE()) OR (fecha = CURDATE() AND CURTIME() < hora)) ORDER BY fecha ASC, hora ASC";
    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':fechaelegida', $fechastring);
    $stmt->bindParam(':ido', $ido);

    if ($stmt->execute() && $stmt->rowCount() > 0) while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) $consultasFuturas[] = $tupla;

    $consulta = "SELECT fecha, hora, asunto FROM consulta WHERE fecha = :fechaelegida AND idodontologo = :ido AND ((fecha < CURDATE()) OR (fecha = CURDATE() AND ADDTIME(hora, SEC_TO_TIME(duracion * 60)) < CURTIME())) ORDER BY fecha ASC, hora ASC";

    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':fechaelegida', $fechastring);
    $stmt->bindParam(':ido', $ido);

    if ($stmt->execute() && $stmt->rowCount() > 0) while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) $consultasPrevias[] = $tupla;

    $consulta = "SELECT ADDTIME(hora, SEC_TO_TIME(duracion * 60)) as horafinalizacion, asunto, fecha, hora FROM consulta WHERE fecha = :fechaelegida AND idodontologo = :ido AND CURDATE() = fecha AND CURTIME() BETWEEN hora AND ADDTIME(hora, SEC_TO_TIME(duracion * 60)) ORDER BY fecha ASC, hora ASC";

    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':fechaelegida', $fechastring);
    $stmt->bindParam(':ido', $ido);

    if ($stmt->execute() && $stmt->rowCount() > 0) while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) $consultasActuales[] = $tupla;
} else if (isset($_GET['fecha1']) && isset($_GET['fecha2'])) {

    $fecha1 = new DateTime($_GET['fecha1']);
    $fecha2 = new DateTime($_GET['fecha2']);

    $fecha1 = $fecha1->format('Y-m-d');
    $fecha2 = $fecha2->format('Y-m-d');

    $ido = $_SESSION['odontologo']['idodontologo'];

    $consulta = "SELECT fecha, hora, asunto FROM consulta WHERE (fecha BETWEEN :primerdiasemana AND :ultimodiasemana) AND idodontologo = :ido AND ((fecha > CURDATE()) OR (fecha = CURDATE() AND CURTIME() < hora)) ORDER BY fecha ASC, hora ASC";
    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':primerdiasemana', $fecha1);
    $stmt->bindParam(':ultimodiasemana', $fecha2);
    $stmt->bindParam(':ido', $ido);

    if ($stmt->execute() && $stmt->rowCount() > 0) while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) $consultasFuturas[] = $tupla;

    $consulta = "SELECT fecha, hora, asunto FROM consulta WHERE (fecha BETWEEN :primerdiasemana AND :ultimodiasemana) AND idodontologo = :ido AND ((fecha < CURDATE()) OR (fecha = CURDATE() AND ADDTIME(hora, SEC_TO_TIME(duracion * 60)) < CURTIME())) ORDER BY fecha ASC, hora ASC";

    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':primerdiasemana', $fecha1);
    $stmt->bindParam(':ultimodiasemana', $fecha2);
    $stmt->bindParam(':ido', $ido);

    if ($stmt->execute() && $stmt->rowCount() > 0) while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) $consultasPrevias[] = $tupla;

    $consulta = "SELECT ADDTIME(hora, SEC_TO_TIME(duracion * 60)) as horafinalizacion, asunto, fecha, hora FROM consulta WHERE (fecha BETWEEN :primerdiasemana AND :ultimodiasemana) AND idodontologo = :ido AND CURDATE() = fecha AND CURTIME() BETWEEN hora AND ADDTIME(hora, SEC_TO_TIME(duracion * 60)) ORDER BY fecha ASC, hora ASC";

    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':primerdiasemana', $fecha1);
    $stmt->bindParam(':ultimodiasemana', $fecha2);
    $stmt->bindParam(':ido', $ido);

    if ($stmt->execute() && $stmt->rowCount() > 0) while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) $consultasActuales[] = $tupla;
}

foreach ($consultasActuales as &$consultaActual) {

    $consultaActual['fecha'] = formatDateTime($consultaActual['fecha'], 'Y-m-d', 'd/m/Y');
    $consultaActual['hora'] = formatDateTime($consultaActual['hora'], 'H:i:s', 'H:i');
}

foreach ($consultasFuturas as &$consultaFutura) {

    $consultaFutura['fecha'] = formatDateTime($consultaFutura['fecha'], 'Y-m-d', 'd/m/Y');
    $consultaFutura['hora'] = formatDateTime($consultaFutura['hora'], 'H:i:s', 'H:i');
}

foreach ($consultasPrevias as &$consultaPrevia) {

    $consultaPrevia['fecha'] = formatDateTime($consultaPrevia['fecha'], 'Y-m-d', 'd/m/Y');
    $consultaPrevia['hora'] = formatDateTime($consultaPrevia['hora'], 'H:i:s', 'H:i');
}

if (isset($_GET['idpaciente'])):

?>

    <div id="tituconsultascontainer" class="d-flex justify-content-center gx-0 mt-3">

        <h1 id="tituloconsultas" class="text-center">Consultas <?php if (!empty($nompaciente)) echo " de {$nompaciente}"; ?> </h1>

    </div>

<?php endif; ?>

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
        if (sizeof($consultasFuturas) > 1) echo "<span>FUTURAS</span>";
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
        if (sizeof($consultasPrevias) > 1) echo "<span>PREVIAS</span>";
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
                <span id="asunto">' . $asuntoP . '</span>
            </div>
            <div class="fechahorawrapper">
                <div class="fechacontainer">
                    <span id="fecha">' . $fechaP . '</span>
                </div>
                <div class="horacontainer">
                    <span id="hora">' . $horaP . '</span>
                </div>
            </div>

        </div>';
        }

        ?>

    </div>

<?php
endif;
if (empty($consultasPrevias) && empty($consultasActuales) && empty($consultasFuturas)) echo '<div class="w-100 h-100 d-flex justify-content-center align-items-center"><h1 class="titinformativo">Lo sentimos, no hay consultas disponibles</h1></div>';
?>