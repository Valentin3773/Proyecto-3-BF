$(() => {
    
    $('head').append('<link rel="stylesheet" href="css/popupmensaje.css">');
});

function createPopup(titulo, contenido) {

    console.log('Creando Popup');

    if($("#div-mensaje-popup").length === 0) $('body').append('<div id="div-mensaje-popup"></div>');

    $('#div-mensaje-popup').hide();
    $.get(`vistas/popupmensaje.php ? Contenido=${contenido}&Aviso=${titulo}`, data => {

        $('body').addClass('blurry');

        $("#div-mensaje-popup").html(data).fadeIn(500);
        $('#btnCerrar').on("click", () => {

            $("#div-mensaje-popup").fadeOut(500);
            $('body').removeClass('blurry');
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