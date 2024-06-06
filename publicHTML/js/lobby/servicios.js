function iniciarServicios() {

    addServListeners();

    setTimeout(() => $('.navslider').slick({

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

                    slidesToShow: 2,
                    arrows: false
                }
            },
            {
                breakpoint: 400,
                settings: {

                    slidesToShow: 1,
                    arrows: false
                }
            }
        ]
        
    }), 0);

    $('.slick-prev').html('');
    $('.slick-next').html('');
}

function addServListeners() {

    $('.btnortodoncia').on('click', evt => {

        window.scrollTo({

            top: $('#ortodoncia')[0].offsetTop - 160,
            behavior: 'smooth'
        });
    });
    $('.btnodontopediatria').on('click', evt => {

        window.scrollTo({

            top: $('#odontopediatria')[0].offsetTop - 160,
            behavior: 'smooth'
        });
    });
    $('.btnprotesis').on('click', evt => {

        window.scrollTo({

            top: $('#protesis')[0].offsetTop - 160,
            behavior: 'smooth'
        });
    });
    $('.btnoperatoria').on('click', evt => {

        window.scrollTo({

            top: $('#operatoria')[0].offsetTop - 160,
            behavior: 'smooth'
        });
    });
    $('.btnortopedia').on('click', evt => {

        window.scrollTo({

            top: $('#ortopedia')[0].offsetTop - 160,
            behavior: 'smooth'
        });
    });
    $('.btncirugia').on('click', evt => {

        window.scrollTo({

            top: $('#cirugia')[0].offsetTop - 160,
            behavior: 'smooth'
        });
    });
    $('.btnimplantes').on('click', evt => {

        window.scrollTo({

            top: $('#implantes')[0].offsetTop - 160,
            behavior: 'smooth'
        });
    });

    let blocked = false;
    let blockTimeout = null;
    let prevDeltaY = 0;

    $(".navslider").on('mousewheel DOMMouseScroll wheel', function (evt) {

        let deltaY = evt.originalEvent.deltaY;
        evt.preventDefault();
        evt.stopPropagation();

        clearTimeout(blockTimeout);
        blockTimeout = setTimeout(() => blocked = false, 50);

        if (deltaY > 0 && deltaY > prevDeltaY || deltaY < 0 && deltaY < prevDeltaY || !blocked) {

            blocked = true;
            prevDeltaY = deltaY;

            if (deltaY > 0) {

                $(this).slick('slickNext');
            }
            else {

                $(this).slick('slickPrev');
            }
        }
    });
}