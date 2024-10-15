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

    changeView(() => loadView('<div class="w-100 h-100 d-flex justify-content-center align-items-center"><h1 class="titinformativo">Bienvenido al administrador</h1></div>'));
});

function addAdminListeners() {

    $('#btnconsultas, nav.mobile #btnconsultas').on('click', () => changeView(cargarVistaConsultas));
    $('#btnpacientes, nav.mobile #btnpacientes').on('click', () => changeView(cargarVistaPacientes));
    $('#btnservicios, nav.mobile #btnservicios').on('click', () => changeView(cargarVistaServicios));

    $('#logo').on('click', resetAdmin);
}

function cargarVistaConsultas() {

    let mesc;
    let yearc;

    $.get("vistas/vistasadmin/sidebarconsultas.php", data => {

        $('.sidebar').html(data);
        $.get('vistas/vistasadmin/vistaconsultacalendario.php', data => {

            loadView(data);

            $.get('backend/admin/getFechaHoraActual.php', respuesta => {

                mesc = new Date(respuesta.fechaActual).getMonth();
                yearc = new Date(respuesta.fechaActual).getFullYear();

                let fechasDisponibles = respuesta.fechasDisponibles.map(objeto => objeto.fecha)

                generarCalendarioComun(respuesta.fechaActual, mesc, yearc, fechasDisponibles);

                $('#calendario .arrowright').on('click', () => {

                    if (mesc < 11) mesc++;

                    else if (new Date(respuesta.fechaActual).getFullYear() + 5 > yearc) {

                        mesc = 0;
                        yearc++;
                    }

                    generarCalendarioComun(respuesta.fechaActual, mesc, yearc, fechasDisponibles);
                    addCalendarioListeners();
                });
                $('#calendario .arrowleft').on('click', () => {

                    if (mesc > 0) mesc--;

                    else if (new Date(respuesta.fechaActual).getFullYear() - 5 < yearc) {

                        mesc = 11;
                        yearc--;
                    }
                    generarCalendarioComun(respuesta.fechaActual, mesc, yearc, fechasDisponibles);
                    addCalendarioListeners();
                });
                addCalendarioListeners();
            });

            $('select#verpor').on('change', addCalendarioListeners);
        });

        console.log("Cargando vista de 'Consultas'");

        $('.pacientec').on('click', function () {

            $('.pacientec, .paciente').css({ 'text-decoration': 'none' });
            $(this).css({ 'text-decoration': 'underline' });

            let url = 'vistas/vistasadmin/vistaconsultas.php?idpaciente=' + $(this).attr('id');

            changeView(() => $.get(url, data => {

                loadView(data);

                slideActionBar(true);

                $('.consulta').click(function (e) {

                    e.preventDefault();

                    const fecha = $(this).attr('data-fecha');
                    const hora = $(this).attr('data-hora');

                    $('main').html('');

                    let ventanaconsultapaciente = 'vistas/vistasadmin/vistaconsultapaciente.php?hora=' + hora + '&fecha=' + fecha;

                    $.get(ventanaconsultapaciente, ventana => {

                        $('main').html(ventana);

                        slideActionBar(false);
                    });
                });
            }));
        });

        slideActionBar(true);

        $('#titactionbar').html('Agendar Consulta');
        $('#agregar').off().on('click', () => $('main').fadeOut(300, cargarVistaAgregarConsulta));
    });

    $('#seccionescss').attr('href', 'css/administrador/consultas.css');
    $('nav a, nav.mobile a').css({ 'text-decoration': 'none' });
    $('#btnconsultas, nav.mobile #btnconsultas').css({ 'text-decoration': 'underline' });
}

