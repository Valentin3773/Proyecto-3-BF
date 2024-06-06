$(() => {

    cargarVistaLogin(); 
});

function cargarVistaLogin() {

    window.scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistaslogin/vistalogin.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Login'");

        $('#btnregistrarsel').on('click', cargarVistaRegistro);

        $('#inemail').focus();
    });
}

function cargarVistaRegistro() {

    window.scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistaslogin/vistaregistro.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Registro'");

        $('#innombre').focus();
    });

    $('html, body').css("height", "unset");
}