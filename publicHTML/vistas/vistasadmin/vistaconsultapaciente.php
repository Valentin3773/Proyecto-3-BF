<?php
include("../../backend/conexion.php");
include("../../backend/extractor.php");

session_start();
$ido = $_SESSION['odontologo']['idodontologo'];
$hora = $_GET['hora'];
$fecha = $_GET['fecha'];
$nombreP = $_GET['nombreP'];

$consultaPaciente = array();

    //Obtiene la consulta del paciente referente
    $consulta = ' SELECT * FROM consulta WHERE hora = :hora AND fecha =:fecha AND idodontologo = :ido ';
    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':ido', $ido);
    $stmt->bindParam(':hora', $hora);
    $stmt->bindParam(':fecha', $fecha);

    if ($stmt->execute() && $stmt->rowCount() > 0) {

        $tupla = $stmt->fetch(PDO::FETCH_ASSOC);

    }else { 
        echo "Error al ejecutar la consulta";
    } //Fin

    //Obtiene un array de fechas
    $fechaA = getFechaActual();
    $consulta = "SELECT DATE_ADD(:fechaA, INTERVAL 2 MONTH)";
    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':fechaA', $fechaA);
    
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        $fechaF = $stmt->fetchColumn();
    } else {
        echo "Error al ejecutar la consulta";
    }
    $conjFechas = getDatesFromRange($fechaA, $fechaF);
    //fin

    $conjHoras = array();



    $conjHoras = getAdjustedHoursFromRange('07:00:00','22:00:00');


?>
<script src="js/administrador/consultapaciente.js"></script>

<div class="contenedor_ConsultaPaciente row justify-content-center gx-0">
    <div class="contConsultaAsunto">
        <div class="headerConsulta w-auto text-center">
            <h1>Consulta de <?= $nombreP ?></h1>
        </div>
        <div class="contAsunto w-auto text-center">
            <h1><input id="asunto-CP" class="text-center" type="text" value=<?= $tupla['asunto'] ?> disabled></h1>
        </div>
    </div>

    <div>
        <div class=" d-flex justify-content-evenly">
            <div class="contHora m-0">
                <div class="tituloHora">
                    <h1>Hora</h1>
                 </div>
                <div class="contentHora d-flex justify-content-evenly">
                    <h1><?= $tupla['hora'] ?></h1>
                    <select name="" id="hora-CP" class="form-select" disabled>
                        <option value="">Elija un horario</option>
                        <?php
                            foreach ($conjHoras as $horaValue) {
                                echo '<option value="">'.$horaValue.'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="contDuracion m-0">
                <div class="tituloDuracion">
                    <h1>Duración</h1>
                </div>
                <div class="contentDuracion">
                    <h2><input id="duracion-CP"  type="number" value=<?= $tupla['duracion'] ?> disabled></h2>
                </div>
            </div>
            <div class="contFecha m-0">
                <div class="divTituloFecha">
                    <div class="tituloFecha">
                        <h1>Fecha</h1>
                    </div>
                </div>
                <div class="contentFecha d-flex justify-content-evenly">
                    <h1><?= $tupla['fecha'] ?></h1>
                    <select name="" id="fecha-CP" class="form-select" disabled>
                        <option value="">Elija una fecha</option>
                        <?php
                            foreach ($conjFechas as $fechaValue) {
                                if(isDateAvailable($ido,$fecha)){
                                echo '<option value="">'.$fechaValue.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>


        </div>

        <div class="d-flex">
            <div class="contResumen">
                <div class="tituloResumen"><h1>Resumen</h1></div>
                <div class="contentResumen">
                    <textarea name="" id="resumen-CP" disabled>
                        <?= $tupla['resumen'] ?>
                    </textarea>
                </div>
            </div>
        </div>
        
        <div class="contInteractivo d-flex justify-content-evenly">
            <button  id="btnModificar" class="btnInteractivo" value="Modificar"><h1>Modificar</h1></button>
            <button  id="btnCancelar" class="btnInteractivo"  value="Cancelar" disabled ><h1>Cancelar</h1></button>
            <button  id="btnGuardar"  class="btnInteractivo"  value="Guardar" disabled><h1>Guardar</h1></button>
        </div>
    </div>

</div>