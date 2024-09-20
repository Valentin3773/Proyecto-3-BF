<?php

include('extractor.php');

echo json_encode(duracionesDisponibles(DateTime::createFromFormat('d-m-Y', "27-09-2024"), '17:00', 1));
echo json_encode(horasDisponibles("27-09-2024", 1));

// $extendedHours = [];

// $fecha = DateTime::createFromFormat('d-m-Y', "25-09-2024");

// foreach(horasDisponibles($fecha->format('Y-m-d'), 1) as $hora) {
    
//     $extendedHours[] = $hora;
// }

// echo json_encode($extendedHours);

?>