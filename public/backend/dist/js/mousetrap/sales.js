var url = window.location.pathname;

if(url == "/billings/salescreate" || url == '/billings/billingsreport/1' || url == "/billings/unapprovedbillingsreport/1" || url == "/billings/cancelledbillingsreport/1"){


    Mousetrap.bind('ctrl+alt+i', function(e) {
        window.location.replace("/billings/billingsreport/1");
        return false;
    });

    Mousetrap.bind('ctrl+alt+u', function(e) {
        window.location.replace("/billings/unapprovedbillingsreport/1");
        return false;
    });

    Mousetrap.bind('ctrl+alt+c', function(e) {
        window.location.replace("/billings/cancelledbillingsreport/1");
        return false;
    });

}

