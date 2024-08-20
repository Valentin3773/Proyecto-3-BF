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

    //$('#in').prop('disabled', true);
    $.get('vistas/vistasperfil/vistaperfil.php', contenido => {
        
        let Datos = {
            tipo: "",
            dato: "",
            namedata: "",
            old: "",
            id: ""
        };

        
        $('main').empty().html(contenido).fadeIn(200);

        //Listeners con datos importantes
        $('#mdF').on('click', function () {console.log("Foto");});
        $('#mdN').on('click', function () {console.log("Nombre");Datos['namedata']="nombre";$('#inNombre').removeAttr('disabled');Datos['old'] = $('#inNombre').val();Datos['id']="#inNombre";integrarBoton(Datos)});
        $('#mdA').on('click', function () {console.log("Apellido"); Datos['namedata']="apellido";$('#inApellido').removeAttr('disabled');Datos['old'] = $('#inApellido').val();Datos['id']="#inApellido";integrarBoton(Datos)});
        $('#mdT').on('click', function () {console.log("Telefono"); Datos['namedata']="telefono";$('#inTelefono').removeAttr('disabled');Datos['old'] = $('#inTelefono').val();Datos['id']="#inTelefono";integrarBoton(Datos)});
        $('#mdD').on('click', function () {console.log("Direccion"); Datos['namedata']="direccion";$('#inDireccion').removeAttr('disabled');Datos['old'] = $('#inDireccion').val();Datos['id']="#inDireccion";integrarBoton(Datos)});
        $('#mdE').on('click', function () {console.log("Email"); Datos['namedata']="email";$('#inEmail').removeAttr('disabled');Datos['old'] = $('#inEmail').val();Datos['id']="#inEmail";integrarBoton(Datos)});
    });

    $('#sidebar #btnsuperiores button').css({'text-decoration': 'none'});
    $('#miperfil').css({'text-decoration': 'underline'});
    $('#seccionescss').prop('href', 'css/perfil/miperfil.css');
}

function integrarBoton($datos){
    //Aca sucede la magia
    $('#btnGC').html( "<button id='guardarCambios' class='btn btn-primary'>Guardar Cambios</button> <button id='Cancelar' class='btn btn-primary'>Cancelar</button>" );
    $('#guardarCambios').on('click', function () {console.log("Guardar");$datos['tipo']=$("#\\$\\$\\$").attr('data-type');$datos['dato']=$($datos['id']).val();funcionguardarCambios($datos)}); 
    $('#Cancelar').on('click', function () {console.log("Cancelar");funcioncancelarCambios($datos)});
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