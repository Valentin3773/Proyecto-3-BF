<?php

include("../../backend/conexion.php");
include("../../backend/extractor.php");

session_start();

reloadSession();

if (!isset($_SESSION['odontologo'])) exit();

$ido = $_SESSION['odontologo']['idodontologo'];
$hora = $_GET['hora'];
$fecha = $_GET['fecha'];

$consultaPaciente = array();

$sql = 'SELECT * FROM consulta c JOIN paciente p ON c.idpaciente = p.idpaciente WHERE hora = :hora AND fecha = :fecha AND idodontologo = :ido';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':ido', $ido);
$stmt->bindParam(':hora', $hora);
$stmt->bindParam(':fecha', $fecha);

if ($stmt->execute() && $stmt->rowCount() > 0) $tupla = $stmt->fetch(PDO::FETCH_ASSOC);

else echo "Error al ejecutar la consulta";

$nombreP = $tupla["nombre"];

// Obtiene un array de fechas
$fechaA = getFechaActual();
$fechaF = sumarFecha($fechaA, "mes", 3);

$conjFechas = [];

foreach (getDatesFromRange($fechaA, $fechaF) as $dateFromRange) if (fechaDisponible($dateFromRange, $ido)) $conjFechas[] = $dateFromRange;

$conjHoras = horasDisponibles($fecha, $ido);

?>

<script src="js/administrador/consultapaciente.js"></script>
<link rel="stylesheet" href="css/administrador/consultapaciente.css">

<div class="contenedor_ConsultaPaciente gx-0">

    <div class="contConsultaAsunto">
        <div class="headerConsulta w-auto text-center">
            <h1>Consulta de <?= $nombreP ?></h1>
        </div>
        <div class="contAsunto w-auto text-center">
            <h1><input id="asunto-CP" class="text-center" type="text" value="<?= $tupla['asunto'] ?>" disabled></h1>
        </div>
    </div>

    <div class="px-3">

        <div class="row w-100 gx-0">
            <div class="col-xl-5 col-lg-5 col-12 contHora m-0">
                <div class="tituloHora">
                    <h1>Hora</h1>
                </div>
                <div class="contentHora">
                    
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-12 contDuracion">
                <div class="tituloDuracion">
                    <h1>Duración</h1>
                </div>
                <div class="contentDuracion"></div>
            </div>
            <div class="col-xl-5 col-lg-5 col-12 contFecha m-0">
                <div class="divTituloFecha">
                    <div class="tituloFecha">
                        <h1>Fecha</h1>
                    </div>
                </div>
                <div class="contentFecha">
                    
                </div>
            </div>
        </div>

        <div class="contResumen mt-3">
            <div class="tituloResumen">
                <h1>Resumen</h1>
            </div>
            <div class="contentResumen">
                <textarea name="" id="resumen-CP" disabled><?= $tupla['resumen'] ?></textarea>
            </div>
        </div>

    </div>

    <div class="contInteractivo d-flex justify-content-evenly">
        <button id="btnModificar" class="btnInteractivo" value="Modificar">
            Modificar
        </button>
        <button id="btnCancelar" class="btnInteractivo" value="Cancelar" disabled>
            Cancelar
        </button>
        <button id="btnGuardar" class="btnInteractivo" value="Guardar" disabled>
            Guardar
        </button>
        <button id="btnEliminar" class="btnInteractivo" value="Eliminar" disabled>Eliminar</button>
    </div>
</div>