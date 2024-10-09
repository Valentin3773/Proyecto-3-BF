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
        $('#iniadmin').on('click', cargarVistaLoginAdmin);
        $('#olvidarpass').on('click', cargarVistaRecuperarPass);
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
        $('#btnvolver').on('click', volverLogin);
        $('#ingresarad').on('click', function (event) {

            event.preventDefault();
            let data = new FormData($('#formLogin')[0]);

            $('#ingresarad').prop('disabled', true).css({'background-color': 'rgb(0, 178, 255, .5)'}).html('<i class="fas fa-spinner fa-pulse"></i>');

            loginAdminConfirm(data);
        });
    });
    $('html, body').css("height", "100%");
}

function cargarVistaRecuperarPass() {

    window.scrollTo({ top: 0, behavior: 'smooth' });

    $.get("vistas/vistaslogin/vistarecuperarpass.php", data => {

        $('#btnvolver').on('click', volverLogin);
        $('main').html(data);
        $('.btnRecu').off("click");
        $('.btnRecu').on('click', function (e) {
            e.preventDefault();
            $email = $('#inEmail').val();
            recucontra($email);
        });
        $('.btnEmail').off("click");
        $('.btnEmail').on('click', function () {
            $.get("vistas/vistaslogin/vistarecuemail.php", data => {
                $('#btnvolver').on('click', volverLogin);
                $('main').html(data);
                $('.btnRecu').on('click', function (e) {
                    e.preventDefault();
                    recuemail($('#inNombre').val(),$('#inApellido').val(),$('#inDocumento').val());
                });
            });
        });
    });
}
function recuemail($nombre,$apellido,$documento){
    if($nombre == "" || $nombre == null) {
        createPopup("Nuevo aviso","Le falto rellenar los datos");
    } else if ($apellido == "" || $apellido == null) {
        createPopup("Nuevo aviso","Le falto rellenar los datos");
    } else if ($documento == "" || $documento == null) {
        createPopup("Nuevo aviso","Le falto rellenar los datos");
    } else {
        let formData = new FormData();
        formData.append('nombre',$nombre);
        formData.append('apellido',$apellido);
        formData.append('documento',$documento);
        $.ajax({
            type: "POST",
            url: "backend/login/recuemail.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                createPopup('Nuevo aviso', response);
            }, 
            error: () => {
                createPopup("No puede ser",'Ocurrio un error :3');
            }
        });
    }
}
function recucontra($email) {
    
    let formData = new FormData();
    formData.append('email', $email);

    $.ajax({

        type: "POST",
        url: "backend/login/cambiarpass.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if(response['respuesta'] != undefined){
                createPopup("Nuevo aviso",response['respuesta']);
                cargarCodigoEmail(response['codigo']);
            } else {
                createPopup("Nuevo aviso",response['noexiste']);
            }
        }, 
        error: () => {
            createPopup("No puede ser",'Ocurrio un error :3');
        }
    });
}
function verificarCodigo($codigo,$pass,$repass,$secp){
   let formData = new FormData();
   formData.append('codigo',$codigo);
   formData.append('contraseña',$pass);
   formData.append('recontraseña',$repass);
   formData.append('secp', $secp);
    $.ajax({

        type: "POST",
        url: "backend/login/verificarcodigo.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            createPopup("Nuevo aviso",response);
        }, 
        error: () => {
            createPopup("Nuevo aviso",'Ocurrio un error :3');
        }
    });
}
function cargarCodigoEmail($codigo){
    window.scrollTo({ top: 0, behavior: 'smooth' });
    $.get("vistas/vistaslogin/vistacodigoemail.php", data => {
        $('main').html(data);
        $('.btnRecu').on('click', function (e) {
            e.preventDefault();
            if($('#inContra').val() === $('#inConcontra').val()){
                verificarCodigo($('#inCodigo').val(),$('#inContra').val(),$('#inConcontra').val(),$codigo);
            } else {
                createPopup("Parece que hubo un malentendido","Las contraseñas ingresadas no coinciden");
            }
        });
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

    console.log(datos);

    if ($('#jejeje').val() === '') {

        $.ajax({
            
            type: "POST",
            url: "backend/login/loginmanager.php",
            data: datos,
            processData: false,
            contentType: false,
            success: response => {
                
                if (response.error === undefined) createHeaderPopup('Nuevo Aviso', response.enviar, 'index.php');

                else {
                    
                    createPopup('Nuevo Aviso', response.error);
                    $('#ingresar').prop('disabled', false);
                }

                $('#ingresar').css({'background-color': 'rgb(0, 178, 255, 1)'}).html('Ingresar');
            },
            error: (jqXHR, estado, outputError) => {

                console.log(jqXHR,estado, outputError);
            }
        });
    } 
    else console.log("Fuera bot hijueputa!!!");
}

async function registerConfirm(datos) {
    
    if ($('#jejeje').val() === '') {

        if(await createConfirmPopup('Confirmación', '¿Estás seguro de crear el usuario?', ['No', 'Sí'])) $.ajax({

            type: "POST",
            url: "backend/login/registromanager.php",
            data: datos,
            processData: false,
            contentType: false,
            success: response => {

                if (response.error === undefined) createHeaderPopup('Nuevo Aviso', response.registrado, cargarVistaLogin);

                else {
                    
                    createPopup('Nuevo Aviso', response.error);
                    $('#btnregistrarsel').prop('disabled', false);
                }

                $('#btnregistrarsel').css({'background-color': 'rgb(0, 178, 255, 1)'}).html('Registrarse');
            },
            error: (jqXHR, estado, outputError) => console.log(jqXHR,estado, outputError)
        });
    }
    else console.log("Fuera bot hijueputa!!!");
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

                if (response.error === undefined) createHeaderPopup('Nuevo Aviso', response.admin, 'index.php');

                else {
                    
                    createPopup('Nuevo Aviso', response.error);
                    $('#ingresarad').prop('disabled', false);
                }

                $('#ingresarad').css({'background-color': 'rgb(0, 178, 255, 1)'}).html('Ingresar');
            },
            error: (jqXHR, estado, outputError) => console.log(jqXHR,estado, outputError)
        });
    } 
    else console.log("Fuera bot hijueputa!!!");
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
        error: (jqXHR, estado, outputError) => console.log(jqXHR,estado, outputError)
    });
}