<?php

include('backend/extractor.php');

session_start();

if(!isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) header('Location: index.php');

reloadSession();

if (!isset($_GET['estado']) || (isset($_GET['estado']) && $_GET['estado'] == 1)) $estado = 1;

else if (isset($_GET['estado'])) {

    $estado = $_GET['estado'];

    if(!isset($_SESSION['odontologo']) && ($estado == 3 || $estado == 4)) $estado = 1;
}

?>

<!DOCTYPE html>
<html lang="es" id="html">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Perfil</title>

    <!-- Librerias -->
    <script src="lib/jquery-3.7.1.min.js"></script>
    <script src="lib/jquery-ui-1.14.0/jquery-ui.min.js"></script>
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

    <div id="container">

        <div id="sidebar" class="oculto py-4">

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

        <button id="desplegar">
            <svg class="svg-icon" viewBox="0 0 20 20" fill="white">
                <path d="M12.075,10.812c1.358-0.853,2.242-2.507,2.242-4.037c0-2.181-1.795-4.618-4.198-4.618S5.921,4.594,5.921,6.775c0,1.53,0.884,3.185,2.242,4.037c-3.222,0.865-5.6,3.807-5.6,7.298c0,0.23,0.189,0.42,0.42,0.42h14.273c0.23,0,0.42-0.189,0.42-0.42C17.676,14.619,15.297,11.677,12.075,10.812 M6.761,6.775c0-2.162,1.773-3.778,3.358-3.778s3.359,1.616,3.359,3.778c0,2.162-1.774,3.778-3.359,3.778S6.761,8.937,6.761,6.775 M3.415,17.69c0.218-3.51,3.142-6.297,6.704-6.297c3.562,0,6.486,2.787,6.705,6.297H3.415z"></path>
            </svg>
        </button>

        <main data-vista="<?= $estado ?>"></main>

    </div>

</body>

</html>