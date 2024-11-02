$(() => {

    $('#headermobile #logocontainer > img').on('click', () => changePage('index.php'));
    $('#miperfil, #sidebarmobile #miperfil').on('click', () => changeView(cargarVistaPerfil));
    $('#misconsultas, #sidebarmobile #misconsultas').on('click', () => changeView(cargarVistaConsultas));
    $('#horarios, #sidebarmobile #horarios').on('click', () => changeView(cargarVistaHorarios));
    $('#inactividades, #sidebarmobile #inactividades').on('click', () => changeView(cargarVistaInactividades));
    $('#seguridad, #sidebarmobile #seguridad').on('click', () => changeView(cargarVistaSeguridad));
    $('#cerrarsesion, #sidebarmobile #cerrarsesion').on('click', async () => {

        if (await createConfirmPopup('Confirmación', '¿Estás seguro de cerrar sesión?', ['No', 'Sí'])) changeView(() => window.location.href = 'login.php?estado=3');

    }).on('mouseenter', () => $('#cerrarsesion').html('<i class="fas fa-sign-out-alt"></i>')).on('mouseleave', () => $('#cerrarsesion').html('<i class="fas fa-sign-out-alt"></i>&nbsp;Cerrar Sesión'));

    $('#btnopciones').on('click', desplegarSidebarMobile);

    $('#btnvolver').on('click', () => changePage('index.php'));

    switch ($('main').data('vista')) {

        case 1: changeView(cargarVistaPerfil); break;

        case 2:changeView(cargarVistaConsultas); break;

        case 3: changeView(cargarVistaHorarios); break;

        case 4: changeView(cargarVistaInactividades); break;

        case 5: changeView(cargarVistaSeguridad); break;

        case 6: changeView(cargarVistaAgregarHorario); break;

        case 7: changeView(cargarVistaAgregarInactividad); break;
    }

    history.replaceState({ path: 'perfil.php' }, '', 'perfil.php');

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

var Datos = {

    tipo: "",
    dato: "",
    namedata: "",
    old: "",
    inputID: ""
};

function cargarVistaPerfil() {

    history.pushState(null, 'Perfil', '/perfil');

    $('main').prop('data-vista', 0);

    console.log("Cargando vista de 'Mi Perfil'");

    $.get('vistas/vistasperfil/vistaperfil.php', contenido => {

        loadView(contenido);
        integrarEventos();
    });

    if ($('#sidebarmobile').hasClass('visible')) desplegarSidebarMobile();

    $('#sidebar #btnsuperiores button, #sidebarmobile #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#miperfil, #sidebarmobile #miperfil').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/miperfil.css');
}

function integrarBoton($datos, $id) {

    // Aca sucede la magia
    if ($($id).find('img').attr('src', 'img/iconosvg/Guardar.svg')) {

        quitarEventos();

        $($id).on('click', function () {

            if ($($datos['inputID']).val() != $datos['old']) {

                integrarEventos();
                $datos['tipo'] = $("#\\$\\$\\$").attr('data-type');
                $datos['dato'] = $($datos['inputID']).val();
                $($id).find('img').attr('src', 'img/iconosvg/lapiz.svg');
                $($datos['inputID']).prop('disabled', true);
                funcionguardarCambios($datos);
                console.log("Guardar");
            }
            else {

                $($datos['inputID']).val($datos['old']);
                $($id).find('img').attr('src', 'img/iconosvg/lapiz.svg');
                $($datos['inputID']).prop('disabled', true);
                integrarEventos();
                console.log("Cancelar" + $datos['old']);
            }
            // window.location.replace("index.php");
        });
    }
    else $($id).find('img').attr('src', 'img/iconosvg/Guardar.svg');
}

function integrarEventos() {

    // Listeners con datos importantes
    $('#mdF').on('click', () => $('#inFile').click());

    $('#inFile').off().on('change', function (event) {

        let file = event.target.files[0];
        try {

            if (file) {

                // Mostrar la imagen
                let reader = new FileReader();
                reader.onload = e => $('#fotoperfil').prop('src', e.target.result);

                reader.readAsDataURL(file);

                // Parseo de la imagen
                let formData = new FormData();
                formData.append('file', file);

                $.ajax({

                    type: "POST",
                    url: "./backend/perfil/subirIMG.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        if (response.error == undefined) createHeaderPopup('Nuevo Aviso', response.exito, () => changeView(cargarVistaPerfil));

                        else createPopup('Nuevo Aviso', response.error);
                    },
                    error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: 3" + outputError + estado + jqXHR)
                });
            }
        }
        catch (error) {

            console.error(error);
        }
    });

    $('#mdN').on('click', function () { Datos['namedata'] = "nombre"; $('#inNombre').prop('disabled', false); Datos['old'] = $('#inNombre').val(); Datos['inputID'] = "#inNombre"; integrarBoton(Datos, "#mdN"); $('#inNombre').focus(); });
    $('#mdA').on('click', function () { Datos['namedata'] = "apellido"; $('#inApellido').prop('disabled', false); Datos['old'] = $('#inApellido').val(); Datos['inputID'] = "#inApellido"; integrarBoton(Datos, "#mdA"); $('#inApellido').focus(); });
    $('#mdT').on('click', function () { Datos['namedata'] = "telefono"; $('#inTelefono').prop('disabled', false); Datos['old'] = $('#inTelefono').val(); Datos['inputID'] = "#inTelefono"; integrarBoton(Datos, "#mdT"); $('#inTelefono').focus(); });
    $('#mdD').on('click', function () { Datos['namedata'] = "direccion"; $('#inDireccion').prop('disabled', false); Datos['old'] = $('#inDireccion').val(); Datos['inputID'] = "#inDireccion"; integrarBoton(Datos, "#mdD"); $('#inDireccion').focus(); });
    $('#mdE').on('click', function () { Datos['namedata'] = "email"; $('#inEmail').prop('disabled', false); Datos['old'] = $('#inEmail').val(); Datos['inputID'] = "#inEmail"; integrarBoton(Datos, "#mdE"); $('#inEmail').focus(); });
}

function quitarEventos() {

    $('#mdF').off("click");
    $('#mdN').off("click");
    $('#mdA').off("click");
    $('#mdT').off("click");
    $('#mdD').off("click");
    $('#mdE').off("click");
}

function cargarVistaConsultas() {

    history.pushState(null, 'Mis Consultas', '/perfil/consultas');

    console.log("Cargando vista de 'Mis Consultas'");

    $.get('vistas/vistasperfil/vistaconsultas.php', contenido => {

        loadView(contenido);
    });

    if ($('#sidebarmobile').hasClass('visible')) desplegarSidebarMobile();

    $('#sidebar #btnsuperiores button, #sidebarmobile #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#misconsultas, #sidebarmobile #misconsultas').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/consultas.css');
}

function cargarVistaHorarios() {

    history.pushState(null, 'Horarios', '/perfil/horarios');

    console.log("Cargando vista de 'Horarios'");

    $.get('vistas/vistasperfil/vistahorarios.php', contenido => {

        loadView(contenido);
        $('#agregarhorario').on('click', () => $('main').fadeOut(200, cargarVistaAgregarHorario));
        if ($('.subtitulo').attr('data-cantidad') != 0) $('#eliminarhorario').addClass('visible').removeClass('invisible');
        else $('#eliminarhorario').addClass('invisible').removeClass('visible');
        $('#conthorarios').sortable({

            axis: 'y',
            containment: 'parent',
            delay: 300
        });
        $('#eliminarhorario').on('click', () => {

            if ($('#eliminarhorario').attr('data-eliminar') != 'si') {

                $('.horario .tachito').addClass('visible').removeClass('invisible');
                $('#eliminarhorario').html('<i class="fas fa-window-close" style="color: #ffffff;"></i>').attr('data-eliminar', 'si');
            }
            else {

                $('.horario .tachito').addClass('invisible').removeClass('visible');
                $('#eliminarhorario').html('<i class="fas fa-trash-alt" style="color: #ffffff;"></i>').attr('data-eliminar', 'no');
            }
        });

        $('.horario .tachito').on('click', async function () {

            if (await createConfirmPopup('Confirmación', '¿Estas seguro de que deseas eliminar el horario?', ['No', 'Sí'])) {

                $('#eliminarhorario').html('<i class="fas fa-spinner fa-pulse" style="color: #ffffff;"></i>').prop('disabled', true).attr('data-eliminar', 'no');
                $('.horario .tachito').addClass('invisible').removeClass('visible');

                $.ajax({

                    type: "POST",
                    url: "backend/perfil/eliminarHorario.php",
                    data: JSON.stringify({ horario: $(this).attr('data-horario') }),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function (response) {

                        $('#eliminarhorario').html('<i class="fas fa-trash-alt" style="color: #ffffff;"></i>').prop('disabled', false);

                        if (response.exito !== '') createHeaderPopup('Nuevo Aviso', response.exito, cargarVistaHorarios, 10);

                        else createPopup('Nuevo Aviso', response.error);
                    },
                    error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
                });
            }
        });
    });

    if ($('#sidebarmobile').hasClass('visible')) desplegarSidebarMobile();

    $('#sidebar #btnsuperiores button, #sidebarmobile #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#horarios, #sidebarmobile #horarios').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/horarios.css');
}

function cargarVistaInactividades() {

    history.pushState(null, 'Inactividades', '/perfil/inactividades');

    console.log("Cargando vista de 'Inactividades'");

    $.get('vistas/vistasperfil/vistainactividades.php', contenido => {

        loadView(contenido);
        $('#agregarinactividad').on('click', () => $('main').fadeOut(200, cargarVistaAgregarInactividad));
        $('#continactividades').sortable({

            axis: 'y',
            containment: 'parent',
            delay: 300
        });
        if ($('.subtitulo').attr('data-cantidad') != 0) $('#eliminarinactividad').addClass('visible').removeClass('invisible');
        else $('#eliminarinactividad').addClass('invisible').removeClass('visible');
        $('#eliminarinactividad').on('click', () => {

            if ($('#eliminarinactividad').attr('data-eliminar') != 'si') {

                $('.inactividad .tachito').addClass('visible').removeClass('invisible');
                $('#eliminarinactividad').html('<i class="fas fa-window-close" style="color: #ffffff;"></i>').attr('data-eliminar', 'si');
            }
            else {

                $('.inactividad .tachito').addClass('invisible').removeClass('visible');
                $('#eliminarinactividad').html('<i class="fas fa-trash-alt" style="color: #ffffff;"></i>').attr('data-eliminar', 'no');
            }
        });

        $('.inactividad .tachito').on('click', async function () {

            if (await createConfirmPopup('Confirmación', '¿Estas seguro de que deseas eliminar la inactividad?', ['No', 'Sí'])) {

                $('#eliminarinactividad').html('<i class="fas fa-spinner fa-pulse" style="color: #ffffff;"></i>').prop('disabled', true).attr('data-eliminar', 'no');
                $('.inactividad .tachito').addClass('invisible').removeClass('visible');

                $.ajax({

                    type: "POST",
                    url: "backend/perfil/eliminarInactividad.php",
                    data: JSON.stringify({ inactividad: $(this).attr('data-inactividad') }),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function (response) {

                        $('#eliminarinactividad').html('<i class="fas fa-trash-alt" style="color: #ffffff;"></i>').prop('disabled', false);

                        if (response.exito !== '') createHeaderPopup('Nuevo Aviso', response.exito, cargarVistaInactividades);

                        else createPopup('Nuevo Aviso', response.error);
                    },
                    error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
                });
            }
        });
    });

    if ($('#sidebarmobile').hasClass('visible')) desplegarSidebarMobile();

    $('#sidebar #btnsuperiores button, #sidebarmobile #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#inactividades, #sidebarmobile #inactividades').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/inactividades.css');
}

function cargarVistaSeguridad() {

    history.pushState(null, 'Seguridad', '/perfil/seguridad');

    console.log("Cargando vista de 'Seguridad'");

    $.get('vistas/vistasperfil/vistaseguridad.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
        $('#cambiarpass').on('click', async function (e) {

            e.preventDefault();
            if (await createConfirmPopup('Confirmación', '¿Estás seguro de cambiar tu contraseña?', ['No', 'Sí'])) cambiarContrasenia($('#oldpass').val(), $('#newpass').val(), $('#newpassagain').val());
        });
        $('#nomolestar').on('click', async () => {

            if ($('#nomolestar').is(':checked')) $.ajax({

                type: "POST",
                url: "backend/perfil/cambiarnomolestar.php",
                data: JSON.stringify({ nomolestar: false }),
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                success: function (response) {

                    if (response.exito.length > 0) createPopup('Nuevo Aviso', response.exito);
                    else createPopup('Nuevo Aviso', response.error);
                },
                error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
            });

            else $.ajax({

                type: "POST",
                url: "backend/perfil/cambiarnomolestar.php",
                data: JSON.stringify({ nomolestar: true }),
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                success: function (response) {

                    if (response.exito.length > 0) createPopup('Nuevo Aviso', response.exito);
                    else createPopup('Nuevo Aviso', response.error);
                },
                error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
            });

        });

        $('#verificaremail').on('click', async function () {

            if (await createConfirmPopup('Confirmación', '¿Estas seguro de enviar un correo de verificación?', ['No', 'Sí'])) {

                $(this).prop('disabled', true).css({ 'background-color': 'rgb(0, 224, 157, .4)' }).html('<i class="fas fa-spinner fa-pulse"></i>');

                $.ajax({

                    type: "POST",
                    url: "backend/login/enviaremailverificador.php",
                    success: function (response) {

                        $('#verificaremail').prop('disabled', false).css({ 'background-color': 'rgb(0, 224, 157, 1)' }).html('Verificar email');

                        if (response.exito.length > 0) createPopup('Nuevo Aviso', response.exito);
                        else createPopup('Nuevo Aviso', response.error);
                    },
                    error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)

                });
            }
        });
    });

    if ($('#sidebarmobile').hasClass('visible')) desplegarSidebarMobile();

    $('#sidebar #btnsuperiores button, #sidebarmobile #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#seguridad, #sidebarmobile #seguridad').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/seguridad.css');
}

