<?php

session_start();

if(isset($_SESSION['odontologo'])) {

        
} 
else {

    // header('Location: index.php');
};

?>

<!DOCTYPE html>
<html lang="es" id="html">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Administrador</title>
        
        <!-- JQuery -->
        <script src="js/jquery-3.7.1.min.js"></script>
        <!-- Bootstrap -->
        <link rel="stylesheet" href="css/bootstrap-5.2.3-dist/css/bootstrap.min.css">
        <script defer src="css/bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>

        <!-- Favicon -->
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">

        <!-- CSS -->
        <link id="seccionescss" rel="stylesheet" href="">
        <link rel="stylesheet" href="css/administrador/adminheader.css">
        <link rel="stylesheet" href="css/administrador/consultas.css">

        <!-- JS -->
        <script defer src="js/administrador/administrador.js"></script>
        <script src="js/preloader.js"></script>

    </head>

    <body>
        
        <div id="preloader" class="d-flex justify-content-center align-items-center">

            <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        
        </div>
        
        <div id="container" class="row justify-content-center gx-0">

            <header class="row gx-3 p-0">

                <div class="col-xl-2 col-lg-2 col-md-2">

                    <div class="w-100 h-100 logocontainer d-flex justify-content-center align-items-center">

                        <img src="img/logaso.png" alt="Logo" class="img-thumbnail">

                    </div>
                    
                </div>

                <div class="col-xl-10 col-lg-10 col-md-10 navcontainer p-3 pl-3">

                    <nav>

                        <ul class="p-0">

                            <li><a href="javascript:void(0)" id="btnconsultas" title="Consultas">Consultas</a></li>
                            <li><a href="javascript:void(0)" id="btnpacientes"  title="Pacientes">Pacientes</a></li>
                            <li><a href="javascript:void(0)" id="btnservicios"  title="Servicios">Servicios</a></li>

                        </ul>

                    </nav>

                    <div id="odontologocontainer">

                        <h2 class="m-0">Odontólogo: <?php echo $_SESSION['odontologo']['nombre']; ?></h2>

                    </div>

                </div>

            </header>

            <div class="row segfila gx-3 p-0">

                <div class="col-xl-2 col-lg-2 col-md-2">

                    <div class="sidebarcontainer w-100 h-100 d-flex flex-column">
                        
                        <div class="sidebar h-75 w-100">

                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>

                        </div>
                        <div class="actionbar h-25 w-100"></div>

                    </div>

                </div>
                
                <main class="col-xl-10 col-lg-10 col-md-10">

                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, soluta quas. Quos, cumque soluta illo dicta esse laboriosam aliquam optio consequatur non quisquam odit deserunt, nobis distinctio blanditiis possimus quam!</p>

                </main>

            </div>

        </div>

    </body>

</html>