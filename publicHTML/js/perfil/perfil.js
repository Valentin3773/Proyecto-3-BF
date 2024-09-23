$(() => {

    $('#miperfil').on('click', () => changeView(cargarVistaPerfil));
    $('#misconsultas').on('click', () => changeView(cargarVistaConsultas));
    $('#horarios').on('click', () => changeView(cargarVistaHorarios));
    $('#inactividades').on('click', () => changeView(cargarVistaInactividades));
    $('#seguridad').on('click', () => changeView(cargarVistaSeguridad));
    $('#cerrarsesion').on('click', () => $('main').fadeOut(150, () => window.location.href = 'login.php?estado=3')).on('mouseenter', () => $('#cerrarsesion').html('<i class="fas fa-sign-out-alt"></i>')).on('mouseleave', () => $('#cerrarsesion').html('<i class="fas fa-sign-out-alt"></i>&nbsp;Cerrar Sesión'));

    switch ($('main').data('vista')) {

        case 1: cargarVistaPerfil(); break;

        case 2: cargarVistaConsultas(); break;

        case 3: cargarVistaHorarios(); break;

        case 4: cargarVistaInactividades(); break;
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

    $('main').prop('data-vista', 0);

    console.log("Cargando vista de 'Mi Perfil'");

    $.get('vistas/vistasperfil/vistaperfil.php', contenido => {

        loadView(contenido);
        integrarEventos();
    });

    $('#sidebar #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#miperfil').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/miperfil.css');
}

function integrarBoton($datos, $id) {

    // Aca sucede la magia
    if ($($id).attr('src', 'img/iconosvg/Guardar.svg')) {

        quitarEventos();

        $($id).on('click', function () {

            if ($($datos['inputID']).val() != $datos['old']) {

                integrarEventos();
                $datos['tipo'] = $("#\\$\\$\\$").attr('data-type');
                $datos['dato'] = $($datos['inputID']).val();
                $($id).attr('src', 'img/iconosvg/lapiz.svg');
                $($datos['inputID']).prop('disabled', true);
                funcionguardarCambios($datos);
                console.log("Guardar");
            }
            else {

                $($datos['inputID']).val($datos['old']);
                $($id).attr('src', 'img/iconosvg/lapiz.svg');
                $($datos['inputID']).prop('disabled', true);
                integrarEventos();
                console.log("Cancelar" + $datos['old']);
            }
            // window.location.replace("index.php");
        });
    }
    else $($id).attr('src', 'img/iconosvg/Guardar.svg');
}

function integrarEventos() {

    //Listeners con datos importantes
    $('#mdF').on('click', function () {

        $('#inFile').click();
        $('#inFile').change(function (event) {

            let file = event.target.files[0];
            try {

                if (file) {

                    // Mostrar la imagen
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        $('#fotoperfil').prop('src', e.target.result);
                    }
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

                            if(response.error == undefined) window.location.reload();
                            
                            else {console.log(response.error);
                        }
                        },  error: (jqXHR, estado, outputError) => {
    
                            console.log("Error al procesar la solicitud: 3" + outputError + estado + jqXHR);
                        }
                    });
                }
            } catch (error) {

                console.log(error);
            }
        });
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

    console.log("Cargando vista de 'Mis Consultas'");

    $.get('vistas/vistasperfil/vistaconsultas.php', contenido => {

        loadView(contenido);
    });

    $('#sidebar #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#misconsultas').css({'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/consultas.css');
}

function cargarVistaHorarios() {

    console.log("Cargando vista de 'Horarios'");

    $.get('vistas/vistasperfil/vistahorarios.php', contenido => {

        loadView(contenido);
        $('#agregarhorario').on('click', () => $('main').fadeOut(200, cargarVistaAgregarHorario));
        if($('.subtitulo').attr('data-cantidad') != 0) $('#eliminarhorario').addClass('visible').removeClass('invisible');
        else $('#eliminarhorario').addClass('invisible').removeClass('visible');
        $('#conthorarios').sortable({

            axis: 'y',
            containment: 'parent',
            delay: 300
        });
        $('#eliminarhorario').on('click', () => {

            console.log($('#eliminarhorario').attr('data-eliminar'));

            if($('#eliminarhorario').attr('data-eliminar') != 'si') {

                $('.horario .tachito').addClass('visible').removeClass('invisible');
                $('#eliminarhorario').html('<i class="fas fa-window-close" style="color: #ffffff;"></i>').attr('data-eliminar', 'si');
            }
            else {
                
                $('.horario .tachito').addClass('invisible').removeClass('visible');
                $('#eliminarhorario').html('<i class="fas fa-trash-alt" style="color: #ffffff;"></i>').attr('data-eliminar', 'no');
            }
        });

        $('.horario .tachito').on('click', async function() {

            if(await createConfirmPopup('Confirmación', '¿Estas seguro de que deseas eliminar el horario?', ['No', 'Sí'])) {

                $('#eliminarhorario').html('<i class="fas fa-spinner fa-pulse"></i>').prop('disabled', true).attr('data-eliminar', 'no');
                $('.horario .tachito').addClass('invisible').removeClass('visible');

                $.ajax({

                    type: "POST",
                    url: "backend/perfil/eliminarHorario.php",
                    data: JSON.stringify({horario: $(this).attr('data-horario')}),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function (response) {
                        
                        $('#eliminarhorario').html('<i class="fas fa-trash-alt" style="color: #ffffff;"></i>').prop('disabled', false);

                        if(response.exito !== '') createHeaderPopup('Nuevo Aviso', response.exito, cargarVistaHorarios);

                        else createPopup('Nuevo Aviso', response.error);
                    },
                    error: (jqXHR, estado, outputError) => console.log(jqXHR,estado, outputError)
                });
            }
        });
    });

    $('#sidebar #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#horarios').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/horarios.css');
}

function cargarVistaInactividades() {

    console.log("Cargando vista de 'Inactividades'");

    $.get('vistas/vistasperfil/vistainactividades.php', contenido => {

        loadView(contenido);
        $('#agregarinactividad').on('click', () => $('main').fadeOut(200, cargarVistaAgregarInactividad));
        $('#continactividades').sortable({

            axis: 'y',
            containment: 'parent',
            delay: 300
        });
        if($('.subtitulo').attr('data-cantidad') != 0) $('#eliminarinactividad').addClass('visible').removeClass('invisible');
        else $('#eliminarinactividad').addClass('invisible').removeClass('visible');
        $('#eliminarinactividad').on('click', () => {

            console.log($('#eliminarinactividad').attr('data-eliminar'));

            if($('#eliminarinactividad').attr('data-eliminar') != 'si') {

                $('.inactividad .tachito').addClass('visible').removeClass('invisible');
                $('#eliminarinactividad').html('<i class="fas fa-window-close" style="color: #ffffff;"></i>').attr('data-eliminar', 'si');
            }
            else {

                $('.inactividad .tachito').addClass('invisible').removeClass('visible');
                $('#eliminarinactividad').html('<i class="fas fa-trash-alt" style="color: #ffffff;"></i>').attr('data-eliminar', 'no');
            }
        });

        $('.inactividad .tachito').on('click', async function() {

            if(await createConfirmPopup('Confirmación', '¿Estas seguro de que deseas eliminar la inactividad?', ['No', 'Sí'])) {

                $('#eliminarinactividad').html('<i class="fas fa-spinner fa-pulse"></i>').prop('disabled', true).attr('data-eliminar', 'no');
                $('.inactividad .tachito').addClass('invisible').removeClass('visible');

                $.ajax({

                    type: "POST",
                    url: "backend/perfil/eliminarInactividad.php",
                    data: JSON.stringify({inactividad: $(this).attr('data-inactividad')}),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function (response) {

                        $('#eliminarinactividad').html('<i class="fas fa-trash-alt" style="color: #ffffff;"></i>').prop('disabled', false);

                        if(response.exito !== '') createHeaderPopup('Nuevo Aviso', response.exito, cargarVistaInactividades);

                        else createPopup('Nuevo Aviso', response.error);
                    },
                    error: (jqXHR, estado, outputError) => console.log(jqXHR,estado, outputError)
                });
            }
        });
    });

    $('#sidebar #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#inactividades').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/inactividades.css');
}

function cargarVistaSeguridad() {

    console.log("Cargando vista de 'Seguridad'");

    $.get('vistas/vistasperfil/vistaseguridad.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
        $('#cambiarpass').on('click', function (e) {

            e.preventDefault();
            cambiarContrasenia($('#oldpass').val(), $('#newpass').val(), $('#newpassagain').val());
        });
        $('#nomolestar').on('click', () => {

            if($('#nomolestar').is(':checked')) {

                $.ajax({

                    type: "POST",
                    url: "backend/perfil/cambiarnomolestar.php",
                    data: JSON.stringify({nomolestar: false}),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function (response) {

                        if(response.exito.length > 0) createPopup('Nuevo Aviso', response.exito);
                        else createPopup('Nuevo Aviso', response.error);
                    },
                    error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
                });
            }
            else {

                $.ajax({

                    type: "POST",
                    url: "backend/perfil/cambiarnomolestar.php",
                    data: JSON.stringify({nomolestar: true}),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function (response) {

                        if(response.exito.length > 0) createPopup('Nuevo Aviso', response.exito);
                        else createPopup('Nuevo Aviso', response.error);
                    },
                    error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
                });
            }
        });
    });

    $('#sidebar #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#seguridad').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/seguridad.css');
}