function cargarVistaAgregarHorario() {

    history.pushState(null, 'Agregar Horario', '/perfil/horarios/agregar');

    console.log("Cargando vista de 'Agregar Horario'");

    $.get('vistas/vistasperfil/vistaagregarhorario.php', contenido => {

        let horario = {

            dia: null,
            horainicio: null,
            horafinalizacion: null,
        }

        $('main').empty().html(contenido).fadeIn(200);

        $('#confirmarhorario').addClass('inactivo').removeClass('activo').prop('disabled', true);
        $('select#horainicio, select#horafinalizacion').prop('disabled', true);

        $('#contagregarhorario select#dia').on('change', function () {

            if ($(this).val() != '') {

                $.ajax({

                    type: "POST",
                    url: "backend/perfil/getHorasInicio.php",
                    data: JSON.stringify({ dia: $(this).val() }),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function (response) {

                        let horasinicio = Object.values(response.horasInicio);
                        let horasiniciocomun = Object.values(response.horasInicioComun);

                        $('#confirmarhorario').addClass('inactivo').removeClass('activo').prop('disabled', true);

                        if (horasinicio.length > 0) {

                            $('#contagregarhorario select#horainicio').prop('disabled', false).html('<option selected value="">Seleccione la hora de inicio</option>');
                            $('#contagregarhorario select#horafinalizacion').html('<option selected value="">Seleccione la hora de finalización</option>').prop('disabled', true);
                            
                            for(let i = 0; i < horasinicio.length; i++) $('#contagregarhorario select#horainicio').append(`<option value='${horasinicio[i]}'>${horasiniciocomun[i]}</option>`);
            
                            horario.dia = Number($('select#dia').val());
                        }
                    },
                    error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
                });
            }
            else {

                $('#confirmarhorario').addClass('inactivo').removeClass('activo').prop('disabled', true);
                $('select#horainicio').prop('disabled', true).html('<option selected value="">Seleccione la hora de inicio</option>');
                $('select#horafinalizacion').prop('disabled', true).html('<option selected value="">Seleccione la hora de finalización</option>');
            }
        });

        $('#contagregarhorario select#horainicio').on('change', function () {

            if ($(this).val() != '') {

                $.ajax({

                    type: "POST",
                    url: "backend/perfil/getHorasFinalizacion.php",
                    data: JSON.stringify({ dia: $('select#dia').val(), horainicio: $(this).val() }),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function (response) {

                        let horasfinalizacion = Object.values(response.horasFinalizacion);
                        let horasfinalizacioncomun = Object.values(response.horasFinalizacionComun);

                        $('#confirmarhorario').addClass('inactivo').removeClass('activo').prop('disabled', true);

                        if (horasfinalizacion.length > 0) {

                            $('#contagregarhorario select#horafinalizacion').prop('disabled', false).empty().append('<option selected value="">Seleccione la hora de finalización</option>');
                            
                            for(let i = 0; i < horasfinalizacion.length; i++) $('#contagregarhorario select#horafinalizacion').append(`<option value='${horasfinalizacion[i]}'>${horasfinalizacioncomun[i]}</option>`);
                            
                            horario.horainicio = $('select#horainicio').val();
                        }
                    },
                    error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
                });
            }
            else {

                $('#confirmarhorario').addClass('inactivo').removeClass('activo').prop('disabled', true);
                $('select#horafinalizacion').prop('disabled', true).html('<option selected value="">Seleccione la hora de finalización</option>');
            }
        });

        $('#contagregarhorario select#horafinalizacion').on('change', function () {

            if ($(this).val() != '') {

                $('#confirmarhorario').addClass('activo').removeClass('inactivo').prop('disabled', false);
                horario.horafinalizacion = $('select#horafinalizacion').val();
            }
        });

        $('#confirmarhorario').on('click', async () => {

            if (await createConfirmPopup('Confirmación', '¿Estás seguro de agregar el horario?')) {

                $('#confirmarhorario').html('<i class="fas fa-spinner fa-pulse" style="color: #ffffff;"></i>').prop('disabled', true).css({ 'background-color': 'rgb(66, 148, 255, .4)' });

                if (horario.dia != null && horario.horainicio != null && horario.horafinalizacion != null) $.ajax({

                    type: "POST",
                    url: "backend/perfil/agregarHorario.php",
                    data: JSON.stringify(horario),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function (response) {

                        $('#confirmarhorario').html('Agregar').prop('disabled', false).css({ 'background-color': 'rgb(66, 148, 255, 1)' });

                        if (response.exito !== '') createHeaderPopup('Nuevo Aviso', response.exito, () => changeView(cargarVistaHorarios));

                        else createPopup('Nuevo Aviso', response.error);
                    },
                    error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
                });
            }
        });
    });

    $('#sidebar #btnsuperiores button, #sidebarmobile #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#horarios, #sidebarmobile #horarios').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/horarios.css');
}

