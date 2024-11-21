$(() => {

    switch ($('main').data('vista')) {

        case 1: changeView(cargarVistaLogin); break;

        case 2: changeView(cargarVistaRegistro); break;

        case 3: changeView(cerrarSesion); break;

        case 4: 
        
        history.pushState(null, 'Login', '/login');

        createHeaderPopup('Nuevo Aviso', 'El código de verificación no es válido', () => changePage('index.php')); 
        
        break;

        case 5: 

        history.pushState(null, 'Login', '/login');
        
        createHeaderPopup('Nuevo Aviso', 'Su cuenta se ha verificado y activado', () => changePage('index.php')); 
        
        break;

        case 6: 

        history.pushState(null, 'Login', '/login');
        
        createHeaderPopup('Nuevo Aviso', 'Lo sentimos, no puede iniciar sesión con Google porque no existe un usuario asociado al correo electrónico', () => changeView(cargarVistaLogin), 10); 
        
        break;

        case 7: changeView(cargarVistaLoginAdmin); break;

        case 8: changeView(cargarVistaRecuperarPass); break;

        case 9: changeView(cargarVistaRecuperarEmail); break;
    }

    history.replaceState({ path: 'login.php' }, '', 'login.php');

    $(document).on('keydown', evt => {

        if(evt.key === "Escape" || evt.keyCode === 27) $('#btnvolver').click();
    });
});

function cargarVistaLogin() {

    history.pushState(null, 'Login', '/login');

    window.scrollTo({ top: 0, behavior: 'smooth' });

    $.get("vistas/vistaslogin/vistalogin.php", data => {

        loadView(data);
        console.log("Cargando vista de 'Login'");

        $('#btnvolver').off().on('click', () => changeView(volverInicio));
        $('.mostrarpass').off().on('click', function () {
            
            togglePassIcon([$('#incontrasenia')], $(this).attr('id'));
        });
        $('#btnregistrarsel').on('click', () => changeView(cargarVistaRegistro));
        $('#iniadmin').on('click', () => changeView(cargarVistaLoginAdmin));
        $('#olvidarpass').on('click', () => changeView(cargarVistaRecuperarPass));
        $('#googlebutton').on('click', () => changePage('loginGoogle/index.php'));
        $('#ingresar').on('click', function (event) {

            event.preventDefault();
            let data = new FormData($('#formLogin')[0]);

            $('#ingresar').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, .5)' }).html('<i class="fas fa-spinner fa-pulse"></i>');

            loginConfirm(data);
        });
        $('#inemail').focus();
    });
    
    $('html, body').css({ 'height': '100%' });
    $('main').css({ 'overflow': 'hidden' });
}

function cargarVistaRegistro() {

    history.pushState(null, 'Login', '/registro');

    window.scrollTo({ top: 0, behavior: 'smooth' });

    $.get("vistas/vistaslogin/vistaregistro.php", data => {

        loadView(data);
        console.log("Cargando vista de 'Registro'");

        $('#btnvolver').off().on('click', volverLogin);
        $('.mostrarpass').off().on('click', function () {
            
            if($(this).attr('id') === '1') togglePassIcon([$('#incontrasenia')], $(this).attr('id'));

            else togglePassIcon([$('#inconcontrasenia')], $(this).attr('id'));
        });
        $('#btnregistrarsel').on('click', function (event) {

            event.preventDefault();
            let data = new FormData($('#formRegistrar')[0]);

            $('#btnregistrarsel').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, .5)' }).html('<i class="fas fa-spinner fa-pulse"></i>');

            registerConfirm(data);
        });
    });

    $('main').css({
        
        'overflow-y': 'scroll',
        'overflow-x': 'hidden'
    });
}

