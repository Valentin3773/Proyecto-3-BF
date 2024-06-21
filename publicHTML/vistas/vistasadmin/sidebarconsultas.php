<?php

include("../../backend/conexion.php");

$pacientes = array();
$consulta = "SELECT DISTINCT p.idpaciente, p.nombre, p.apellido FROM odontologo o JOIN consulta c ON o.idodontologo = c.idodontologo JOIN consulta_paciente cp ON c.fecha = cp.fecha AND c.hora = cp.hora AND c.idodontologo = cp.idodontologo JOIN paciente p ON cp.idpaciente = p.idpaciente WHERE o.idodontologo = :idodontologo";

$stmt = $pdo->prepare($consulta);
$stmt->bindParam(':idodontologo', $_SESSION['odontologo']['idodontologo']);

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