$(window).on('scroll ', checkButtonPosition);
$(window).on('scroll', checkHeaderPosition);

let footer = $('footer');
let button = $('#btnchat');

function checkButtonPosition() {
    var posFooter = $('footer').get(0).getBoundingClientRect();

    if (posFooter.top <= $(window).height()) {
        $('#btnchat').css({ 'animation': 'chatup 0.6s ease forwards' });

    } else {
        $('#btnchat').css({ 'animation': 'chatdown 0.6s ease forwards' });

    }

}


function checkHeaderPosition() {
    var header = $('header');

    if ($(window).scrollTop() > 1) {
        header.addClass('comprimido').removeClass('extendido');
    } else {
        header.addClass('extendido').removeClass('comprimido');
    }
}



