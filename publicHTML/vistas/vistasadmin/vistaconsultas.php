<?php
include("../../backend/conexion.php");
session_start();

//Datos
$consultasFuturas = array();
$consultasPrevias = array();
$consultasActuales = array();

//Identificador
$id = $_SESSION['odontologo']['idodontologo'];

//Zone y Tiempo
$fechaActual = date('Y-m-d');
date_default_timezone_set('America/Argentina/Jujuy');
$horaActual = date('H:i:s');

//Inicio de carga de consultas Futuras
$consulta = "SELECT * FROM consulta WHERE fecha > :fechaActual and idodontologo = :id";
$stmt = $pdo->prepare($consulta);
$stmt->bindParam(':fechaActual', $fechaActual);
$stmt->bindParam(':id', $id);

if ($stmt->execute() && $stmt->rowCount() > 0) {
    while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $consultasFuturas[] = $tupla;
    }
}
//Fin


//Inicio de carga de consultas Futuras
$consulta = "SELECT * FROM consulta WHERE idodontologo = :id AND hora > :horaActual AND fecha = :fechaActual";
$stmt = $pdo->prepare($consulta);
$stmt->bindParam(':id', $id);
$stmt->bindParam(':horaActual', $horaActual);
$stmt->bindParam(':fechaActual', $fechaActual);

if ($stmt->execute() && $stmt->rowCount() > 0) {
    while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $consultasPrevias[] = $tupla;
    }
}
//Fin

?>

<div id="tituconsultascontainer" class="d-flex justify-content-center gx-0 mt-3">

    <h1 id="tituloconsultas" class="text-center">Consultas</h1>

</div>

<div class="separador my-3">

    <hr>
    <span>EN CURSO</span>
    <hr>

</div>
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
<div id="consultasencurso">

    <div class="consulta">

        <div class="horacontainer">

            <span>Hasta:</span>
            <span>15:00</span>

        </div>

        <div class="asuntocontainer">

            <span>Control de Ortodoncia</span>

        </div>

    </div>

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