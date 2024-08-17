let infoconsulta = {

    odontologo: null,
    fecha: null,
    hora: null,
    asunto: null,
    paso: 1,
    vistaactual: null
}

$(() => {

    $.get('vistas/vistasconsulta/progressbar.php', contenido => {

        $('.progressbarcontainer').html(contenido);
        addReservaListeners();
    });

    cargarVistaOdontologo();
});

function addReservaListeners() {

    $('#progressbar .odontologo').on('click', () => { if (infoconsulta.odontologo !== null && infoconsulta.vistaactual !== 'odontologo') cargarVistaOdontologo() });
    $('#progressbar .fecha').on('click', () => { if (infoconsulta.odontologo !== null && infoconsulta.vistaactual !== 'fecha') cargarVistaFecha() });
    $('#progressbar .hora').on('click', () => { if (infoconsulta.fecha !== null && infoconsulta.vistaactual !== 'hora') cargarVistaHora() });
    $('#progressbar .confirmar').on('click', () => { if (infoconsulta.hora !== null && infoconsulta.vistaactual !== 'confirmar') cargarVistaConfirmar() });
}

function cargarVistaOdontologo() {

    $('#pasocontainer').empty();
    $('#pasocontainer')[0].scrollTo({ top: 0, behavior: 'smooth' });

    $.get("vistas/vistasconsulta/odontologo.php", data => {
        
        $('#pasocontainer').html(data);
        console.log("Cargando vista de 'Elegir Odontólogo'");

        infoconsulta.vistaactual = 'odontologo';

        if (infoconsulta.odontologo !== null) {

            $('#elegirodontologo #' + infoconsulta.odontologo + ' input').prop('checked', true);
            $('#elegirodontologo #btnsmov #siguienteform').prop('disabled', false).css({ 'background-color': 'rgb(0, 178, 255, 1)' });
        }
        else $('#elegirodontologo #btnsmov #siguienteform').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, .45)' });

        moveProgressBar();

        $('#seccionescss').prop('href', 'css/reservarconsulta/odontologo.css');

        $('#progressbar .paso').css({ 'text-decoration': 'none' });
        $('#progressbar .odontologo').css({ 'text-decoration': 'underline' });

        $('#elegirodontologo .odontologo').on('click', function () {

            $('#elegirodontologo #' + $(this).prop('id') + ' input').prop('checked', true);

            if ($('.odontologo input:checked').val()) {

                $('#elegirodontologo #btnsmov #siguienteform').prop('disabled', false).css({ 'background-color': 'rgb(0, 178, 255, 1)' });

                if (infoconsulta.paso > 1 && $('.odontologo input:checked').val() != infoconsulta.odontologo) {

                    infoconsulta.paso = 1;
                    moveProgressBar();
                    infoconsulta.odontologo = null;
                    infoconsulta.fecha = null;
                    fechaselec = null;
                    infoconsulta.hora = null;
                    infoconsulta.asunto = null;
                }
            }
        });

        $('#elegirodontologo #btnsmov #anteriorform, #enviarreserva').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, .45)' });

        $('#elegirodontologo #btnsmov #siguienteform').on('click', () => {

            let seleccionado = $('.odontologo input:checked').val();

            if (seleccionado) infoconsulta.odontologo = Number(seleccionado);
            else infoconsulta.odontologo = null;

            if (infoconsulta.paso <= 2) infoconsulta.paso = 2;

            cargarVistaFecha();
        });
    });
}

let disponiblon;
let fechaselec = null;

