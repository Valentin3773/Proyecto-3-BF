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

    resetAdmin();

    switch ($('body').data('vista')) {

        case 'consultas': changeView(cargarVistaConsultaCalendario); break;

        case 'agendarconsulta': changeView(cargarVistaAgregarConsulta); break;

        case 'pacientes': changeView(cargarVistaPacientes); break;

        case 'servicios': changeView(cargarVistaServicios); break;

        case 'agregarservicio': changeView(cargarVistaAgregarServicio); break;
    }
});

let cargando = false;

function addAdminListeners() {

    $('#btnconsultas, nav.mobile #btnconsultas').on('click', () => {

        if (!cargando) changeView(cargarVistaConsultaCalendario)
    });
    $('#btnpacientes, nav.mobile #btnpacientes').on('click', () => {

        if (!cargando) changeView(cargarVistaPacientes)
    });
    $('#btnservicios, nav.mobile #btnservicios').on('click', () => {

        if (!cargando) changeView(cargarVistaServicios)
    });

    $('#logo, #logomobile').on('click', () => {

        if (!cargando) changePage('index.php');
    });

    $('header.mobile #btnopciones').on('click', desplegarSidebarMobile);
}

function cargarVistaConsultaCalendario() {

    history.pushState(null, 'Consultas', '/administrador/consultas');

    let mesc;
    let yearc;

    $.get("vistas/vistasadmin/sidebarconsultas.php", data => {

        loadSidebar(data);

        $('.pacientec').on('click', function () {

            if (!cargando) changeView(() => cargarVistaConsultasPaciente($(this).attr('id')));
        });
    });

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

    slideActionBar(true);

    $('#titactionbar').html('Agendar Consulta');
    $('#agregar').off().on('click', () => {

        if (!cargando) $('main').fadeOut(300, cargarVistaAgregarConsulta)
    });

    $('#seccionescss').attr('href', 'css/administrador/consultas.css');
    $('nav a, nav.mobile a').css({ 'text-decoration': 'none' });
    $('#btnconsultas, nav.mobile #btnconsultas').css({ 'text-decoration': 'underline' });

    manageMobileAddButton(true, 'Agendar<br>Consulta', () => changeView(cargarVistaAgregarConsulta));
}

function cargarVistaConsultasPaciente(idp) {

    history.pushState(null, 'Consultas', '/administrador/consultas');

    $('.pacientec, .paciente').css({ 'text-decoration': 'none' });
    $(this).css({ 'text-decoration': 'underline' });

    $.get("vistas/vistasadmin/sidebarconsultas.php", data => {

        loadSidebar(data)

        $('.pacientec').on('click', function () {

            if (!cargando) changeView(() => cargarVistaConsultasPaciente($(this).attr('id')));
        });
    });

    let url = 'vistas/vistasadmin/vistaconsultas.php?idpaciente=' + idp;

    $.get(url, data => {

        loadView(data);

        $('.consulta').on('click', function (e) {

            const fecha = $(this).attr('data-fecha');
            const hora = $(this).attr('data-hora');

            changeView(() => cargarVistaConsultaDetalle(fecha, hora));
        });
    });

    manageMobileAddButton(true, 'Agendar<br>Consulta', () => changeView(cargarVistaAgregarConsulta));
}

function cargarVistaConsultaDetalle(fecha, hora) {

    history.pushState(null, 'Consultas', '/administrador/consultas');

    let ventanaconsultapaciente = 'vistas/vistasadmin/vistaconsultapaciente.php?hora=' + hora + '&fecha=' + fecha;

    $.get(ventanaconsultapaciente, ventana => {

        loadView(ventana);

        loadSidebar('');

        slideActionBar(false);
    });

    $('#seccionescss').attr('href', 'css/administrador/consultas.css');
    $('nav a, nav.mobile a').css({ 'text-decoration': 'none' });
    $('#btnconsultas, nav.mobile #btnconsultas').css({ 'text-decoration': 'underline' });

    manageMobileAddButton(false, 'Agendar<br>Consulta');
}

