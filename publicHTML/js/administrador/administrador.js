$(() => {

    addAdminListeners();
});

function addAdminListeners() {

    $('#btnconsultas').on('click', cargarVistaConsultas);
    $('#btnpacientes').on('click', cargarVistaPacientes);
    $('#btnservicios').on('click', cargarVistaServicios);
}

function cargarVistaConsultas() {

    $('.sidebar')[0].scrollTo({top: 0, behavior: 'smooth'});
    $('main')[0].scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistasadmin/sidebarconsultas.php", data => {

        $('.sidebar').html(data);
        $('main').html('');
        console.log("Cargando vista de 'Consultas'");

        $('.pacientec').on('click', function() {

            $('.pacientec').css({'text-decoration': 'none'});
            $(this).css({'text-decoration': 'underline'});

            let url = 'vistas/vistasadmin/vistaconsultas.php?idpaciente=' + $(this).attr('id');
    
            console.log(url);
    
            $.get(url, data => {
    
                $('main').html(data);
        
            });
        });
    });

    $('#seccionescss').attr('href', 'css/administrador/consultas.css');
    $('#btnconsultas').css({'text-decoration': 'underline'});
}

function cargarVistaPacientes() {

    $('.sidebar')[0].scrollTo({top: 0, behavior: 'smooth'});
    $('main')[0].scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistasadmin/vistapacientes.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Pacientes'");

        $.get("vistas/vistasadmin/sidebarpacientes.php", data => {

            $('.sidebar').html(data);
        });
    });

    $('#seccionescss').attr('href', 'css/administrador/pacientes.css');
}

function cargarVistaServicios() {

    $('.sidebar')[0].scrollTo({top: 0, behavior: 'smooth'});
    $('main')[0].scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistasadmin/vistaservicios.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Servicios'");
    });

    $('#seccionescss').attr('href', 'css/administrador/servicios.css');
}

function addPacienteListener() {

    
}