function addCalendarioListeners() {

    let opcion = $('select#verpor').val();

    $('#calendarbody div#dia.disponible').off();
    $('#calendarbody .semana').off();

    if (opcion === 'dia') {

        $('#calendario').removeClass('porsemana').addClass('pordia');

        $('#calendarbody div#dia.disponible').off().on('click', function () {

            let fecha = new Date($(this).attr('data-year'), Number($(this).attr('data-mes')) + 1, $(this).attr('data-dia'));

            let url = `vistas/vistasadmin/vistaconsultas.php?anio=${fecha.getFullYear()}&mes=${fecha.getMonth()}&dia=${fecha.getDate()}`;

            console.log(url);

            changeView(() => $.get(url, contenido => {

                loadView(contenido);

                $('.consulta').click(function (e) {

                    e.preventDefault();

                    const fecha = $(this).attr('data-fecha');
                    const hora = $(this).attr('data-hora');

                    $('main').empty();

                    let ventanaconsultapaciente = 'vistas/vistasadmin/vistaconsultapaciente.php?hora=' + hora + '&fecha=' + fecha;

                    $.get(ventanaconsultapaciente, ventana => {

                        $('main').html(ventana);

                        slideActionBar(false);
                    });
                });
            }));
        });
    }
    else if (opcion === 'semana') {

        $('#calendario').removeClass('pordia').addClass('porsemana');

        $('#calendarbody .semana.novacia').off().on('click', function () {

            let unDia = $(this).children().find('#dia');

            let unaFecha = `${unDia.attr('data-year')}-${Number(unDia.attr('data-mes')) + 1}-${unDia.attr('data-dia')}`;

            console.log(unaFecha);

            let fechasSemana = getWeekDates(unaFecha);

            fecha1 = fechasSemana[0]; // Primer fecha de la semana
            fecha2 = fechasSemana[fechasSemana.length - 1]; // Última fecha de la semana

            fecha1 = `${fecha1.getFullYear()}-${Number(fecha1.getMonth()) + 1}-${fecha1.getDate()}`;
            fecha2 = `${fecha2.getFullYear()}-${Number(fecha2.getMonth()) + 1}-${fecha2.getDate()}`;

            console.log(fechasSemana);

            let url = `vistas/vistasadmin/vistaconsultas.php?fecha1=${fecha1}&fecha2=${fecha2}`;

            // console.log(url);

            changeView(() => $.get(url, contenido => {

                loadView(contenido);

                $('.consulta').click(function (e) {

                    e.preventDefault();

                    const fecha = $(this).attr('data-fecha');
                    const hora = $(this).attr('data-hora');

                    $('main').empty();

                    let ventanaconsultapaciente = 'vistas/vistasadmin/vistaconsultapaciente.php?hora=' + hora + '&fecha=' + fecha;

                    $.get(ventanaconsultapaciente, ventana => {

                        $('main').html(ventana);

                        slideActionBar(false);
                    });
                });
            }));
        });
    }
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

        $('#contagregarconsulta #fecha').datepicker({ beforeShowDay: date => permitirFechas(date, fechasPermitidas) }).on('change', () => {

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

                    response.horasDisponibles.forEach(elemento => $('#contagregarconsulta #hora').append(`<option value="${elemento}">${elemento}</option>`));
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

                    response.duracionesDisponibles.forEach(elemento => $('#contagregarconsulta #duracion').append(`<option value="${elemento}">${fancyHoras(elemento)}</option>`));
                },
                error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
            });
            else $('#contagregarconsulta #duracion').html('<option selected value="">Seleccione la duración</option>').prop('disabled', true);

            $('#agregarconsulta').prop('disabled', true).removeClass('activo').addClass('inactivo');
        });

        $('#contagregarconsulta #duracion').on('change', () => {

            if ($('#contagregarconsulta #duracion').val() !== '' && $('#contagregarconsulta #asunto').val().length >= 6) $('#agregarconsulta').prop('disabled', false).removeClass('inactivo').addClass('activo');

            else $('#agregarconsulta').prop('disabled', true).removeClass('activo').addClass('inactivo');
        });

        $('#contagregarconsulta #asunto').on('input', () => {

            if ($('#contagregarconsulta #duracion').val() !== '' && $('#contagregarconsulta #asunto').val().length >= 6) $('#agregarconsulta').prop('disabled', false).removeClass('inactivo').addClass('activo');

            else $('#agregarconsulta').prop('disabled', true).removeClass('activo').addClass('inactivo');
        });

        $('#agregarconsulta').on('click', async () => {

            if (await createConfirmPopup('Confirmación', '¿Estás seguro de agendar la consulta?')) {

                $('#agregarservicio').html('<i class="fas fa-spinner fa-pulse"></i>').prop('disabled', true);

                let formdatos = new FormData($('#contagregarconsulta')[0]);

                $.ajax({

                    type: "POST",
                    url: "backend/admin/agregarconsulta.php",
                    data: formdatos,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        $('#agregarservicio').html('Agregar').prop('disabled', false);

                        if (response.error == undefined) createHeaderPopup('Nuevo Aviso', response.exito, cargarVistaConsultas);

                        else createPopup('Nuevo Aviso', response.error);
                    },
                    error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
                });
            }
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
                            $('[id="mdF"]').eq(0).html('<img src="img/iconosvg/lapiz.svg" alt="Modificar" title="Modificar">');
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

        $('#agregarservicio').on('click', async () => {

            if (await createConfirmPopup('Confirmación', '¿Estás seguro de agregar el servicio?')) {

                $('#agregarservicio').html('<i class="fas fa-spinner fa-pulse"></i>').prop('disabled', true);

                formData.append('nombre', $('#nombre').val()); formData.append('descripcion', $('#descripcion').val());

                if ($('#nombre').val().length >= 4 && $('#descripcion').val().length >= 20) $.ajax({

                    type: "POST",
                    url: "backend/admin/agregarservicio.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        $('#agregarservicio').html('Agregar').prop('disabled', false);

                        if (response.error == undefined) createHeaderPopup('Nuevo Aviso', response.exito, cargarVistaServicios);

                        else createPopup('Nuevo Aviso', response.error);
                    },
                    error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
                });
            }
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

                changeView(() => cargarVistaPacienteDetalle($(this).attr('id')));
            });
        });

        slideActionBar(false);
    });

    $('#seccionescss').attr('href', 'css/administrador/pacientes.css');
    $('nav a, nav.mobile a').css({ 'text-decoration': 'none' });
    $('#btnpacientes, nav.mobile #btnpacientes').css({ 'text-decoration': 'underline' });
}

