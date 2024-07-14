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
        $('#iniadmin').on('click', cargarVistaLoginAdmin)
        $('#ingresar').on('click', function (event) {

            event.preventDefault();
            let data = new FormData($('#formLogin')[0]);
            loginConfirm(data);
        });
        $('#inemail').focus();
    });
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

    $('html, body').css("height", "100%");
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

    if ($('#jejeje').val() === '') {
        $.ajax({
            
            type: "POST",
            url: "backend/login/loginmanager.php",
            data: datos,
            processData: false,
            contentType: false,
            success: response => {
               
                if (response.error === undefined) {
                    let titulo = "Nuevo Aviso";
                    $('#div-mensaje-popup').hide(); 
                    $.get("popupmensaje.php ? Contenido="+response.enviar+"&Aviso="+titulo+"", data => {
                        $("#div-mensaje-popup").fadeIn(500);
                        $('#div-mensaje-popup').html(data);
                        $('#btnCerrar').on("click",function () { 
                            $("#div-mensaje-popup").fadeOut(500);
                            window.location.href = "index.php?iniciado=1";
                         });
                    });
                    
                } 
                else {

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

                console.error(estado, outputError);
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

                if (response.error === undefined) {
                    let titulo = "Nuevo Aviso";
                    $("#div-mensaje-popup").hide(); 
                    $.get("popupmensaje.php ? Contenido="+response.registrar+"&Aviso="+titulo+"", data => {
                        $("#div-mensaje-popup").fadeIn(500);
                        $('#div-mensaje-popup').html(data);
                        $('#btnCerrar').on("click",function () {
                            $("#div-mensaje-popup").fadeOut(500);
                         });
                    });
                } 
                else {
                    let titulo = "Nuevo Aviso";
                    $("#div-mensaje-popup").hide();
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

                if (response.error === undefined) {
                    let titulo = "Nuevo Aviso"; 
                    $('#div-mensaje-popup').hide(); 
                    $.get("popupmensaje.php ? Contenido="+response.admin+"&Aviso="+titulo+"", data => {
                        $("#div-mensaje-popup").fadeIn(500);
                        $('#div-mensaje-popup').html(data);
                        $('#btnCerrar').on("click",function () { 
                            $("#div-mensaje-popup").fadeOut(500);
                            window.location.href = "administrador.php";
                         });
                    });             

                } 
                else {

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

                console.error(estado, outputError);
            }
        });
    } 
    else {

        console.log("Fuera bot hijueputa!!!");
    }
}