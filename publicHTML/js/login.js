$(() => {

    cargarVistaLogin();
});

function cargarVistaLogin() {

    window.scrollTo({ top: 0, behavior: 'smooth' });

    $.get("vistas/vistaslogin/vistalogin.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Login'");

        $('#btnvolver').on('click', volverInicio);
        $('#btnregistrarsel').on('click', cargarVistaRegistro);
        $('#ingresar').on('click', function (event) {

            event.preventDefault();
            let data = new FormData($('#formLogin')[0]);
            loginComfirm(data);
        });
        $('#inemail').focus();
    });
}

function loginComfirm(datos) {

    if ($('#jejeje').val() === '') {

        $.ajax({

            type: "POST",
            url: "backend/loginmanager.php",
            data: datos,
            processData: false,
            contentType: false,
            success: response => {

                if (response.error === undefined) {

                    window.location.href = "index.php?iniciado=1";
                } 
                else {

                    alert(response.error);
                }
            },
            error: (jqXHR, estado, outputError) => {

                console.error(estado, outputError);
            }
        });
    } 
    else {

        console.log("Fuera bot hijueputa!!!");
    }
}

function volverInicio() {

    window.scrollTo({ top: 0, behavior: 'smooth' });
    window.location.href = "index.php";
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
            registerComfirm(data);
        });
        $('#innombre').focus();
    });

    $('html, body').css("height", "unset");
}

function volverLogin() {

    window.scrollTo({ top: 0, behavior: 'smooth' });
    window.location.href = "login.php";
}

function registerComfirm(datos) {

    if ($('#jejeje').val() === '') {

        $.ajax({

            type: "POST",
            url: "backend/registromanager.php",
            data: datos,
            processData: false,
            contentType: false,
            success: response => {

                if (response.error === undefined) {

                    alert(response.registrado);
                } 
                else {

                    alert(response.error);
                }
            },
            error: (jqXHR, estado, outputError) => {

                console.error(estado, outputError);
            }
        });
    } 
    else {

        console.log("Fuera bot hijueputa!!!");
    }
}