function cargarVistaLoginAdmin() {

    history.pushState(null, 'Login Admin', '/login/administrador');

    window.scrollTo({ top: 0, behavior: 'smooth' });

    $.get("vistas/vistaslogin/vistaloginadmin.php", data => {

        loadView(data);
        console.log("Cargando vista de 'Login'");
        $('#btnvolver').off().on('click', volverLogin);
        $('.mostrarpass').off().on('click', function () {
            
            togglePassIcon([$('#incontrasenia')], $(this).attr('id'));
        });
        $('#ingresarad').on('click', function (event) {

            event.preventDefault();
            let data = new FormData($('#formLogin')[0]);

            $('#ingresarad').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, .5)' }).html('<i class="fas fa-spinner fa-pulse"></i>');

            loginAdminConfirm(data);
        });
    });
    $('html, body').css("height", "100%");
}

function cargarVistaRecuperarPass() {

    history.pushState(null, 'Recuperar', '/login/recuperar');

    window.scrollTo({ top: 0, behavior: 'smooth' });

    $.get("vistas/vistaslogin/vistarecuperarpass.php", data => {

        loadView(data);
        $('#btnvolver').off().on('click', volverLogin);
        $('.btnRecu').off("click");
        $('.btnRecu').on('click', function (e) {

            e.preventDefault();

            $email = $('#inEmail').val();
            recucontra($email);
        });
        $('.btnEmail').off("click");
        $('.btnEmail').on('click', () => changeView(cargarVistaRecuperarEmail));
    });

    $('main').css({
        
        'overflow-y': 'scroll',
        'overflow-x': 'hidden'
    });
}

function cargarVistaRecuperarEmail() {

    history.pushState(null, 'Recuperar', '/login/recuperar/email');

    $.get("vistas/vistaslogin/vistarecuemail.php", data => {

        loadView(data);
        $('#btnvolver').off().on('click', () => changeView(cargarVistaRecuperarPass));
        $('.btnRecu').on('click', function (e) {

            e.preventDefault();
            recuemail($('#inNombre').val(), $('#inApellido').val(), $('#inDocumento').val());
        });
    });
}

function recuemail($nombre, $apellido, $documento) {

    if ($nombre == "" || $nombre == null) createPopup("Nuevo aviso", "Debe completar los datos");

    else if ($apellido == "" || $apellido == null) createPopup("Nuevo aviso", "Debe completar los datos");

    else if ($documento == "" || $documento == null) createPopup("Nuevo aviso", "Debe completar los datos");

    else {

        $('.btnRecu').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, .5)' }).html('<i class="fas fa-spinner fa-pulse"></i>');

        let formData = new FormData();
        formData.append('nombre', $nombre);
        formData.append('apellido', $apellido);
        formData.append('documento', $documento);

        $.ajax({

            type: "POST",
            url: "backend/login/recuemail.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {

                console.log(response);

                if(response.exito != undefined) createHeaderPopup('Nuevo aviso', response.exito, () => changeView(cargarVistaLogin));
                
                else createPopup('Nuevo aviso', response.error);
                
                $('.btnRecu').prop('disabled', false).css({ 'background-color': 'rgb(0, 178, 255, 1)' }).html('Recuperar email');
            },
            error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
        });
    }
}

function recucontra($email) {

    let formData = new FormData();
    formData.append('email', $email);

    $('.btnRecu').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, .5)' }).html('<i class="fas fa-spinner fa-pulse"></i>');

    $.ajax({

        type: "POST",
        url: "backend/login/cambiarpass.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {

            if (response.respuesta != undefined) {

                createHeaderPopup("Nuevo aviso", response.respuesta, () => changeView(() => cargarCodigoEmail(response.codigo)));

                $('.btnRecu').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, 1)' }).html('Enviar Código');
            }
            else {

                console.log(response);
                
                createPopup("Nuevo aviso", response.noexiste);

                $('.btnRecu').prop('disabled', false).css({ 'background-color': 'rgb(0, 178, 255, 1)' }).html('Enviar Código');
            }
        },
        error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
    });
}

function verificarCodigo($codigo, $pass, $repass, $secp) {

    $('.btnRecu').prop('disabled', true).css({ 'background-color': 'rgb(0, 178, 255, .5)' }).html('<i class="fas fa-spinner fa-pulse"></i>');

    let formData = new FormData();
    formData.append('codigo', $codigo);
    formData.append('contrasenia', $pass);
    formData.append('recontrasenia', $repass);
    formData.append('secp', $secp);

    $.ajax({

        type: "POST",
        url: "backend/login/verificarcodigo.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {

            if(response.exito != undefined) createHeaderPopup("Nuevo aviso", response.exito, () => changeView(cargarVistaLogin));

            else createPopup("Nuevo aviso", response.error);

            $('.btnRecu').prop('disabled', false).css({ 'background-color': 'rgb(0, 178, 255, 1)' }).html('Cambiar contraseña');
        },
        error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
    });
}

