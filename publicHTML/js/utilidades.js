function createPopup(titulo, contenido) {

    console.log('Creando Popup');

    if($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

    $('#div-mensaje-popup').hide();
    $.get("vistas/popupmensaje.php ? Contenido=" + contenido + "&Aviso=" + titulo, data => {

        $("#div-mensaje-popup").fadeIn(500).html(data);
        $('#btnCerrar').on("click", () => {

            $("#div-mensaje-popup").fadeOut(500);
        });
    });
}

function createHeaderPopup(titulo, contenido, accion) {

    console.log('Creando Popup');

    if($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

    $('#div-mensaje-popup').hide();
    $.get("vistas/popupmensaje.php ? Contenido=" + contenido + "&Aviso=" + titulo, data => {

        $("#div-mensaje-popup").fadeIn(500).html(data);
        $('#btnCerrar').on("click", () => {

            $("#div-mensaje-popup").fadeOut(500);

            if(typeof accion == 'string') window.location.href = accion;

            else if(typeof accion == 'function') accion();  
        });
    });
}