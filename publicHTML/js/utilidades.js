$(() => {

    $('head').append('<link rel="stylesheet" href="css/popupmensaje.css">');
});

function createPopup(titulo, contenido, duracion = 5) {

    console.log('Creando Popup');

    if ($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

    $('#div-mensaje-popup').hide();
    $.get(`vistas/popupmensaje.php ? Contenido=${contenido}&Aviso=${titulo}`, data => {

        $('.mensaje-Popup').css({ '--progress': '0%', '--duracion': `0s` });

        $("#div-mensaje-popup").html(data).fadeIn(500);

        $('.mensaje-Popup').css({ '--progress': '100%', '--duracion': `${duracion}s` });

        let timeoutid = setTimeout(() => $("#div-mensaje-popup").fadeOut(500), duracion * 1000);
        $('#btnCerrar').focus().on("click", () => {

            clearTimeout(timeoutid);
            $("#div-mensaje-popup").fadeOut(500);
            //$('body').removeClass('blurry');
        });

    });
}

function createHeaderPopup(titulo, contenido, accion, duracion = 5) {

    console.log('Creando Popup Redireccionador');

    if ($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

    $('#div-mensaje-popup').hide();
    $.get("vistas/popupmensaje.php ? Contenido=" + contenido + "&Aviso=" + titulo, data => {

        $('.mensaje-Popup').css({ '--progress': '0%', '--duracion': `0s` });

        $("#div-mensaje-popup").html(data).fadeIn(500);

        $('.mensaje-Popup').css({ '--progress': '100%', '--duracion': `${duracion}s` });

        let timeoutid = setTimeout(() => $("#div-mensaje-popup").fadeOut(500, () => {

            $("#div-mensaje-popup link").remove();

            if (typeof accion == 'string') window.location.href = accion;

            else if (typeof accion == 'function') accion();

        }), duracion * 1000);

        $('#btnCerrar').focus().on("click", () => {

            clearTimeout(timeoutid);

            $("#div-mensaje-popup").fadeOut(500, () => {

                $("#div-mensaje-popup link").remove();

                if (typeof accion == 'string') window.location.href = accion;

                else if (typeof accion == 'function') accion();
            });
        });
    });
}

function createConfirmPopup(titulo, contenido, botonestxt = ['Cancelar', 'Confirmar']) {

    let promesa = new Promise(respuesta => {

        console.log('Creando Popup Confirmador');

        if ($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

        $('#div-mensaje-popup').hide();
        $.get(`vistas/popupconfirmar.php ? Contenido=${contenido}&Aviso=${titulo}&txtcancelar=${botonestxt[0]}&txtconfirmar=${botonestxt[1]}`, data => {

            $("#div-mensaje-popup").html(data).fadeIn(500);
            $('#btnCancelar').focus().on("click", () => {

                $("#div-mensaje-popup").fadeOut(500);
                $("#div-mensaje-popup link").remove();

                respuesta(false);
            });
            $('#btnConfirmar').on("click", () => {

                $("#div-mensaje-popup").fadeOut(500);
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

            $("#div-mensaje-popup").html(data).fadeIn(500);
            $('#popup-input').on('input', function () {

                if ($(this).val().length >= 3) $('#btnConfirmar').css({ 'background-color': 'rgb(10, 240, 171, 1)' }).prop('disabled', false);

                else $('#btnConfirmar').css({ 'background-color': 'rgb(10, 240, 171, .4)' }).prop('disabled', true);
            })
            $('#btnCancelar').focus().on("click", () => {

                $("#div-mensaje-popup").fadeOut(500);
                $("#div-mensaje-popup link").remove();

                respuesta('');
            });
            $('#btnConfirmar').css({ 'background-color': 'rgb(10, 240, 171, .4)' }).prop('disabled', true).on("click", () => {

                $("#div-mensaje-popup").fadeOut(500);
                $("#div-mensaje-popup link").remove();

                let texto = $('#popup-input').val();

                respuesta(texto);
            });
        });
    });

    return promesa;
}

function createFeedbackPopup(titulo, contenido, datosconsulta, botonestxt = ['Cancelar', 'Confirmar']) {

    let promesa = new Promise(respuesta => {

        console.log('Creando Popup');

        if ($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

        $('#div-mensaje-popup').hide();
        $.get(`vistas/popupcalificar.php ? Contenido=${contenido}&Aviso=${titulo}&txtcancelar=${botonestxt[0]}&txtconfirmar=${botonestxt[1]}`, data => {

            $("#div-mensaje-popup").html(data).fadeIn(500);
            $('.estrella').on('mouseenter', function () {

                let hoveredId = parseInt($(this).attr('id'));

                $('.estrella').each(function() {

                    let currentId = parseInt($(this).attr('id'));
                    if (currentId <= hoveredId) $(this).addClass('activada');
                });

            }).on('mouseleave', () => {
                
                $('.estrella').removeClass('activada');
            });
            $('#div-mensaje-popup #mensaje').on('input', function () {

                if ($(this).val().length >= 6) $('#btnConfirmar').css({ 'background-color': 'rgb(10, 240, 171, 1)' }).prop('disabled', false);
            });
            $('#btnCancelar').focus().on("click", () => {

                $("#div-mensaje-popup").fadeOut(500);
                $("#div-mensaje-popup link").remove();

                respuesta(null);
            });
            $('#btnConfirmar').css({ 'background-color': 'rgb(10, 240, 171, .4)' }).prop('disabled', true).on("click", () => {

                $("#div-mensaje-popup").fadeOut(500);
                $("#div-mensaje-popup link").remove();

                let msg = $('#div-mensaje-popup #mensaje').val();

                respuesta({

                    fecha: datosconsulta.fecha,
                    hora: datosconsulta.hora,
                    ido: datosconsulta.ido,
                    mensaje: msg
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