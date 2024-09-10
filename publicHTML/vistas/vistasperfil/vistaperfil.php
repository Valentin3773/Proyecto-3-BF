<?php

session_start();

include("../../backend/extractor.php");

reloadSession();

$usertipo = isset($_SESSION['paciente']) ? false : true;

$usuario = isset($_SESSION['paciente']) ? $_SESSION['paciente'] : $_SESSION['odontologo'];

?>

<h1 class="subtitulo" id="$$$" data-type="$<?= $usertipo ?>">Mi Perfil</h1>

<div id="fotowrapper">

    <div id="fotoperfilcontainer">

        <?php

        if (isset($usuario['foto'])) echo "<img id='fotoperfil' src='backend/almacenamiento/fotosdeperfil/$usuario[foto]' alt='Foto de perfil' title='Foto de perfil'>";
        
        else echo "<img id='fotoperfil' src='img/iconoperfil.png' alt='Foto de perfil' title='Foto de perfil'>";

        ?>

        <div class="lapizeditar">
            <img src="img/iconosvg/lapiz.svg" id="mdF" alt="Modificar" title="Modificar">
            <input type="file" id="inFile" accept="image/*">
        </div>
        <div id="fotoperfilIMG"></div>
    </div>

</div>

<div class="row px-2" id="contenedorPadre">

    <div class="col-xl-6 col-lg-6 col-12">

        <div class="datowrapper">

            <label for="nombrecontainer">Nombre</label>

            <div id="nombrecontainer" class="datocontainer col-12">

                <div id="nombre" class="dato">

                    <input type="text" value="<?= $usuario['nombre'] ?>" id="inNombre" disabled>

                </div>

                <div class="lapizcontainer">

                    <div class="lapizeditar">
                        <img src="img/iconosvg/lapiz.svg" id="mdN" alt="Modificar" title="Modificar">
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-xl-6 col-lg-6 col-12">

        <div class="datowrapper">

            <label for="apellidocontainer">Apellido</label>

            <div id="apellidocontainer" class="datocontainer col-12">

                <div id="apellido" class="dato">

                <input type="text" value="<?= $usuario['apellido'] ?>" id="inApellido" disabled>

                </div>

                <div class="lapizcontainer">

                    <div class="lapizeditar">
                        <img src="img/iconosvg/lapiz.svg" id="mdA" alt="Modificar" title="Modificar">
                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php if(!$usertipo && isset($usuario['telefono'])): ?>

    <div class="datowrapper">

        <label for="telefonocontainer">Teléfono</label>

        <div id="telefonocontainer" class="datocontainer col-12">

            <div id="telefono" class="dato">

             <input type="text" value="<?= $usuario['telefono'] ?>" id="inTelefono" disabled>

            </div>

            <div class="lapizcontainer">

                <div class="lapizeditar">
                    <img src="img/iconosvg/lapiz.svg" id="mdT" alt="Modificar" title="Modificar">
                </div>

            </div>

        </div>

    </div>

    <?php 

    endif; 
    if(!$usertipo && isset($usuario['direccion'])):

    ?>

    <div class="datowrapper">

        <label for="direccioncontainer">Dirección</label>

        <div id="direccioncontainer" class="datocontainer col-12">

            <div id="direccion" class="dato">

             <input type="text" value="<?= $usuario['direccion'] ?>" id="inDireccion" disabled>

            </div>

            <div class="lapizcontainer">

                <div class="lapizeditar">
                    <img src="img/iconosvg/lapiz.svg" id="mdD" alt="Modificar" title="Modificar">
                </div>

            </div>

        </div>

    </div>

    <?php endif; ?>

    <div class="datowrapper">

        <label for="emailcontainer">Email</label>

        <div id="emailcontainer" class="datocontainer col-12">

            <div id="email" class="dato">

             <input type="text" value="<?= $usuario['email'] ?>" id="inEmail" disabled>

            </div>

            <div class="lapizcontainer">

                <div class="lapizeditar">
                    <img src="img/iconosvg/lapiz.svg" id="mdE" alt="Modificar" title="Modificar">
                </div>

            </div>

        </div>

    </div>
    <div id="btnGC"></div>
</div>