$(window).on('scroll', checkButtonPosition);
$(window).on('scroll', checkHeaderPosition);

let footer = document.querySelector('footer');
let button = document.querySelector('#btnchat');

function checkButtonPosition() {

    let bounding = footer.getBoundingClientRect();

    if (bounding.top <= window.innerHeight) {
        
        $('#btnchat').css({animation: 'chatup 0.6s ease forwards'});
    } 
    else {
        
        $('#btnchat').css({animation: 'chatdown 0.6s ease forwards'});
    }
}

function checkHeaderPosition() {

    let header = document.querySelector('header');

    if(window.scrollY > 1) {

        header.className = "comprimido";
    }
    else {

        header.className = "extendido";
    }
}



