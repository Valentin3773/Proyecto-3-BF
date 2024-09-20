<?php

include('../../backend/conexion.php');
include('../../backend/extractor.php');

session_start();
reloadSession();

if(!isset($_SESSION['odontologo'])) exit();

$sql = "SELECT idpaciente, nombre, apellido FROM paciente ORDER BY nombre ASC, apellido ASC";

$stmt = $pdo->prepare($sql);

if($stmt->execute()) $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h1 class="subtitulo">Agregar consulta</h1>

<div class="d-flex justify-content-center align-items-center">

    <form id="contagregarconsulta">

        <div id="contpaciente">

            <label for="paciente">Paciente</label>
            <select name="paciente" id="paciente" class="form-select" required>

                <option selected value="">Seleccione un paciente</option>

                <?php foreach($pacientes as $paciente) echo "<option value='{$paciente["idpaciente"]}'>{$paciente["nombre"]} {$paciente["apellido"]}</option>"; ?>

            </select>

        </div>

        <div id="contfecha">

            <label for="fecha">Fecha</label>
            <input type="text" id="fecha" name="fecha" value="" class="form-control" title="Seleccione una fecha" placeholder="Seleccione una fecha" disabled readonly required>

        </div>

        <div id="conthora">

            <label for="hora">Hora</label>
            <select name="hora" id="hora" class="form-select" disabled required>

                <option selected value="">Seleccione la hora</option>

            </select>

        </div>

        <div id="contduracion">

            <label for="duracion">Duración</label>
            <select name="duracion" id="duracion" class="form-select" disabled required>

                <option selected value="">Seleccione la duración</option>

            </select>

        </div>

        <div id="contasunto">

            <label for="asunto">Asunto</label>
            <input  name="asunto" id="asunto" type="text" class="form-control" value="" title="Escriba el asunto de la consulta" placeholder="Escriba el asunto" disabled required>

        </div>

        <div id="contbtnagregar" class="d-flex justify-content-center align-items-center">

            <button type="button" id="agregarconsulta" class="inactivo" disabled>Agregar</button>

        </div>

    </form>

</div>