function addCalendarioListeners() {

    let opcion = $('select#verpor').val();

    $('#calendarbody div#dia.disponible').off();
    $('#calendarbody .semana').off();

    if (opcion === 'dia') {

        $('#calendario').removeClass('porsemana').addClass('pordia');

        $('#calendarbody div#dia.disponible').off().on('click', function () {

            let fecha = new Date($(this).attr('data-year'), $(this).attr('data-mes'), $(this).attr('data-dia'));

            let url = `vistas/vistasadmin/vistaconsultas.php?anio=${fecha.getFullYear()}&mes=${Number(fecha.getMonth()) + 1}&dia=${fecha.getDate()}`;

            changeView(() => $.get(url, contenido => {

                $.get("vistas/vistasadmin/sidebarconsultas.php", data => {

                    loadSidebar(data)

                    $('.pacientec').on('click', function () {

                        if (!cargando) changeView(() => cargarVistaConsultasPaciente($(this).attr('id')));
                    });
                });

                loadView(contenido);

                $('.consulta').on('click', function (e) {

                    const fecha = $(this).attr('data-fecha');
                    const hora = $(this).attr('data-hora');

                    changeView(() => cargarVistaConsultaDetalle(fecha, hora));
                });
            }));
        });
    }
    else if (opcion === 'semana') {

        $('#calendario').removeClass('pordia').addClass('porsemana');

        $('#calendarbody .semana.novacia').off().on('click', function () {

            let unDia = $(this).children().find('#dia');

            let unaFecha = `${unDia.attr('data-year')}-${Number(unDia.attr('data-mes')) + 1}-${unDia.attr('data-dia')}`;

            let fechasSemana = getWeekDates(unaFecha);

            fecha1 = fechasSemana[0]; // Primer fecha de la semana
            fecha2 = fechasSemana[fechasSemana.length - 1]; // Última fecha de la semana

            fecha1 = `${fecha1.getFullYear()}-${Number(fecha1.getMonth()) + 1}-${fecha1.getDate()}`;
            fecha2 = `${fecha2.getFullYear()}-${Number(fecha2.getMonth()) + 1}-${fecha2.getDate()}`;

            let url = `vistas/vistasadmin/vistaconsultas.php?fecha1=${fecha1}&fecha2=${fecha2}`;

            changeView(() => $.get(url, contenido => {

                $.get("vistas/vistasadmin/sidebarconsultas.php", data => {

                    loadSidebar(data)

                    $('.pacientec').on('click', function () {

                        if (!cargando) changeView(() => cargarVistaConsultasPaciente($(this).attr('id')));
                    });
                });

                loadView(contenido);

                $('.consulta').on('click', function (e) {

                    const fecha = $(this).attr('data-fecha');
                    const hora = $(this).attr('data-hora');

                    changeView(() => cargarVistaConsultaDetalle(fecha, hora));
                });
            }));
        });
    }
}

function cargarVistaAgregarConsulta() {

    history.pushState(null, 'Agendar Consulta', '/administrador/consultas/agendar');

    loadSidebar('');

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

            if ($('#contagregarconsulta #fecha').val() !== '') {

                cargando = true;

                $.ajax({

                    type: "POST",
                    url: "backend/admin/horamanagerad.php",
                    data: datos,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        cargando = false;

                        $('#contagregarconsulta #hora').html('<option selected value="">Seleccione una hora</option>').prop('disabled', false);

                        response.horasDisponibles.forEach(elemento => $('#contagregarconsulta #hora').append(`<option value="${elemento}">${elemento}</option>`));
                    },
                    error: (jqXHR, estado, outputError) => {

                        console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR);
                        cargando = false;
                    }
                });
            }

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

            if ($('#contagregarconsulta #hora').val() !== '') {

                cargando = true;

                $.ajax({

                    type: "POST",
                    url: "backend/admin/duracionmanager.php",
                    data: datos,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        cargando = false

                        $('#contagregarconsulta #duracion').html('<option selected value="">Seleccione la duración</option>').prop('disabled', false);

                        response.duracionesDisponibles.forEach(elemento => $('#contagregarconsulta #duracion').append(`<option value="${elemento}">${fancyHoras(elemento)}</option>`));
                    },
                    error: (jqXHR, estado, outputError) => {

                        console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR);
                        cargando = false;
                    }
                });
            }
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

                $('#agregarconsulta').html('<i class="fas fa-spinner fa-pulse"></i>').prop('disabled', true).removeClass('activo').addClass('inactivo');

                let formdatos = new FormData($('#contagregarconsulta')[0]);

                cargando = true;

                $.ajax({

                    type: "POST",
                    url: "backend/admin/agregarconsulta.php",
                    data: formdatos,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        cargando = false;

                        $('#agregarconsulta').html('Agregar').prop('disabled', false).removeClass('inactivo').addClass('activo');

                        if (response.error == undefined) createHeaderPopup('Nuevo Aviso', response.exito, cargarVistaConsultaCalendario);

                        else createPopup('Nuevo Aviso', response.error);
                    },
                    error: (jqXHR, estado, outputError) => {

                        console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR);
                        cargando = false;
                    }
                });
            }
        });
    });

    $('#seccionescss').attr('href', 'css/administrador/consultas.css');
    $('nav a, nav.mobile a').css({ 'text-decoration': 'none' });
    $('#btnconsultas, nav.mobile #btnconsultas').css({ 'text-decoration': 'underline' });

    manageMobileAddButton(false, 'Agendar<br>Consulta');
}

