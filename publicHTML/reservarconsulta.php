<?php

include('backend/conexion.php');
include('backend/extractor.php');

session_start();
reloadSession();

$habilitacionson = false;
$habilitacion = "nohabilitado";
$iniciado = "noiniciado";

if(!isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) {
    
    
}
else if(!isset($_SESSION['paciente']) && isset($_SESSION['odontologo'])) {
    
    header('Location: index.php');
    exit();
}
else {

    $idp = $_SESSION['paciente']['idpaciente'];

    if($idp) $iniciado = "iniciado";

    $habilitacionson = reservaHabilitada($idp);
    
    $habilitacion = $habilitacionson ? "habilitado" : "nohabilitado";
}

?>

<!DOCTYPE html>
<html lang="es">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta description="Reserva una consulta con un odontólogo">
        <meta name="Reservar Consulta">

            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-408EQQ11WH"></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
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
        <link id="seccionescss" rel="stylesheet" href="">
        <link rel="stylesheet" href="css/reservarconsulta/reservaheader.css">

        <!-- JS -->
        <script src="js/preloader.js"></script>
        <script src="js/utilidades.js"></script>
        <script defer src="js/reservaconsulta/calendario.js"></script>
        <script defer src="js/reservaconsulta/reservarconsulta.js"></script>

        <title>Reservar Consulta</title>

    </head>

    <body data-habilitado="<?= $habilitacion ?>" data-iniciado="<?= $iniciado ?>">

        <div id="preloader" class="d-flex justify-content-center align-items-center">

            <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>

        </div>

        <?php if($habilitacionson): ?>

        <div id="container" class="gx-0">

            <header class="container-fluid p-0">

                <a href="javascript:void(0)" id="logo" title="Ir al inicio">

                    <img src="img/logaso.png" alt="Logo" id="imglogo">

                </a>

                <div class="progressbarcontainer"></div>

            </header>

            <main>

                <div id="pasocontainer"></div>

                <div id="enviarcontainer">

                    <button type="button" id="enviarreserva" disabled>Enviar</button>

                </div>

            </main>

        </div>

        <?php endif; ?>

    </body>

</html>