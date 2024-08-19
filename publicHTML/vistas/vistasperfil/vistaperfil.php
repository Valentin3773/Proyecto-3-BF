<?php

session_start();

$usertipo = $_SESSION['paciente'] ? false : true;

$usuario = $_SESSION['paciente'] ?? $_SESSION['odontologo'];

?>

<h1 class="subtitulo">Mi Perfil</h1>

<div id="fotowrapper">

    <div id="fotoperfilcontainer">

        <?php

        if (isset($usuario['foto'])) {

            echo "<img id='fotoperfil' src='{$usuario["foto"]}' alt='Foto de perfil' title='Foto de perfil'>";
        }

        ?>

        <div class="lapizeditar">
            <img src="img/iconosvg/lapiz.svg" alt="Modificar" title="Modificar">
        </div>

    </div>

</div>

<div class="row px-2">

    <div class="col-xl-6 col-lg-6 col-12">

        <div class="datowrapper">

            <label for="nombrecontainer">Nombre</label>

            <div id="nombrecontainer" class="datocontainer col-12">

                <div id="nombre" class="dato">

                    <h4><?= $usuario['nombre'] ?></h4>

                </div>

                <div class="lapizcontainer">

                    <div class="lapizeditar">
                        <img src="img/iconosvg/lapiz.svg" alt="Modificar" title="Modificar">
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

                    <h4><?= $usuario['apellido'] ?></h4>

                </div>

                <div class="lapizcontainer">

                    <div class="lapizeditar">
                        <img src="img/iconosvg/lapiz.svg" alt="Modificar" title="Modificar">
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

                <h4><?= $usuario['telefono'] ?></h4>

            </div>

            <div class="lapizcontainer">

                <div class="lapizeditar">
                    <img src="img/iconosvg/lapiz.svg" alt="Modificar" title="Modificar">
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

                <h4><?= $usuario['direccion'] ?></h4>

            </div>

            <div class="lapizcontainer">

                <div class="lapizeditar">
                    <img src="img/iconosvg/lapiz.svg" alt="Modificar" title="Modificar">
                </div>

            </div>

        </div>

    </div>

    <?php endif; ?>

    <div class="datowrapper">

        <label for="emailcontainer">Email</label>

        <div id="emailcontainer" class="datocontainer col-12">

            <div id="email" class="dato">

                <h4><?= $usuario['email'] ?></h4>

            </div>

            <div class="lapizcontainer">

                <div class="lapizeditar">
                    <img src="img/iconosvg/lapiz.svg" alt="Modificar" title="Modificar">
                </div>

            </div>

        </div>

    </div>

</div>