function cargarVistaFecha() {

    $('#pasocontainer').empty();
    $('#pasocontainer')[0].scrollTo({ top: 0, behavior: 'smooth' });

    let mesc;
    let yearc;

    $.get("vistas/vistasconsulta/fecha.php", contenido => {

        $('#pasocontainer').html(contenido);
        console.log("Cargando vista de 'Elegir Fecha'");

        infoconsulta.vistaactual = 'fecha';

        moveProgressBar();

        if (infoconsulta.fecha !== null) fechaselec = infoconsulta.fecha;

        $('#calendariocontainer h3.fechaselec').hide()

        $('#seccionescss').prop('href', 'css/reservarconsulta/fecha.css');

        $('#progressbar .paso').css({ 'text-decoration': 'none' });
        $('#progressbar .fecha').css({ 'text-decoration': 'underline' });

        if (infoconsulta.fecha !== null) $('#elegirfecha #btnsmov #siguienteform').prop('disabled', false).css({ 'background-color': 'rgb(0, 178, 255, 1)' });
        else $('#elegirfecha #btnsmov #siguienteform').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, .45)' });

        if (infoconsulta.paso >= 2) $('#elegirfecha #btnsmov #anteriorform').prop('disabled', false).css({ 'background-color': 'rgb(0, 178, 255, 1)' }).on('click', cargarVistaOdontologo);
        else $('#elegirfecha #btnsmov #anteriorform, #enviarreserva').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, .45)' });

        $.ajax({

            type: 'POST',
            url: 'backend/consulta/fechamanager.php',
            data: JSON.stringify(infoconsulta),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function (respuesta) {

                mesc = new Date(respuesta.fechaActual).getMonth();
                yearc = new Date(respuesta.fechaActual).getFullYear();

                if(infoconsulta.fecha === null) generarCalendario(respuesta.fechaActual, respuesta.fechasDisponibles, mesc, yearc, fechaselec);
                else generarCalendario(respuesta.fechaActual, respuesta.fechasDisponibles, fechaselec.getMonth(), fechaselec.getFullYear(), fechaselec);
                addSelectListener();

                $('#calendario .arrowright').on('click', () => {

                    if (mesc < 11) mesc++;

                    else if (new Date(respuesta.fechaActual).getFullYear() + 1 > yearc) {

                        mesc = 0;
                        yearc++;
                    }

                    generarCalendario(respuesta.fechaActual, respuesta.fechasDisponibles, mesc, yearc, fechaselec);
                    addSelectListener();
                });
                $('#calendario .arrowleft').on('click', () => {

                    if (mesc > 0) mesc--;

                    else if (new Date(respuesta.fechaActual).getFullYear() < yearc) {

                        mesc = 11;
                        yearc--;
                    }

                    generarCalendario(respuesta.fechaActual, respuesta.fechasDisponibles, mesc, yearc, fechaselec);
                    addSelectListener();
                });
            },
            error: function (xhr, status, error) {

                infoconsulta.paso = 1
                cargarVistaOdontologo();
                alert("Ha ocurrido un error");
                console.error("Error fatal");
            }
        });

        $('#elegirfecha #btnsmov #siguienteform').on('click', () => {

            if (fechaselec !== null) {

                infoconsulta.fecha = fechaselec;

                if (infoconsulta.paso <= 3) infoconsulta.paso = 3;

                fechaselec = null;

                cargarVistaHora();
            }
        });
    });
}

function cargarVistaHora() {

    $('#pasocontainer').empty();
    $('#pasocontainer')[0].scrollTo({ top: 0, behavior: 'smooth' });
    
    $.get("vistas/vistasconsulta/hora.php", contenido => {
        
        $('#pasocontainer').html(contenido);
        console.log("Cargando vista de 'Elegir Hora'");

        infoconsulta.vistaactual = 'hora';

        $('#elegirhora #btnsmov #siguienteform').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, .45)' });

        moveProgressBar();

        $('#seccionescss').prop('href', 'css/reservarconsulta/hora.css');

        $('#progressbar .paso').css({ 'text-decoration': 'none' });
        $('#progressbar .hora').css({ 'text-decoration': 'underline' });

        if (infoconsulta.paso >= 3) $('#elegirhora #btnsmov #anteriorform').prop('disabled', false).css({ 'background-color': 'rgb(0, 178, 255, 1)' }).on('click', cargarVistaFecha);
        else $('#elegirhora #btnsmov #anteriorform, #enviarreserva').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, .45)' });

        $.ajax({

            type: 'POST',
            url: 'backend/consulta/horamanager.php',
            data: JSON.stringify(infoconsulta),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function (respuesta) {
                
                cargarHorasDisponibles(respuesta.horasDisponibles, respuesta.horarios);

                if(infoconsulta.hora != null) {

                    let horaseleccionada = infoconsulta.hora.minuto !== 0 ? infoconsulta.hora.hora + ':' + infoconsulta.hora.minuto : infoconsulta.hora.hora + ':' + infoconsulta.hora.minuto + '0';

                    $(`#elegirhora .hora input[value="${horaseleccionada}"]`).prop('checked', true);

                    $('#elegirhora #btnsmov #siguienteform').prop('disabled', false).css({ 'background-color': 'rgb(0, 178, 255, 1)' });
                }
            },
            error: function (xhr, status, error) {

                infoconsulta.paso = 2;
                cargarVistaFecha();
                alert("Ha ocurrido un error");
                console.error("Error fatal");
            }
        });

        $('#elegirhora #btnsmov #siguienteform').on('click', () => {

            let horasplit = $('.hora input:checked').val().split(':');
            console.log(horasplit);

            if (horasplit) {
                
                infoconsulta.hora = {

                    hora: Number(horasplit[0]),
                    minuto: Number(horasplit[1])
                };
            }

            else infoconsulta.hora = null;

            if (infoconsulta.paso <= 4) infoconsulta.paso = 4;

            cargarVistaConfirmar();
        });
    });
}

