$(() => {

    addAdminListeners();

    $.datepicker.regional['es'] = {

        closeText: 'Cerrar',
        prevText: 'Anterior',
        nextText: 'Siguiente',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };

    $.datepicker.setDefaults($.datepicker.regional['es']);
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

        $('.pacientec').on('click', function () {

            $('.pacientec').css({ 'text-decoration': 'none' });
            $(this).css({ 'text-decoration': 'underline' });

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

                    let ventanaconsultapaciente = 'vistas/vistasadmin/vistaconsultapaciente.php?hora=' + hora + '&fecha=' + fecha + ' &nombreP=' + nombreP + '';

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
    $('nav a, nav.mobile a').css({ 'text-decoration': 'none' });
    $('#btnconsultas, nav.mobile #btnconsultas').css({ 'text-decoration': 'underline' });
}

function cargarVistaAgregarConsulta() {

    $('.sidebar').empty();

    $.get('vistas/vistasadmin/vistaagregarconsulta.php', contenido => {

        loadView(contenido);

        $.get('backend/admin/fechamanagerad.php', data => fechasPermitidas = data.fechasDisponibles);

        $('#contagregarconsulta #paciente').on('change', () => {

            $('#contagregarconsulta #hora').html('<option selected value="">Seleccione una hora</option>').prop('disabled', true);
            $('#contagregarconsulta #duracion').html('<option selected value="">Seleccione la duración</option>').prop('disabled', true);
            $('#agregarconsulta').prop('disabled', true).removeClass('activo').addClass('inactivo');

            if ($('#contagregarconsulta #paciente').val() !== '') $('#contagregarconsulta #fecha, #contagregarconsulta #asunto').val('').prop('disabled', false);

            else $('#contagregarconsulta #fecha, #contagregarconsulta #asunto').val('').prop('disabled', true);
        });

        $('#contagregarconsulta #fecha').datepicker({ beforeShowDay: permitirFechas }).on('change', () => {

            let fechapamandar = $('#contagregarconsulta #fecha').val().split('/');

            let datos = JSON.stringify({

                dia: fechapamandar[0],
                mes: fechapamandar[1],
                anio: fechapamandar[2]
            });

            if ($('#contagregarconsulta #fecha').val() !== '') $.ajax({

                type: "POST",
                url: "backend/admin/horamanagerad.php",
                data: datos,
                processData: false,
                contentType: false,
                success: function (response) {

                    $('#contagregarconsulta #hora').html('<option selected value="">Seleccione una hora</option>').prop('disabled', false);

                    response.horasDisponibles.forEach(elemento => {

                        $('#contagregarconsulta #hora').append(`<option value="${elemento}">${elemento}</option>`);
                    });
                },
                error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
            });

            $('#contagregarconsulta #duracion').html('<option selected value="">Seleccione la duración</option>').prop('disabled', true);
            $('#agregarconsulta').prop('disabled', true).removeClass('activo').addClass('inactivo');
        });

        $('#contagregarconsulta #hora').on('change', () => {

            let fechapamandar = $('#contagregarconsulta #fecha').val().split('/');

            let datos = JSON.stringify({

                dia: fechapamandar[0],
                mes: fechapamandar[1],
                anio: fechapamandar[2],
                hora: $('#contagregarconsulta #hora').val()
            });

            if ($('#contagregarconsulta #hora').val() !== '') $.ajax({

                type: "POST",
                url: "backend/admin/duracionmanager.php",
                data: datos,
                processData: false,
                contentType: false,
                success: function (response) {

                    $('#contagregarconsulta #duracion').html('<option selected value="">Seleccione la duración</option>').prop('disabled', false);

                    response.duracionesDisponibles.forEach(elemento => {

                        $('#contagregarconsulta #duracion').append(`<option value="${elemento}">${fancyHoras(elemento)}</option>`);
                    });
                },
                error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
            });
            else $('#contagregarconsulta #duracion').html('<option selected value="">Seleccione la duración</option>').prop('disabled', true);
            
            $('#agregarconsulta').prop('disabled', true).removeClass('activo').addClass('inactivo');
        });

        $('#contagregarconsulta #duracion').on('change', () => {

            if($('#contagregarconsulta #duracion').val() !== '' && $('#contagregarconsulta #asunto').val().length >= 6) $('#agregarconsulta').prop('disabled', false).removeClass('inactivo').addClass('activo');

            else $('#agregarconsulta').prop('disabled', true).removeClass('activo').addClass('inactivo');
        });

        $('#contagregarconsulta #asunto').on('input', () => {

            if($('#contagregarconsulta #duracion').val() !== '' && $('#contagregarconsulta #asunto').val().length >= 6) $('#agregarconsulta').prop('disabled', false).removeClass('inactivo').addClass('activo');

            else $('#agregarconsulta').prop('disabled', true).removeClass('activo').addClass('inactivo');
        });

        $('#agregarconsulta').on('click', () => {

            let formdatos = new FormData($('#contagregarconsulta')[0]);

            $.ajax({

                type: "POST",
                url: "backend/admin/agregarconsulta.php",
                data: formdatos,
                processData: false,
                contentType: false,
                success: function (response) {

                    if (response.error == undefined) createHeaderPopup('Nuevo Aviso', response.exito, cargarVistaConsultas);

                    else createPopup('Nuevo Aviso', response.error);
                },
                error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
            });
        });
    });
}

function cargarVistaAgregarServicio() {

    $('.sidebar').empty();

    $.get('vistas/vistasadmin/vistaagregarservicio.php', contenido => {

        loadView(contenido);
        let formData = new FormData();


        $('[id="mdF"]').eq(0).on('click', function () {
            $('#inFile1').click();
            $('#inFile1').change(function (event) {

                try {
                    let file1 = event.target.files[0];
                    if (file1) {

                        // Mostrar la imagen
                        let reader = new FileReader();
                        reader.onload = function (e) {
                            $('#icoservicio').prop('src', e.target.result);
                        }

                        // Incrusto la imagen
                        reader.readAsDataURL(file1);

                        // Preparo el formulario
                        formData.append('file1', file1);
                    }
                }
                catch (error) {

                    console.log(error);
                }
            });
        });

        $('[id="mdF"]').eq(1).on('click', function () {
            $('#inFile2').click();
            $('#inFile2').change(function (event) {

                try {
                    let file2 = event.target.files[0];
                    if (file2) {

                        // Mostrar la imagen
                        let reader = new FileReader();
                        reader.onload = function (e) {
                            $('#imgservicio').prop('src', e.target.result);
                        }

                        // Incrusto la imagen
                        reader.readAsDataURL(file2);

                        // Preparo el formulario
                        formData.append('file2', file2);

                    }
                }
                catch (error) {

                    console.log(error);
                }
            });
        });

        $('#nombre').on('input', () => {

            if ($('#nombre').val().length >= 4) $('#descripcion').prop('disabled', false);

            else {

                $('#descripcion').empty().prop('disabled', true);
                $('#agregarservicio').prop('disabled', true).removeClass('activo').addClass('inactivo');
            }
        });

        $('#descripcion').on('input', () => {

            if ($('#descripcion').val().length >= 20) $('#agregarservicio').prop('disabled', false).removeClass('inactivo').addClass('activo');

            else $('#agregarservicio').prop('disabled', true).removeClass('activo').addClass('inactivo');
        })

        $('#agregarservicio').on('click', () => {

            formData.append('nombre', $('#nombre').val()); formData.append('descripcion', $('#descripcion').val());

            if ($('#nombre').val().length >= 4 && $('#descripcion').val().length >= 20) $.ajax({

                type: "POST",
                url: "backend/admin/agregarservicio.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {

                    if (response.error == undefined) createHeaderPopup('Nuevo Aviso', response.exito, cargarVistaServicios);

                    else createPopup('Nuevo Aviso', response.error);
                },
                error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
            });
        });
    });
}

function cargarVistaPacientes() {

    $.get("vistas/vistasadmin/sidebarpacientes.php", data => {

        console.log("Cargando vista de 'Pacientes'");

        $('.sidebar').empty().html(data).fadeIn(200);

        $.get('vistas/vistasadmin/vistapacientes.php', data => {

            loadView(data);

            $('.paciente, .p-contenedor').on('click', function () {

                $('main').empty();

                $('.paciente').css({ 'text-decoration': 'none' });
                $(this).css({ 'text-decoration': 'underline' });
                $('.pacientescontainer #' + $(this).attr('id')).css({ 'text-decoration': 'underline' });

                let url = 'vistas/vistasadmin/vistapacientes.php?idpaciente=' + $(this).attr('id');

                changeView(() => $.get(url, contenido => loadView(contenido)));
            });
        });

        slideActionBar(false);
    });

    $('#seccionescss').attr('href', 'css/administrador/pacientes.css');
    $('nav a, nav.mobile a').css({ 'text-decoration': 'none' });
    $('#btnpacientes, nav.mobile #btnpacientes').css({ 'text-decoration': 'underline' });
}

function cargarVistaServicios() {

    $.get("vistas/vistasadmin/vistaservicios.php", data => {

        loadView(data);
        console.log("Cargando vista de 'Servicios'");

        $('.sidebar').empty().fadeIn(200);

        $('.servicio').on('click', function () {

            let id = ($(this).attr('id'));
            $('main').load(`vistas/vistasadmin/vistaservicios.php?numservicio=` + $(this).attr('id'), function () {

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

                                // Preparo el formulario
                                let formData = new FormData();
                                formData.append('id', id);
                                formData.append('file', file);
                                enviarIMGServicio(formData, 0);;
                            }
                        }
                        catch (error) {

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
                                enviarIMGServicio(formData, 1);
                            }
                        }
                        catch (error) {

                            console.log(error);
                        }
                    });
                })
            });
        });
    });

    slideActionBar(true);

    $('#agregar').off().on('click', () => changeView(cargarVistaAgregarServicio));

    $('#seccionescss').attr('href', 'css/administrador/servicios.css');
    $('nav a, nav.mobile a').css({ 'text-decoration': 'none' });
    $('#btnservicios, nav.mobile #btnservicios').css({ 'text-decoration': 'underline' });
}

