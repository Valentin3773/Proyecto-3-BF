$(() => addBTNListeners());

var iniData = {

    asunto: ($('#asunto-CP').val()),
    duracion: Number($('#duracion-CP').val()),
    resumen: ($('#resumen-CP').val()),
    fechaV: $('.contentFecha h1').html(),
    horaV: $('.contentHora h1').html()
}

function addBTNListeners() {

    $('#btnModificar').on('click', funcionModificar);
    $('#btnCancelar').on('click', funcionCancelar);
    $('#btnGuardar').on('click', funcionGuardar);
    $('#btnEliminar').on('click', functionEliminar);

    $('#btnCancelar').css("opacity", 0.5);
    $('#btnGuardar').css("opacity", 0.5);
    $('#btnEliminar').css("opacity", 0.5);
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

    $('#btnModificar').css("opacity", 1);
    $('#btnModificar').removeAttr('disabled');

    $('#btnCancelar').prop('disabled', true);
    $('#btnGuardar').prop('disabled', true);
    $('#asunto-CP').prop('disabled', true);
    $('#hora-CP').prop('disabled', true);
    $('#duracion-CP').prop('disabled', true);
    $('#fecha-CP').prop('disabled', true);
    $('#resumen-CP').prop('disabled', true);

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

    console.log(data);

    $.ajax({

        type: 'POST',
        url: url,
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: response => {

            if (response.error === undefined) createPopup("Nuevo aviso",response);

            else createPopup("Nuevo aviso",response);
        },
        error: (jqXHR, estado, outputError) => {

            console.error("Error al procesar la solicitud: " + outputError +jqXHR +estado);
        }
    });

}

function functionEliminar() {

    console.log("Eliminar");

    const url = 'backend/admin/deleteConsultaPaciente.php';
    const data = {
        fechaV: $('.contentFecha #fechaV').html(),
        horaV: $('.contentHora #horaV').html()
    };
    
    var Confirmar = prompt("¿Está seguro?", "Ingrese Confirmar");

    if (Confirmar === null) {

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

    } else if (Confirmar.toLowerCase() === "confirmar") {

        $.ajax({

            type: "POST",
            url: url,
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function (response) {
                if (response.error === undefined) {

                    console.log(response);
                    //window.location.href = "administrador.php";
                } 
                else {

                    console.log(response);
                }
            },
            error: function (jqXHR, estado, outputError) {

                console.error("Error al procesar la solicitud: " + outputError +jqXHR +estado);
            }
        });
    }
}
