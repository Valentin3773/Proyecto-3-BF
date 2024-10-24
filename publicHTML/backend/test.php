<?php

include('extractor.php');

header('Content-Type: application/json');
echo json_encode(obtenerNotificacionesConsulta('2024-10-21', '09:30:00', 1));

?>