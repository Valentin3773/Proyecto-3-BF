$(() => {

    cargarVistaInicio();

    $('#logo').on('click', cargarVistaInicio)

    $('#inicio').on('click', cargarVistaInicio);

    $('#servicios').on('click', cargarVistaServicios);

    $('#contacto').on('click', cargarVistaContacto);

});

function cargarVistaInicio() {

    $.get("http://127.0.0.1:5500/publicHTML/vistainicio.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Inicio'");
    });

    $('#inicio').css({ 'text-decoration': 'underline' });
    $('#servicios').css({ 'text-decoration': 'none' });
    $('#contacto').css({ 'text-decoration': 'none' });
}

function cargarVistaServicios() {

    $.get("http://127.0.0.1:5500/publicHTML/vistaservicios.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Servicios'");
    });

    $('#servicios').css({ 'text-decoration': 'underline' });
    $('#inicio').css({ 'text-decoration': 'none' });
    $('#contacto').css({ 'text-decoration': 'none' });
}

function cargarVistaContacto() {

    $.get("http://127.0.0.1:5500/publicHTML/vistacontacto.php", data => {

        $('main').html(data);
        console.log("Cargando vista de 'Contacto'");
    });

    $('#contacto').css({ 'text-decoration': 'underline' });
    $('#inicio').css({ 'text-decoration': 'none' });
    $('#servicios').css({ 'text-decoration': 'none' });
}
$(document).ready(function () {
    var imagenes = 3;

    $('.slider-inner').css('transform', 'translateX(-100%)');

    let index = 1;

    setInterval(function () {
        let porcentaje = index * -100;

        $('.slider-inner').css('transform', 'translateX(' + porcentaje + '%)');
        index++;

        if (index >= imagenes) {
            index = 0;
        }

    }, 4000);
});


