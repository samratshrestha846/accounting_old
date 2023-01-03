jQuery(document).ready(function($) {


    // Search
    $(".search-box i").on("click", function(){
        $(".search-overlay").toggleClass("search-overlay-active");
    });
    $(".search-overlay-close").on("click", function(){
        $(".search-overlay").removeClass("search-overlay-active");
    });
    // Search End


        

       // Search Toggle
       $("#search, #search1").click(function(){
          $(".search-toggle").slideToggle('fast');
      });
       // Search Toggle End


    // Dropdown
    $(document).ready(function(){
        $(".dropdown").hover(
            function() {
                $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true,true).slideDown("fast");
                $(this).toggleClass('open');
            },
            function() {
                $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true,true).slideUp("fast");
                $(this).toggleClass('open');
            }
            );
    });
    // Dropdown End



    // News
    $('#services').owlCarousel({
        loop:true,
        dots:false,
        margin:30,
        autoplay:true,
        autoplayTimeout:3000,
        nav:true,
        navText : ["<i class='las la-angle-left'></i>","<i class='las la-angle-right'></i>"],
        responsive:{
            0:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:3
            }
        }
    })
    // News End



    // News
    $('#partner').owlCarousel({
        loop:true,
        dots:false,
        margin:20,
        autoplay:false,
        autoplayTimeout:3000,
        nav:false,
        navText : ["<i class='las la-angle-left'></i>","<i class='las la-angle-right'></i>"],
        responsive:{
            0:{
                items:2
            },
            768:{
                items:3
            },
            992:{
                items:5
            }
        }
    })
    // News End



    // Header active
    var yourNavigation = $(".header");
    stickyDiv = "active";
    yourHeader = $('.header-part').height();

    $(window).scroll(function() {
        if( $(this).scrollTop() > yourHeader ) {
            yourNavigation.addClass(stickyDiv);
        } else {
            yourNavigation.removeClass(stickyDiv);
        }
    });

    // Header Active End


    // Scroll Top Js
    $(function(){
            // Scroll Event
            $(window).on('scroll', function(){
                var scrolled = $(window).scrollTop();
                if (scrolled > 600) $('.go-top').addClass('active');
                if (scrolled < 600) $('.go-top').removeClass('active');
            });
            // Click Event
            $('.go-top').on('click', function() {
                $("html, body").animate({ scrollTop: "0" },  300);
            });
        });

        // WOW Animation JS
        if($('.wow').length){
            var wow = new WOW({
                mobile: false
            });
            wow.init();
        }
    // Scroll Top Js ENd



    // News
    $('#activities').owlCarousel({
        loop:true,
        dots:false,
        margin:30,
        autoplay:true,
        autoplayTimeout:3000,
        nav:true,
        navText : ["<i class='las la-angle-left'></i>","<i class='las la-angle-right'></i>"],
        responsive:{
            0:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:3
            }
        }
    })
    // News End



    // H-Gallery
    $('#h-gallery').owlCarousel({
        loop:true,
        dots:false,
        margin:30,
        autoplay:true,
        autoplayTimeout:3000,
        nav:false,
        navText : ["<i class='las la-angle-left'></i>","<i class='las la-angle-right'></i>"],
        responsive:{
            0:{
                items:1
            },
            768:{
                items:3
            },
            992:{
                items:4
            }
        }
    })
    // H-Gallery End



    // Thumbnail Slider
    $('#image-gallery').lightSlider({
        gallery: true,
        item: 1,
        thumbItem: 6,
        slideMargin: 0,
        thumbMargin: 10,
        speed: 500,
        auto: true,
        loop: true,
        keyPress: true,
        controls: true,
        enableTouch: true,
        verticalHeight: 450,
        prevHtml: '<i class="fas fa-angle-left"></i>',
        nextHtml: '<i class="fas fa-angle-right"></i>',
        responsive: [{
            breakpoint: 767,
            settings: {
                thumbItem: 4,
            }
        },
        {
            breakpoint: 575,
            settings: {
                thumbItem: 3,
            }
        }
        ],
        onSliderLoad: function() {
            $('#image-gallery').removeClass('cS-hidden');
        }
    });

        // Thumbnail Slider End




        // Gallery
        $(document).ready(function(){
          $('#lightgallery').lightGallery();
      });
    // Gallery End



    // Date Picker
    $( function() {
        $( "#datepicker" ).datepicker();
    } );
    // Date Picker End


    // Header active
    var yourNavigation = $("#header");
    stickyDiv = "active";
    yourHeader = $('#header').height();

    $(window).scroll(function() {
        if( $(this).scrollTop() > yourHeader ) {
            yourNavigation.addClass(stickyDiv);
        } else {
            yourNavigation.removeClass(stickyDiv);
        }
    });

    // Header Active End


    // Scroll Top Js
    $(function(){
            // Scroll Event
            $(window).on('scroll', function(){
                var scrolled = $(window).scrollTop();
                if (scrolled > 50) $('.go-top').addClass('active');
                if (scrolled < 50) $('.go-top').removeClass('active');
            });
            // Click Event
            $('.go-top').on('click', function() {
                $("html, body").animate({ scrollTop: "0" },  50);
            });
        });

        // WOW Animation JS
        if($('.wow').length){
            var wow = new WOW({
                mobile: false
            });
            wow.init();
        }
    // Scroll Top Js ENd



    // Skip Ads
    $('.skip-ads-btn').on('click', function() {
        $('.skip-ads-col').addClass('active');
    });

    $(function() {
        setTimeout(function() { $(".skip-ads-col").fadeOut(1000); }, 10000)

    })
        // Skip Ads End




        // Animation
        AOS.init();
        // Animation End


    // Keyboard
    $('.keyboard')
    .keyboard({
        layout : 'num',
            restrictInput : true, // Prevent keys not in the displayed keyboard from being typed in
            preventPaste : true,  // prevent ctrl-v and right click
            autoAccept : true,
            alwaysOpen: false,
            usePreview: false
        })

    });
