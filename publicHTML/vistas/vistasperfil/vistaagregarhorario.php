<?php

$semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

?>

<h1 class="subtitulo">Agregar Horario</h1>

<div class="d-flex justify-content-center align-items-center">

    <div id="contagregarhorario">

        <div id="contdia">

            <label for="dia">Día</label>
            <select name="dia" id="dia" class="form-select">

                <option selected>Seleccione un día</option>
                <?php for($i = 0; $i < sizeof($semana); $i++) echo "<option value='". ($i + 1) ."'>{$semana[$i]}</option>"; ?>

            </select>

        </div>

        <div id="conthorainicio">

            <label for="horainicio">Hora de inicio</label>
            <select name="horainicio" id="horainicio" class="form-select" disabled>

                <option selected>Seleccione la hora de inicio</option>

            </select>

        </div>

        <div id="conthorafinalizacion">

            <label for="horafinalizacion">Hora de finalización</label>
            <select name="horafinalizacion" id="horafinalizacion" class="form-select" disabled>

                <option selected>Seleccione la hora de finalización</option>

            </select>

        </div>

        <div id="contbtnagregar" class="d-flex justify-content-center align-items-center">

            <button type="button" id="confirmarhorario" disabled>Agregar</button>

        </div>

    </div>

</div>