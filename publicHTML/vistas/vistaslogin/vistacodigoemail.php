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

<div class="container" id="containerRecuPass">
    
    <link rel="stylesheet" href="./css/login/recuperarpass.css">
    <div class="text-center">
        <div class="d-flex justify-content-center row text-center">
            <img src="./img/logaso.png" id="logaso" alt="">
            <label for="" id="Serif" class="H1">Recuperar Contraseña | Segunda Parte</label>
        </div>
        <form class="form-control mt-3 pt-4 px-4" id="formRecuPass" action="" method="post" autocomplete="off">
            <label id="Serif" for="" class="H2">Debe Ingresar el codigo que le llego a su correo, ademas de ingresar la nueva constraseña</label>
            <div id="contenedor_formulario" class="mb-4 text-start">
                <div id="titulo_input">
                    <label for="inCodigo" class="text-end row">Código</label>
                </div>
                <div>                                
                    <input id="inCodigo" type="text" name="" title="Ingrese el codigo aquí" placeholder="Ingrese el codigo aquí" >
                </div>
            </div>

            <div id="contenedor_formulario" class="mb-4 text-start">

                <div id="titulo_input">
                    <label for="inContra" class="text-end row">Nueva contraseña</label>
                </div>
                <div>
                    <input id="inContra" type="password" name="" title="Ingrese su nueva contraseña" placeholder="Ingrese su nueva contraseña">
                </div>

            </div>

            <div id="contenedor_formulario" class="mb-4 text-start">

                <div id="titulo_input">
                    <label for="inConcontra" class="text-end row">Repetir nueva contraseña</label>
                </div>
                <div>                    
                    <input id="inConcontra" type="password" name="" title="Repita la nueva contraseña" placeholder="Repita la nueva contraseña">
                </div>

            </div>

            <div id="buttonRecu">
                <button id="contColor" class="btnRecu mt-4 mb-4 btn contBTN text-white H4">Enviar Código</button>
            </div>

        </form>
    </div>

</div>