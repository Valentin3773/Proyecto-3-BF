<?php

include('backend/extractor.php');

session_start();
reloadSession();

if (!isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) {

    header('Location: index.php');
    exit();
}

reloadSession();

if (!isset($_GET['estado']) || (isset($_GET['estado']) && $_GET['estado'] == 1)) $estado = 1;

else if (isset($_GET['estado'])) {

    $estado = $_GET['estado'];

    if (!isset($_SESSION['odontologo']) && ($estado == 3 || $estado == 4 || $estado == 6 || $estado == 7)) $estado = 1;
}

$urlbase = getUrlDominio();

?>

<!DOCTYPE html>
<html lang="es" id="html">

    <head>

        <base href="<?= $urlbase ?>">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta description="Administra tu perfil de usuario">
        <meta name="Mi perfil">

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

        <title>Perfil</title>

        <!-- Librerias -->
        <script src="lib/jquery-3.7.1.min.js"></script>
        <script src="lib/jquery-ui-1.14.0/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="lib/jquery-ui-1.14.0/jquery-ui.min.css">
        <link rel="stylesheet" href="lib/bootstrap-5.2.3-dist/css/bootstrap.min.css">
        <script defer src="lib/bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="lib/fontawesome-free-5.15.4-web/css/all.min.css">
        <script defer src="lib/fontawesome-free-5.15.4-web/js/all.min.js"></script>

        <!-- Favicon -->
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">

        <!-- CSS -->
        <link rel="stylesheet" href="css/perfil/perfilheader.css">
        <link id="seccionescss" rel="stylesheet">

        <!-- JS -->
        <script> history.pushState(null, 'Perfil', '/perfil'); </script>
        <script src="js/preloader.js"></script>
        <script src="js/utilidades.js"></script>
        <script defer src="js/perfil/updateProfile.js"></script>
        <script defer src="js/perfil/perfil.js"></script>

    </head>

    <body>

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

        <header id="headermobile">

            <div id="logocontainer">
                <img src="img/logaso.png" alt="Logo" title="Logo" class="img-thumbnail">
            </div>

            <div id="opcionescontainer" class="p-2">

                <button id="btnopciones" type="button">

                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">

                        <path stroke="black" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"></path>

                    </svg>

                </button>

                <div id="sidebarmobile" class="invisible py-4">

                    <div id="btnsuperiores">

                        <button id="miperfil">Mi Perfil</button>

                        <?php if (isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])): ?>

                            <button id="misconsultas">Mis Consultas</button>

                        <?php

                        endif;
                        if (isset($_SESSION['odontologo']) && !isset($_SESSION['paciente'])):

                        ?>

                            <button id="horarios">Horarios</button>
                            <button id="inactividades">Inactividades</button>

                        <?php endif; ?>

                        <button id="seguridad">Seguridad</button>

                    </div>

                    <button id="cerrarsesion"><i class="fas fa-sign-out-alt"></i>&nbsp;Cerrar Sesión</button>

                </div>

            </div>

        </header>

        <div id="container">

            <div id="sidebar" class="py-4">

                <div id="btnsuperiores">

                    <button id="miperfil">Mi Perfil</button>

                    <?php if (isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])): ?>

                        <button id="misconsultas">Mis Consultas</button>

                    <?php

                    endif;
                    if (isset($_SESSION['odontologo']) && !isset($_SESSION['paciente'])):

                    ?>

                        <button id="horarios">Horarios</button>
                        <button id="inactividades">Inactividades</button>

                    <?php endif; ?>

                    <button id="seguridad">Seguridad</button>

                </div>

                <button id="cerrarsesion"><i class="fas fa-sign-out-alt"></i>&nbsp;Cerrar Sesión</button>

            </div>

            <main data-vista="<?= $estado ?>"></main>

        </div>

    </body>

</html>