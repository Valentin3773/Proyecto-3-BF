$(() => addBTNListeners());

var iniData = {

    asunto: ($('#asunto-CP').val()),
    duracion: Number($('#duracion-CP').val()),
    resumen: ($('#resumen-CP').val()),
    fechaV: $('.contentFecha #fechaV').html(),
    horaV: $('.contentHora #horaV').html()
}

function addBTNListeners() {

    $('#btnModificar').on('click', funcionModificar);
    $('#btnCancelar').on('click', funcionCancelar);
    $('#btnGuardar').on('click', funcionGuardar);
    $('#btnEliminar').on('click', functionEliminar);
    $('#fecha-CP').on('change', cambiarHorario);

    $('#btnCancelar').css("opacity", 0.5);
    $('#btnGuardar').css("opacity", 0.5);
    $('#btnEliminar').css("opacity", 0.5);
}

function cambiarHorario() {

    let fecha = $('#fecha-CP option:selected').html();
    let horaV = $('.contentHora #horaV').html();
    const url = 'backend/admin/getHorarioDisponibles.php';

    $.ajax({

        type: 'POST',
        url: url,
        data: JSON.stringify({ fecha: fecha }),
        contentType: 'application/json',
        success: function(response) {

            console.log(response);

            let horarios = JSON.parse(response);

            $('#hora-CP').empty();

            for (let i = 0; i < horarios.length; i++) {

                $('#hora-CP').append("<option value='" + horarios[i] + "'>" + horarios[i] + "</option>");
            }
            $('#hora-CP').append("<option id='horaV' value='" + horaV + "'>" + horaV + "</option>");
        },
        error: function(error) {

            console.log('Error al enviar los datos:', error);
        }
    });

    ajustarDuracion();
}

function ajustarDuracion() {

    let fecha = $('#fecha-CP option:selected').html();
    let hora = $('#hora-CP option:selected').html();
    const url = 'backend/admin/getDuracionDisponibles.php';

    $.ajax({

        type: 'POST',
        url: url,
        data: JSON.stringify({ fecha: fecha, hora: hora}),
        contentType: 'application/json',
        success: function(response) {

            console.log(response);

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
    $('#btnCancelar').css("opacity", 1);
    $('#btnGuardar').css("opacity", 1);
    $('#btnEliminar').css("opacity", 1);

    $('#btnModificar').css("opacity", 0.5);
    $('#btnModificar').prop('disabled', true);

    $('#btnCancelar').removeAttr('disabled');
    $('#btnGuardar').removeAttr('disabled');
    $('#btnEliminar').removeAttr('disabled');
    $('#asunto-CP').removeAttr('disabled');
    $('#hora-CP').removeAttr('disabled');
    $('#duracion-CP').removeAttr('disabled');
    $('#fecha-CP').removeAttr('disabled');
    $('#resumen-CP').removeAttr('disabled');
}

function funcionCancelar() {

    console.log("Cancelar");
    $('#btnCancelar').css("opacity", 0.5);
    $('#btnGuardar').css("opacity", 0.5);
    $('#btnEliminar').css("opacity", 0.5);

    $('#btnModificar').css("opacity", 1);
    $('#btnModificar').removeAttr('disabled');

    $('#btnCancelar').prop('disabled', true);
    $('#btnGuardar').prop('disabled', true);
    $('#btnEliminar').prop('disabled', true);
    $('#asunto-CP').prop('disabled', true);
    $('#hora-CP').prop('disabled', true);
    $('#duracion-CP').prop('disabled', true);
    $('#fecha-CP').prop('disabled', true);
    $('#resumen-CP').prop('disabled', true);
    $("#hora-CP").val($("#hora-CP option:first").val());
    $("#fecha-CP").val($("#fecha-CP option:first").val());

    $('#asunto-CP').val(iniData['asunto']);
    $('#duracion-CP').val(iniData['duracion']);
    $('#resumen-CP').val(iniData['resumen']);
}

function funcionGuardar() {

    console.log("Guardar");
    $('#btnCancelar').css("opacity", 0.5);
    $('#btnGuardar').css("opacity", 0.5);
    $('#btnEliminar').css("opacity", 0.5);


    $('#btnModificar').css("opacity", 1);
    $('#btnModificar').removeAttr('disabled');

    $('#btnEliminar').prop('disabled', true);
    $('#btnCancelar').prop('disabled', true);
    $('#btnGuardar').prop('disabled', true);
    $('#asunto-CP').prop('disabled', true);
    $('#hora-CP').prop('disabled', true);
    $('#duracion-CP').prop('disabled', true);
    $('#fecha-CP').prop('disabled', true);
    $('#resumen-CP').prop('disabled', true);

    if (iniData['asunto'] == $('#asunto-CP').val() && iniData['horaV'] == $('#hora-CP option:selected').html() && iniData['duracion'] == $('#duracion-CP').val() && iniData['fechaV'] == $('#fecha-CP option:selected').html() && iniData['resumen'] == $('#resumen-CP').val()) {

        createPopup('Atención', 'No se han encontrado cambios para guardar');
    }
    else {

        const url = 'backend/admin/updateConsultaPaciente.php';
        const data = {

            asunto: ($('#asunto-CP').val()),
            hora: ($('#hora-CP option:selected').html()),
            duracion: Number($('#duracion-CP').val()),
            fecha: ($('#fecha-CP option:selected').html()),
            resumen: ($('#resumen-CP').val()),
            fechaV: $('.contentFecha #fechaV').html(),
            horaV: $('.contentHora #horaV').html()
        };

        $.ajax({

            type: 'POST',
            url: url,
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: response => {

                if (response.error === undefined) {
                    createPopup('Nuevo Aviso',response);
                    iniData.asunto = $('#asunto-CP').val();
                    iniData.duracion = Number($('#duracion-CP option:selected').val());
                    iniData.fechaV = $('#fecha-CP option:selected').html();
                    iniData.horaV = $('#hora-CP option:selected').html();
                    iniData.resumen = $('#resumen-CP').val();
                }

                else createPopup("Nuevo aviso", response);
            },
            error: (jqXHR, estado, outputError) => {

                console.error("Error al procesar la solicitud: " + outputError + jqXHR + estado);
            }
        });

        changeView(() => cargarVistaConsultaDetalle(data.fecha, data.hora));
    }
}

async function functionEliminar() {

    console.log("Eliminar");

    const url = 'backend/admin/deleteConsultaPaciente.php';
    const data = {

        fechaV: $('.contentFecha #fechaV').html(),
        horaV: $('.contentHora #horaV').html()
    };

    console.log("Cancelado");

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

    if (await createConfirmPopup("Atención", "¿Realmente desea eliminar esta consulta?")) {

        $.ajax({

            type: "POST",
            url: url,
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function (response) {

                if (response.error === undefined) createHeaderPopup("Nuevo aviso", "Se elimino la consulta con exito", () => changeView(cargarVistaConsultaCalendario));
                
                else createPopup("Nuevo aviso", response);
            },
            error: function (jqXHR, estado, outputError) {

                console.error("Error al procesar la solicitud: " + outputError + jqXHR + estado);
            }
        });
    }
}
