<?php

include("../../backend/conexion.php");

session_start();

if(!isset($_SESSION['odontologo'])) {
    
    header('Location: ../../index.php');
    exit();
}

$pacientes = array();
$consulta = "SELECT DISTINCT p.idpaciente, p.nombre, p.apellido FROM odontologo o JOIN consulta c ON o.idodontologo = c.idodontologo JOIN paciente p ON c.idpaciente = p.idpaciente WHERE o.idodontologo = :ido ORDER BY nombre ASC";

$ido = $_SESSION['odontologo']['idodontologo'];

$stmt = $pdo->prepare($consulta);
$stmt->bindParam(':ido', $ido);

if ($stmt->execute() && $stmt->rowCount() > 0) {

    while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $pacientes[] = $tupla; 
    }
}

?>

<h2 class="w-100">Pacientes</h2>

<div class="pacientescontainer">

    <?php

    foreach ($pacientes as $paciente) {
        
        $idPaciente = $paciente['idpaciente'];
        $nombre = $paciente['nombre'];
        $apellido = $paciente['apellido'];

        echo '<button class="paciente" id="'.$idPaciente.'">' . $nombre . ' ' . $apellido . '</button>';
    }

    ?>

</div>  