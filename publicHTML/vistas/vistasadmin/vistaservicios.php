<?php

include("../../backend/conexion.php");

session_start();

if(!isset($_SESSION['odontologo'])) header('Location: ../../index.php');

if(!isset($_GET['numservicio'])) {

    $sql = "SELECT * FROM servicio ORDER BY nombre ASC";

    $stmt = $pdo->prepare($sql);

    if($stmt->execute()) $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <div id="tituservicioscontainer" class="d-flex justify-content-center gx-0 mt-3">

        <h1 id="tituloservicios" class="text-center">Servicios</h1>

    </div> 
    <div id="servicioscontainer"> 
    <?php

    foreach($servicios as $servicio)

        echo "<div id='{$servicio["numero"]}' class='servicio'><h3>{$servicio["nombre"]}<h3></div>";

    ?> </div> <?php
} 
else if(is_numeric($_GET['numservicio']) && $_GET['numservicio'] > 0) {
    
    $numservicio = $_GET['numservicio'];

    $sql = "SELECT * FROM servicio WHERE numero = :numservicio ORDER BY nombre ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':numservicio', $numservicio);

    if($stmt->execute() && $stmt->rowCount() == 1) $servicio = $stmt->fetch(PDO::FETCH_ASSOC);
?>

    <div id="cuerpo">

        <h1><?= $servicio['nombre'] ?></h1>

    </div>

<?php
}
?>