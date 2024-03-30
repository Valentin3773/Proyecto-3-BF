window.addEventListener('scroll', checkButtonPosition);
window.addEventListener('scroll', checkHeaderPosition);

let footer = document.querySelector('footer');
let button = document.querySelector('#btnchat');

function checkButtonPosition() {

    let bounding = footer.getBoundingClientRect();

    if (bounding.top <= window.innerHeight) {
        
        document.querySelector('#btnchat').style.animation = 'chatup 0.6s ease forwards';
    } 
    else {
        
        document.querySelector('#btnchat').style.animation = 'chatdown 0.6s ease forwards';
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



