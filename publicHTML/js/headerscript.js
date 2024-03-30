window.addEventListener('scroll', checkChatIcon);

let bounding = document.querySelector('footer').getBoundingClientRect();

function checkChatIcon() {
    
    if(bounding.top <= window.innerHeight) {

        console.log("egjewige");
        document.querySelector('#btnchat').style.bottom = '40px';
    }
    else {

        console.log("pepepe");
        document.querySelector('#btnchat').style.bottom = '20px';
    }
}