function cargarVistaAgregarHorario() {

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

        $('#contagregarhorario select#dia').on('change', function() {

            if($(this).val() != '') {
                
                $.ajax({

                    type: "POST",
                    url: "backend/perfil/getHorasInicio.php",
                    data: JSON.stringify({dia: $(this).val()}),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function (response) {
                        
                        let horasinicio = Object.values(response.horasInicio);

                        $('#confirmarhorario').addClass('inactivo').removeClass('activo').prop('disabled', true);

                        if(horasinicio.length > 0) {

                            $('#contagregarhorario select#horainicio').prop('disabled', false).html('<option selected value="">Seleccione la hora de inicio</option>');
                            $('#contagregarhorario select#horafinalizacion').html('<option selected value="">Seleccione la hora de finalización</option>').prop('disabled', true);
                            horasinicio.forEach(elemento => $('#contagregarhorario select#horainicio').append(`<option value='${elemento}'>${elemento}</option>`));
                            horario.dia = Number($('select#dia').val());
                        }
                    },
                    error: (jqXHR, estado, outputError) => console.log(jqXHR,estado, outputError)
                });
            }
            else {

                $('#confirmarhorario').addClass('inactivo').removeClass('activo').prop('disabled', true);
                $('select#horainicio').prop('disabled', true).html('<option selected value="">Seleccione la hora de inicio</option>');
                $('select#horafinalizacion').prop('disabled', true).html('<option selected value="">Seleccione la hora de finalización</option>');
            }
        });

        $('#contagregarhorario select#horainicio').on('change', function() {

            if($(this).val() != '') {

                $.ajax({

                    type: "POST",
                    url: "backend/perfil/getHorasFinalizacion.php",
                    data: JSON.stringify({dia: $('select#dia').val(), horainicio: $(this).val()}),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function (response) {
                        
                        let horasfinalizacion = Object.values(response.horasFinalizacion);

                        $('#confirmarhorario').addClass('inactivo').removeClass('activo').prop('disabled', true);

                        if(horasfinalizacion.length > 0) {

                            $('#contagregarhorario select#horafinalizacion').prop('disabled', false).empty().append('<option selected value="">Seleccione la hora de finalización</option>');
                            horasfinalizacion.forEach(elemento => $('#contagregarhorario select#horafinalizacion').append(`<option value='${elemento}'>${elemento}</option>`));
                            horario.horainicio = $('select#horainicio').val();
                        }
                    },
                    error: (jqXHR, estado, outputError) => console.log(jqXHR,estado, outputError)
                });
            }
            else {
                
                $('#confirmarhorario').addClass('inactivo').removeClass('activo').prop('disabled', true);
                $('select#horafinalizacion').prop('disabled', true).html('<option selected value="">Seleccione la hora de finalización</option>');
            }
        });

        $('#contagregarhorario select#horafinalizacion').on('change', function() {

            if($(this).val() != '') { 
                
                $('#confirmarhorario').addClass('activo').removeClass('inactivo').prop('disabled', false);
                horario.horafinalizacion = $('select#horafinalizacion').val();
            }
        });

        $('#confirmarhorario').on('click', () => {

            $('#confirmarhorario').html('<i class="fas fa-spinner fa-pulse"></i>').prop('disabled', true);

            if(horario.dia != null && horario.horainicio != null && horario.horafinalizacion != null) $.ajax({

                type: "POST",
                url: "backend/perfil/agregarHorario.php",
                data: JSON.stringify(horario),
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                success: function (response) {

                    $('#confirmarhorario').html('Agregar').prop('disabled', false);

                    if(response.exito !== '') createHeaderPopup('Nuevo Aviso', response.exito, () => changeView(cargarVistaHorarios));

                    else createPopup('Nuevo Aviso', response.error);
                },
                error: (jqXHR, estado, outputError) => console.log(jqXHR,estado, outputError)
            });
        });
    });
}

let fechasPermitidas = [];

function cargarVistaAgregarInactividad() {

    console.log("Cargando vista de 'Agregar Inactividad'");

    $.get('vistas/vistasperfil/vistaagregarinactividad.php', contenido => {

        loadView(contenido);

        $('#contagregarinactividad #fechainicio').datepicker({ beforeShowDay: permitirFechas }).on('change', () => {
            

        });
    });
}

function cambiarContrasenia($1, $2, $3) {

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

            if (response.error === undefined) {
                
                createPopup('Nuevo Aviso', response.enviar);
                $('form#formcambiar')[0].reset();
            }
            else createPopup('Nuevo Aviso', response.error);
        },
        error: (jqXHR, estado, outputError) => {

            console.log("Error al procesar la solicitud: 3" + outputError + estado + jqXHR);
        }
    });
}

function changeView(vista) {

    $('main').fadeOut(200, vista);
}

function loadView(contenido) {

    $('main').empty().html(contenido).fadeIn(200);
}