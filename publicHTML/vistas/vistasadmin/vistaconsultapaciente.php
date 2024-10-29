<?php

include("../../backend/conexion.php");
include("../../backend/extractor.php");

session_start();
reloadSession();

if(!isset($_SESSION['odontologo'])) {
    
    header('Location: ../../index.php');
    exit();
}

$ido = $_SESSION['odontologo']['idodontologo'];
$hora = $_GET['hora'];
$fecha = $_GET['fecha'];

// Formateo de string a interpretación de SQL
$fechatiempoString = $fecha . ' ' . $hora;
$formato = DateTime::createFromFormat('d/m/Y H:i', $fechatiempoString);
$Fechaensql = $formato->format('Y-m-d');
$Tiempoensql = $formato->format('H:i:s');
$tupla = array();

$sql = 'SELECT * FROM consulta JOIN paciente ON consulta.idpaciente = paciente.idpaciente WHERE idodontologo = :ido AND fecha = :fecha AND hora =:hora';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':ido', $ido);
$stmt->bindParam(':fecha', $Fechaensql);
$stmt->bindParam(':hora', $Tiempoensql);

if($stmt->execute() && $stmt->rowCount() > 0) $tupla = $stmt->fetch(PDO::FETCH_ASSOC);

else echo "Ha ocurrido un error";

$nombreP = $tupla["nombre"];

// Obtiene un array de fechas
$fechaA = getFechaActual();
$fechaF = sumarFecha($fechaA, "mes", 3);

$conjFechas = [];

foreach (getDatesFromRange($fechaA, $fechaF) as $dateFromRange) if (fechaDisponible($dateFromRange, $ido)) $conjFechas[] = $dateFromRange;
$conjHoras = horasDisponibles($fecha, $ido);
$conjDuracion = duracionesDisponibles($formato,$Tiempoensql,$ido);

$Tiempoensql = formatDateTime($Tiempoensql,'H:i:s', 'H:i');

?>

<script src="js/administrador/consultapaciente.js"></script>
<link rel="stylesheet" href="css/administrador/consultapaciente.css">

<div class="contenedor_ConsultaPaciente gx-0 mt-3">

    <div class="contConsultaAsunto">
        <div class="headerConsulta w-auto text-center">
            <h1>Consulta de <?= $nombreP ?></h1>
        </div>
        <div class="contAsunto w-auto text-center">
            <h1><input id="asunto-CP" class="text-center" type="text" value="<?= $tupla['asunto'] ?>" disabled></h1>
        </div>
    </div>

    <div class="px-3 lodivcontenedor">
    <div>
        <div class="row w-100 gx-0 justify-content-between contenedorcentral">
            <div class="col-xl-5 col-lg-5 col-12 contHora m-0">
                <div class="tituloHora">
                    <h1>Hora</h1>
                </div>
                <div class="contentHora">
                    <select name="" id="hora-CP" disabled>
                        <?php
                            if(!empty($conjHoras)) {

                                echo "<option id='horaV'>" . $Tiempoensql . "</option>";
                                for($i = 0; $i < count($conjHoras); $i++) {
                                    if(!($Tiempoensql == $conjHoras[$i])){
                                        echo "<option value= '$conjHoras[$i]'>$conjHoras[$i]</option>";
                                    }
                                } 
                            } 
                            else {

                                echo "<option id='horaV'>" . $Tiempoensql. "</option>";
                                echo "<option>No hay horarios disponibles</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-12  contDuracion">
                <div class="tituloDuracion">
                    <h1>Duración</h1>
                </div>
                <div class="contentDuracion">
                    <select type="number" name="" id="duracion-CP" value="" disabled>
                    <option><?= $tupla['duracion'] ?></option>
                        <?php
                                if(!empty($conjDuracion)) {

                                    for($i = 0; $i < count($conjDuracion); $i++) {
                                        if(!($tupla['duracion'] == $conjDuracion[$i])){}

                                        echo "<option value= '$conjDuracion[$i]'>$conjDuracion[$i]</option>";
                                    }
                                } 
                                else {

                                    echo "<option'>" .$tupla['duracion']. "</option>";
                                    echo "<option>No hay duraciones disponibles</option>";
                                }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-12 contFecha m-0">
                <div class="divTituloFecha">
                    <div class="tituloFecha">
                        <h1>Fecha</h1>
                    </div>
                </div>
                <div class="contentFecha">
                    <select name="" id="fecha-CP" disabled>
                    <?php 
                            if(!empty($conjFechas)) {
                                $fechaActual = DateTime::createFromFormat('Y-m-d', $Fechaensql)->format('d/m/Y');
                                echo "<option id='fechaV' value='$Fechaensql'>" . $fechaActual . "</option>";
                                for($i = 0; $i < count($conjFechas); $i++) {
                                    if(!($Fechaensql == $conjFechas[$i])){
                                        $fechaFormateada = DateTime::createFromFormat('Y-m-d', $conjFechas[$i])->format('d/m/Y');
                                        echo "<option value= '$conjFechas[$i]'>$fechaFormateada</option>";
                                    }
                                } 
                            } 
                            else {

                                echo "<option id='fechaV'>" . $Fechaensql . "</option>"; 
                                echo "<option>No hay fechas disponibles</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="contResumen mt-5">
            <div class="tituloResumen">
                <h1>Resumen</h1>
            </div>
            <div class="contentResumen">
                <textarea name="" id="resumen-CP" disabled><?= $tupla['resumen'] ?></textarea>
            </div>
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