function enviarIMGServicio($data, $tipo) {

    if ($tipo == 0) {

        $.ajax({

            type: "POST",
            url: "backend/admin/actualizarIconoService.php",
            data: $data,
            processData: false,
            contentType: false,
            success: function (response) {

                if (response.error == undefined) createPopup('Nuevo Aviso', response.enviar); //window.location.reload();

                else console.log(response.error);
            },
            error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
        });
    }
    else if ($tipo == 1) {

        $.ajax({
            type: "POST",
            url: "backend/admin/actualizarImagenService.php",
            data: $data,
            processData: false,
            contentType: false,
            success: function (response) {

                if (response.error == undefined) createPopup('Nuevo Aviso', response.enviar); // window.location.reload();

                else console.log(response.error);
            },
            error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
        });
    }
    else alert('Que mira bobo :3');
}

function resetAdmin() {

    $('.sidebar').empty();
    $('main').empty();

    $('nav a, nav.mobile a').css({ 'text-decoration': 'none' });

    slideActionBar(false);
}

function slideActionBar(estado) {

    if (estado) {

        $('#agregar').addClass('visible').removeClass('invisible');
        $('.sidebar')[0].style.setProperty('height', '62dvh', 'important');
    }
    else {

        $('#agregar').addClass('invisible').removeClass('visible');
        $('.sidebar')[0].style.setProperty('height', '100dvh', 'important');
    }
}

let fechasPermitidas = ['20-09-2024'];

function permitirFechas(date) {

    var year = date.getFullYear();
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var day = ("0" + date.getDate()).slice(-2);
    var dateString = day + "-" + month + "-" + year;

    if ($.inArray(dateString, fechasPermitidas) !== -1) return [true, 'fechadisponible', "Fecha disponible"];

    else return [false, 'fechanodisponible', "Fecha no disponible"];
}

function fancyHoras(minutos) {

    const horas = Math.floor(minutos / 60);
    const minutosRestantes = minutos % 60;

    let resultado = '';

    if (horas > 0) resultado += horas + ' hora' + (horas > 1 ? 's' : '');

    if (horas > 0 && minutosRestantes > 0) resultado += ' y ';

    if (minutosRestantes > 0) resultado += minutosRestantes + ' minuto' + (minutosRestantes > 1 ? 's' : '');

    return resultado || '0 minutos';
}

function changeView(vista) {

    $('main').fadeOut(200, vista);
    $('sidebar').fadeOut(200);
}

function loadView(contenido) {

    $('main').empty().html(contenido).fadeIn(200);
}