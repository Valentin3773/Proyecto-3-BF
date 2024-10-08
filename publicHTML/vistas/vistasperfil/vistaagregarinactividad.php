<h1 class="subtitulo">Agregar Inactividad</h1>

<div class="d-flex justify-content-center align-items-center">

    <form id="contagregarinactividad">

        <div id="contfechainicio">

            <label for="fechainicio">Fecha de inicio</label>
            <input type="text" id="fechainicio" name="fechainicio" class="form-control" title="Ingrese la fecha de inicio" placeholder="Ingrese la fecha de inicio" required readonly>

        </div>

        <div id="conthorainicio">

            <label for="horainicio">Hora de inicio</label>
            <select name="horainicio" id="horainicio" class="form-select" disabled>

                <option selected value="">Seleccione la hora de inicio</option>

            </select>

        </div>

        <div id="contfechafinalizacion">

            <label for="fechafinalizacion">Fecha de finalización</label>
            <input type="text" id="fechafinalizacion" name="fechafinalizacion" class="form-control" title="Ingrese la fecha de finalización" placeholder="Ingrese la fecha de finalización" required readonly disabled>

        </div>

        <div id="conthorafinalizacion">

            <label for="horafinalizacion">Hora de finalización</label>
            <select name="horafinalizacion" id="horafinalizacion" class="form-select" disabled>

                <option selected value="">Seleccione la hora de finalización</option>

            </select>

        </div>

        <div id="contbtnagregar" class="d-flex justify-content-center align-items-center">

            <button type="button" id="confirmarinactividad" name="confirmarinactividad" disabled>Agregar</button>

        </div>

    </form>

</div>