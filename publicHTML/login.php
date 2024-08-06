<?php 

session_start();


?>

<!DOCTYPE html>
<html lang="es" id="html">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Iniciar Sesión</title>
        
        <!-- Librerias -->
        <script src="lib/jquery-3.7.1.min.js"></script>
        <link rel="stylesheet" href="lib/bootstrap-5.2.3-dist/css/bootstrap.min.css">
        <script defer src="lib/bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>

        <!-- Favicon -->
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">

        <!-- CSS -->
        <link rel="stylesheet" href="css/login.css">
        <link rel="stylesheet" href="css/volver.css">

        <!-- JS -->
        <script src="js/preloader.js"></script>
        <script src="js/utilidades.js"></script>
        <script defer src="js/login.js"></script>
        
    </head>

    <body>

        <div id="div-mensaje-popup"></div>
        <div id="preloader" class="d-flex justify-content-center align-items-center">

            <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        
        </div>
        
        <main> 

            <?php

            if(!isset($_GET['estado']) || (isset($_GET['estado']) && $_GET['estado'] == 1)) {

                include("vistas/vistaslogin/vistalogin.php");
            }  

            else if(isset($_GET['estado']) && $_GET['estado'] == 2) {

                include("vistas/vistaslogin/vistaregistro.php");
            } 

            else if(isset($_GET['estado']) && $_GET['estado'] == 3) {
                
                include("vistas/vistaslogin/vistaloginadmin.php");
            }

            ?> 

        </main>

    </body>
    
</html>