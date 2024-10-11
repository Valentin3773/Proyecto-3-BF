<button class="volver" id="btnvolver">
    <div class="volver-box">
        <span class="volver-elem">
            <svg viewBox="0 0 46 40" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M46 20.038c0-.7-.3-1.5-.8-2.1l-16-17c-1.1-1-3.2-1.4-4.4-.3-1.2 1.1-1.2 3.3 0 4.4l11.3 11.9H3c-1.7 0-3 1.3-3 3s1.3 3 3 3h33.1l-11.3 11.9c-1 1-1.2 3.3 0 4.4 1.2 1.1 3.3.8 4.4-.3l16-17c.5-.5.8-1.1.8-1.9z"></path>
            </svg>
        </span>
        <span class="volver-elem">
            <svg viewBox="0 0 46 40">
                <path
                    d="M46 20.038c0-.7-.3-1.5-.8-2.1l-16-17c-1.1-1-3.2-1.4-4.4-.3-1.2 1.1-1.2 3.3 0 4.4l11.3 11.9H3c-1.7 0-3 1.3-3 3s1.3 3 3 3h33.1l-11.3 11.9c-1 1-1.2 3.3 0 4.4 1.2 1.1 3.3.8 4.4-.3l16-17c.5-.5.8-1.1.8-1.9z"></path>
            </svg>
        </span>
    </div>
</button>

<div class="container mt-3 py-2" id="containerRecuPass">

    <link rel="stylesheet" href="./css/login/recuperarpass.css">
    <div class="text-center">
        <div class="d-flex justify-content-center row text-center">
            <img src="./img/logaso.png" id="logaso" alt="">
            <h1 class="text-center w-100 mt-3">Recuperar Contraseña</h1>
        </div>
        <form class="form-control mt-3 px-4 py-3" id="formRecuPass" action="" method="post">
            <span>Se enviará un código de verificación a tu casilla de correo electrónico.</span>
            <div id="contenedor_formulario" class="mb-4 mt-3">

                <div id="titulo_input">
                    <label for="inEmail" class="text-end row">Email</label>
                </div>
                <div>
                    <input type="email" name="email" id="inEmail" title="Ingrese su correo electrónico" placeholder="Ingrese su correo electrónico" required autofocus>
                </div>

            </div>
            <div id="buttonRecu">
                <button id="contColor" class="btnRecu my-2 btn contBTN text-white H4">Enviar Código</button>
            </div>
        </form>
        <div id="buttonRecu" class="mt-3 px-3 row justify-content-center">
            <span>¿Has olvidado tu email?</span>
            <button id="contColor" class="btnEmail btn text-white H6 contColorH6">Recuperar correo electrónico</button>
        </div>
    </div>

</div>