function cargarVistaAgregarServicio() {

    history.pushState(null, 'Agregar Servicio', '/administrador/servicios/agregar');

    loadSidebar('');

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

        $('#agregarservicio').on('click', async evt => {

            if (await createConfirmPopup('Confirmación', '¿Estás seguro de agregar el servicio?')) {

                $('#agregarservicio').html('<i class="fas fa-spinner fa-pulse"></i>').prop('disabled', true).removeClass('activo').addClass('inactivo');

                formData.append('nombre', $('#nombre').val()); formData.append('descripcion', $('#descripcion').val());

                cargando = true;

                $.ajax({

                    type: "POST",
                    url: "backend/admin/agregarservicio.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        cargando = false;

                        $('#agregarservicio').html('Agregar').prop('disabled', false).removeClass('inactivo').addClass('activo');

                        if (response.error == undefined) createHeaderPopup('Nuevo Aviso', response.exito, cargarVistaServicios);

                        else createPopup('Nuevo Aviso', response.error);
                    },
                    error: (jqXHR, estado, outputError) => {

                        console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR);
                        cargando = false;
                    }
                });
            }
        });
    });

    $('#titactionbar').html('Agregar Servicio');
    $('#agregar').off().on('click', () => changeView(cargarVistaAgregarServicio));

    $('#seccionescss').attr('href', 'css/administrador/servicios.css');
    $('nav a, nav.mobile a').css({ 'text-decoration': 'none' });
    $('#btnservicios, nav.mobile #btnservicios').css({ 'text-decoration': 'underline' });

    manageMobileAddButton(false, 'Agregar<br>Servicio');
}

