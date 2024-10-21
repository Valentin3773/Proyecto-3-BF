<?php

include('../../backend/extractor.php');

session_start();
reloadSession();

if(!isset($_SESSION['paciente'])) exit();

if(!reservaHabilitada($_SESSION['paciente']['idpaciente'])) exit();

?>

<form id="elegirhora">

    <h1 id="formheader">Seleccione la Hora</h1>

    <div id="formbody">

        <ul id="horasdisponibles"></ul>

        <div id="btnsmov">

            <button id='anteriorform' type="button">Anterior</button>

            <button id='siguienteform' type="button">Siguiente</button>

        </div>

    </div>

</form>