function cargarVistaPacienteDetalle(idp) {

    let url = 'vistas/vistasadmin/vistapacientes.php?idpaciente=' + idp;

    $.get(url, contenido => {
        
        let datospaciente = {

            idpaciente: Number(idp),
            documento: null,
            direccion: null,
            telefono: null,
            email: null,

            enfermedades: [],
            medicacion: []
        }

        loadView(contenido);

        let editar = $('#editarpaciente');
        let guardar = $('#guardarpaciente');
        let contenedor = $('#pcontainer .datos');

        datospaciente.enfermedades = contenedor.find('.enfermedad').map(function() { return $(this).attr('data-enfermedad'); }).get();
        datospaciente.medicacion = contenedor.find('.medicamento').map(function() { return $(this).attr('data-medicamento'); }).get();

        $('#odontologocontainer').on('click', () => {
            
            datospaciente.medicacion.filter(String);
            datospaciente.enfermedades.filter(String);
            console.log(datospaciente);
        });

        editar.on('click', () => {

            if (contenedor.attr('data-editar') == 'edit') {

                contenedor.attr('data-editar', 'noedit');
                guardar.prop('disabled', true);
                editar.html('Editar').css({ 'padding': '.7rem 5rem' });

                $('#pcontainer input.valor').prop('disabled', true);
                $('.enfermedad .eliminarenfermedad, .medicamento .eliminarmedicamento, #agregarenfermedad, #agregarmedicacion').removeClass('visible').addClass('invisible');
            }
            else {

                contenedor.attr('data-editar', 'edit');
                guardar.prop('disabled', false);
                editar.html('Cancelar').css({ 'padding': '.7rem 4rem' });

                $('#pcontainer input.valor').prop('disabled', false);
                $('.enfermedad .eliminarenfermedad, .medicamento .eliminarmedicamento, #agregarenfermedad, #agregarmedicacion').removeClass('invisible').addClass('visible');
            }
        });

        contenedor.find('#agregarmedicacion').on('click', async () => {

            let medicamento = await createInputPopup('Agregar medicación', 'Ingrese el medicamento');

            if(medicamento.length == 0) return;

            datospaciente.medicacion.push(medicamento);

            let medicamentoli = `<li class='medicamento' data-medicamento='${medicamento}'><span>${medicamento}</span><div class='eliminarmedicamento visible' data-medicamento='${medicamento}'><i class='fas fa-trash-alt' style='color: #ffffff;'></i></div></li>`;

            contenedor.find('#medicacion #contagregar').before(medicamentoli);

            contenedor.find('.eliminarmedicamento').off().on('click', function() {

                let medicamento = $(this).attr('data-medicamento');

                contenedor.find(`.medicamento[data-medicamento='${medicamento}']`).remove();

                let index = datospaciente.medicacion.indexOf(medicamento);

                if (index !== -1) datospaciente.medicacion.splice(index, 1);
            });
        });

        contenedor.find('#agregarenfermedad').on('click', async () => {

            let enfermedad = await createInputPopup('Agregar enfermedad', 'Ingrese la enfermedad');

            if(enfermedad.length == 0) return;

            datospaciente.enfermedades.push(enfermedad);

            let enfermedadli = `<li class='enfermedad' data-enfermedad='${enfermedad}'><span>${enfermedad}</span><div class='eliminarenfermedad visible' data-enfermedad='${enfermedad}'><i class='fas fa-trash-alt' style='color: #ffffff;'></i></div></li>`;

            contenedor.find('#enfermedades #contagregar').before(enfermedadli);

            contenedor.find('.eliminarenfermedad').off().on('click', function () {

                let enfermedad = $(this).attr('data-enfermedad');
    
                contenedor.find(`.enfermedad[data-enfermedad='${enfermedad}']`).remove();
    
                let index = datospaciente.enfermedades.indexOf(enfermedad);
    
                if (index !== -1) datospaciente.enfermedades.splice(index, 1);
            });
        });

        contenedor.find('.eliminarenfermedad').off().on('click', function () {

            let enfermedad = $(this).attr('data-enfermedad');

            contenedor.find(`.enfermedad[data-enfermedad='${enfermedad}']`).remove();

            let index = datospaciente.enfermedades.indexOf(enfermedad);

            if (index !== -1) datospaciente.enfermedades.splice(index, 1);
        });

        contenedor.find('.eliminarmedicamento').off().on('click', function() {

            let medicamento = $(this).attr('data-medicamento');

            contenedor.find(`.medicamento[data-medicamento='${medicamento}']`).remove();

            let index = datospaciente.medicacion.indexOf(medicamento);

            if (index !== -1) datospaciente.medicacion.splice(index, 1);
        });

        guardar.on('click', () => {

            contenedor.attr('data-editar', 'noedit');
            guardar.prop('disabled', true);
            editar.html('Editar').css({ 'padding': '.7rem 5rem' });

            contenedor.find('input.valor').prop('disabled', true);
            contenedor.find('.enfermedad .eliminarenfermedad, .medicamento .eliminarmedicamento, #agregarenfermedad, #agregarmedicacion').removeClass('visible').addClass('invisible');

            datospaciente.documento = contenedor.find('#documento > input').val();
            datospaciente.direccion = contenedor.find('#direccion > input').val();
            datospaciente.telefono = contenedor.find('#telefono > input').val();
            datospaciente.email = contenedor.find('#email > input').val();

            $.ajax({

                type: "POST",
                url: "backend/admin/editarpaciente.php",
                data: JSON.stringify(datospaciente),
                processData: false,
                contentType: false,
                success: function (response) {

                    if (response.error == undefined) { 
                        
                        createHeaderPopup('Nuevo Aviso', response.exito, () => changeView(() => cargarVistaPacienteDetalle(idp)));
                    }

                    else createPopup('Nuevo Aviso', response.error)
                },
                error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
            });
        });
    });
}

