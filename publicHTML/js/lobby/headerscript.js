$(() => {
    
    $('#navmobile').show().addClass('marboliviano');

    $('#navmobile a#btnopciones').remove();

    $(window).on('scroll', checkButtonPosition);
    $(window).on('scroll', checkHeaderPosition);

    $('#btnopciones').on('click', desplegarMenu);

    $('#btnup').on('click', gototop).hide();

    $('#iniciom, #nosotrosm, #serviciosm, #contactom, #perfilm').on('click', () => desplegarMenu());

    $('#btnperfil, #btnperfilm').on('click', desplegarMenuPerfil);

    $('#btnperfil ul #iniciar').on('click', () => window.location.href = 'login.php?estado=1');

    $('#btnperfil ul #registrarse').on('click', () => window.location.href = 'login.php?estado=2');

    $('#btnperfil ul #cerrarsesion').on('click', () => window.location.href = 'login.php?estado=3');

    $('#btnperfil ul #miperfil').on('click', () => window.location.href = 'perfil.php');
    $('#btnperfil ul #misconsultas').on('click', () => window.location.href = 'perfil.php?estado=2');

    $('main').on('click', () => {

        if($('#btnperfil ul').hasClass('visible')) desplegarMenuPerfil();

        if($('#navmobile').hasClass('desplegado')) desplegarMenu();
    });
});

function checkButtonPosition() {

    var posFooter = $('footer').get(0).getBoundingClientRect();
    
    if(window.innerWidth < 600) {
           
        if (posFooter.top <= $(window).height()) {
            
            $('#btnchat').css({ 'animation': 'chatup 0.6s ease forwards' });
        } 
        else {

            $('#btnchat').css({ 'animation': 'chatdown 0.6s ease forwards' });
        }
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

function gototop() {

    window.scrollTo({top: 0, behavior: 'smooth'});
}