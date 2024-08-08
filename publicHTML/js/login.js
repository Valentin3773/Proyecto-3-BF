$(() => {

    let estado = $('main').data('vista');

    switch(estado) {

        case 1: cargarVistaLogin(); break;

        case 2: cargarVistaRegistro(); break;
    }
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
            registerConfirm(data);
        });
        $('#innombre').focus();
    });

    $('html, body').css("height", "unset");
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
            loginAdminConfirm(data);
        });
        $('#inemail').focus();
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

                if (response.error === undefined) createPopup('Nuevo Aviso', response.enviar);

                else createPopup('Nuevo Aviso', response.error)
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

                if (response.error === undefined) createPopup('Nuevo Aviso', response.registrado);

                else createPopup('Nuevo Aviso', response.error)
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

                if (response.error === undefined) createPopup('Nuevo Aviso', response.admin);

                else createPopup('Nuevo Aviso', response.error)
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