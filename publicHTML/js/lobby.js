$(() => {

    cargarVistaInicio();

    addListeners();
});

function addListeners() {

    $('#logo').on('click', cargarVistaInicio)

    $('#inicio, #iniciom').on('click', cargarVistaInicio);

    $('#servicios, #serviciosm').on('click', cargarVistaServicios);

    $('#contacto, #contactom').on('click', cargarVistaContacto);
}

function cargarVistaInicio() {

    $.get("http://127.0.0.1:5500/publicHTML/vistainicio.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Inicio'");
    });

    $('#inicio, #iniciom').css({ 'text-decoration': 'underline' });
    $('#servicios, #serviciosm').css({ 'text-decoration': 'none' });
    $('#contacto, #contactom').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('src', 'css/inicio.css');
}

function cargarVistaServicios() {

    $.get("http://127.0.0.1:5500/publicHTML/vistaservicios.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Servicios'");
    });

    $('#servicios, #serviciosm').css({ 'text-decoration': 'underline' });
    $('#inicio, #iniciom').css({ 'text-decoration': 'none' });
    $('#contacto, #contactom').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('src', 'css/servicios.css');
}

function cargarVistaContacto() {

    $.get("http://127.0.0.1:5500/publicHTML/vistacontacto.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Contacto'");
    });

    $('#contacto, #contactom').css({ 'text-decoration': 'underline' });
    $('#inicio, #iniciom').css({ 'text-decoration': 'none' });
    $('#servicios, #serviciosm').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('src', 'css/contacto.css');
}

