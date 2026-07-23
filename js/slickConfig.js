$('.webSlider').slick({
    centerMode: true,
    slidesToScroll: 1,
    dots: true,
    infinite: true,
    //variableWidth: true,
    variableHeight: true,
    centerPadding: '30%',
    slidesToShow: 1,
    //autoplay: true,
    //autoplaySpeed: 6000,
    responsive: [{
            breakpoint: 1200,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '22%',
                slidesToShow: 1
            }
        },
        {
            breakpoint: 810,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '20%',
                slidesToShow: 1
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '15%',
                slidesToShow: 1
            }
        }
    ]
});
$('.projectsSlider').slick({
    centerMode: true,
    slidesToScroll: 1,
    dots: true,
    infinite: true,
    // variableWidth: true,
    // variableHeight: true,
    centerPadding: '30%',
    slidesToShow: 1,
    //autoplay: true,
    //autoplaySpeed: 6000,
    responsive: [{
            breakpoint: 1200,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '22%',
                slidesToShow: 1
            }
        },
        {
            breakpoint: 810,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '20%',
                slidesToShow: 1
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '15%',
                slidesToShow: 1
            }
        }
    ]
});