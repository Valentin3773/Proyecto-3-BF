let mdesplegado = false;

$(() => {
    
    $('#navmobile').show().addClass('marboliviano');

    $(window).on('scroll', checkButtonPosition);
    $(window).on('scroll', checkHeaderPosition);

    $('#btnopciones').on('click', desplegarMenu);

    $('#btnup').on('click', gototop).hide();

    $('#iniciom, #serviciosm, #contactom, #perfilm').on('click', () => desplegarMenu());

    $('#btnperfil').on('click', desplegarMenuPerfil);

    $('#btnperfil ul #iniciar').on('click', () => window.location.href = 'login.php?estado=1');

    $('#btnperfil ul #registrarse').on('click', () => window.location.href = 'login.php?estado=2');
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
    let mnav = $('#navmobile');

    let btnup = $('#btnup');

    if ($(window).scrollTop() > 1) {

        header.addClass('comprimido').removeClass('extendido');
        mnav.css({'top': '100px'});

        btnup.fadeIn();
    } 
    else {

        header.addClass('extendido').removeClass('comprimido');
        mnav.css({'top': '120px'});

        btnup.fadeOut();
    }
}

function desplegarMenu() {

    if(!mdesplegado) {

        $('#navmobile').addClass('desplegado').removeClass('marboliviano');

        mdesplegado = true;
    }
    else {

        $('#navmobile').removeClass('desplegado').addClass('marboliviano');

        mdesplegado = false;
    }
}

function desplegarMenuPerfil() {

    if($('#btnperfil ul').hasClass('visible')) $('#btnperfil ul').addClass('invisible').removeClass('visible');

    else $('#btnperfil ul').addClass('visible').removeClass('invisible');
}

function gototop() {

    window.scrollTo({top: 0, behavior: 'smooth'});
}