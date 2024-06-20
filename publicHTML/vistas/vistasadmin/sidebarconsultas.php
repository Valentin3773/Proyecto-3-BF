<?php
include("../../backend/conexion.php");
session_start();

$consultas = array();
$id = $_SESSION['odontologo']['idodontologo'];
$fechaActual = date('Y-m-d');

$consulta = "SELECT * FROM consulta WHERE fecha >= :fechaActual and idodontologo = :id";
$stmt = $pdo->prepare($consulta);
$stmt->bindParam(':fechaActual', $fechaActual);
$stmt->bindParam(':id', $id);

if ($stmt->execute() && $stmt->rowCount() > 0) {
    while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $consultas[] = $tupla;
    }
}
?>

<h2 class="w-100">Consultas</h2>

<div class="consultascontainer">
<?php
$contador = 1; 

foreach ($consultas as $consultita) {
    $asunto = $consultita['asunto'];
    $fecha = $consultita['fecha'];
    $idOdontologo = $consultita['idodontologo'];

    echo '<button class="consultaside" id="'.$idOdontologo.'">' .'N°'. $contador . ' | ' . $fecha . '<br>' . $asunto .'</button>';

    $contador++;
}
?>
</div>
