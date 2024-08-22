$(() => {

    $('#miperfil').on('click', () => $('main').fadeOut(200, cargarVistaPerfil));
    $('#misconsultas').on('click', () => $('main').fadeOut(200, cargarVistaConsultas));
    $('#horarios').on('click', () => $('main').fadeOut(200, cargarVistaHorarios));
    $('#inactividades').on('click', () => $('main').fadeOut(200, cargarVistaInactividades));
    $('#seguridad').on('click', () => $('main').fadeOut(200, cargarVistaSeguridad));
    $('#cerrarsesion').on('click', () => $('main').fadeOut(150, () => window.location.href = 'login.php?estado=3'));

    switch ($('main').data('vista')) {

        case 1: cargarVistaPerfil(); break;

        case 2: cargarVistaConsultas(); break;
    }

});

var Datos = {
    tipo: "",
    dato: "",
    namedata: "",
    old: "",
    id: ""
};

function cargarVistaPerfil() {

    $('main').prop('data-vista', 0);

    console.log("Cargando vista de 'Mi Perfil'");

    $.get('vistas/vistasperfil/vistaperfil.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
        integrarEventos();
    });

    $('#sidebar #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#miperfil').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/miperfil.css');
}

function integrarBoton($datos, $id) {
    //Aca sucede la magia
    $($id).attr('src', 'img/iconosvg/Guardar.svg');
    quitarEventos();
    $($id).on('click', function () {
        console.log("Guardar");
        $datos['tipo'] = $("#\\$\\$\\$").attr('data-type');
        $datos['dato'] = $($datos['id']).val();
        funcionguardarCambios($datos);
        integrarEventos();
        $datos['dato'] = "";
        $datos['namedata'] = "";
        $datos['old'] = "";
        $datos['id'] = "";
        console.log(Datos);
        window.location.replace("index.php");
    });
}

function integrarEventos() {
    //Listeners con datos importantes
    $('#mdF').on('click', function () { console.log("Foto"); });
    $('#mdN').on('click', function () { console.log("Nombre"); Datos['namedata'] = "nombre"; $('#inNombre').removeAttr('disabled'); Datos['old'] = $('#inNombre').val(); Datos['id'] = "#inNombre"; integrarBoton(Datos, "#mdN"); alert(Datos['old']); });
    $('#mdA').on('click', function () { console.log("Apellido"); Datos['namedata'] = "apellido"; $('#inApellido').removeAttr('disabled'); Datos['old'] = $('#inApellido').val(); Datos['id'] = "#inApellido"; integrarBoton(Datos, "#mdA") });
    $('#mdT').on('click', function () { console.log("Telefono"); Datos['namedata'] = "telefono"; $('#inTelefono').removeAttr('disabled'); Datos['old'] = $('#inTelefono').val(); Datos['id'] = "#inTelefono"; integrarBoton(Datos, "#mdT") });
    $('#mdD').on('click', function () { console.log("Direccion"); Datos['namedata'] = "direccion"; $('#inDireccion').removeAttr('disabled'); Datos['old'] = $('#inDireccion').val(); Datos['id'] = "#inDireccion"; integrarBoton(Datos, "#mdD") });
    $('#mdE').on('click', function () { console.log("Email"); Datos['namedata'] = "email"; $('#inEmail').removeAttr('disabled'); Datos['old'] = $('#inEmail').val(); Datos['id'] = "#inEmail"; integrarBoton(Datos, "#mdE") });
}

function quitarEventos() {
    $('#mdF').off("click");
    $('#mdN').off("click");
    $('#mdA').off("click");
    $('#mdT').off("click");
    $('#mdD').off("click");
    $('#mdE').off("click");
}

function cargarVistaConsultas() {

    console.log("Cargando vista de 'Mis Consultas'");

    $.get('vistas/vistasperfil/vistaconsultas.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
    });

    $('#sidebar #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#misconsultas').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/consultas.css');
}

function cargarVistaHorarios() {

    console.log("Cargando vista de 'Horarios'");

    $.get('vistas/vistasperfil/vistahorarios.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
    });

    $('#sidebar #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#horarios').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/horarios.css');
}

function cargarVistaInactividades() {

    console.log("Cargando vista de 'Inactividades'");

    $.get('vistas/vistasperfil/vistainactividades.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
    });

    $('#sidebar #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#inactividades').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/inactividades.css');
}

function cargarVistaSeguridad() {

    console.log("Cargando vista de 'Seguridad'");

    $.get('vistas/vistasperfil/vistaseguridad.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
    });

    $('#sidebar #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#seguridad').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/seguridad.css');
}