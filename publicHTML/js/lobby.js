$(() => {

    cargarVistaInicio();

    addListeners();

    let header = $('header');

    header.
});

function addListeners() {

    $('#logo').on('click', cargarVistaInicio)

    $('#inicio').on('click', cargarVistaInicio);

    $('#servicios').on('click', cargarVistaServicios);

    $('#contacto').on('click', cargarVistaContacto);
}

function cargarVistaInicio() {

    $.get("http://127.0.0.1:5500/publicHTML/vistainicio.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Inicio'");
    });

    $('#inicio').css({ 'text-decoration': 'underline' });
    $('#servicios').css({ 'text-decoration': 'none' });
    $('#contacto').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('src', 'css/inicio.css');
}

function cargarVistaServicios() {

    $.get("http://127.0.0.1:5500/publicHTML/vistaservicios.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Servicios'");
    });

    $('#servicios').css({ 'text-decoration': 'underline' });
    $('#inicio').css({ 'text-decoration': 'none' });
    $('#contacto').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('src', 'css/servicios.css');
}

function cargarVistaContacto() {

    $.get("http://127.0.0.1:5500/publicHTML/vistacontacto.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Contacto'");
    });

    $('#contacto').css({ 'text-decoration': 'underline' });
    $('#inicio').css({ 'text-decoration': 'none' });
    $('#servicios').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('src', 'css/contacto.css');
}

