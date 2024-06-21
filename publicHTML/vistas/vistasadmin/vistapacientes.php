<?php

include("../../backend/conexion.php");
session_start();

$pacientes = array();

$consulta = "SELECT nombre, documento, telefono FROM paciente";
$stmt = $pdo->prepare($consulta);

if ($stmt->execute() && $stmt->rowCount() > 0) {

    while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $pacientes[] = $tupla;
    }
}

foreach ($pacientes as $paciente) {

    $nombre = $paciente['nombre'];
    $documento = $paciente['documento'];
    $telefono = $paciente['telefono'];

    echo '
      <div class="p-contenedor">
        <div class="perfil">
          <img src="img/profile.jpg" width="80" height="80" alt="Profile Picture" class="foto">
          <div>
            <h2 class="Nombre">' . $nombre . '</h2>
            <p class="Documento">ID:' . $documento . '</p>
            <p class="Telefono">Telefono: ' . $documento . '</p>
          </div>
        </div>
      </div>
    ';
}

?>