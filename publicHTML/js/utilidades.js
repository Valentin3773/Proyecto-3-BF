$(() => {
    
    $('head').append('<link rel="stylesheet" href="css/popupmensaje.css">');
});

function createPopup(titulo, contenido) {

    console.log('Creando Popup');

    if($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

    $('#div-mensaje-popup').hide();
    $.get(`vistas/popupmensaje.php ? Contenido=${contenido}&Aviso=${titulo}`, data => {

        //$('body').addClass('blurry');

        $("#div-mensaje-popup").html(data).fadeIn(500);
        $('#btnCerrar').on("click", () => {

            $("#div-mensaje-popup").fadeOut(500);
            //$('body').removeClass('blurry');
        });
    });
}

function createHeaderPopup(titulo, contenido, accion) {

    console.log('Creando Popup');

    if($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

    $('#div-mensaje-popup').hide();
    $.get("vistas/popupmensaje.php ? Contenido=" + contenido + "&Aviso=" + titulo, data => {

        $("#div-mensaje-popup").html(data).fadeIn(500);
        $('#btnCerrar').on("click", () => {

            $("#div-mensaje-popup").fadeOut(500);
            $("#div-mensaje-popup link").remove();

            if(typeof accion == 'string') window.location.href = accion;

            else if(typeof accion == 'function') accion();  
        });
    });
}

function createConfirmPopup(titulo, contenido, botonestxt = ['Cancelar', 'Confirmar']) {

    let promesa = new Promise(respuesta => {

        console.log('Creando Popup');

        if($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

        $('#div-mensaje-popup').hide();
        $.get(`vistas/popupconfirmar.php ? Contenido=${contenido}&Aviso=${titulo}&txtcancelar=${botonestxt[0]}&txtconfirmar=${botonestxt[1]}`, data => {

            $("#div-mensaje-popup").html(data).fadeIn(500);
            $('#btnCancelar').on("click", () => {

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

function getWeekDates(date) {

    const currentDate = new Date(date);

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

function permitirFechas(date) {

    var year = date.getFullYear();
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var day = ("0" + date.getDate()).slice(-2);
    var dateString = day + "-" + month + "-" + year;

    if ($.inArray(dateString, fechasPermitidas) !== -1) return [true, 'fechadisponible', "Fecha disponible"];

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