let fechasPermitidasInicioInactividad = [];
let fechasPermitidasFinalizacionInactividad = [];

function cargarVistaAgregarInactividad() {

    history.pushState(null, 'Agregar Inactividad', '/perfil/inactividades/agregar');

    console.log("Cargando vista de 'Agregar Inactividad'");

    $.get('vistas/vistasperfil/vistaagregarinactividad.php', contenido => {

        let inactividad = {

            fechainicio: null,
            horainicio: null,
            fechafinalizacion: null,
            horafinalizacion: null
        }

        loadView(contenido);

        $.get('backend/perfil/getFechasInicioInactividad.php', data => fechasPermitidasInicioInactividad = data.fechasDisponibles);

        $('#confirmarinactividad').prop('disabled', true).removeClass('activo').addClass('inactivo');

        $('#contagregarinactividad #fechainicio').datepicker({ beforeShowDay: date => permitirFechas(date, fechasPermitidasInicioInactividad) }).on('change', function () {

            $('input#fechafinalizacion').prop('disabled', true).val('');
            $('select#horafinalizacion').prop('disabled', true).html('<option selected value="">Seleccione la hora de finalización<option>');
            $('#confirmarinactividad').prop('disabled', true).removeClass('activo').addClass('inactivo');

            if ($(this).val() !== '') {

                let fechapamandar = $(this).val().split('/');

                let datos = JSON.stringify({

                    dia: fechapamandar[0],
                    mes: fechapamandar[1],
                    anio: fechapamandar[2]
                });

                inactividad.fechainicio = $('input#fechainicio').val();

                $.ajax({

                    type: "POST",
                    url: "backend/perfil/getHorasInicioInactividad.php",
                    data: datos,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        $('select#horainicio').html('<option selected value="">Seleccione una hora</option>').prop('disabled', false);

                        response.horasDisponibles.forEach(elemento => $('select#horainicio').append(`<option value="${elemento}">${elemento}</option>`));

                        $('select#horainicio').prop('disabled', false);
                    },
                    error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
                });
            }

            else $('select#horainicio').prop('disabled', true).val('');
        });

        $('#contagregarinactividad #horainicio').on('change', function () {

            $('select#horafinalizacion').prop('disabled', true).html('<option selected value="">Seleccione la hora de finalización<option>');
            $('#confirmarinactividad').prop('disabled', true).removeClass('activo').addClass('inactivo');

            if ($(this).val() !== '') {

                inactividad.horainicio = $('select#horainicio').val();

                $.ajax({

                    type: "POST",
                    url: "backend/perfil/getFechasFinalizacionInactividad.php",
                    data: JSON.stringify(inactividad),
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        fechasPermitidasFinalizacionInactividad = response.fechasDisponibles;

                        $('input#fechafinalizacion').prop('disabled', false).val('');
                    },
                    error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
                });
            }

            else $('input#fechafinalizacion').prop('disabled', true).val('');
        });

        $('#contagregarinactividad #fechafinalizacion').datepicker({ beforeShowDay: date => permitirFechas(date, fechasPermitidasFinalizacionInactividad) }).on('change', function () {

            $('#confirmarinactividad').prop('disabled', true).removeClass('activo').addClass('inactivo');

            if ($(this).val() !== '') {

                inactividad.fechafinalizacion = $('input#fechafinalizacion').val();

                $.ajax({

                    type: "POST",
                    url: "backend/perfil/getHorasFinalizacionInactividad.php",
                    data: JSON.stringify(inactividad),
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        $('select#horafinalizacion').html('<option selected value="">Seleccione una hora</option>').prop('disabled', false);

                        response.horasDisponibles.forEach(elemento => $('select#horafinalizacion').append(`<option value="${elemento}">${elemento}</option>`));

                        inactividad.fechafinalizacion = $('input#fechafinalizacion').val();

                        $('select#horafinalizacion').prop('disabled', false);
                    },
                    error: (jqXHR, estado, outputError) => console.error("Error al procesar la solicitud: " + outputError + estado + jqXHR)
                });
            }

            else $('select#horafinalizacion').prop('disabled', true).val('');
        });

        $('#contagregarinactividad #horafinalizacion').on('change', function () {

            $('#confirmarinactividad').prop('disabled', true).removeClass('activo').addClass('inactivo');

            if ($(this).val() !== '') {

                $('#confirmarinactividad').prop('disabled', false).removeClass('inactivo').addClass('activo');

                inactividad.horafinalizacion = $('select#horafinalizacion').val();
            }
            else $('#confirmarinactividad').prop('disabled', true).css({ 'background-color': 'rgb(66, 148, 255, .4)' });
        });

        $('#confirmarinactividad').on('click', async evt => {

            evt.preventDefault();

            if (await createConfirmPopup('Confirmación', '¿Estás seguro de agregar la inactividad?')) {

                $('#confirmarinactividad').html('<i class="fas fa-spinner fa-pulse" style="color: #ffffff;"></i>').prop('disabled', true).removeClass('activo').addClass('inactivo');

                $datos = new FormData($('#contagregarinactividad')[0]);

                if (inactividad.fechainicio != null && inactividad.horainicio != null && inactividad.fechafinalizacion != null && inactividad.horafinalizacion != null) $.ajax({

                    type: "POST",
                    url: "backend/perfil/agregarInactividad.php",
                    data: $datos,
                    contentType: false,
                    processData: false,
                    success: function (response) {

                        $('#confirmarinactividad').html('Agregar').prop('disabled', false).removeClass('inactivo').addClass('activo');

                        if (response.exito.length > 0) createHeaderPopup('Nuevo Aviso', response.exito, () => changeView(cargarVistaInactividades), 10);

                        else createPopup('Nuevo Aviso', response.error);
                    },
                    error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
                });
            }
        });
    });
    
    $('#sidebar #btnsuperiores button, #sidebarmobile #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#inactividades, #sidebarmobile #inactividades').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/inactividades.css');
}

