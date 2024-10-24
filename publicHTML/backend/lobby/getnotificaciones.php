<?php

include('../conexion.php');
include('../extractor.php');

session_start();
reloadSession();

if (!isset($_SESSION['paciente']) || $_SERVER['REQUEST_METHOD'] != 'GET') {
    
    header('Content-Type: application/json');
    echo json_encode(['odontologo' => true]);
    exit();
}

$idp = $_SESSION['paciente']['idpaciente'];
$nomolestar = $_SESSION['paciente']['nomolestar'];

if (!$nomolestar) {

    $respuesta = ['avisar' => [], 'calificar' => []];

    $consultas = obtenerNotificacionesPaciente($idp);

    foreach ($consultas as $consulta) {

        $fechaActual = getFechaActual();
        $horaActual = getHoraActual();

        $fecha = $consulta['fecha'];
        $hora = $consulta['hora'];
        $ido = $consulta['idodontologo'];
        $duracion = $consulta['duracion'];
        $asunto = $consulta['asunto'];
        $booleanos = $consulta['booleanos'];

        $sql = "SELECT nombre, apellido FROM odontologo WHERE idodontologo = :ido";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ido', $ido);

        if (!$booleanos[1] && $stmt->execute() && $stmt->rowCount() == 1 && (diferenciaFechas($fecha, $fechaActual) == 1 || (diferenciaFechas($fecha, $fechaActual) == 0 && horaMenor($horaActual, $hora)))) {

            $odontologo = $stmt->fetch(PDO::FETCH_ASSOC);

            $booleanos[1] = true;
            if (modificarBooleanosNotificacionesConsulta($fecha, $hora, $ido, $booleanos)) $respuesta['avisar'][] = [

                'fecha' => $fecha,
                'hora' => $hora,
                'idodontologo' => $ido,
                'asunto' => $asunto,
                'nombreo' => $odontologo['nombre'],
                'apellidoo' => $odontologo['apellido']
            ];
        }

        $tiempoconsulta = DateTime::createFromFormat("Y-m-d H:i:s", "{$fecha} {$hora}");
        $tiempoconsulta->modify("+{$duracion} minutes");

        $tiempoactual = DateTime::createFromFormat("Y-m-d H:i:s", "{$fechaActual} {$horaActual}");

        if ($tiempoactual > $tiempoconsulta && !$booleanos[2]) {

            $respuesta['calificar'][] = [

                'fecha' => $fecha,
                'hora' => $hora,
                'idodontologo' => $ido,
                'asunto' => $asunto
            ];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}
else {
    
    $respuesta = ['nomolestar' => true];

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

?>