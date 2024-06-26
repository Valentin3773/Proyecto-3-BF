<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="es" id="html">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Iniciar Sesión</title>
        
        <!-- JQuery -->
        <script src="js/jquery-3.7.1.min.js"></script>
        <!-- Bootstrap -->
        <link rel="stylesheet" href="css/bootstrap-5.2.3-dist/css/bootstrap.min.css">
        <script defer src="css/bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>

        <!-- Favicon -->
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">

        <!-- CSS -->
        <link rel="stylesheet" href="css/login.css">
        <link rel="stylesheet" href="css/volver.css">

        <!-- JS -->
        <script defer src="js/login.js"></script>
        <script src="js/preloader.js"></script>

    </head>

    <body>
        
        <div id="preloader" class="d-flex justify-content-center align-items-center">

            <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        
        </div>

        <main></main>

    </body>

</html>