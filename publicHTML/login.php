<?php

session_start();

if ((isset($_SESSION['paciente']) || isset($_SESSION['odontologo'])) && $_GET['estado'] != 3 && $_GET['estado'] != 4 && $_GET['estado'] != 5) header('Location: index.php');

include('backend/extractor.php');

reloadSession();

if (!isset($_GET['estado']) || (isset($_GET['estado']) && $_GET['estado'] == 1)) $estado = 1;

else if (isset($_GET['estado'])) $estado = $_GET['estado'];

?>

<!DOCTYPE html>
<html lang="es" id="html">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Iniciar Sesión</title>
        <meta description="Inicia sesión para tener una experiencia completa en la clínica">
        <meta name="Iniciar Sesión">

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

        <!-- Librerias -->
        <script src="lib/jquery-3.7.1.min.js"></script>
        <link rel="stylesheet" href="lib/bootstrap-5.2.3-dist/css/bootstrap.min.css">
        <script defer src="lib/bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="lib/fontawesome-free-5.15.4-web/css/all.min.css">
        <script defer src="lib/fontawesome-free-5.15.4-web/js/all.min.js"></script>

        <!-- Favicon -->
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">

        <!-- CSS -->
        <link rel="stylesheet" href="css/login/login.css">
        <!-- <link rel="stylesheet" href="css/login/recuperarpass.css"> -->
        <link rel="stylesheet" href="css/volver.css">

        <!-- JS -->
        <script src="js/preloader.js"></script>
        <script src="js/utilidades.js"></script>
        <script defer src="js/login/login.js"></script>

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

        <main data-vista="<?= $estado ?>">

            <?php

            switch ($estado) {

                case 1:
                    include("vistas/vistaslogin/vistalogin.php");
                break;

                case 2:
                    include("vistas/vistaslogin/vistaregistro.php");
                break;
            }

            ?>

        </main>

    </body>

</html>