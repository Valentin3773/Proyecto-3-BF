$(() => {

    addAdminListeners();
});

function addAdminListeners() {

    $('#btnconsultas, nav.mobile #btnconsultas').on('click', cargarVistaConsultas);
    $('#btnpacientes, nav.mobile #btnpacientes').on('click', cargarVistaPacientes);
    $('#btnservicios, nav.mobile #btnservicios').on('click', cargarVistaServicios);

    $('#logo').on('click', resetAdmin);
}

function cargarVistaConsultas() {

    $('.sidebar').empty();
    $('main').empty();

    $('.sidebar')[0].scrollTo({top: 0, behavior: 'smooth'});
    $('main')[0].scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistasadmin/sidebarconsultas.php", data => {

        $('.sidebar').html(data);
        $('main').html('<div class="w-100 h-100 d-flex justify-content-center align-items-center"><h1 class="titdefconsultas">Has clic en un paciente para ver sus consultas</h1></div>');
        console.log("Cargando vista de 'Consultas'");

        $('.pacientec').on('click', function() {

            $('main').empty();

            $('.pacientec').css({'text-decoration': 'none'});
            $(this).css({'text-decoration': 'underline'});

            let url = 'vistas/vistasadmin/vistaconsultas.php?idpaciente=' + $(this).attr('id');
            const nombreP = $(this).html();

            $.get(url, data => {
    
                $('main').html(data);

                $('.consulta').click(function (e) { 
                    e.preventDefault();
                     const fecha = $(this).attr('data-fecha');
                     const hora = $(this).attr('data-hora');
                     

                    $('main').html('');
                    let ventanaconsultapaciente = 'vistas/vistasadmin/vistaconsultapaciente.php? hora='+hora+'&fecha='+fecha+' &nombreP='+nombreP+'';

                    $.get(ventanaconsultapaciente,ventana => {

                        $('main').html(ventana);

                    });
                });
            });

        });

    });

    $('#seccionescss').attr('href', 'css/administrador/consultas.css');
    $('nav a, nav.mobile a').css({'text-decoration': 'none'});
    $('#btnconsultas, nav.mobile #btnconsultas').css({'text-decoration': 'underline'});
}

function cargarVistaPacientes() {
    
    $('.sidebar').empty();
    $('main').empty();

    $('.sidebar')[0].scrollTo({top: 0, behavior: 'smooth'});
    $('main')[0].scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistasadmin/sidebarpacientes.php", data => {

        console.log("Cargando vista de 'Pacientes'");

        $('.sidebar').html(data);
        
        $.get('vistas/vistasadmin/vistapacientes.php', data => {

            $('main').html(data);

            $('.paciente, .p-contenedor').on('click', function() {

                $('main').empty();

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
    $('nav a, nav.mobile a').css({'text-decoration': 'none'});
    $('#btnpacientes, nav.mobile #btnpacientes').css({'text-decoration': 'underline'});
}

function cargarVistaServicios() {

    $('.sidebar').empty();
    $('main').empty();

    $('.sidebar')[0].scrollTo({top: 0, behavior: 'smooth'});
    $('main')[0].scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistasadmin/vistaservicios.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Servicios'");
    });

    $('#seccionescss').attr('href', 'css/administrador/servicios.css');
    $('nav a, nav.mobile a').css({'text-decoration': 'none'});
    $('#btnservicios, nav.mobile #btnservicios').css({'text-decoration': 'underline'});
}

function resetAdmin() {

    $('.sidebar').empty();
    $('main').empty();

    $('nav a, nav.mobile a').css({'text-decoration': 'none'});
}