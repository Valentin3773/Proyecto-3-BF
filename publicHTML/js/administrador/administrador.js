$(() => {

    addAdminListeners();
});

function addAdminListeners() {

    $('#btnconsultas').on('click', cargarVistaConsultas);
    $('#btnpacientes').on('click', );
    $('#btnservicios').on('click', );
}

function cargarVistaConsultas() {

    $.get("vistas/vistasadmin/sidebarconsultas.php", data => {

        $('.sidebar').html(data);
        console.log("Cargando vista de 'Consultas'");
    });

    $('#seccionescss').attr('href', 'css/administrador/consultas.css');
    $('#btnconsultas').css({'text-decoration': 'underline'});
}

function cargarVistaPacientes() {

    $.get("vistas/vistasadmin/vistapacientes.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Pacientes'");
    });

    $('#seccionescss').attr('href', 'css/administrador/pacientes.css');
}

function cargarVistaServicios() {

    $.get("vistas/vistasadmin/vistaservicios.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Servicios'");
    });

    $('#seccionescss').attr('href', 'css/administrador/servicios.css');
}