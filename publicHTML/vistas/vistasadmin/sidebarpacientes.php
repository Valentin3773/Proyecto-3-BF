<?php

include("../../backend/conexion.php");
$clientes = array();
$consulta = "SELECT * FROM paciente";
$stmt = $pdo->prepare($consulta);

if ($stmt->execute() && $stmt->rowCount() > 0) {
    while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $clientes[] = $tupla; 
    }
}
?>

<h2 class="w-100">Pacientes</h2>
<div class="pacientescontainer">
<?php
$contador = 1; 

foreach ($clientes as $paciente) {
    $idPaciente = $paciente['idpaciente'];
    $nombre = $paciente['nombre'];

    echo '<button class="paciente" id="'.$idPaciente.'">' . $contador . ' ' . $nombre . '</button>';

    $contador++;
}
?>
</div>