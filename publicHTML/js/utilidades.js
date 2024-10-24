$(() => {

    $('body').css({ 'opacity': 1 });
    $('head').append('<link rel="stylesheet" href="css/popupmensaje.css">');

    window.mostrandoPopup = false;
});

function createPopup(titulo, contenido, duracion = 5) {
    
    $('#div-mensaje-popup').remove();

    let promesa = new Promise(respuesta => {

        console.log('Creando Popup');

        if ($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

        $('#div-mensaje-popup').hide();
        $.get(`vistas/popupmensaje.php ? Contenido=${contenido}&Aviso=${titulo}`, data => {

            window.mostrandoPopup = true;

            $('.mensaje-Popup').css({ '--progress': '0%', '--duracion': `0s` });

            $("#div-mensaje-popup").html(data).fadeIn(500);

            $('.mensaje-Popup').css({ '--progress': '100%', '--duracion': `${duracion}s` });

            let timeoutid = setTimeout(() => {
                
                respuesta(true);
                $("#div-mensaje-popup").fadeOut(500, () => window.mostrandoPopup = false)

            }, duracion * 1000);
            $('#div-mensaje-popup #btnCerrar').focus().on("click", () => {
                
                respuesta(true);

                clearTimeout(timeoutid);
                $("#div-mensaje-popup").fadeOut(500, () => window.mostrandoPopup = false);
            });
        });
    });
    return promesa;
}

function createHeaderPopup(titulo, contenido, accion, duracion = 5) {

    $('#div-mensaje-popup').remove();

    let promesa = new Promise(respuesta => {

        console.log('Creando Popup Redireccionador');

        if ($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

        $('#div-mensaje-popup').hide();
        $.get("vistas/popupmensaje.php ? Contenido=" + contenido + "&Aviso=" + titulo, data => {

            window.mostrandoPopup = true;

            $('.mensaje-Popup').css({ '--progress': '0%', '--duracion': `0s` });

            $("#div-mensaje-popup").html(data).fadeIn(500);

            $('.mensaje-Popup').css({ '--progress': '100%', '--duracion': `${duracion}s` });

            let timeoutid = setTimeout(() => $("#div-mensaje-popup").fadeOut(500, () => {

                respuesta(true);

                window.mostrandoPopup = false;

                $("#div-mensaje-popup link").remove();

                if (typeof accion == 'string') window.location.href = accion;

                else if (typeof accion == 'function') accion();

            }), duracion * 1000);

            $('#div-mensaje-popup #btnCerrar').focus().on("click", () => {

                clearTimeout(timeoutid);

                $("#div-mensaje-popup").fadeOut(500, () => {

                    window.mostrandoPopup = false;

                    respuesta(true);

                    $("#div-mensaje-popup link").remove();

                    if (typeof accion == 'string') window.location.href = accion;

                    else if (typeof accion == 'function') accion();
                });
            });
        });
    });
    return promesa;
}

function createConfirmPopup(titulo, contenido, botonestxt = ['Cancelar', 'Confirmar']) {

    let promesa = new Promise(respuesta => {

        console.log('Creando Popup Confirmador');

        if ($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

        $('#div-mensaje-popup').hide();
        $.get(`vistas/popupconfirmar.php ? Contenido=${contenido}&Aviso=${titulo}&txtcancelar=${botonestxt[0]}&txtconfirmar=${botonestxt[1]}`, data => {

            window.mostrandoPopup = true;

            $("#div-mensaje-popup").html(data).fadeIn(500);
            $('#div-mensaje-popup #btnCancelar').focus().on("click", () => {

                $("#div-mensaje-popup").fadeOut(500, () => window.mostrandoPopup = false);
                $("#div-mensaje-popup link").remove();

                respuesta(false);
            });
            $('#div-mensaje-popup #btnConfirmar').on("click", () => {

                $("#div-mensaje-popup").fadeOut(500, () => window.mostrandoPopup = false);
                $("#div-mensaje-popup link").remove();

                respuesta(true);
            });
        });
    });
    return promesa;
}

function createInputPopup(titulo, placeholder, botonestxt = ['Cancelar', 'Confirmar']) {

    let promesa = new Promise(respuesta => {

        console.log('Creando Popup Ingresador');

        if ($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

        $('#div-mensaje-popup').hide();
        $.get(`vistas/popupingresar.php ? placeholder=${placeholder}&Aviso=${titulo}&txtcancelar=${botonestxt[0]}&txtconfirmar=${botonestxt[1]}`, data => {

            window.mostrandoPopup = true;

            $("#div-mensaje-popup").html(data).fadeIn(500);
            $('#div-mensaje-popup #popup-input').on('input', function () {

                if ($(this).val().length >= 3) $('#btnConfirmar').css({ 'background-color': 'rgb(10, 240, 171, 1)' }).prop('disabled', false);

                else $('#btnConfirmar').css({ 'background-color': 'rgb(10, 240, 171, .4)' }).prop('disabled', true);
            })
            $('#div-mensaje-popup #btnCancelar').focus().on("click", () => {

                $("#div-mensaje-popup").fadeOut(500, () => window.mostrandoPopup = false);
                $("#div-mensaje-popup link").remove();

                respuesta('');
            });
            $('#div-mensaje-popup #btnConfirmar').css({ 'background-color': 'rgb(10, 240, 171, .4)' }).prop('disabled', true).on("click", () => {

                $("#div-mensaje-popup").fadeOut(500, () => window.mostrandoPopup = false);
                $("#div-mensaje-popup link").remove();

                let texto = $('#popup-input').val();

                respuesta(texto);
            });
        });
    });
    return promesa;
}

function createFeedbackPopup(titulo, contenido, datosconsulta, botonestxt = ['Cancelar', 'Enviar']) {

    let promesa = new Promise(respuesta => {

        console.log('Creando Popup');

        if ($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

        $('#div-mensaje-popup').hide();
        $.get(`vistas/popupcalificar.php ? Contenido=${contenido}&Aviso=${titulo}&txtcancelar=${botonestxt[0]}&txtconfirmar=${botonestxt[1]}`, data => {
            
            window.mostrandoPopup = true;

            let estrellas = null;

            $("#div-mensaje-popup").html(data).fadeIn(500);
            $('#div-mensaje-popup #popup-Titulo').on('click', () => console.log(estrellas));
            $('#div-mensaje-popup .estrella').on('mouseenter', function() {

                let hoveredId = parseInt($(this).attr('id'));

                $('.estrella').each(function() {

                    let currentId = parseInt($(this).attr('id'));
                    if (currentId <= hoveredId) $(this).addClass('activada');
                });
            })
            .on('mouseleave', function() {
                
                $('.estrella').removeClass('activada');
            })
            .on('click', function() {

                $('.estrella').removeClass('activa');

                let id = Number($(this).attr('id'));
                
                estrellas = id;

                for(let i = 1; i <= id; i++) $(`#${i}.estrella`).addClass('activa');

                if (estrellas != null) $('#div-mensaje-popup #btnConfirmar').css({ 'background-color': 'rgb(10, 240, 171, 1)' }).prop('disabled', false);

                else $('#div-mensaje-popup #btnConfirmar').css({ 'background-color': 'rgb(10, 240, 171, .4)' }).prop('disabled', true);
            });
            $('#div-mensaje-popup #btnCancelar').focus().on("click", () => {

                $("#div-mensaje-popup").fadeOut(500, () => window.mostrandoPopup = false);
                $("#div-mensaje-popup link").remove();

                respuesta(null);
            });
            $('#div-mensaje-popup #btnConfirmar').css({ 'background-color': 'rgb(10, 240, 171, .4)' }).prop('disabled', true).on("click", () => {

                $("#div-mensaje-popup").fadeOut(500, () => window.mostrandoPopup = false);
                $("#div-mensaje-popup link").remove();

                let msg = $('#div-mensaje-popup #mensaje').val();

                msg = msg.length > 0 ? msg : null;

                respuesta({

                    fecha: datosconsulta.fecha,
                    hora: datosconsulta.hora,
                    ido: datosconsulta.ido,
                    asunto: contenido,
                    mensaje: msg,
                    puntaje: estrellas
                });
            });
        });
    });
    return promesa;
}

function getWeekDates(date) {

    const currentDate = new Date(date.split('-')[0], Number(date.split('-')[1]) - 1, date.split('-')[2]);

    const dayOfWeek = currentDate.getDay();

    // Ajustar para que el lunes sea el primer día de la semana
    const startOfWeek = new Date(currentDate);

    startOfWeek.setDate(currentDate.getDate() - ((dayOfWeek + 6) % 7)); // Resta para llegar al lunes

    const weekDates = [];

    for (let i = 0; i < 7; i++) {

        const tempDate = new Date(startOfWeek);
        tempDate.setDate(startOfWeek.getDate() + i);
        weekDates.push(tempDate);
    }
    return weekDates;
}

function permitirFechas(date, fechasPermitidasas) {

    var year = date.getFullYear();
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var day = ("0" + date.getDate()).slice(-2);
    var dateString = day + "-" + month + "-" + year;

    if ($.inArray(dateString, fechasPermitidasas) !== -1) return [true, 'fechadisponible', "Fecha disponible"];

    else return [false, 'fechanodisponible', "Fecha no disponible"];
}

function fancyHoras(minutos) {

    const horas = Math.floor(minutos / 60);
    const minutosRestantes = minutos % 60;

    let resultado = '';

    if (horas > 0) resultado += horas + ' hora' + (horas > 1 ? 's' : '');

    if (horas > 0 && minutosRestantes > 0) resultado += ' y ';

    if (minutosRestantes > 0) resultado += minutosRestantes + ' minuto' + (minutosRestantes > 1 ? 's' : '');

    return resultado || '0 minutos';
}

function deepEqual(obj1, obj2) {

    if (obj1 === obj2) return true;

    if (typeof obj1 !== 'object' || typeof obj2 !== 'object' || obj1 === null || obj2 === null) return false;

    let keys1 = Object.keys(obj1);
    let keys2 = Object.keys(obj2);

    if (keys1.length !== keys2.length) return false;

    for (let key of keys1) if (!keys2.includes(key) || !deepEqual(obj1[key], obj2[key])) return false;

    return true;
}

function changePage(pagina) {

    $('body').css({ 'opacity': 0 });
    setTimeout(() => window.location.href = pagina, 500)
}