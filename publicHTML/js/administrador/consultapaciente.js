$(function () {
    addBTNListeners();
});

function addBTNListeners() {

    $('#btnModificar').on('click', function () { funcionModificar() });
    $('#btnCancelar').on('click', function () { funcionCancelar() });
    $('#btnGuardar').on('click', function () { funcionGuardar() });

    $('#btnCancelar').css("opacity", 0.5);
    $('#btnGuardar').css("opacity", 0.5);

}

function funcionModificar() {
    console.log("Modificar");
    $('#btnCancelar').css("opacity", 1);
    $('#btnGuardar').css("opacity", 1);

    $('#btnModificar').css("opacity", 0.5);
    $('#btnModificar').prop('disabled', true);

    $('#btnCancelar').removeAttr('disabled');
    $('#btnGuardar').removeAttr('disabled');
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

    $('#btnModificar').css("opacity", 1);
    $('#btnModificar').removeAttr('disabled');

    $('#btnCancelar').prop('disabled', true);
    $('#btnGuardar').prop('disabled', true);
    $('#asunto-CP').prop('disabled', true);
    $('#hora-CP').prop('disabled', true);
    $('#duracion-CP').prop('disabled', true);
    $('#fecha-CP').prop('disabled', true);
    $('#resumen-CP').prop('disabled', true);
    $("#hora-CP").val($("#hora-CP option:first").val());
    $("#fecha-CP").val($("#fecha-CP option:first").val());
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
        fechaV:$('.contentFecha h1').html(),
        horaV:$('.contentHora h1').html()
    };

    $.ajax({
        type: 'POST',
        url: url,
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: response => {
            if (response.error === undefined) {
                let titulo = "Nuevo Aviso";
                    $('#div-mensaje-popup').hide(); 
                    $.get("popupmensaje.php ? Contenido="+response.enviar+"&Aviso="+titulo+"", data => {
                        $("#div-mensaje-popup").fadeIn(500);
                        $('#div-mensaje-popup').html(data);
                        $('#btnCerrar').on("click",function () { 
                            $("#div-mensaje-popup").fadeOut(500);
                         });
                    });
            } else {
                let titulo = "Nuevo Aviso";
                    $('#div-mensaje-popup').hide(); 
                    $.get("popupmensaje.php ? Contenido="+response.error+"&Aviso="+titulo+"", data => {
                        $("#div-mensaje-popup").fadeIn(500);
                        $('#div-mensaje-popup').html(data);
                        $('#btnCerrar').on("click",function () { 
                            $("#div-mensaje-popup").fadeOut(500);
                         });
                    });
            }
        },
        error: (jqXHR, estado, outputError) => {
            alert("Error al procesar la solicitud: " + outputError);
        }
    });
    
}