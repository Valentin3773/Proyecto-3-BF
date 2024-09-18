<?php

include('../../backend/extractor.php');

session_start();
reloadSession();

if(!isset($_SESSION['odontologo'])) exit();

?>

<h1 class="subtitulo">Agregar servicio</h1>

<div class="d-flex justify-content-center align-items-center">

    <div id="contagregarservicio">

        <div id="contnombre">

            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" title="Ingrese el nombre" placeholder="Ingrese el nombre" required>

        </div>

        <div id="contdescripcion">

            <label for="descripcion">Descripción</label>
            <input type="text" class="form-control" id="descripcion" title="Ingrese la descripción" placeholder="Ingrese la descripción" required>

        </div>

        <div id="contimg">
            
        </div>

        <div id="contbtnagregar" class="d-flex justify-content-center align-items-center">

            <button type="button" id="agregarconsulta" class="inactivo" disabled>Agregar</button>

        </div>

    </div>

</div>