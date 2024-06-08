$(() => {

    cargarVistaLogin(); 
});

function cargarVistaLogin() {

    window.scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistaslogin/vistalogin.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Login'");

        $('#btnvolver').on('click', volverInicio);
        $('#btnregistrarsel').on('click', cargarVistaRegistro);
        $('#inemail').focus();


    });


}

function volverInicio() {

    window.scrollTo({top: 0, behavior: 'smooth'});
    window.location.href = "index.php"; 
    
}


function cargarVistaRegistro() {

    window.scrollTo({top: 0, behavior: 'smooth'});

    $.get("vistas/vistaslogin/vistaregistro.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Registro'");

        $('#btnvolver').on('click', volverInicio);
        $('#innombre').focus();
    });

    $('html, body').css("height", "unset");
}