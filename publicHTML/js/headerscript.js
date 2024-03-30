window.addEventListener('scroll', checkButtonPosition);

let footer = document.querySelector('footer');
let button = document.querySelector('#btnchat');

function checkButtonPosition() {
    let bounding = footer.getBoundingClientRect();

    if (bounding.top <= window.innerHeight) {
        document.querySelector('#btnchat').style.animation = 'chatup 0.6s ease forwards';
        console.log("Poroto")
    } else {
        document.querySelector('#btnchat').style.animation = 'chatdown 0.6s ease forwards';

    }
}

