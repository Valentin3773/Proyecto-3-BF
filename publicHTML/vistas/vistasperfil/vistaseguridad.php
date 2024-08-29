<?php

session_start();

include("../../backend/extractor.php");

reloadSession();

?>

<h1 class="subtitulo">Seguridad</h1>

<!-- <h2 id="titulocambiarpass" class="mt-4">Privacidad</h2> -->

<div id="privacidad">


</div>

<h2 id="titulocambiarpass" class="mt-4">Cambiar Contraseña</h2>

<form method = "POST" id="formcambiar">

    <div id="contenedor_formulario" class="mb-4">

        <div id="titulo_input"><label for="oldpass" class="text-center">Contraseña actual</label></div>
        <div>

            <input type="text" id="oldpass" name="oldpass" title="Ingrese su contraseña actual" placeholder="Ingrese su contraseña actual" required>

        </div>

    </div>
    <div id="contenedor_formulario" class="mb-4">

        <div id="titulo_input"><label for="newpass" class="text-center">Nueva contraseña</label></div>
        <div>

            <input type="text" id="newpass" name="newpass" title="Ingrese su nueva contraseña" placeholder="Ingrese su nueva contraseña" required>

        </div>

    </div>
    <div id="contenedor_formulario" class="mb-4">

        <div id="titulo_input"><label for="oldpass" class="text-center">Repetir nueva contraseña</label></div>
        <div>

            <input type="text" id="newpassagain" name="newpassagain" title="Repita su nueva contraseña" placeholder="Repita su nueva contraseña" required>

        </div>

    </div>

    <div id="cambiarpasscontainer">

        <button class="c-button c-button--gooey"> Cambiar Contraseña
            <div class="c-button__blobs">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </button>
        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" style="display: block; height: 0; width: 0;">
            <defs>
                <filter id="goo">
                    <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"></feGaussianBlur>
                    <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo"></feColorMatrix>
                    <feBlend in="SourceGraphic" in2="goo"></feBlend>
                </filter>
            </defs>
        </svg>

    </div>

</form>