function cargarCodigoEmail($codigo) {

    window.scrollTo({ top: 0, behavior: 'smooth' });

    $.get("vistas/vistaslogin/vistacodigoemail.php", data => {

        loadView(data);

        $('#btnvolver').off().on('click', () => changeView(cargarVistaRecuperarPass));

        $('.mostrarpass').off().on('click', function () {
            
            if($(this).attr('id') === '1') togglePassIcon([$('#inContra')], $(this).attr('id'));

            else togglePassIcon([$('#inConcontra')], $(this).attr('id'));
        });

        $('.btnRecu').on('click', function (e) {

            e.preventDefault();

            if($('#inContra').val() === $('#inConcontra').val()) verificarCodigo($('#inCodigo').val(), $('#inContra').val(), $('#inConcontra').val(), $codigo);
            
            else createPopup("Nuevo Aviso", "Las contraseñas ingresadas no coinciden");
        });
    });

    $('main').css({
        
        'overflow-y': 'scroll',
        'overflow-x': 'hidden'
    });
}
function volverInicio() {

    window.scrollTo({ top: 0, behavior: 'smooth' });
    changePage('index.php');
}

function volverLogin() {

    window.scrollTo({ top: 0, behavior: 'smooth' });
    changeView(cargarVistaLogin);
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

                if (response.error === undefined) createHeaderPopup('Nuevo Aviso', response.enviar, volverInicio);

                else {

                    createPopup('Nuevo Aviso', response.error);
                    $('#ingresar').prop('disabled', false);
                }

                $('#ingresar').css({ 'background-color': 'rgb(0, 178, 255, 1)' }).html('Ingresar');
            },
            error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
        });
    }
    else console.log("Fuera bot hijueputa!!!");
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

                if (response.error === undefined) createHeaderPopup('Nuevo Aviso', response.registrado, () => changeView(cargarVistaLogin));

                else {

                    createPopup('Nuevo Aviso', response.error);
                    $('#btnregistrarsel').prop('disabled', false);
                }

                $('#btnregistrarsel').css({ 'background-color': 'rgb(0, 178, 255, 1)' }).html('Registrarse');
            },
            error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
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

                if (response.error === undefined) createHeaderPopup('Nuevo Aviso', response.admin, volverInicio);

                else {

                    createPopup('Nuevo Aviso', response.error);
                    $('#ingresarad').prop('disabled', false);
                }

                $('#ingresarad').css({ 'background-color': 'rgb(0, 178, 255, 1)' }).html('Ingresar');
            },
            error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
        });
    }
    else console.log("Fuera bot hijueputa!!!");
}

function cerrarSesion() {

    history.pushState(null, 'Login', '/login');

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
        error: (jqXHR, estado, outputError) => console.log(jqXHR, estado, outputError)
    });
}

function togglePassIcon(campos, id) {

    let btnmostrar = $(`.mostrarpass#${id}`);
    let mostrando = btnmostrar.attr('src') === 'img/iconosvg/view.svg';

    if(mostrando) {

        btnmostrar.attr('src', 'img/iconosvg/hide.svg');
        btnmostrar.attr('title', 'Esconder contraseña');

        campos.forEach(elemento => elemento.attr('type', 'text'));
    }
    else {

        btnmostrar.attr('src', 'img/iconosvg/view.svg');
        btnmostrar.attr('title', 'Mostrar contraseña');

        campos.forEach(elemento => elemento.attr('type', 'password'));
    }
}

function changeView(vista) {

    $('main').fadeOut(200, vista);
    $('main')[0].scrollTo({top: 0, behavior: 'smooth'});
}

function loadView(contenido) {

    $('main').empty().html(contenido).fadeIn(200);
}