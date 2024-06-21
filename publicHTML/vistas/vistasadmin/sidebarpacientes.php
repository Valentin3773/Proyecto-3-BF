<?php

include("../../backend/conexion.php");

$clientes = array();
$consulta = "SELECT idpaciente, nombre, apellido FROM paciente";

$stmt = $pdo->prepare($consulta);

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