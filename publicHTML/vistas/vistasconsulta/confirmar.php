<?php

include('../../backend/extractor.php');

session_start();
reloadSession();

if(!isset($_SESSION['paciente'])) exit();

if(!reservaHabilitada($_SESSION['paciente']['idpaciente'])) exit();

?>

<form id="confirmardatos">

    <h1 id="formheader">Confirme los Datos</h1>

    <div id="formbody">

        <div id="detallescontainer">

            <div id="detalles">

                <div class="odontologo campo">

                    <h3 class="clave m-0">Odontologo</h3>

                    <span class="valor"></span>

                </div>

                <div class="fecha campo">

                    <h3 class="clave m-0">Fecha</h3>

                    <span class="valor"></span>

                </div>

                <div class="hora campo">

                    <h3 class="clave m-0">Hora</h3>

                    <span class="valor"></span>

                </div>

                <div id="contenedor_formulario" class="mb-4">

                    <div id="titulo_input"><label for="inasunto" class="text-end row">Asunto</label></div>
                    <div>

                        <input type="text" name="asunto" id="inasunto" title="Ingrese el asunto" placeholder="Escriba el asunto de su consulta" required>
                    
                    </div>

                </div>

            </div>

        </div>

        <div id="btnsmov">

            <button id='anteriorform' type="button" class="mb-4">Anterior</button>

        </div>  

    </div>

</form>