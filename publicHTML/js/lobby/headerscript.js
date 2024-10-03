$(() => {
    
    // $('#btnopciones').append($('#opcionescontainer > ul'));
    // $('#opcionescontainer > ul').remove();

    $('#navmobile').show().addClass('marboliviano');

    $('#navmobile a#btnopciones').remove();

    $(window).on('scroll', checkButtonPosition);
    $(window).on('scroll', checkHeaderPosition);

    $('#btnopciones').on('click', () => {
        
        if($('#opcionescontainer .menuperfilm').hasClass('visible')) desplegarMenuPerfilMobile();

        else desplegarMenu();
    });

    $('#btnup').on('click', gototop).hide();

    $('#iniciom, #nosotrosm, #serviciosm, #contactom, #perfilm').on('click', () => desplegarMenu());

    $('#btnperfil').on('click', desplegarMenuPerfil);

    $('#btnperfilm').on('click', desplegarMenuPerfilMobile);

    $('#btnperfil ul #iniciar, #opcionescontainer .menuperfilm #iniciar').on('click', () => changePage(() => window.location.href = 'login.php?estado=1'));

    $('#btnperfil ul #registrarse, #opcionescontainer .menuperfilm #registrarse').on('click', () => changePage(() => window.location.href = 'login.php?estado=2'));
    
    $('#btnperfil ul #cerrarsesion, #opcionescontainer .menuperfilm #cerrarsesion').on('click', async () => {
        
        if(await createConfirmPopup('Confirmación', '¿Estás seguro de cerrar sesión?')) changePage(() => window.location.href = 'login.php?estado=3');
    });

    $('#btnperfil ul #miperfil, #opcionescontainer .menuperfilm #miperfil').on('click', () => changePage(() => window.location.href = 'perfil.php'));
    $('#btnperfil ul #misconsultas, #opcionescontainer .menuperfilm #miperfil').on('click', () => changePage(() => window.location.href = 'perfil.php?estado=2'));
    $('#btnperfil ul #mishorarios, #opcionescontainer .menuperfilm #mishorarios').on('click', () => changePage(() => window.location.href = 'perfil.php?estado=3'));
    $('#btnperfil ul #misinactividades, #opcionescontainer .menuperfilm #misinactividades').on('click', () => changePage(() => window.location.href = 'perfil.php?estado=4'));
    $('#btnperfil ul #administrador, #opcionescontainer .menuperfilm #administrador').on('click',  () => changePage(() => window.location.href = 'administrador.php'));

    $('main').on('click', () => {

        if($('#btnperfil ul, #opcionescontainer .menuperfilm').hasClass('visible')) {

            desplegarMenuPerfil();
            desplegarMenuPerfilMobile();
        }

        if($('#navmobile').hasClass('desplegado')) desplegarMenu();
    });
});

function checkButtonPosition() {

    var posFooter = $('footer').get(0).getBoundingClientRect();
    
    if(window.innerWidth < 600) {
           
        if (posFooter.top <= $(window).height()) $('#btnchat, #btnup').css({ 'animation': 'chatup 0.6s ease forwards' });
        
        else $('#btnchat, #btnup').css({ 'animation': 'chatdown 0.6s ease forwards' });
    }
}

function checkHeaderPosition() {

    let header = $('header');

    let btnup = $('#btnup');

    if ($(window).scrollTop() > 1) {

        header.addClass('comprimido').removeClass('extendido');
        
        btnup.fadeIn();
    } 
    else {

        header.addClass('extendido').removeClass('comprimido');
        
        btnup.fadeOut();
    }
}

function desplegarMenu() {

    if($('#navmobile').hasClass('marboliviano')) $('#navmobile').addClass('desplegado').removeClass('marboliviano');
    
    else $('#navmobile').removeClass('desplegado').addClass('marboliviano');
}

function desplegarMenuPerfil() {

    if($('#btnperfil ul').hasClass('visible')) $('#btnperfil ul').addClass('invisible').removeClass('visible');

    else $('#btnperfil ul').addClass('visible').removeClass('invisible');
}

function desplegarMenuPerfilMobile() {

    if($('#opcionescontainer .menuperfilm').hasClass('visible')) $('#opcionescontainer .menuperfilm').addClass('invisible').removeClass('visible');

    else {
        
        $('#opcionescontainer .menuperfilm').addClass('visible').removeClass('invisible');
        desplegarMenu();
    }
}

function gototop() {

    window.scrollTo({top: 0, behavior: 'smooth'});
}

function changePage(funcion) {

    $('body').fadeOut(300, funcion);
}

function changeView(vista) {

    $('main, footer').fadeOut(300, vista);
    setTimeout(() => $('#hrelleno').css({'height': '10000px'}), 300);
}

function loadView(contenido) {

    setTimeout(() => $('#hrelleno').css({'height': '120px'}), 10);
    $('main').empty().html(contenido).fadeIn(300, () => $('footer').fadeIn(200));
}