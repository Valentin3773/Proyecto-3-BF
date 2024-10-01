<?php

include("../../backend/extractor.php");

session_start();
reloadSession();

if(isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) {

    if($_SESSION['paciente']['nomolestar'] == 0) $checked = "checked";

    else $checked = "";
}

if(!isset($_SESSION['paciente']['verificador']) || (isset($_SESSION['paciente']['verificador']) && $_SESSION['paciente']['verificador'] != 'verificado')) $verificado = false;

else $verificado = true;

?>

<h1 class="subtitulo">Seguridad</h1>

<?php if(isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])): ?>

    <h2 id="tituloprivacidad" class="mt-4">Privacidad</h2>

    <form id="privacidad" class="d-flex justify-content-center align-items-center gap-4">

        <label for="nomolestar" class="form-check-label fs-4">¿Desea recibir notificaciones?</label>
        <input type="checkbox" name="nomolestar" id="nomolestar" class="form-check-input m-0" required <?= $checked ?> >

    </form>

    <h2 id="tituloverificar" class="mt-4">Verificación de email</h2>

    <form id="verificar" class="d-flex justify-content-center align-items-center gap-4">

        <?php if(!$verificado) { ?>
            
            <button type="button" id="verificaremail" name="verificaremail" title="Verificar email" required>Verificar email</button>
            
        <?php } else { ?>

            <span class="fs-4">Su email está verificado</span>

        <?php } ?>

    </form>

<?php endif; ?>

<h2 id="titulocambiarpass" class="mt-4">Cambiar contraseña</h2>

<form method="POST" id="formcambiar">

    <div id="contenedor_formulario" class="mb-4">

        <div id="titulo_input"><label for="oldpass" class="text-center">Contraseña actual</label></div>
        <div>

            <input type="password" id="oldpass" name="oldpass" title="Ingrese su contraseña actual" placeholder="Ingrese su contraseña actual" required>

        </div>

    </div>
    <div id="contenedor_formulario" class="mb-4">

        <div id="titulo_input"><label for="newpass" class="text-center">Nueva contraseña</label></div>
        <div>

            <input type="password" id="newpass" name="newpass" title="Ingrese su nueva contraseña" placeholder="Ingrese su nueva contraseña" required>

        </div>

    </div>
    <div id="contenedor_formulario" class="mb-4">

        <div id="titulo_input"><label for="oldpass" class="text-center">Repetir nueva contraseña</label></div>
        <div>

            <input type="password" id="newpassagain" name="newpassagain" title="Repita su nueva contraseña" placeholder="Repita su nueva contraseña" required>

        </div>

    </div>

    <div id="cambiarpasscontainer">

        <button type="submit" id="cambiarpass">Cambiar contraseña</button>

    </div>

</form>