$(() => {

    addAdminListeners();
});

function addAdminListeners() {

    $('#btnconsultas').on('click', cargarVistaConsultas);
    $('#btnpacientes').on('click', cargarVistaPacientes);
    $('#btnservicios').on('click', cargarVistaServicios);

    $('#logo').on('click', () => {

        $('.sidebar').html('');
        $('main').html('');
    });
}

function cargarVistaConsultas() {

    $('.sidebar').html('');
    $('main').html('');

    $('.sidebar')[0].scrollTo({top: 0, behavior: 'smooth'});
    $('main')[0].scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistasadmin/sidebarconsultas.php", data => {

        $('.sidebar').html(data);
        $('main').html('<div class="w-100 h-100 d-flex justify-content-center align-items-center"><h1 class="titdefconsultas">Has clic en un paciente para ver sus consultas</h1></div>');
        console.log("Cargando vista de 'Consultas'");

        $('.pacientec').on('click', function() {

            $('main').html('');

            $('.pacientec').css({'text-decoration': 'none'});
            $(this).css({'text-decoration': 'underline'});

            let url = 'vistas/vistasadmin/vistaconsultas.php?idpaciente=' + $(this).attr('id');

            $.get(url, data => {
    
                $('main').html(data);
        
            });
        });
    });

    $('#seccionescss').attr('href', 'css/administrador/consultas.css');
    $('nav a').css({'text-decoration': 'none'});
    $('#btnconsultas').css({'text-decoration': 'underline'});
}

function cargarVistaPacientes() {
    
    $('.sidebar').html('');
    $('main').html('');

    $('.sidebar')[0].scrollTo({top: 0, behavior: 'smooth'});
    $('main')[0].scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistasadmin/sidebarpacientes.php", data => {

        console.log("Cargando vista de 'Pacientes'");

        $('.sidebar').html(data);
        
        $.get('vistas/vistasadmin/vistapacientes.php', data => {

            $('main').html(data);

            $('.paciente, .p-contenedor').on('click', function() {

                $('main').html('');

                $('.paciente').css({'text-decoration': 'none'});
                $(this).css({'text-decoration': 'underline'});
                $('.pacientescontainer #' + $(this).attr('id')).css({'text-decoration': 'underline'});
    
                let url = 'vistas/vistasadmin/vistapacientes.php?idpaciente=' + $(this).attr('id');
    
                $.get(url, data => {
        
                    $('main').html(data);
            
                });
            });
        });
    });

    $('#seccionescss').attr('href', 'css/administrador/pacientes.css');
    $('nav a').css({'text-decoration': 'none'});
    $('#btnpacientes').css({'text-decoration': 'underline'});
}

function cargarVistaServicios() {

    $('.sidebar').html('');
    $('main').html('');

    $('.sidebar')[0].scrollTo({top: 0, behavior: 'smooth'});
    $('main')[0].scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistasadmin/vistaservicios.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Servicios'");
    });

    $('#seccionescss').attr('href', 'css/administrador/servicios.css');
    $('nav a').css({'text-decoration': 'none'});
    $('#btnservicios').css({'text-decoration': 'underline'});
}