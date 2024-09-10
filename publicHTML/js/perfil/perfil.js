$(() => {

    $('#miperfil').on('click', () => $('main').fadeOut(200, cargarVistaPerfil));
    $('#misconsultas').on('click', () => $('main').fadeOut(200, cargarVistaConsultas));
    $('#horarios').on('click', () => $('main').fadeOut(200, cargarVistaHorarios));
    $('#inactividades').on('click', () => $('main').fadeOut(200, cargarVistaInactividades));
    $('#seguridad').on('click', () => $('main').fadeOut(200, cargarVistaSeguridad));
    $('#cerrarsesion').on('click', () => $('main').fadeOut(150, () => window.location.href = 'login.php?estado=3')).on('mouseenter', () => $('#cerrarsesion').html('<i class="fas fa-sign-out-alt"></i>')).on('mouseleave', () => $('#cerrarsesion').html('<i class="fas fa-sign-out-alt"></i>&nbsp;Cerrar Sesión'));

    switch ($('main').data('vista')) {

        case 1: cargarVistaPerfil(); break;

        case 2: cargarVistaConsultas(); break;

        case 3: cargarVistaHorarios(); break;

        case 4: cargarVistaInactividades(); break;
    }

    history.replaceState({ path: 'perfil.php' }, '', 'perfil.php');
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

        $('main').empty().html(contenido).fadeIn(200);
        integrarEventos();
    });

    $('#sidebar #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#miperfil').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/miperfil.css');
}

function integrarBoton($datos, $id) {

    //Aca sucede la magia
    if ($($id).attr('src', 'img/iconosvg/Guardar.svg')) {

        console.log(0)
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
            var file = event.target.files[0];
            try {
                if (file) {

                    //Mostrar la imagen
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#fotoperfil').prop('src', e.target.result);
                    }
                    reader.readAsDataURL(file);

                    //Parseo de la imagen
                    var formData = new FormData();//
                    formData.append('file', file)

                    $.ajax({
                        type: "POST",
                        url: "./backend/perfil/subirIMG.php",
                        data: formData,    
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if(response.error == undefined){
                                window.location.reload();
                            }
                            else {console.log(response.error);}
                        },  error: (jqXHR, estado, outputError) => {
    
                            console.log("Error al procesar la solicitud: 3" + outputError+estado+jqXHR);
                        }
                    });

                }
            } catch (error) {
                console.log(error);
            }
        });
    });
    $('#mdN').on('click', function () { Datos['namedata'] = "nombre"; $('#inNombre').removeAttr('disabled'); Datos['old'] = $('#inNombre').val(); Datos['inputID'] = "#inNombre"; integrarBoton(Datos, "#mdN"); });
    $('#mdA').on('click', function () { Datos['namedata'] = "apellido"; $('#inApellido').removeAttr('disabled'); Datos['old'] = $('#inApellido').val(); Datos['inputID'] = "#inApellido"; integrarBoton(Datos, "#mdA") });
    $('#mdT').on('click', function () { Datos['namedata'] = "telefono"; $('#inTelefono').removeAttr('disabled'); Datos['old'] = $('#inTelefono').val(); Datos['inputID'] = "#inTelefono"; integrarBoton(Datos, "#mdT") });
    $('#mdD').on('click', function () { Datos['namedata'] = "direccion"; $('#inDireccion').removeAttr('disabled'); Datos['old'] = $('#inDireccion').val(); Datos['inputID'] = "#inDireccion"; integrarBoton(Datos, "#mdD") });
    $('#mdE').on('click', function () { Datos['namedata'] = "email"; $('#inEmail').removeAttr('disabled'); Datos['old'] = $('#inEmail').val(); Datos['inputID'] = "#inEmail"; integrarBoton(Datos, "#mdE") });
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

        $('main').empty().html(contenido).fadeIn(200);
    });

    $('#sidebar #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#misconsultas').css({'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/consultas.css');
}

function cargarVistaHorarios() {

    console.log("Cargando vista de 'Horarios'");

    $.get('vistas/vistasperfil/vistahorarios.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
        $('#agregarhorario').on('click', () => $('main').fadeOut(200, cargarVistaAgregarHorario));
        $('#conthorarios').sortable({

            axis: 'y',
            containment: 'parent',
            delay: 300
        });
    });

    $('#sidebar #btnsuperiores button').css({ 'text-decoration': 'none' });
    $('#horarios').css({ 'text-decoration': 'underline' });
    $('#seccionescss').prop('href', 'css/perfil/horarios.css');
}

function cargarVistaInactividades() {

    console.log("Cargando vista de 'Inactividades'");

    $.get('vistas/vistasperfil/vistainactividades.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
        $('#agregarinactividad').on('click', () => $('main').fadeOut(200, cargarVistaAgregarInactividad));
        $('#continactividades').sortable({

            axis: 'y',
            containment: 'parent',
            delay: 300
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

            if(horario.dia != null && horario.horainicio != null && horario.horafinalizacion != null) $.ajax({

                type: "POST",
                url: "backend/perfil/agregarHorario.php",
                data: JSON.stringify(horario),
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                success: function (response) {

                    console.log(response);

                    if(response.exito !== '') createHeaderPopup('Nuevo Aviso', response.exito, () => $('main').fadeOut(200, cargarVistaHorarios));

                    else createPopup('Nuevo Aviso', response.error);
                },
                error: (jqXHR, estado, outputError) => console.log(jqXHR,estado, outputError)
            });
        });
    });
}

function cargarVistaAgregarInactividad() {

    console.log("Cargando vista de 'Agregar Inactividad'");

    $.get('vistas/vistasperfil/vistaagregarinactividad.php', contenido => {

        $('main').empty().html(contenido).fadeIn(200);
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