function cambiarContrasenia($1, $2, $3) {

    $('#cambiarpass').prop('disabled', true).css({ 'background-color': 'rgb(0, 224, 157, .4)' }).html('<i class="fas fa-spinner fa-pulse"></i>');

    let data = {

        old: $1,
        new: $2,
        newA: $3
    };

    let url = "./backend/perfil/changePASS.php";

    $.ajax({

        type: 'POST',
        url: url,
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: response => {

            $('#cambiarpass').prop('disabled', false).css({ 'background-color': 'rgb(0, 224, 157, 1)' }).html('Cambiar contraseña');

            if (response.error === undefined) {

                createPopup('Nuevo Aviso', response.enviar);
                $('form#formcambiar')[0].reset();
            }
            else createPopup('Nuevo Aviso', response.error);
        },
        error: (jqXHR, estado, outputError) => console.log("Error al procesar la solicitud: 3" + outputError + estado + jqXHR)
    });
}

function desplegarSidebarMobile() {

    let sidebarmobile = $('#sidebarmobile');

    if (sidebarmobile.hasClass('visible')) sidebarmobile.removeClass('visible').addClass('invisible');

    else sidebarmobile.removeClass('invisible').addClass('visible');
}

function changeView(vista) {

    $('main').fadeOut(200, vista);
    $('main')[0].scrollTo({ top: 0, behavior: 'smooth' });
}

function loadView(contenido) {

    $('main').empty().html(contenido).fadeIn(200);
}