<?php

include('backend/extractor.php');

session_start();

reloadSession();

$vista = $_GET['vista'] ?? 'inicio';

$urlbase = getUrlDominio();

?>

<!DOCTYPE html>

<html lang="es">

<head>
    <base href="<?= $urlbase ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="WZQCoGwsuUGYiYGQOZBqDICW3ZsbFxuc-ML5QG8_OW0" />
    <meta name="description" content="Ofrecemos atención odontológica de alta calidad">
    <meta name="Inicio">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-408EQQ11WH"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-408EQQ11WH');
    </script>
    
    <!-- Consent Manager -->
    <script async type="text/javascript" data-cmp-ab="1" src="https://cdn.consentmanager.net/delivery/autoblocking/945e0f3de1d2a.js" data-cmp-host="d.delivery.consentmanager.net" data-cmp-cdn="cdn.consentmanager.net" data-cmp-codesrc="16"></script>
    
    <!-- Librerías -->
    <script src="lib/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="lib/bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <script defer src="lib/bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="lib/slick/slick.css">
    <script defer src="lib/slick/slick.min.js"></script>
    <link rel="stylesheet" href="lib/fontawesome-free-5.15.4-web/css/all.min.css">
    <script defer src="lib/fontawesome-free-5.15.4-web/js/all.min.js"></script>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    
    <!-- CSS -->
    <link rel="stylesheet" href="css/lobby/headerstyle.css">
    <link id="seccionescss" rel="stylesheet" href="css/lobby/inicio.css">
    
    <!-- Scripts -->
    <script> history.pushState(null, 'Lobby', '/lobby'); </script>
    <script src="js/preloader.js"></script>
    <script src="js/utilidades.js"></script>
    <script defer src="js/lobby/headerscript.js"></script>
    <script defer src="js/lobby/servicios.js"></script>
    <script defer src="js/lobby/lobby.js"></script>
    
    <link rel="canonical" href="https://clinica-local.ddns.net/Proyecto-3-BF/publicHTML/index.php" />
    <title>Clínica Salud Bucal</title>
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W3NSM9G6"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div id="preloader" class="d-flex justify-content-center align-items-center">

        <div class="lds-spinner">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>

    </div>

    <div id="container" class="hidden">

        <header class="extendido">

            <div class="left">

                <a href="javascript:void(0)" id="logo" title="Ir al inicio">

                    <img src="img/logaso.png" alt="Logo" id="imglogo" class="img-thumbnail">

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

                    <a href="reservarconsulta.php" id="btnreservar" title="Reservar una consulta">Reservar Consulta</a>

                </div>

                <a href="javascript:void(0)" title="Mi perfil" id="btnperfil">

                    <?php

                    if (!isset($_SESSION['paciente']) || (isset($_SESSION['paciente']) && !isset($_SESSION['paciente']['foto']))) {

                        echo "<img src='img/iconoperfil.png' alt='Foto de Perfil' title='Mi Perfil'>";
                    } else echo "<img src='backend/almacenamiento/fotosdeperfil/{$_SESSION["paciente"]["foto"]}' alt='Foto de Perfil' title='Mi Perfil'>";

                    ?>

                    <?php

                    if (!isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) {

                    ?>

                        <ul class="invisible menuperfilm" id="menunoiniciado">

                            <li><button id="iniciar">Iniciar sesión</button></li>
                            <li><button id="iniciargoogle" onclick="window.location.href='./loginGoogle/index.php'">Iniciar con Google <img src="img/iconosvg/google.svg" alt="Google Icon"></button></li>

                            <hr>

                            <li><button id="registrarse">Registrarse</button></li>

                        </ul>

                    <?php

                    } else {

                    ?>

                        <ul class="invisible menuperfilm" id="menuiniciado">

                            <li><button id="miperfil">Mi Perfil</button></li>
                            <?php if (isset($_SESSION['paciente'])): ?> <li><button id="misconsultas">Mis Consultas</button></li> <?php endif; ?>

                            <?php if (isset($_SESSION['odontologo'])): ?>

                                <li><button id="mishorarios">Mis Horarios</button></li>
                                <li><button id="misinactividades">Mis Inactividades</button></li>
                                <li><button id="administrador">Administrador</button></li>

                            <?php endif; ?>

                            <hr>

                            <li><button id="cerrarsesion"><i class="fas fa-sign-out-alt"></i>&nbsp;Cerrar Sesión</button></li>

                        </ul>

                    <?php } ?>

                </a>

                <div id="opcionescontainer">

                    <a href="javascript:void(0)" id="btnopciones" title="Desplegar menú">

                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">

                            <path stroke="black" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14" />

                        </svg>

                        <div id="navmobile" class="d-none">

                            <ul class="mb-0" id="navmul">

                                <li>
                                    <a href="javascript:void(0)" id="nosotrosm" title="¿Quiénes somos?">¿Quiénes somos?</a>
                                </li>
                                <hr>
                                <li>
                                    <img src="img/iconosvg/toothbrush-and-paste-svgrepo-com.svg" alt="">
                                    <a href="javascript:void(0)" id="serviciosm" title="Servicios">Servicios</a>
                                </li>
                                <hr>
                                <li>
                                    <img src="img/iconosvg/contact-chatting-communication-svgrepo-com.svg" alt="">
                                    <a href="javascript:void(0)" id="contactom" title="Contacto">Contacto</a>
                                </li>
                                <hr>
                                <li>
                                    <svg class="svg-icon" viewBox="0 0 20 20" fill="white">
                                        <path d="M12.075,10.812c1.358-0.853,2.242-2.507,2.242-4.037c0-2.181-1.795-4.618-4.198-4.618S5.921,4.594,5.921,6.775c0,1.53,0.884,3.185,2.242,4.037c-3.222,0.865-5.6,3.807-5.6,7.298c0,0.23,0.189,0.42,0.42,0.42h14.273c0.23,0,0.42-0.189,0.42-0.42C17.676,14.619,15.297,11.677,12.075,10.812 M6.761,6.775c0-2.162,1.773-3.778,3.358-3.778s3.359,1.616,3.359,3.778c0,2.162-1.774,3.778-3.359,3.778S6.761,8.937,6.761,6.775 M3.415,17.69c0.218-3.51,3.142-6.297,6.704-6.297c3.562,0,6.486,2.787,6.705,6.297H3.415z"></path>
                                    </svg>
                                    <a href="javascript:void(0)" id="btnperfilm" title="Contacto">Mi perfil</a>
                                </li>

                            </ul>

                        </div>

                        <?php

                        if (!isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) {

                        ?>

                            <ul class="invisible menuperfilm" id="menunoiniciadom">

                                <li><button id="iniciar">Iniciar sesión</button></li>
                                <li><button id="iniciargoogle">Iniciar con Google <img src="img/iconosvg/google.svg" alt="Google Icon"></button></li>

                                <hr>

                                <li><button id="registrarse">Registrarse</button></li>

                            </ul>

                        <?php

                        } else {

                        ?>

                            <ul class="invisible menuperfilm" id="menuiniciadom">

                                <li><button id="miperfil">Mi Perfil</button></li>
                                <?php if (isset($_SESSION['paciente'])): ?> <li><button id="misconsultas">Mis Consultas</button></li> <?php endif; ?>

                                <?php if (isset($_SESSION['odontologo'])): ?>

                                    <li><button id="mishorarios">Mis Horarios</button></li>
                                    <li><button id="misinactividades">Mis Inactividades</button></li>
                                    <li><button id="administrador">Administrador</button></li>

                                <?php endif; ?>

                                <hr>

                                <li><button id="cerrarsesion"><i class="fas fa-sign-out-alt"></i>&nbsp;Cerrar Sesión</button></li>

                            </ul>

                        <?php } ?>

                    </a>

                </div>

            </div>

        </header>

        <div id="hrelleno"></div>

        <main data-vista="<?= $vista ?>"> <?php include("vistas/vistaslobby/vistainicio.php") ?> </main>

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

                    &copy;2024 <span id="ftFont"> Clínica Salud Bucal </span> | Developed by <a href="aboutUs/aboutus.php" target="_blank"><img id="logopa" src="img/logopa.png" alt="La Program Army"></a>

                </p>

            </div>

        </footer>

    </div>
    <a href="?cmpscreen" class="cmpfooterlink cmpfooterlinkcmp">Privacy settings</a>
</body>

</html>