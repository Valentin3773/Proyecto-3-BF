var uniqueServicios = [];
var visto = new Set();
var serviciosiniciados = false;

function iniciarServicios(servicios) {

    if(serviciosiniciados) {console.log(serviciosiniciados); return}
    else serviciosiniciados = true;

    
    for (let i = 0; i < servicios.length; i++) {

        if (!visto.has(servicios[i].numero)) {

            visto.add(servicios[i].numero);
            uniqueServicios.push(servicios[i]);
            
        }
        
    } 

    uniqueServicios.forEach(elemento => {

        let slideservicio = $(`<div id="${elemento.numero}" class="card btn${elemento.nombre}"></div>`);

        if (elemento.icono != null) {
            slideservicio.append(`<img src="backend/almacenamiento/iconservice/${elemento.icono}" alt="${elemento.nombre}" class="card-img-top img-thumbnail">`);
        } else {
            slideservicio.append(`<img src="img/logaso.png" alt="${elemento.nombre}" class="card-img-top img-thumbnail">`);
        }

        slideservicio.append(`<div class="card-body"><h3 class="card-title text-center m-0">${elemento.nombre}</h3></div>`);

        $('.navslider').append(slideservicio);

        let secservicio = $(`<section id="${elemento.numero}" class="mt-5 gx-0"><div class="sectitcontainer text-center"><h2 class="sectitulo">${elemento.nombre}</h2></div><div class="seccontainer row mt-5 gx-0"><div class="col-xl-1 col-lg-1"></div><article class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 d-flex align-items-center p-3"><p>${elemento.descripcion}</p></article><aside class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 d-flex justify-content-center align-items-center"></aside><div class="col-xl-1 col-lg-1"></div></div></section>`);

        if (elemento.imagen != null) {
            secservicio.find('aside').html(`<img src="backend/almacenamiento/imgservice/${elemento.imagen}" alt="${elemento.nombre}" class="card-img-top img-thumbnail">`);
        } else {
            secservicio.find('aside').html(`<img src="img/logaso.png" alt="${elemento.nombre}" class="card-img-top img-thumbnail">`);
        }

        $('main #sectionservicecontainer').append(secservicio);
    });

    setTimeout(() => {

        $('.navslider').slick({

            dots: false,
            infinite: true,
            autoplay: true,
            appendArrows: $('.navslider'),
            autoplaySpeed: 2000,
            speed: 500,
            slidesToShow: 7,
            slidesToScroll: 1,
            responsive: [

                {
                    breakpoint: 2400,
                    settings: {

                        slidesToShow: 6
                    }
                },
                {
                    breakpoint: 2000,
                    settings: {

                        slidesToShow: 5
                    }
                },
                {
                    breakpoint: 1600,
                    settings: {

                        slidesToShow: 4
                    }
                },
                {
                    breakpoint: 1300,
                    settings: {

                        slidesToShow: 3,
                        arrows: false
                    }
                },
                {
                    breakpoint: 800,
                    settings: {

                        slidesToShow: 1,
                        arrows: false
                    }
                }
            ]
        });

    }, 1000);

    $('.slick-prev').empty();
    $('.slick-next').empty();

    addServListeners(uniqueServicios);
}

function addServListeners(servicios) {

    servicios.forEach(elemento => {

        $(`.navslider #${elemento.numero}`).off().on('click', evt => window.scrollTo({

            top: $(`#sectionservicecontainer #${elemento.numero}`)[0].offsetTop - 160,
            behavior: 'smooth'

        }));
    });

    let blocked = false;
    let blockTimeout = null;
    let prevDeltaY = 0;

    $(".navslider").off().on('mousewheel DOMMouseScroll wheel', function (evt) {

        let deltaY = evt.originalEvent.deltaY;
        evt.preventDefault();
        evt.stopPropagation();

        clearTimeout(blockTimeout);
        blockTimeout = setTimeout(() => blocked = false, 50);

        if (deltaY > 0 && deltaY > prevDeltaY || deltaY < 0 && deltaY < prevDeltaY || !blocked) {

            blocked = true;
            prevDeltaY = deltaY;

            if (deltaY > 0) $(this).slick('slickNext');
            
            else $(this).slick('slickPrev');
        }
    });
}