function cargarVistaPacientes() {

    history.pushState(null, 'Pacientes', '/administrador/pacientes');

    $.get("vistas/vistasadmin/sidebarpacientes.php", data => {

        console.log("Cargando vista de 'Pacientes'");

        loadSidebar(data);

        $.get('vistas/vistasadmin/vistapacientes.php', data => {

            loadView(data);

            $('.paciente, .p-contenedor').off().on('click', function () {

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

    manageMobileAddButton(false, '');
}

function cargarVistaPacienteDetalle(idp) {

    let url = 'vistas/vistasadmin/vistapacientes.php?idpaciente=' + idp;

    $.get(url, contenido => {

        let datospaciente = {

            idpaciente: Number(idp),
            nombre: null,
            apellido: null,
            documento: null,
            direccion: null,
            telefono: null,
            email: null,

            enfermedades: [],
            medicacion: []
        }

        let fotopaciente = null;

        loadView(contenido);

        $.get('vistas/vistasadmin/sidebarpacientes.php', contenido => {

            loadSidebar(contenido);

            $('.paciente, .p-contenedor').off().on('click', function () {

                changeView(() => cargarVistaPacienteDetalle($(this).attr('id')));
                $('main').empty();

                $('.paciente').css({ 'text-decoration': 'none' });
                $(this).css({ 'text-decoration': 'underline' });
                $('.pacientescontainer #' + $(this).attr('id')).css({ 'text-decoration': 'underline' });

                changeView(() => cargarVistaPacienteDetalle($(this).attr('id')));
            });
        });

        let editar = $('#editarpaciente');
        let guardar = $('#guardarpaciente');
        let contenedor = $('#pcontainer .datos');

        datospaciente.enfermedades = contenedor.find('.enfermedad').map(function () { return $(this).attr('data-enfermedad'); }).get();
        datospaciente.medicacion = contenedor.find('.medicamento').map(function () { return $(this).attr('data-medicamento'); }).get();

        editar.on('click', () => {

            if (contenedor.attr('data-editar') == 'edit') changeView(() => cargarVistaPacienteDetalle(idp));

            else {

                contenedor.attr('data-editar', 'edit');
                guardar.prop('disabled', false);
                // editar.html('Cancelar').css({ 'padding': '.7rem 4rem' });

                $('#pcontainer input.valor').prop('disabled', false);
                $('.enfermedad .eliminarenfermedad, .medicamento .eliminarmedicamento, #agregarenfermedad, #agregarmedicacion').removeClass('invisible').addClass('visible');
                $('#mdF').attr('desactivado', 'false');
            }
        });

        contenedor.find('#agregarmedicacion').on('click', async () => {

            let medicamento = await createInputPopup('Agregar medicación', 'Ingrese el medicamento');

            if (medicamento.length == 0) return;

            datospaciente.medicacion.push(medicamento);

            contenedor.find('#medicacion .nomedicacion').remove();

            let medicamentoli = `<li class='medicamento' data-medicamento='${medicamento}'><span>${medicamento}</span><div class='eliminarmedicamento visible' data-medicamento='${medicamento}'><i class='fas fa-trash-alt' style='color: #ffffff;'></i></div></li>`;

            contenedor.find('#medicacion #contagregar').before(medicamentoli);

            contenedor.find('.eliminarmedicamento').off().on('click', function () {

                let medicamento = $(this).attr('data-medicamento');

                contenedor.find(`.medicamento[data-medicamento='${medicamento}']`).remove();

                let index = datospaciente.medicacion.indexOf(medicamento);

                if (index !== -1) datospaciente.medicacion.splice(index, 1);

                if (datospaciente.medicacion.length === 0) contenedor.find('#medicacion > ul.valor > #contagregar').before(`<span class='medicamento nomedicacion d-flex justify-content-center align-items-center'>No hay medicación</span>`);
            });
        });

        contenedor.find('#agregarenfermedad').on('click', async () => {

            let enfermedad = await createInputPopup('Agregar enfermedad', 'Ingrese la enfermedad');

            if (enfermedad.length == 0) return;

            datospaciente.enfermedades.push(enfermedad);

            contenedor.find('#enfermedades .noenfermedades').remove();

            let enfermedadli = `<li class='enfermedad' data-enfermedad='${enfermedad}'><span>${enfermedad}</span><div class='eliminarenfermedad visible' data-enfermedad='${enfermedad}'><i class='fas fa-trash-alt' style='color: #ffffff;'></i></div></li>`;

            contenedor.find('#enfermedades #contagregar').before(enfermedadli);

            contenedor.find('.eliminarenfermedad').off().on('click', function () {

                let enfermedad = $(this).attr('data-enfermedad');

                contenedor.find(`.enfermedad[data-enfermedad='${enfermedad}']`).remove();

                let index = datospaciente.enfermedades.indexOf(enfermedad);

                if (index !== -1) datospaciente.enfermedades.splice(index, 1);

                if (datospaciente.enfermedades.length === 0) contenedor.find('#enfermedades > ul.valor > #contagregar').before(`<span class='enfermedad noenfermedades d-flex justify-content-center align-items-center'>No hay enfermedades</span>`);
            });
        });

        contenedor.find('.eliminarenfermedad').off().on('click', function () {

            let enfermedad = $(this).attr('data-enfermedad');

            contenedor.find(`.enfermedad[data-enfermedad='${enfermedad}']`).remove();

            let index = datospaciente.enfermedades.indexOf(enfermedad);

            if (index !== -1) datospaciente.enfermedades.splice(index, 1);

            if (datospaciente.enfermedades.length === 0) contenedor.find('#enfermedades > ul.valor > #contagregar').before(`<span class='enfermedad noenfermedades d-flex justify-content-center align-items-center'>No hay enfermedades</span>`);
        });

        contenedor.find('.eliminarmedicamento').off().on('click', function () {

            let medicamento = $(this).attr('data-medicamento');

            contenedor.find(`.medicamento[data-medicamento='${medicamento}']`).remove();

            let index = datospaciente.medicacion.indexOf(medicamento);

            if (index !== -1) datospaciente.medicacion.splice(index, 1);

            if (datospaciente.medicacion.length === 0) contenedor.find('#medicacion > ul.valor > #contagregar').before(`<span class='medicamento nomedicacion d-flex justify-content-center align-items-center'>No hay medicación</span>`);
        });

        $('#pcontainer #mdF').off().on('click', function () {

            if (contenedor.attr('data-editar') === 'edit' && $(this).attr('desactivado') === 'false') $('#pcontainer #inFile').click();
        });

        $('#pcontainer #inFile').off().on('change', event => {

            let file = event.target.files[0];

            try {

                if (file) {

                    let reader = new FileReader();
                    reader.onload = e => $('#pcontainer #fotoperfil > img').prop('src', e.target.result);

                    reader.readAsDataURL(file);

                    fotopaciente = new FormData();
                    fotopaciente.append('file', file);
                    fotopaciente.append('idpaciente', idp);
                }
            }
            catch (error) {

                console.error(error);
            }
        });

        guardar.on('click', async () => {

            if (await createConfirmPopup('Confirmación', '¿Estás seguro de modificar los datos del paciente?', ['No', 'Sí'])) {

                guardar.prop('disabled', true).html('<i class="fas fa-spinner fa-pulse"></i>');

                contenedor.attr('data-editar', 'noedit');
                guardar.prop('disabled', true);
                // editar.html('Editar').css({ 'padding': '.7rem 5rem' });

                contenedor.find('input.valor').prop('disabled', true);
                contenedor.find('.enfermedad .eliminarenfermedad, .medicamento .eliminarmedicamento, #agregarenfermedad, #agregarmedicacion').removeClass('visible').addClass('invisible');

                datospaciente.nombre = contenedor.find('#nombre > input').val();
                datospaciente.apellido = contenedor.find('#apellido > input').val();
                datospaciente.documento = contenedor.find('#documento > input').val();
                datospaciente.direccion = contenedor.find('#direccion > input').val();
                datospaciente.telefono = contenedor.find('#telefono > input').val();
                datospaciente.email = contenedor.find('#email > input').val();

                datospaciente.medicacion.filter(String);
                datospaciente.enfermedades.filter(String);

                cargando = true;

                $.ajax({

                    type: "POST",
                    url: "backend/admin/editarpaciente.php",
                    data: JSON.stringify(datospaciente),
                    processData: false,
                    contentType: false,
                    success: function (responso) {

                        if (responso.error == undefined) {

                            if (fotopaciente != null) {

                                cargando = true;

                                $.ajax({

                                    type: "POST",
                                    url: "backend/admin/subirimgpaciente.php",
                                    data: fotopaciente,
                                    processData: false,
                                    contentType: false,
                                    success: function (response) {

                                        cargando = false;

                                        if (response[0]) createHeaderPopup('Nuevo Aviso', responso.exito, () => cargarVistaPacienteDetalle(idp), 10);

                                        else createPopup('Nuevo Aviso', responso.error, 10);
                                    },
                                    error: (jqXHR, estado, outputError) => {

                                        console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR);
                                        cargando = false;
                                    }
                                });
                            }
                            else {

                                cargando = false;
                                createHeaderPopup('Nuevo Aviso', responso.exito, () => cargarVistaPacienteDetalle(idp));
                            }
                        }
                        else {

                            createPopup('Nuevo Aviso', responso.error);
                            cargando = false;
                        }
                        guardar.prop('disabled', false).html('Guardar');
                    },
                    error: (jqXHR, estado, outputError) => {

                        console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR);
                        cargando = false;
                    }
                });
            }
        });
    });

    $('#seccionescss').attr('href', 'css/administrador/pacientes.css');
    $('nav a, nav.mobile a').css({ 'text-decoration': 'none' });
    $('#btnpacientes, nav.mobile #btnpacientes').css({ 'text-decoration': 'underline' });

    manageMobileAddButton(false, '');
}

function cargarVistaServicios() {

    history.pushState(null, 'Servicios', '/administrador/servicios');

    $.get("vistas/vistasadmin/vistaservicios.php", data => {

        loadView(data);
        console.log("Cargando vista de 'Servicios'");

        loadSidebar('');

        $('.servicio').on('click', function () {

            let numservicio = $(this).attr('id');

            changeView(() => cargarVistaServicioDetalle(numservicio));
        });
    });

    slideActionBar(true);

    $('#titactionbar').html('Agregar Servicio');
    $('#agregar').off().on('click', () => changeView(cargarVistaAgregarServicio));

    $('#seccionescss').attr('href', 'css/administrador/servicios.css');
    $('nav a, nav.mobile a').css({ 'text-decoration': 'none' });
    $('#btnservicios, nav.mobile #btnservicios').css({ 'text-decoration': 'underline' });

    manageMobileAddButton(true, 'Agregar<br>Servicio', () => changeView(cargarVistaAgregarServicio));
}

function cargarVistaServicioDetalle(numservicio) {

    history.pushState(null, 'Servicios', '/administrador/servicios');

    let id = numservicio;

    $.get(`vistas/vistasadmin/vistaservicios.php?numservicio=${id}`, contenido => {

        console.log

        loadSidebar('');
        loadView(contenido);

        let temp = {

            titulo: "",
            desc: ""
        };

        $('#eliminarservicio').on('click', async function () {

            if (await createConfirmPopup('Confirmación', '¿Realmente desea eliminar este servicio?')) {

                $(this).html('<i class="fas fa-spinner fa-pulse" style="color: #ffffff;"></i>').prop('disabled', true).css({ 'opacity': '.5' });

                cargando = true;

                $.ajax({

                    type: "POST",
                    url: "backend/admin/eliminarservicio.php",
                    data: JSON.stringify({ numero: numservicio }),
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        cargando = false;

                        if (response.error == undefined) createHeaderPopup('Nuevo Aviso', response.exito, () => changeView(cargarVistaServicios));

                        else createPopup('Nuevo Aviso', response.error);

                        $(this).html('<i class="fas fa-trash-alt" style="color: #ffffff;"></i>').prop('disabled', false).css({ 'opacity': '1' });
                    },
                    error: (jqXHR, estado, outputError) => {

                        console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR);
                        cargando = false;
                    }
                });
            }
        });

        $('[id="mdC"]').eq(0).on('click', function () {

            if ($('.contTitulon').attr('disabled')) {

                $('[id="mdC"]').eq(0).find('img').attr('src', 'img/iconosvg/Guardar.svg');
                temp['titulo'] = $('.contTitulon').val();
                $('.contTitulon').prop('disabled', false).focus();
            }
            else {

                if (temp['titulo'] == $('.contTitulon').val()) {

                    $('.contTitulon').attr('disabled', true)
                    $('[id="mdC"]').eq(0).find('img').attr('src', 'img/iconosvg/lapiz.svg');
                }
                else {

                    let formData = new FormData();
                    formData.append('titulo', $('.contTitulon').val());
                    formData.append('id', id);

                    cargando = true;

                    $.ajax({

                        type: "POST",
                        url: "backend/admin/actualizarDataService.php",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {

                            cargando = false;

                            if (response.error == undefined) { createPopup('Nuevo Aviso', response.exito); $('.contTitulon').attr('disabled', true); }

                            else {

                                createPopup('Nuevo Aviso', response.error);
                                cargarVistaServicioDetalle(numservicio);
                            }
                        },
                        error: (jqXHR, estado, outputError) => {

                            console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR);
                            cargando = false;
                        }
                    });
                    $('[id="mdC"]').eq(0).find('img').attr('src', 'img/iconosvg/lapiz.svg');
                }
            }
        });
        $('[id="mdC"]').eq(1).on('click', function () {

            if ($('#descripcion').attr('readonly')) {

                $('[id="mdC"]').eq(1).find('img').attr('src', 'img/iconosvg/Guardar.svg');
                temp['desc'] = $('#descripcion').val();
                $('#descripcion').prop('readonly', false).focus();
            }
            else {

                if (temp['desc'] == $('#descripcion').val()) {

                    $('#descripcion').prop('readonly', false).focus();
                    $('[id="mdC"]').eq(1).find('img').attr('src', 'img/iconosvg/lapiz.svg');
                }
                else {

                    let formData = new FormData();
                    formData.append('descripcion', $('#descripcion').val());
                    formData.append('id', id);

                    cargando = true;

                    $.ajax({

                        type: "POST",
                        url: "backend/admin/actualizarDataService.php",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {

                            cargando = false;

                            if (response.error == undefined) { createPopup('Nuevo Aviso', response.exito); $('#descripcion').attr('readonly', true); }

                            else {

                                cargarVistaServicioDetalle(numservicio);
                                createPopup('Nuevo Aviso', response.error);
                            }
                        },
                        error: (jqXHR, estado, outputError) => {

                            console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR);
                            cargando = false;
                        }
                    });
                    $('[id="mdC"]').eq(1).find('img').attr('src', 'img/iconosvg/lapiz.svg');
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
                        enviarIMGServicio(formData, 0, numservicio);;
                    }
                }
                catch (error) {

                    console.error(error);
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
                        enviarIMGServicio(formData, 1, numservicio);
                    }
                }
                catch (error) {

                    console.log(error);
                }
            });
        })
    });

    $('#titactionbar').html('Agregar Servicio');
    $('#agregar').off().on('click', () => changeView(cargarVistaAgregarServicio));

    $('#seccionescss').attr('href', 'css/administrador/servicios.css');
    $('nav a, nav.mobile a').css({ 'text-decoration': 'none' });
    $('#btnservicios, nav.mobile #btnservicios').css({ 'text-decoration': 'underline' });

    manageMobileAddButton(true, 'Agregar<br>Servicio', () => changeView(cargarVistaAgregarServicio));
}

