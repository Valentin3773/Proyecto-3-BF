<?php

include("../../backend/conexion.php");

session_start();

if (!isset($_SESSION['odontologo'])) header('Location: ../../index.php');

if (!isset($_GET['numservicio'])) {

    $sql = "SELECT * FROM servicio ORDER BY nombre ASC";

    $stmt = $pdo->prepare($sql);

    if ($stmt->execute()) $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
    <div id="tituservicioscontainer" class="d-flex justify-content-center gx-0 mt-3">

        <h1 id="tituloservicios" class="text-center">Servicios</h1>

    </div>
    <div id="servicioscontainer">
        <?php

        foreach ($servicios as $servicio)

            echo "<div id='{$servicio["numero"]}' class='servicio'><h3>{$servicio["nombre"]}<h3></div>";

        ?> </div> <?php
                } else if (is_numeric($_GET['numservicio']) && $_GET['numservicio'] > 0) {

                    $numservicio = $_GET['numservicio'];

                    $sql = "SELECT * FROM servicio WHERE numero = :numservicio ORDER BY nombre ASC";

                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':numservicio', $numservicio);

                    if ($stmt->execute() && $stmt->rowCount() == 1) $servicio = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>

    <div id="cuerpo">

        <h1><?= $servicio['nombre'] ?></h1>

        <div id="contimg" class="my-5">

            <div id="iconocontainer">

                <span class="leyenda">Icono</span>

                <div id="icowrapper">

                    <?php
                    if (isset($servicio["icono"])) echo "<img src='backend/almacenamiento/iconservice/{$servicio["icono"]}' alt='Icono del servicio' id='icoservicio'>";

                    else echo "<img src='img/logaso.png' alt='Icono del servicio' id='icoservicio'>";
                    ?>

                    <div class="lapizeditar">
                        <img src="img/iconosvg/lapiz.svg" id="mdF" alt="Modificar" title="Modificar">
                        <input type="file" id="inFile1" accept="image/*">
                    </div>

                </div>

            </div>
            <div id="imgcontainer">

                <span class="leyenda">Imagen</span>

                <div id="imgwrapper">

                    <?php
                    if (isset($servicio["imagen"])) echo "<img src='backend/almacenamiento/imgservice/{$servicio["imagen"]}' alt='Imagen del servicio' id='imgservicio'>";

                    else echo "<img src='img/logaso.png' alt='Imagen del servicio' id='imgservicio'>";
                    ?>

                    <div class="lapizeditar">
                        <img src="img/iconosvg/lapiz.svg" id="mdF" alt="Modificar" title="Modificar">
                        <input type="file" id="inFile2" accept="image/*">
                    </div>

                </div>
            </div>

        </div>

        <div class="form-group p-3">
            <label for="descripcion">Descripcion</label>
            <textarea name="descripcion" id="descripcion"><?=$servicio['descripcion']?></textarea>
        </div>

    </div>

<?php
                }
?>
