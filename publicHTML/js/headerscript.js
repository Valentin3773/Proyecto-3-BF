$(window).on('scroll', checkButtonPosition);
$(window).on('scroll', checkHeaderPosition);

$('#eujames')[0].volume = 0;

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

    if ($(window).scrollTop() > 1) {

        header.addClass('comprimido').removeClass('extendido');
    } 
    else {

        header.addClass('extendido').removeClass('comprimido');
    }
}