function cargarVistaServicios() {

    $.get("vistas/vistasadmin/vistaservicios.php", data => {

        loadView(data);
        console.log("Cargando vista de 'Servicios'");

        $('.sidebar').empty().fadeIn(200);

        $('.servicio').on('click', function () {

            let id = ($(this).attr('id'));

            $('main').load(`vistas/vistasadmin/vistaservicios.php?numservicio=` + $(this).attr('id'), function () {

                let temp = {

                    titulo: "",
                    desc: ""
                };

                $('[id="mdC"]').eq(0).on('click', function () {

                    if ($('.contTitulon').attr('disabled')) {

                        temp['titulo'] = $('.contTitulon').val();
                        $('.contTitulon').attr('disabled', false);
                    } 
                    else {

                        if (temp['titulo'] == $('.contTitulon').val()) $('.contTitulon').attr('disabled', true);
                        
                        else {

                            let formData = new FormData();
                            formData.append('titulo', $('.contTitulon').val());
                            formData.append('id', id);

                            $.ajax({

                                type: "POST",
                                url: "backend/admin/actualizarDataService.php",
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function (response) {

                                    if (response.error == undefined) { createPopup('Nuevo Aviso', response); $('.contTitulon').attr('disabled', true); }

                                    else createPopup('Nuevo Aviso', response);
                                },
                                error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
                            });
                        }
                    }
                });
                $('[id="mdC"]').eq(1).on('click', function () {

                    if ($('#descripcion').attr('readonly')) {

                        temp['desc'] = $('#descripcion').val();
                        $('#descripcion').attr('readonly', false);
                    } 
                    else {

                        if (temp['desc'] == $('#descripcion').val()) $('#descripcion').attr('readonly', true);
                        
                        else {

                            let formData = new FormData();
                            formData.append('descripcion', $('#descripcion').val());
                            formData.append('id', id);

                            $.ajax({

                                type: "POST",
                                url: "backend/admin/actualizarDataService.php",
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function (response) {

                                    if (response.error == undefined) { createPopup('Nuevo Aviso', response); $('#descripcion').attr('readonly', true); }

                                    else console.log(response.error);
                                },
                                error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
                            });
                        }
                    }
                });
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

    $('#titactionbar').html('Agregar Servicio');
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

    changeView(() => loadView('<div class="w-100 h-100 d-flex justify-content-center align-items-center"><h1 class="titinformativo">Bienvenido al administrador</h1></div>'));

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

let fechasPermitidas = [];

function changeView(vista) {

    $('main').fadeOut(200, vista);
    $('sidebar').fadeOut(200);
}

function loadView(contenido) {

    $('main').empty().html(contenido).fadeIn(200);
}