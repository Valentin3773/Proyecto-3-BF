$(() => {

    switch($('main').data('vista')) {

        case 1: cargarVistaLogin(); break;

        case 2: cargarVistaRegistro(); break;

        case 3: cerrarSesion(); break;

        case 4: createHeaderPopup('Nuevo Aviso', 'El código de verificación no es válido', 'index.php'); break;

        case 5: createHeaderPopup('Nuevo Aviso', 'Su cuenta se ha verificado y activado', 'index.php'); break;
    }

    history.replaceState({path: 'login.php'}, '', 'login.php');
});

function cargarVistaLogin() {

    window.scrollTo({ top: 0, behavior: 'smooth' });

    $.get("vistas/vistaslogin/vistalogin.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Login'");

        $('#btnvolver').on('click', volverInicio);
        $('#btnregistrarsel').on('click', cargarVistaRegistro);
        $('#iniadmin').on('click', cargarVistaLoginAdmin)
        $('#ingresar').on('click', function (event) {

            event.preventDefault();
            let data = new FormData($('#formLogin')[0]);

            $('#ingresar').prop('disabled', true).css({'background-color': 'rgb(0, 178, 255, .5)'}).html('<i class="fas fa-spinner fa-pulse"></i>');

            loginConfirm(data);
        });
        $('#inemail').focus();
    });
    $('html, body').css("height", "100%");
}

function cargarVistaRegistro() {

    window.scrollTo({ top: 0, behavior: 'smooth' });

    $.get("vistas/vistaslogin/vistaregistro.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Registro'");

        $('#btnvolver').on('click', volverLogin);
        $('#btnregistrarsel').on('click', function (event) {

            event.preventDefault();
            let data = new FormData($('#formRegistrar')[0]);

            $('#btnregistrarsel').prop('disabled', true).css({'background-color': 'rgb(0, 178, 255, .5)'}).html('<i class="fas fa-spinner fa-pulse"></i>');

            registerConfirm(data);
        });
    });
}

function cargarVistaLoginAdmin() {

    window.scrollTo({ top: 0, behavior: 'smooth' });

    $.get("vistas/vistaslogin/vistaloginadmin.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Login'");
        $('#btnvolver').on('click', volverInicio);
        $('#ingresarad').on('click', function (event) {

            event.preventDefault();
            let data = new FormData($('#formLogin')[0]);

            $('#ingresarad').prop('disabled', true).css({'background-color': 'rgb(0, 178, 255, .5)'}).html('<i class="fas fa-spinner fa-pulse"></i>');

            loginAdminConfirm(data);
        });
    });
    $('html, body').css("height", "100%");
}

function volverInicio() {

    window.scrollTo({ top: 0, behavior: 'smooth' });
    window.location.href = "index.php";
}

function volverLogin() {

    window.scrollTo({ top: 0, behavior: 'smooth' });
    window.location.href = "login.php";
}

function loginConfirm(datos) {

    console.log(datos);

    if ($('#jejeje').val() === '') {

        $.ajax({
            
            type: "POST",
            url: "backend/login/loginmanager.php",
            data: datos,
            processData: false,
            contentType: false,
            success: response => {
                
                console.log(response);

                if (response.error === undefined) createHeaderPopup('Nuevo Aviso', response.enviar, 'index.php');

                else createPopup('Nuevo Aviso', response.error);

                $('#ingresar').prop('disabled', false).css({'background-color': 'rgb(0, 178, 255, 1)'}).html('Ingresar');
            },
            error: (jqXHR, estado, outputError) => {

                console.log(jqXHR,estado, outputError);
            }
        });
    } 
    else {
        
        console.log("Fuera bot hijueputa!!!");
    }
}

function registerConfirm(datos) {
    
    if ($('#jejeje').val() === '') {

        $.ajax({

            type: "POST",
            url: "backend/login/registromanager.php",
            data: datos,
            processData: false,
            contentType: false,
            success: response => {

                if (response.error === undefined) createHeaderPopup('Nuevo Aviso', response.registrado, cargarVistaLogin);

                else createPopup('Nuevo Aviso', response.error);

                $('#btnregistrarsel').prop('disabled', false).css({'background-color': 'rgb(0, 178, 255, 1)'}).html('Registrarse');
            },
            error: (jqXHR, estado, outputError) => {

                console.log(jqXHR,estado, outputError);
            }
        });
    } 
    else {

        console.log("Fuera bot hijueputa!!!");
    }
}

function loginAdminConfirm(datos) {

    if ($('#jejeje').val() === '') {

        $.ajax({

            type: "POST",
            url: "backend/admin/adminloginmanager.php",
            data: datos,
            processData: false,
            contentType: false,
            success: response => {

                if (response.error === undefined) createHeaderPopup('Nuevo Aviso', response.admin, 'administrador.php');

                else createPopup('Nuevo Aviso', response.error);

                $('#ingresarad').prop('disabled', false).css({'background-color': 'rgb(0, 178, 255, 1)'}).html('Ingresar');
            },
            error: (jqXHR, estado, outputError) => {

                console.log(jqXHR,estado, outputError);
            }
        });
    } 
    else {

        console.log("Fuera bot hijueputa!!!");
    }
}

function cerrarSesion() {

    $.ajax({

        type: "POST",
        url: "backend/login/cerrarsesion.php",
        data: true,
        processData: false,
        contentType: JSON,
        success: respuesta => {

            if (respuesta.error === undefined) createHeaderPopup('Nuevo Aviso', respuesta.exito, 'index.php');

            else createHeaderPopup('Nuevo Aviso', respuesta.error, 'index.php');
        },
        error: (jqXHR, estado, outputError) => {

            console.log(jqXHR,estado, outputError);
        }
    });
}