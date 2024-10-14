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
            <textarea type="text" class="form-control" id="descripcion" title="Ingrese la descripción" placeholder="Ingrese la descripción" disabled required></textarea>

        </div>

        <div id="contimg" class="mt-3 mb-5">

            <div id="iconocontainer">

                <span class="leyenda">Icono</span>

                <div id="icowrapper">

                    <img src='img/logaso.png' alt='Icono del servicio' id='icoservicio'>

                    <div id="mdF" class="lapizeditar">
                        <i class="fas fa-upload"></i>
                    </div>
                    <input type="file" id="inFile1" accept="image/*">

                </div>

            </div>
            <div id="imgcontainer">

                <span class="leyenda">Imagen</span>

                <div id="imgwrapper">

                    <img src='img/logaso.png' alt='Imagen del servicio' id='imgservicio'>

                    <div  id="mdF" class="lapizeditar">
                        <i class="fas fa-upload"></i>
                    </div>
                    <input type="file" id="inFile2" accept="image/*">
                </div>
            </div>

        </div>

        <div id="contbtnagregar" class="d-flex justify-content-center align-items-center">

            <button type="button" id="agregarservicio" class="inactivo" disabled>Agregar</button>

        </div>

    </div>

</div>