function enviarIMGServicio($data, $tipo, numservicio) {

    if ($tipo == 0) {

        cargando = true;

        $.ajax({

            type: "POST",
            url: "backend/admin/actualizarIconoService.php",
            data: $data,
            processData: false,
            contentType: false,
            success: function (response) {

                cargando = false;

                if (response.error == undefined) createPopup('Nuevo Aviso', response.enviar); //window.location.reload();

                else createPopup('Nuevo Aviso', response.error);

                cargarVistaServicioDetalle(numservicio);
            },
            error: (jqXHR, estado, outputError) => {

                console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR);
                cargando = false;
            }
        });
    }
    else if ($tipo == 1) {

        cargando = true;

        $.ajax({
            type: "POST",
            url: "backend/admin/actualizarImagenService.php",
            data: $data,
            processData: false,
            contentType: false,
            success: function (response) {

                cargando = false;

                if (response.error == undefined) createPopup('Nuevo Aviso', response.enviar); // window.location.reload();

                else createPopup('Nuevo Aviso',response.error);
            },
            error: (jqXHR, estado, outputError) => {

                console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR);
                cargando = false;
            }
        });
    }
    else alert('Que mira bobo :3');
}

function resetAdmin() {

    history.pushState(null, 'Administrador', '/administrador');

    changeView(() => {

        loadSidebar('');
        loadView('<div class="w-100 h-100 d-flex justify-content-center align-items-center"><h1 class="titinformativo">Bienvenido al administrador</h1></div>');
    });

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

function loadSidebar(contenido) {

    console.log('Cargando Sidebar');

    const sidebar = $('.sidebar');
    const sidebarmobile = $('header.mobile #sidebarmobile');
    const btnopciones = $('header.mobile #btnopciones');

    sidebar.empty().html(contenido).css({ 'opacity': 1 })[0].scrollTo({ top: 0, behavior: 'smooth' });
    sidebarmobile.empty().html(contenido).css({ 'opacity': 1 })[0].scrollTo({ top: 0, behavior: 'smooth' });

    if (contenido === '') btnopciones.removeClass('visible').addClass('invisible');

    else btnopciones.removeClass('invisible').addClass('visible');

    if (sidebarmobile.hasClass('visible')) desplegarSidebarMobile();
}

function changeView(vista) {

    $('main').fadeOut(200, vista);
    $('.sidebar').css({ 'opacity': 0 });
}

function loadView(contenido) {

    $('main').empty().html(contenido).fadeIn(200)[0].scrollTo({ top: 0, behavior: 'smooth' });
}

function manageMobileAddButton(active, texto, onclick = () => { }) {

    let mobileButton = $('#agregarmobile');

    if (active) mobileButton.removeClass('inactivo').addClass('activo');

    else mobileButton.removeClass('activo').addClass('inactivo');

    mobileButton.html(texto).off().on('click', onclick);
}

function desplegarSidebarMobile() {

    const sidebarmobile = $('header.mobile #sidebarmobile');
    const mobilepacientescontainer = $('header.mobile #sidebarmobile > .pacientescontainer');

    if (sidebarmobile.hasClass('invisible') && !sidebarmobile.is(':empty')) {

        sidebarmobile.removeClass('invisible').addClass('visible');
        mobilepacientescontainer.removeClass('invisible').addClass('visible');
    }

    else {

        sidebarmobile.removeClass('visible').addClass('invisible');
        mobilepacientescontainer.removeClass('visible').addClass('invisible');
    }
}