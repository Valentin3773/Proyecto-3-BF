$(() => {

    addAdminListeners();
});

function addAdminListeners() {

    $('#btnconsultas, nav.mobile #btnconsultas').on('click', () => changeView(cargarVistaConsultas));
    $('#btnpacientes, nav.mobile #btnpacientes').on('click', () => changeView(cargarVistaPacientes));
    $('#btnservicios, nav.mobile #btnservicios').on('click', () => changeView(cargarVistaServicios));

    $('#logo').on('click', resetAdmin);
}

function cargarVistaConsultas() {

    $.get("vistas/vistasadmin/sidebarconsultas.php", data => {

        $('.sidebar').html(data);
        loadView('<div class="w-100 h-100 d-flex justify-content-center align-items-center"><h1 class="titdefconsultas">Has clic en un paciente para ver sus consultas</h1></div>');
        console.log("Cargando vista de 'Consultas'");

        $('.pacientec').on('click', function() {

            $('.pacientec').css({'text-decoration': 'none'});
            $(this).css({'text-decoration': 'underline'});

            let url = 'vistas/vistasadmin/vistaconsultas.php?idpaciente=' + $(this).attr('id');
            const nombreP = $(this).html();

            $.get(url, data => {
    
                loadView(data);

                slideActionBar(true);

                $('.consulta').click(function (e) { 

                    e.preventDefault();

                    const fecha = $(this).attr('data-fecha');
                    const hora = $(this).attr('data-hora');
                     
                    $('main').html('');

                    let ventanaconsultapaciente = 'vistas/vistasadmin/vistaconsultapaciente.php?hora='+hora+'&fecha='+fecha+' &nombreP='+nombreP+'';

                    $.get(ventanaconsultapaciente, ventana => {

                        $('main').html(ventana);

                        slideActionBar(false);
                    });
                });
            });
        });

        slideActionBar(true);

        $('#agregar').off().on('click', () => $('main').fadeOut(300, cargarVistaAgregarConsulta));
    });

    $('#seccionescss').attr('href', 'css/administrador/consultas.css');
    $('nav a, nav.mobile a').css({'text-decoration': 'none'});
    $('#btnconsultas, nav.mobile #btnconsultas').css({'text-decoration': 'underline'});
}

function cargarVistaAgregarConsulta() {

    $('.sidebar').empty();

    $.get('vistas/vistasadmin/vistaagregarconsulta.php', contenido => {

        loadView(contenido);
    })
}

function cargarVistaPacientes() {

    $.get("vistas/vistasadmin/sidebarpacientes.php", data => {

        console.log("Cargando vista de 'Pacientes'");

        $('.sidebar').empty().html(data).fadeIn(200);
        
        $.get('vistas/vistasadmin/vistapacientes.php', data => {

            loadView(data);

            $('.paciente, .p-contenedor').on('click', function() {

                $('main').empty();

                $('.paciente').css({'text-decoration': 'none'});
                $(this).css({'text-decoration': 'underline'});
                $('.pacientescontainer #' + $(this).attr('id')).css({'text-decoration': 'underline'});
    
                let url = 'vistas/vistasadmin/vistapacientes.php?idpaciente=' + $(this).attr('id');
    
                $('main').load(url);
            });
        });

        slideActionBar(false);
    });

    $('#seccionescss').attr('href', 'css/administrador/pacientes.css');
    $('nav a, nav.mobile a').css({'text-decoration': 'none'});
    $('#btnpacientes, nav.mobile #btnpacientes').css({'text-decoration': 'underline'});
}

function cargarVistaServicios() {

    $.get("vistas/vistasadmin/vistaservicios.php", data => {

        loadView(data);
        console.log("Cargando vista de 'Servicios'");

        $('.sidebar').empty().fadeIn(200);

        $('.servicio').on('click', function() {
            let id = ($(this).attr('id'));
            $('main').load(`vistas/vistasadmin/vistaservicios.php?numservicio=` + $(this).attr('id'),function(){
                $('[id="mdF"]').eq(0).on('click', function () {
                    $('#inFile1').click();
                    $('#inFile1').change(function (event) {
            
                        let file = event.target.files[0];
                        try {
            
                            if (file) {
            
                                // Mostrar la imagen
                                let reader = new FileReader();
                                reader.onload = function (e) {
                                    $('#icoservicio').prop('src', e.target.result);
                                }

                                // Incrusto la imagen
                                reader.readAsDataURL(file);

                                //Preparo el formulario
                                let formData = new FormData();
                                formData.append('id', id);
                                formData.append('file', file);
                                enviarIMGServicio(formData,0);;
            
                            }
                        } catch (error) {
            
                            console.log(error);
                        }
                    });
                });
                $('[id="mdF"]').eq(1).on('click', function () {
                    $('#inFile2').click();
                    $('#inFile2').change(function (event) {
            
                        let file = event.target.files[0];
                        try {
            
                            if (file) {
            
                                // Mostrar la imagen
                                let reader = new FileReader();
                                reader.onload = function (e) {
                                    $('#imgservicio').prop('src', e.target.result);
                                }
                                // Incrusto la imagen
                                reader.readAsDataURL(file);

                                //Preparo el formulario
                                let formData = new FormData();
                                formData.append('id', id);
                                formData.append('file', file);
                                enviarIMGServicio(formData,1);
            
                            }
                        } catch (error) {
            
                            console.log(error);
                        }
                    });
                })
            });
        });
   

    });

    slideActionBar(true);

    $('#seccionescss').attr('href', 'css/administrador/servicios.css');
    $('nav a, nav.mobile a').css({'text-decoration': 'none'});
    $('#btnservicios, nav.mobile #btnservicios').css({'text-decoration': 'underline'});
}

function enviarIMGServicio($data,$tipo){
    if($tipo == 0){
        console.log("0aaaa");
        $.ajax({
            type: "POST",
            url: "backend/admin/actualizarIconoService.php",
            data: $data,    
            processData: false,
            contentType: false,
            success: function (response) {

                if(response.error == undefined) createPopup('Nuevo Aviso',response.enviar); //window.location.reload();
                
                else {console.log(response.error);
            }
            },  error: (jqXHR, estado, outputError) => {

                console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR);
            }
        });
    } else if ($tipo == 1){
        $.ajax({
            type: "POST",
            url: "backend/admin/actualizarImagenService.php",
            data: $data,    
            processData: false,
            contentType: false,
            success: function (response) {

                if(response.error == undefined) createPopup('Nuevo Aviso',response.enviar); //window.location.reload();
                
                else {console.log(response.error);
            }
            },  error: (jqXHR, estado, outputError) => {

                console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR);
            }
        });
    } else {
        alert('Que mira bobo :3');
    }
}

function resetAdmin() {

    $('.sidebar').empty();
    $('main').empty();

    $('nav a, nav.mobile a').css({'text-decoration': 'none'});

    slideActionBar(false);
}

function slideActionBar(estado) {

    if(estado) {

        $('#agregar').addClass('visible').removeClass('invisible');
        $('.sidebar')[0].style.setProperty('height', '62dvh', 'important');
    }
    else {

        $('#agregar').addClass('invisible').removeClass('visible');
        $('.sidebar')[0].style.setProperty('height', '100dvh', 'important');
    }
}

function changeView(vista) {

    $('main').fadeOut(200, vista);
    $('sidebar').fadeOut(200);
}

function loadView(contenido) {

    $('main').empty().html(contenido).fadeIn(200);
}