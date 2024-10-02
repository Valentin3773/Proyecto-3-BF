<?php

include('backend/extractor.php');

session_start();

if (!(isset($_SESSION['odontologo']))) header('Location: index.php');

reloadSession();

?>

<!DOCTYPE html>
<html lang="es" id="html">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta description="Administra las consultas, pacientes y servicios de la aplicación">
    <meta name="Administrador">

    <title>Administrador</title>

    <!-- Librerias -->
    <script src="lib/jquery-3.7.1.min.js"></script>
    <script src="lib/jquery-ui-1.14.0/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="lib/jquery-ui-1.14.0/jquery-ui.min.css">
    <link rel="stylesheet" href="lib/bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <script defer src="lib/bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>

    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">

    <!-- CSS -->
    <link id="seccionescss" rel="stylesheet" href="">
    <link rel="stylesheet" href="css/administrador/adminheader.css">

    <!-- JS -->
    <script src="js/preloader.js"></script>
    <script src="js/utilidades.js"></script>
    <script defer src="js/reservaconsulta/calendario.js"></script>
    <script defer src="js/administrador/administrador.js"></script>

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

    <div id="container" class="row justify-content-center gx-0">

        <header id="headerpc" class="row gx-3 p-0">

            <div class="col-xl-2 col-lg-2 col-md-2">

                <div class="w-100 h-100 logocontainer d-flex justify-content-center align-items-center">

                    <a id="logo" href="javascript:void(0)"><img src="img/logaso.png" alt="Logo" class="img-thumbnail" title="Volver al inicio"></a>

                </div>

            </div>

            <div class="col-xl-10 col-lg-10 col-md-10 navcontainer p-3 pl-3">

                <nav>

                    <ul class="p-0">

                        <li><a href="javascript:void(0)" id="btnconsultas" title="Consultas">Consultas</a></li>
                        <li><a href="javascript:void(0)" id="btnpacientes" title="Pacientes">Pacientes</a></li>
                        <li><a href="javascript:void(0)" id="btnservicios" title="Servicios">Servicios</a></li>

                    </ul>

                </nav>

                <div id="odontologocontainer">

                    <h2 class="m-0"> <?= "{$_SESSION['odontologo']['nombre']} {$_SESSION['odontologo']['apellido']}" ?> </h2>

                </div>

            </div>

        </header>

        <header class="mobile">

            <div class="w-100 h-100 logocontainer d-flex justify-content-center align-items-center">

                <a id="logo" href="javascript:void(0)"><img src="img/logaso.png" alt="Logo" class="img-thumbnail" title="Volver al inicio"></a>

            </div>

            <div id="odontologocontainer">

                <h2 class="m-0"> <?= "{$_SESSION['odontologo']['nombre']} {$_SESSION['odontologo']['apellido']}" ?> </h2>

            </div>

        </header>

        <div class="row segfila gx-3 p-0">

            <div class="col-xl-2 col-lg-2 col-md-2">

                <div class="sidebarcontainer w-100 h-100 d-flex flex-column">

                    <div class="sidebar w-100"></div>

                    <div class="actionbar w-100 d-flex justify-content-center align-items-center">
                        <h3 id="titactionbar">Agregar Consulta</h3>
                        <div id="agregar" class="invisible"><div class="mas"></div></div>
                    </div>

                </div>

            </div>

            <main class="col-xl-10 col-lg-10 col-md-10"></main>

        </div>

        <nav class="mobile">

            <ul class="p-0">

                <li><a href="javascript:void(0)" id="btnconsultas" title="Consultas">Consultas</a></li>
                <li><a href="javascript:void(0)" id="btnpacientes" title="Pacientes">Pacientes</a></li>
                <li><a href="javascript:void(0)" id="btnservicios" title="Servicios">Servicios</a></li>

            </ul>

        </nav>

    </div>

</body>

</html>