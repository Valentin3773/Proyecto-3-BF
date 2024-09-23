<h1 id="titverpor" class="m-0 mt-5">Ver consultas por</h1>

<div id="verporcontainer" class="d-flex justify-content-center align-items-center">

    <select name="verpor" id="verpor" class="form-select">

        <option selected value="dia">Día</option>
        <option value="semana">Semana</option>

    </select>

</div>

<section id="calendariocontainer">

    <div id="calendario" class="pordia">

        <div id="calendarheader">

            <div id="monthyear">

                <div class="arrowleft"></div>

                <h2></h2>

                <div class="arrowright"></div>

            </div>

        </div>

        <table id="calendarbody">

            <colgroup>

                <col class="columna">
                <col class="columna">
                <col class="columna">
                <col class="columna">
                <col class="columna">
                <col class="columna">
                <col class="columna">

            </colgroup>

            <thead id="semana">

                <tr>

                    <th><span>Lu</span></th>
                    <th><span>Ma</span></th>
                    <th><span>Mi</span></th>
                    <th><span>Ju</span></th>
                    <th><span>Vi</span></th>
                    <th><span>Sa</span></th>
                    <th><span>Do</span></th>

                </tr>

            </thead>

            <tbody></tbody>

        </table>

    </div>

    <h3 class="fechaselec"></h3>

</section>