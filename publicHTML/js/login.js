$(() => {

    cargarVistaLogin(); 
});

function cargarVistaLogin() {

    window.scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistaslogin/vistalogin.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Login'");

        $('#btnregistrarsel').on('click', cargarVistaRegistro);
    });
}

function cargarVistaRegistro() {

    window.scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistaslogin/vistaregistro.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Registro'");
    });

    $('html,body').css("height", "unset");
}