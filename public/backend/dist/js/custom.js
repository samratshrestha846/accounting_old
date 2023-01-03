jQuery(document).ready(function ($) {

    // Mobile Nav
    $("#menu1").metisMenu();
    // MObile Nav End

    // Side menubar
    $("#close-btn, .toggle-btn").click(function () {
        $("#mySidenav, body").toggleClass("active");
    });



    // Keyword Shortcut js

        // Search Shortcut Key
        function search() {
            var search = $('.search');
            search.val('');
            search.focus();
        }
        // Search Shortcut Key End

        // Up Down Highlight Menu
        function highLight(letter) {
            var navbar  = $('.nav-sidebar>.nav-item');
            var hlight = $('.highlight');
            var index  = navbar.index(hlight);

            // console.log('work');

            navbar.eq(index).removeClass('highlight');

            if ( letter === 'ctrl+up' ) {
                navbar.eq(index - 1).addClass('highlight');
            } else if ( letter === 'ctrl+down' ) {
                navbar.eq(index + 1).addClass('highlight');
            }
        }
        // Up Down Higlight Menu End

        // Left Right Highlight Menu
        function lowLight(letter) {
            var menus  = $('.r-list');
            var llight = $('.highlight');
            var index  = menus.index(llight);

            // console.log('work');

            menus.eq(index).removeClass('highlight');

            if ( letter === 'ctrl+left' ) {
                menus.eq(index - 1).addClass('highlight');
            } else if ( letter === 'ctrl+right' ) {
                menus.eq(index + 1).addClass('highlight');
            }
        }
        // Left Right Higlight Menu End


        Mousetrap.bind('/', search);
        Mousetrap.bind('ctrl+up', function next() { highLight('ctrl+up') },);
        Mousetrap.bind('ctrl+down', function prev() { highLight('ctrl+down') },);
        Mousetrap.bind('ctrl+left', function next() { lowLight('ctrl+left') },);
        Mousetrap.bind('ctrl+right', function prev() { lowLight('ctrl+right') },);
        Mousetrap.bind('?', function modal() { $('#help').modal('show'); },);

        // HIstory Back Function
        Mousetrap.bind('ctrl+alt+left', function(e) {
            history.back();
        });

        // History Forward Function
        Mousetrap.bind('ctrl+alt+right', function(e) {
            history.forward();
        });

        // Journal Entry
        Mousetrap.bind('ctrl+alt+j', function(e) {
            window.location.replace("/journals/create");
            return false;
        });

    Mousetrap.bind("ctrl+left", function next() {
        lowLight("ctrl+left");
    });


        // Purchase Create
        Mousetrap.bind('ctrl+alt+p', function(e) {
            window.location.replace("/billings/purchasecreate");
            return false;
        });

        // Sales Create

        Mousetrap.bind('ctrl+alt+s', function(e) {

            window.location.replace("/billings/salescreate");
            return false;
        });

        //Service sale Create
        Mousetrap.bind('ctrl+alt+d', function(e) {
            window.location.replace("/service_sales/create");
            return false;
        });

        // product Create
        Mousetrap.bind('ctrl+alt+shift+p', function(e) {
            window.location.replace("/product/create");
            return false;
        });

    // History Forward Function
    Mousetrap.bind("ctrl+alt+right", function (e) {
        history.forward();
    });

    // Journal Entry
    Mousetrap.bind("ctrl+alt+j", function (e) {
        window.location.replace("/journals/create");
        return false;
    });

    // Quotation Create
    Mousetrap.bind("ctrl+alt+q", function (e) {
        window.location.replace("/billings/quotationcreate");
        return false;
    });

    // Purchase Create
    Mousetrap.bind("ctrl+alt+p", function (e) {
        window.location.replace("/billings/purchasecreate");
        return false;
    });

    // Sales Create
    Mousetrap.bind("ctrl+alt+s", function (e) {
        window.location.replace("/billings/salescreate");
        return false;
    });

    // product Create
    Mousetrap.bind("ctrl+alt+shift+p", function (e) {
        window.location.replace("/product/create");
        return false;
    });

    // Balance Sheet Page
    Mousetrap.bind("ctrl+alt+b", function (e) {
        window.location.replace("/balancesheet");
        return false;
    });

    // Trial Balance Page
    Mousetrap.bind("ctrl+alt+t", function (e) {
        window.location.replace("/trialbalance");
        return false;
    });

    // PL Account
    Mousetrap.bind("ctrl+alt+l", function (e) {
        window.location.replace("/profitandloss");
        return false;
    });

    // Go To Home Page
    Mousetrap.bind("ctrl+alt+h", function (e) {
        window.location.replace("http://127.0.0.1:8000/");
        return false;
    });

    //Press Enter Key Form Submit Stop
    $('form input').keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
        });

        // Save
        // Mousetrap.bind('ctrl+alt+s', function(e) {
        //     $("#form").submit();
        // });

        // Click Enter Focus Input Field
        // Mousetrap.bind('enter', function() {
        //     alert('enter');
        // });

        // $('form input').keydown(function (e) {
        //     if (e.keyCode == 13) {
        //         e.preventDefault();
        //         return false;
        //     }
        // });
        // $(document).on('keypress', 'form,input,select', function (e) {
        //     if (e.which == 13) {
        //         e.preventDefault();
        //         var $next = $('[tabIndex=' + (+this.tabIndex + 1) + ']');
        //         console.log($next.length);
        //         if (!$next.length) {
        //        $next = $('[tabIndex=1]');        }
        //         $next.focus() .click();
        //     }
        // });
});
