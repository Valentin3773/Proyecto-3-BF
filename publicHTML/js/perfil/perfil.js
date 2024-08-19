$(() => {

    $('#miperfil').on('click', () => $('main').fadeOut(200, cargarVistaPerfil));
    $('#misconsultas').on('click', () => $('main').fadeOut(200, cargarVistaConsultas));
    $('#seguridad').on('click', () => $('main').fadeOut(200, cargarVistaSeguridad));

    $('#cerrarsesion').on('click', () => $('main').fadeOut(150, () => window.location.href = 'login.php?estado=3'));

    switch($('main').data('vista')) {

        case 1: cargarVistaPerfil(); break;

        case 2: cargarVistaConsultas(); break;
    }
});

function cargarVistaPerfil() {

    console.log("Cargando vista de 'Mi Perfil'");

    $('main').empty().load('vistas/vistasperfil/vistaperfil.php').fadeIn(200);

    $('#sidebar #btnsuperiores button').css({'text-decoration': 'none'});
    $('#miperfil').css({'text-decoration': 'underline'});
    $('#seccionescss').prop('href', 'css/perfil/miperfil.css');
}

function cargarVistaConsultas() {

    console.log("Cargando vista de 'Mis Consultas'");

    $('main').empty().load('vistas/vistasperfil/vistaconsultas.php').fadeIn(200);

    $('#sidebar #btnsuperiores button').css({'text-decoration': 'none'});
    $('#misconsultas').css({'text-decoration': 'underline'});
    $('#seccionescss').prop('href', 'css/perfil/consultas.css');
}

function cargarVistaSeguridad() {

    console.log("Cargando vista de 'Seguridad'");

    $('main').empty().load('vistas/vistasperfil/vistaseguridad.php').fadeIn(200);

    $('#sidebar #btnsuperiores button').css({'text-decoration': 'none'});
    $('#seguridad').css({'text-decoration': 'underline'});
    $('#seccionescss').prop('href', 'css/perfil/seguridad.css');
}