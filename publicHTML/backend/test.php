<?php

include('extractor.php');

header('Content-Type: application/json');
echo json_encode(obtenerNotificacionesPaciente(14));

?>