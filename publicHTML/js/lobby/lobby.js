$(() => {

    addListeners();

    cargarVistaInicio();

});

function addListeners() {

    $('#logo').on('click', cargarVistaInicio);

    $('#nosotros').on('click', cargarVistaNosotros);

    $('#inicio, #iniciom').on('click', cargarVistaInicio);

    $('#servicios, #serviciosm').on('click', cargarVistaServicios);

    $('#contacto, #contactom').on('click', cargarVistaContacto);

    $('#btnchat').on('click', () => window.open('https://api.whatsapp.com/send/?phone=598091814295', '_blank'));
}

function cargarVistaInicio() {

    window.scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistaslobby/vistainicio.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Inicio'");

        // history.pushState({}, '', 'inicio');
    });

    $('#inicio, #iniciom').css({ 'text-decoration': 'underline' });
    $('#servicios, #serviciosm').css({ 'text-decoration': 'none' });
    $('#contacto, #contactom').css({ 'text-decoration': 'none' });
    $('#nosotros').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('href', 'css/inicio.css');
}

function cargarVistaServicios() {

    window.scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistaslobby/vistaservicios.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Servicios'");

        iniciarServicios();

        // history.pushState({}, '', 'servicios');
    });

    $('#servicios, #serviciosm').css({ 'text-decoration': 'underline' });
    $('#inicio, #iniciom').css({ 'text-decoration': 'none' });
    $('#contacto, #contactom').css({ 'text-decoration': 'none' });
    $('#nosotros').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('href', 'css/servicios.css');
}

function cargarVistaContacto() {

    window.scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistaslobby/vistacontacto.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Contacto'");

        $('#innombre').focus();

        // history.pushState({}, '', 'contacto');
    });

    $('#contacto, #contactom').css({ 'text-decoration': 'underline' });
    $('#inicio, #iniciom').css({ 'text-decoration': 'none' });
    $('#servicios, #serviciosm').css({ 'text-decoration': 'none' });
    $('#nosotros').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('href', 'css/contacto.css');
}

function cargarVistaNosotros() {

    window.scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistaslobby/vistanosotros.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Nosotros'");

        // history.pushState({}, '', 'contacto');
    });

    $('#inicio, #iniciom').css({ 'text-decoration': 'none' });
    $('#servicios, #serviciosm').css({ 'text-decoration': 'none' });
    $('#contacto, #contactom').css({ 'text-decoration': 'none' });
    $('#nosotros').css({ 'text-decoration': 'underline' });

    $('#seccionescss').attr('href', 'css/nosotros.css');
}