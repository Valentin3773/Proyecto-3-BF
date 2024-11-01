$(() => addBTNListeners());

var iniData = {

    asunto: ($('#asunto-CP').val()),
    duracion: Number($('#duracion-CP').val()),
    resumen: ($('#resumen-CP').val()),
    fechaV: $('.contentFecha #fechaV').val(),
    horaV: $('.contentHora #horaV').val()
}

function addBTNListeners() {

    $('#btnModificar').on('click', funcionModificar);
    $('#btnCancelar').on('click', funcionCancelar);
    $('#btnGuardar').on('click', funcionGuardar);
    $('#btnEliminar').on('click', functionEliminar);
    $('#fecha-CP').on('change',cambiarHorario);
    $('#hora-CP').on('change',ajustarDuracion);

    desactivarInputs();
}

function cambiarHorario() {

    let fecha = $('#fecha-CP option:selected').val();
    let horaV = $('.contentHora #horaV').val();
    const url = 'backend/admin/getHorarioDisponibles.php';

    $.ajax({

        type: 'POST',
        url: url,
        data: JSON.stringify({ fecha: fecha }),
        contentType: 'application/json',
        success: function(response) {

            let horarios = JSON.parse(response);
            $('#hora-CP').empty();
            for (let i = 0; i < horarios.length; i++) {

                $('#hora-CP').append("<option value='" + horarios[i] + "'>" + horarios[i] + "</option>");
            }
            $('#hora-CP').append("<option id='horaV' value='" + horaV + "'style='Display:none'>" + horaV + "</option>");
        },
        error: function(error) {
            
            console.log('Error al enviar los datos:', error);
        }
    });
    ajustarDuracion();
}

function ajustarDuracion() {

    let fecha = $('#fecha-CP option:selected').val();
    let hora = $('#hora-CP option:selected').val();
    const url = 'backend/admin/getDuracionDisponibles.php';

    $.ajax({

        type: 'POST',
        url: url,
        data: JSON.stringify({ fecha: fecha, hora: hora}),
        contentType: 'application/json',
        success: function(response) {

            let duraciones = JSON.parse(response);

            $('#duracion-CP').empty();

            for (let i = 0; i < duraciones.length; i++) {

                $('#duracion-CP').append("<option value='" + duraciones[i] + "'>" + duraciones[i] + "</option>");
            }
        },
        error: function(error) {

            console.log('Error al enviar los datos:', error);
        }
    });
}

function funcionModificar() {

    console.log("Modificar");
    desactivarInputs(false);
}

function funcionCancelar() {

    console.log("Cancelar");

    desactivarInputs();
    
    $("#hora-CP").val($("#hora-CP option:first").val());
    $("#fecha-CP").val($("#fecha-CP option:first").val());

    $('#asunto-CP').val(iniData['asunto']);
    $('#duracion-CP').val(iniData['duracion']);
    $('#resumen-CP').val(iniData['resumen']);
}

async function funcionGuardar() {

    console.log("Guardar");
    
    desactivarInputs();

    if (iniData['asunto'] == $('#asunto-CP').val() && iniData['horaV'] == $('#hora-CP option:selected').html() && iniData['duracion'] == $('#duracion-CP').val() && iniData['fechaV'] == $('#fecha-CP option:selected').html() && iniData['resumen'] == $('#resumen-CP').val()) {

        createPopup('Atención', 'No se han encontrado cambios para guardar');
    }
    else {

        if(await createConfirmPopup('Atención', '¿Realmente desea modificar esta consulta?')) {

            $('#btnGuardar').html('<i class="fas fa-spinner fa-pulse"></i>');

            const url = 'backend/admin/updateConsultaPaciente.php';
            const data = {

                asunto: ($('#asunto-CP').val()),
                hora: ($('#hora-CP option:selected').html()),
                duracion: Number($('#duracion-CP').val()),
                fecha: ($('#fecha-CP option:selected').val()),
                resumen: ($('#resumen-CP').val()),
                fechaV: $('.contentFecha #fechaV').val(),
                horaV: $('.contentHora #horaV').val()
            };

            $.ajax({

                type: 'POST',
                url: url,
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: response => {

                    if (response.error === undefined) {

                        createHeaderPopup('Nuevo Aviso', response, () => changeView(cargarVistaConsultaCalendario));
                        iniData.asunto = $('#asunto-CP').val();
                        iniData.duracion = Number($('#duracion-CP option:selected').val());
                        iniData.fechaV = $('#fecha-CP option:selected').html();
                        iniData.horaV = $('#hora-CP option:selected').html();
                        iniData.resumen = $('#resumen-CP').val();
                    }
                    else createPopup("Nuevo aviso", response);

                    $('#btnGuardar').html('Guardar');
                },
                error: (jqXHR, estado, outputError) => {

                    console.error("Error al procesar la solicitud: " + outputError + jqXHR + estado);
                }
            });
        }
    }
}

async function functionEliminar() {

    const url = 'backend/admin/deleteConsultaPaciente.php';
    const data = {

        fechaV: $('.contentFecha #fechaV').html(),
        horaV: $('.contentHora #horaV').html(),
        asunto: $('#asunto-CP').val()
    };

    if (await createConfirmPopup("Atención", "¿Realmente desea eliminar esta consulta?")) {

        desactivarInputs();

        $('#btnEliminar').html('<i class="fas fa-spinner fa-pulse"></i>');

        $.ajax({

            type: "POST",
            url: url,
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function (response) {

                if (response.error === undefined) createHeaderPopup("Nuevo aviso", response.enviar, () => changeView(cargarVistaConsultaCalendario));
                
                else createPopup("Nuevo aviso", response.error);

                $('#btnEliminar').html('Eliminar');          
            },
            error: function (jqXHR, estado, outputError) {

                console.error("Error al procesar la solicitud: " + outputError + jqXHR + estado);
            }
        });
    }
}

function desactivarInputs(desactivado = true) {

    if(desactivado) {

        $('#btnCancelar').css("opacity", 0.5);
        $('#btnGuardar').css("opacity", 0.5);
        $('#btnEliminar').css("opacity", 0.5);

        $('#btnModificar').css("opacity", 1);
        $('#btnModificar').removeAttr('disabled');

        $('#btnCancelar').prop('disabled', true);
        $('#btnGuardar').prop('disabled', true);
        $('#btnEliminar').prop("disabled", true);
        $('#asunto-CP').prop('disabled', true);
        $('#hora-CP').prop('disabled', true);
        $('#duracion-CP').prop('disabled', true);
        $('#fecha-CP').prop('disabled', true);
        $('#resumen-CP').prop('disabled', true);
    }
    else {

        $('#btnCancelar').css("opacity", 1);
        $('#btnGuardar').css("opacity", 1);
        $('#btnEliminar').css("opacity", 1);

        $('#btnModificar').css("opacity", 0.5);
        $('#btnModificar').prop('disabled', true);

        $('#btnCancelar').prop('disabled', false);
        $('#btnGuardar').prop('disabled', false);
        $('#btnEliminar').prop("disabled", false);
        $('#asunto-CP').prop('disabled', false);
        $('#hora-CP').prop('disabled', false);
        $('#duracion-CP').prop('disabled', false);
        $('#fecha-CP').prop('disabled', false);
        $('#resumen-CP').prop('disabled', false);
    }
}