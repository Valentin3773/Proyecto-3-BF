<?php
session_start();

if(isset($_SESSION['paciente'])) {
    
   // echo $_SESSION['paciente']['nombre'];
}

?>

<!DOCTYPE html>

<html lang="es">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- JQuery -->
        <script src="js/jquery-3.7.1.min.js"></script>
        <!-- Bootstrap -->
        <link rel="stylesheet" href="css/bootstrap-5.2.3-dist/css/bootstrap.min.css">
        <script defer src="css/bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
        <!-- Slick -->
        <link rel="stylesheet" href="css/slick/slick.css">
        <script defer src="css/slick/slick.min.js"></script>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="css/fontawesome-free-5.15.4-web/css/all.min.css">
        <script defer src="css/fontawesome-free-5.15.4-web/js/all.min.js"></script>

        <!-- Favicon -->
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">

        <!-- CSS -->
        <link rel="stylesheet" href="css/lobby/headerstyle.css">
        <link id="seccionescss" rel="stylesheet" href="css/lobby/inicio.css">

        <!-- Scripts -->
        <script src="js/preloader.js"></script>
        <script defer src="js/lobby/headerscript.js"></script>
        <script defer src="js/lobby/servicios.js"></script>
        <script defer src="js/lobby/lobby.js"></script>

        <title>Clínica Salud Bucal ❤️</title>

    </head>

    <body>

        <div id="preloader" class="d-flex justify-content-center align-items-center">

            <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        
        </div>

        <div id="container" class="hidden">

            <header class="extendido">
                
                <div class="left">
                    
                    <a href="javascript:void(0)" id="logo" title="Ir al inicio">

                        <img src="img/logaso.png" alt="Logo" id="imglogo">

                    </a>
                    
                    <div id="titulogo">

                        <h1 class="fs-4"><span class="s1">Clínica</span> <span class="s2">Salud</span> <span class="s3">Bucal</span></h1>
                        <h2 class="fs-6 mb-0">Implantes, Cirugía & Ortodoncia</h2>

                    </div>

                </div>

                <div class="right">

                    <nav>

                        <ul class="mb-0">

                            <li>
                                <a href="javascript:void(0)" id="nosotros" title="Nosotros">¿Quiénes somos?</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" id="servicios" title="Servicios">Servicios</a>
                            </li>

                            <li>
                                <a href="javascript:void(0)" id="contacto" title="Contacto">Contacto</a>
                            </li>

                        </ul>

                    </nav>

                    <div class="contReservar lh-1">

                        <a href="" id="btnreservar" title="Reservar una consulta">Reservar Consulta</a>

                    </div>

                    <a href="login.php" title="Mi perfil" id="btnperfil">

                        <img src="img/iconoperfil.png" alt="Mi Perfil">

                    </a>
                    <div id="opcionescontainer">

                        <a href="javascript:void(0)" id="btnopciones" title="Desplegar menú">

                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">

                                <path stroke="black" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/>

                            </svg>

                        </a>

                    </div>
                    
                </div>
        
            </header>
            
            <div id="navmobile" class="d-none">
                
                <ul class="mb-0" id="navmul">

                    <li>                    
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path d="M18.121,9.88l-7.832-7.836c-0.155-0.158-0.428-0.155-0.584,0L1.842,9.913c-0.262,0.263-0.073,0.705,0.292,0.705h2.069v7.042c0,0.227,0.187,0.414,0.414,0.414h3.725c0.228,0,0.414-0.188,0.414-0.414v-3.313h2.483v3.313c0,0.227,0.187,0.414,0.413,0.414h3.726c0.229,0,0.414-0.188,0.414-0.414v-7.042h2.068h0.004C18.331,10.617,18.389,10.146,18.121,9.88 M14.963,17.245h-2.896v-3.313c0-0.229-0.186-0.415-0.414-0.415H8.342c-0.228,0-0.414,0.187-0.414,0.415v3.313H5.032v-6.628h9.931V17.245z M3.133,9.79l6.864-6.868l6.867,6.868H3.133z"></path>
                        </svg>
                        <a href="#" id="nosotrosm" title="¿Quiénes somos?">¿Quiénes somos?</a>
                    </li>
                    <hr>
                    <li>
                        <img src="img/iconosvg/toothbrush-and-paste-svgrepo-com.svg" alt="">
                        <a href="#" id="serviciosm" title="Servicios">Servicios</a>
                    </li>
                    <hr>
                    <li>
                        <img src="img/iconosvg/contact-chatting-communication-svgrepo-com.svg" alt="">
                        <a href="#" id="contactom" title="Contacto">Contacto</a>
                    </li>
                    <hr>
                    <li>
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path d="M12.075,10.812c1.358-0.853,2.242-2.507,2.242-4.037c0-2.181-1.795-4.618-4.198-4.618S5.921,4.594,5.921,6.775c0,1.53,0.884,3.185,2.242,4.037c-3.222,0.865-5.6,3.807-5.6,7.298c0,0.23,0.189,0.42,0.42,0.42h14.273c0.23,0,0.42-0.189,0.42-0.42C17.676,14.619,15.297,11.677,12.075,10.812 M6.761,6.775c0-2.162,1.773-3.778,3.358-3.778s3.359,1.616,3.359,3.778c0,2.162-1.774,3.778-3.359,3.778S6.761,8.937,6.761,6.775 M3.415,17.69c0.218-3.51,3.142-6.297,6.704-6.297c3.562,0,6.486,2.787,6.705,6.297H3.415z"></path>
                        </svg>
                        <a href="#" id="btnperfilm" title="Contacto">Mi perfil</a>
                    </li>

                </ul>

            </div>

            <div id="hrelleno"></div>

            <main class=""></main>
            
            <div id="btnchat" title="Envíanos un mensaje">
                
                <img src="img/iconosvg/whatsapp.svg" alt="Ícono de chat">

            </div>

            <div id="btnup">

                <i class="fas fa-arrow-up"></i>

            </div>

            <div id="modal" class="oculto d-flex flex-column justify-content-center align-items-center gap-3">

                <div id="modalcontainer">

                    gregergreg

                </div>

                <button id="closemodal">Cerrar</button>

            </div>

            <footer>

                <div id="contFooter">

                    <img src="img/logaso.png" alt="logofooter" id="logofooter" title="Clínica Salud Bucal">
                    <p class="mb-0"> 

                        &copy;2024 <span id="ftFont"> Clínica Salud Bucal </span> | Developed by <a href="" target="_blank"><img id="logopa" src="img/logopa.png" alt="La Program Army"></a>
                    
                    </p>

                </div>
                
            </footer>

        </div>

    </body>

</html>