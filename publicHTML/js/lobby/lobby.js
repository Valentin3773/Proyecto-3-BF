$(() => {

    addListeners();

    let calificaravisar = null;

    $.get('backend/lobby/getnotificaciones.php', respuesta => {

        calificaravisar = respuesta;
        console.log(calificaravisar);

        if(calificaravisar.nomolestar == undefined) {

            if(calificaravisar.avisar.length > 0) {

                let meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];

                calificaravisar.avisar.forEach(async elemento => {
                
                    let hora = elemento.hora.split(':')[0];
                    let minuto = elemento.hora.split(':')[1];

                    let dia = elemento.fecha.split('-')[2];
                    let mes = Number(elemento.fecha.split('-')[1]);
                    let anio = elemento.fecha.split('-')[0];
                    
                    let fechaformateada = `${dia} de ${meses[mes - 1]} de ${anio}`;
                    let $horaformateada = `${hora}:${minuto}`;

                    await createPopup('Nuevo Aviso', `Te recordamos que tienes una consulta para el día ${fechaformateada} a la hora ${$horaformateada}, con el odontólogo ${elemento.nombreo} ${elemento.apellidoo}, por el asunto de: "${elemento.asunto}"`, 35);
                });
            }
        }
    });
});

function addListeners() {

    $('#inicio, #iniciom, #logo').on('click', () => changeView(cargarVistaInicio));

    $('#nosotros, #nosotrosm').on('click', () => changeView(cargarVistaNosotros));

    $('#servicios, #serviciosm').on('click', () => changeView(cargarVistaServicios));

    $('#contacto, #contactom').on('click', () => changeView(cargarVistaContacto));

    $('#btnchat').on('click', () => window.open('https://api.whatsapp.com/send/?phone=598091814295', '_blank'));

    $('#closemodal').on('click', () => $('#modal').addClass('oculto').removeClass('visible'));
}

function cargarVistaInicio() {

    window.scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistaslobby/vistainicio.php", data => {

        loadView(data);
        console.log("Cargando vista de 'Inicio'");

        // history.pushState({}, '', 'inicio');
    });

    $('#inicio, #iniciom').css({ 'text-decoration': 'underline' });
    $('#servicios, #serviciosm').css({ 'text-decoration': 'none' });
    $('#contacto, #contactom').css({ 'text-decoration': 'none' });
    $('#nosotros').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('href', 'css/lobby/inicio.css');
}

function cargarVistaNosotros() {

    window.scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistaslobby/vistanosotros.php", data => {

        loadView(data);
        console.log("Cargando vista de 'Nosotros'");

        // history.pushState({}, '', 'contacto');
    });

    $('#inicio, #iniciom').css({ 'text-decoration': 'none' });
    $('#servicios, #serviciosm').css({ 'text-decoration': 'none' });
    $('#contacto, #contactom').css({ 'text-decoration': 'none' });
    $('#nosotros').css({ 'text-decoration': 'underline' });

    $('#seccionescss').attr('href', 'css/lobby/nosotros.css');
}

function cargarVistaServicios() {

    window.scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistaslobby/vistaservicios.php", data => {

        loadView(data);
        console.log("Cargando vista de 'Servicios'");

        $.get('backend/lobby/apiservicios.php', data => iniciarServicios(data));

        // history.pushState({}, '', 'servicios');
    });

    $('#servicios, #serviciosm').css({ 'text-decoration': 'underline' });
    $('#inicio, #iniciom').css({ 'text-decoration': 'none' });
    $('#contacto, #contactom').css({ 'text-decoration': 'none' });
    $('#nosotros').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('href', 'css/lobby/servicios.css');
}

function cargarVistaContacto() {

    window.scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistaslobby/vistacontacto.php", data => {

        loadView(data);
        console.log("Cargando vista de 'Contacto'");
        $('#enviarmail').on('click', function (event) {

            event.preventDefault();
            let data = new FormData($('#formEmail')[0]);

            emailComfirm(data);
        });
    });

    $('#contacto, #contactom').css({ 'text-decoration': 'underline' });
    $('#inicio, #iniciom').css({ 'text-decoration': 'none' });
    $('#servicios, #serviciosm').css({ 'text-decoration': 'none' });
    $('#nosotros').css({ 'text-decoration': 'none' });

    $('#seccionescss').attr('href', 'css/lobby/contacto.css');
}

async function emailComfirm(datos) {
    
    if ($('#jejeje').val() === '') {

        if(await createConfirmPopup('Confirmación', '¿Estás seguro de enviar el email?')){

            $('#enviarmail').prop('disabled', true).css({'background-color': 'rgb(0, 178, 255, .5)'}).html('<i class="fas fa-spinner fa-pulse"></i>');

            $.ajax({
                
                type: "POST",
                url: "backend/lobby/formcontactomanager.php",
                data: datos,
                processData: false,
                contentType: false,
                success: response => {

                    if (response.error === undefined) {
                        
                        createPopup('Nuevo Aviso', response.enviar);
                        
                        $('#formEmail')[0].reset();
                    }
                    
                    else createPopup('Nuevo Aviso', response.error);

                    $('#enviarmail').prop('disabled', false).css({'background-color': 'rgb(0, 178, 255, 1)'}).html('<div class="svg-wrapper-1"><div class="svg-wrapper"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"></path><path fill="currentColor" d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z"></path></svg></div></div><span>Enviar</span>');
                },
                error: (jqXHR, estado, outputError) => console.error(jqXHR, estado, outputError)
            });
        }
    } 
    else console.log("Fuera bot hijueputa!!!");
}