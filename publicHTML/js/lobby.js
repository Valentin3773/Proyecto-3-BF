$(() => {

    AOS.init();

    addListeners();

    cargarVistaInicio();
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

        // history.pushState({}, '', 'inicio');
    });

    $('#inicio, #iniciom').css({ 'text-decoration': 'underline' });
    $('#servicios, #serviciosm').css({ 'text-decoration': 'none' });
    $('#contacto, #contactom').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('href', 'css/inicio.css');
}

function cargarVistaServicios() {

    $.get("http://127.0.0.1:5500/publicHTML/vistaservicios.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Servicios'");

        iniciarServicios();

        // history.pushState({}, '', 'servicios');
    });

    $('#servicios, #serviciosm').css({ 'text-decoration': 'underline' });
    $('#inicio, #iniciom').css({ 'text-decoration': 'none' });
    $('#contacto, #contactom').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('href', 'css/servicios.css');
}

function cargarVistaContacto() {

    $.get("http://127.0.0.1:5500/publicHTML/vistacontacto.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Contacto'");

        // history.pushState({}, '', 'contacto');
    });

    $('#contacto, #contactom').css({ 'text-decoration': 'underline' });
    $('#inicio, #iniciom').css({ 'text-decoration': 'none' });
    $('#servicios, #serviciosm').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('href', 'css/contacto.css');
}