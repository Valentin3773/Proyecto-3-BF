<button class="volver" id="btnvolver" onclick="location.href = './login.php';">
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
                <label for="" id="Serif" class="H1">Recuperar Contraseña</label>
            </div>
            <form class="form-control mt-3 pt-4 px-4" id="formRecuPass" action="" method="post">
                <label id="Serif" for="" class="H2">Se enviará un código de verificación a tu casilla de correo electrónico.</label>
                <div class="form-group row d-flex mt-4">
                    <div class="d-flex">
                        <label for="" id="contColor" class="text-white px-5 rounded-top">Email</label>
                    </div>
                    <div>
                        <input id="inEmail" type="email" class="form-control" name="" id="" value="Ingrese su correo electrónico">
                    </div>
                </div>
                <div id="buttonRecu">
                    <button id="contColor" class="mt-4 mb-4 btn contBTN text-white H4">Enviar Código</button>
                </div>         
            </form>
            <div id="buttonRecu" class="mt-5 row justify-content-center">
                <label for="" id="Serif" class="H5">¿Has olvidado tu email?</label>
                <button id="contColor" class="mt-2 btn text-white H6 contColorH6">Recuperar correo electrónico</button>
            </div>
        </div>
        <form class="form-control mt-3 pt-4 px-4" id="formRecuPass" action="" method="post">
            <label id="Serif" for="" class="H2">Se enviará un código de verificación a tu casilla de correo electrónico.</label>
            <div class="form-group row d-flex mt-4">
                <div class="d-flex">
                    <label for="" id="contColor" class="text-white px-5 rounded-top">Email</label>
                </div>
                <div>
                    <input id="inEmail" type="email" class="form-control" name="" id="" value="Ingrese su correo electrónico">
                </div>
            </div>
            <button id="contColor" class="mt-4 mb-4 btn contBTN text-white H4">Enviar Código</button>
        </form>
        <div class="mt-5 row justify-content-center">
            <label for="" id="Serif" class="H5">¿Has olvidado tu email?</label>
            <button id="contColor" class="mt-2 btn text-white H6">Recuperar correo electrónico</button>
        </div>
    </div>
</div>