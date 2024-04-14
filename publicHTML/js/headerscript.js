$(window).on('scroll', checkButtonPosition);
$(window).on('scroll', checkHeaderPosition);



function checkButtonPosition() {

    var posFooter = $('footer').get(0).getBoundingClientRect();
    
    if(window.innerWidth > 1) {
           
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



