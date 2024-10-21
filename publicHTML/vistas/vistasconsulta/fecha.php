<?php

include('../../backend/extractor.php');

session_start();
reloadSession();

if(!isset($_SESSION['paciente'])) exit();

if(!reservaHabilitada($_SESSION['paciente']['idpaciente'])) exit();

?>

<form method="POST" id="elegirfecha">

    <h1 id="formheader">Seleccione una Fecha</h1>

    <div id="formbody">

        <div id="calendariocontainer">

            <div id="calendario">

                <div id="calendarheader">

                    <div id="monthyear">

                        <div class="arrowleft"></div>

                        <h2></h2>

                        <div class="arrowright"></div>

                    </div>

                </div>

                <table id="calendarbody">

                    <colgroup>

                        <col class="columna">
                        <col class="columna">
                        <col class="columna">
                        <col class="columna">
                        <col class="columna">
                        <col class="columna">
                        <col class="columna">

                    </colgroup>

                    <thead id="semana">

                        <tr>

                            <th><span>Lu</span></th>
                            <th><span>Ma</span></th>
                            <th><span>Mi</span></th>
                            <th><span>Ju</span></th>
                            <th><span>Vi</span></th>
                            <th><span>Sa</span></th>
                            <th><span>Do</span></th>

                        </tr>

                    </thead>

                    <tbody></tbody>

                </table>

            </div>

            <h3 class="fechaselec"></h3>

        </div>

        <div id="btnsmov">

            <button id='anteriorform' type="button">Anterior</button>

            <button id='siguienteform' type="button">Siguiente</button>

        </div>

    </div>

</form>