function cargarVistaConfirmar() {

    $('#pasocontainer').empty();
    $('#pasocontainer')[0].scrollTo({ top: 0, behavior: 'smooth' });
    
    $.get("vistas/vistasconsulta/confirmar.php", contenido => {

        $('#pasocontainer').html(contenido);
        console.log("Cargando vista de 'Confirmar Datos'");

        infoconsulta.vistaactual = 'confirmar';

        moveProgressBar();

        $('#seccionescss').prop('href', 'css/reservarconsulta/confirmar.css');

        $('#progressbar .paso').css({ 'text-decoration': 'none' });
        $('#progressbar .confirmar').css({ 'text-decoration': 'underline' });

        $('#enviarreserva').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, .45)' });

        $.ajax({

            type: 'POST',
            url: 'backend/consulta/getodontologo.php',
            data: JSON.stringify(infoconsulta),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: respuesta => $('#confirmardatos #detalles .odontologo .valor').html(`${respuesta.nombre} ${respuesta.apellido}`),
            error: function (xhr, status, error) {

                infoconsulta.paso = 3;
                cargarVistaHora();
                alert("Ha ocurrido un error");
                console.error("Error fatal");
            }
        });

        $('#confirmardatos #detalles .fecha .valor').html(`${infoconsulta.fecha.getDate()} / ${infoconsulta.fecha.getMonth()} / ${infoconsulta.fecha.getFullYear()}`);
        
        infoconsulta.hora.minuto !== 0 ? $('#confirmardatos #detalles .hora .valor').html(`${infoconsulta.hora.hora}:${infoconsulta.hora.minuto}`) : $('#confirmardatos #detalles .hora .valor').html(`${infoconsulta.hora.hora}:${infoconsulta.hora.minuto}0`);
    
        $('#inasunto').on('input', () => {

            if($('#inasunto').val().length >= 5) $('#enviarreserva').prop('disabled', false).css({ 'background-color': 'rgb(0, 178, 255, 1)' }).on('click', () => enviarReserva()); 
            else $('#enviarreserva').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, .45)' });
        });
    });
}

function enviarReserva() {

    
}

function addSelectListener() {

    $('#calendario .disponible').on('click', function () {

        $('#calendario .disponible').removeClass('seleccionado');
        $(this).addClass('seleccionado');
        fechaselec = new Date($(this).data('year'), $(this).data('mes'), $(this).data('dia'));
        $('#calendariocontainer h3.fechaselec').fadeIn(200).html(fechaselec.getDate() + '/' + Number(fechaselec.getMonth() + 1) + '/' + fechaselec.getFullYear());
        $('#elegirfecha #btnsmov #siguienteform').prop('disabled', false).css({ 'background-color': 'rgb(0, 178, 255, 1)' });

        if (infoconsulta.paso > 2 && infoconsulta.fecha.getTime() !== fechaselec.getTime()) {

            infoconsulta.paso = 2;
            moveProgressBar();
            infoconsulta.fecha = null;
            infoconsulta.hora = null;
            infoconsulta.asunto = null;
        }
    });
}

function moveProgressBar() {

    switch (infoconsulta.paso) {

        case 1: moveBar(1); break;

        case 2: moveBar(2); break;

        case 3: moveBar(3); break;

        case 4: moveBar(4); break;
    }
}

