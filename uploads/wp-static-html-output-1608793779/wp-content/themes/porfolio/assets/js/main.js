$( document ).ready(function() {
    function onReady(callback) {
        var intervalId = window.setInterval(function() {
          if ($('body') !== undefined) {
            window.clearInterval(intervalId);
            callback.call(this);
          }
        }, 1500);
    }
    
    onReady(function() {
        $('#loading .line-scale-pulse-out').fadeOut( 400, "linear");
        $('#loading').delay( 500 ).fadeOut( 400, "linear");
        AOS.init({disable: 'mobile'});
        let bannerNode = document.querySelector('[alt="www.000webhost.com"]').parentNode.parentNode;
        bannerNode.parentNode.removeChild(bannerNode);
    });

    function fixedNavToggler(){
        if ($(window).scrollTop() > $('.navbar-toggler').outerHeight()){
            $('.navbar-toggler').addClass('fixed')
        }else{
            $('.navbar-toggler').removeClass('fixed')
        }
    };
    fixedNavToggler();
    
    var countTime = 0;
    function countStat(){
        var oTop = $('.about__stats').offset().top - window.innerHeight;
        if (countTime == 0 && $(window).scrollTop() > oTop) {
            $('.about__stats__stat__number').each(function () {
                var $this = $(this);
                jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
                duration: 2000,
                easing: 'swing',
                step: function () {
                    $this.text(Math.ceil(this.Counter));
                }
                });
            });
            countTime = 1;
        }
    }

    function showBtnScrollToTop(){
        if ($(window).scrollTop() > $('#home').outerHeight()/2){
            $('#scrollToTop').addClass('show')
        }else{
            $('#scrollToTop').removeClass('show')
        }
    };
    showBtnScrollToTop()

    $("#scrollToTop").on('click', function() {
        $("body, html").animate({scrollTop:0}, 800);
    });

    $(window).scroll(function() {
        fixedNavToggler();
        showBtnScrollToTop();
        countStat();
    });

    $('.navbar-toggler,#overlay').click(function() {
        $('#sidebar').toggleClass("open");
    });

    $('a[href*="#"]').on('click', function(event) {
        var element = $(this).attr('href');
        $("body, html").animate({scrollTop: $( element ).offset().top}, 700);
    });

    $('.clients__list').slick({
        infinite: true,
        slidesToShow: 6,
        slidesToScroll: 6,
        arrows:false,
        dots:true,
    });

    $('.clients__testimonials').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows:true,
        dots:false,
        responsive: [
            {
              breakpoint: 992,
              settings: {
                arrows:false,
                dots:true
              }
            }
        ]
    });

});
