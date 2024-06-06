<div id="logocontainer" class="container d-flex justify-content-center mt-3">

    <img src="img/logaso.png" alt="Logo Clínica Salud Bucal" class="img-thumbnail">

</div>

<h1 class="text-center w-100 mt-3">Registrarse</h1>

<div class="container pb-4" id="registercontent">

    <form action="./backend/registromanager.php" method="POST" id="formulario" class="mt-3 row p-3">

        <div id="contenedor_formulario_registro" class="mb-3 col-xl-6 col-lg-6 col-12">

            <div id="titulo_input">
                <label for="innombre" class="text-end row">Nombre</label>
            </div>
            <div id="div_input">
                <input type="text" name="nombre" id="innombre" title="Ingrese su nombre" placeholder="Ingrese su nombre" required autofocus>
            </div>

        </div>
        <div id="contenedor_formulario_registro" class="mb-4 col-xl-6 col-lg-6 col-12">

            <div id="titulo_input">
                <label for="inapellido" class="text-end row">Apellido</label>
            </div>
            <div id="div_input">
                <input type="text" name="apellido" id="inapellido" title="Ingrese su apellido" placeholder="Ingrese su apellido" required>
            </div>

        </div>
        <div id="contenedor_formulario_registro" class="mb-4 col-xl-6 col-lg-6 col-12">

            <div id="titulo_input">
                <label for="indocumento" class="text-end row">Documento</label>
            </div>
            <div id="div_input">
                <input type="text" name="documento" id="indocumento" title="Ingrese su documento" placeholder="Ingrese su documento" required>
            </div>

        </div>
        <div id="contenedor_formulario_registro" class="mb-4 col-xl-6 col-lg-6 col-12">

            <div id="titulo_input">
                <label for="intelefono" class="text-end row">Teléfono</label>
            </div>
            <div id="div_input">
                <input type="text" name="telefono" id="intelefono" title="Ingrese su teléfono" placeholder="Ingrese su teléfono" required>
            </div>

        </div>
        <div id="contenedor_formulario_registro" class="mb-4 col-12">

            <div id="titulo_input">
                <label for="indireccion" class="text-end row">Dirección</label>
            </div>
            <div id="div_input">
                <input type="text" name="direccion" id="indireccion" title="Ingrese su dirección" placeholder="Ingrese su dirección">
            </div>

        </div>
        <div id="contenedor_formulario_registro" class="mb-4 col-12">

            <div id="titulo_input">
                <label for="inemail" class="text-end row">Email</label>
            </div>
            <div id="div_input">
                <input type="text" name="email" id="inemail" title="Ingrese su correo electrónico" placeholder="Ingrese su correo electrónico" required>
            </div>

        </div>
        <div id="contenedor_formulario_registro" class="mb-4 col-12">

            <div id="titulo_input">
                <label for="incontrasenia" class="text-end row">Contraseña</label>
            </div>
            <div id="div_input">
                <input type="text" name="contrasenia" id="incontrasenia" title="Ingrese su contraseña" placeholder="Ingrese su contraseña" required>
            </div>

        </div>
        <div id="contenedor_formulario_registro" class="mb-4 col-12 contconcontrasenia">

            <div id="titulo_input">
                <label for="inconcontrasenia" class="text-end row">Confirmar Contraseña</label>
            </div>
            <div id="div_input">
                <input type="text" name="concontrasenia" id="inconcontrasenia" title="Ingrese nuevamente su contraseña" placeholder="Ingrese nuevamente su contraseña" required>
            </div>

        </div>

        <input type="text" id="jejeje" name="jejeje" tabindex="-1">

        <button id="btnregistrarsel" class="registro w-100" type="submit" name="registrarse">Registrarse</button>

    </form>

</div>

</div>