$(() => {

    $('#miperfil').on('click', () => $('main').fadeOut(200, cargarVistaPerfil));
    $('#misconsultas').on('click', () => $('main').fadeOut(200, cargarVistaConsultas));
    $('#horarios').on('click', () => $('main').fadeOut(200, cargarVistaHorarios));
    $('#inactividades').on('click', () => $('main').fadeOut(200, cargarVistaInactividades));
    $('#seguridad').on('click', () => $('main').fadeOut(200, cargarVistaSeguridad));

    $('#cerrarsesion').on('click', () => $('main').fadeOut(150, () => window.location.href = 'login.php?estado=3'));

    switch($('main').data('vista')) {

        case 1: cargarVistaPerfil(); break;

        case 2: cargarVistaConsultas(); break;
    }
});

function cargarVistaPerfil() {

    $('main').prop('data-vista', 0);

    console.log("Cargando vista de 'Mi Perfil'");

    $.get('vistas/vistasperfil/vistaperfil.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
    });

    $('#sidebar #btnsuperiores button').css({'text-decoration': 'none'});
    $('#miperfil').css({'text-decoration': 'underline'});
    $('#seccionescss').prop('href', 'css/perfil/miperfil.css');
}

function cargarVistaConsultas() {

    console.log("Cargando vista de 'Mis Consultas'");

    $.get('vistas/vistasperfil/vistaconsultas.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
    });

    $('#sidebar #btnsuperiores button').css({'text-decoration': 'none'});
    $('#misconsultas').css({'text-decoration': 'underline'});
    $('#seccionescss').prop('href', 'css/perfil/consultas.css');
}

function cargarVistaHorarios() {

    console.log("Cargando vista de 'Horarios'");

    $.get('vistas/vistasperfil/vistahorarios.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
    });

    $('#sidebar #btnsuperiores button').css({'text-decoration': 'none'});
    $('#horarios').css({'text-decoration': 'underline'});
    $('#seccionescss').prop('href', 'css/perfil/horarios.css');
}

function cargarVistaInactividades() {

    console.log("Cargando vista de 'Inactividades'");

    $.get('vistas/vistasperfil/vistainactividades.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
    });

    $('#sidebar #btnsuperiores button').css({'text-decoration': 'none'});
    $('#inactividades').css({'text-decoration': 'underline'});
    $('#seccionescss').prop('href', 'css/perfil/inactividades.css');
}

function cargarVistaSeguridad() {

    console.log("Cargando vista de 'Seguridad'");

    $.get('vistas/vistasperfil/vistaseguridad.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
    });

    $('#sidebar #btnsuperiores button').css({'text-decoration': 'none'});
    $('#seguridad').css({'text-decoration': 'underline'});
    $('#seccionescss').prop('href', 'css/perfil/seguridad.css');
}