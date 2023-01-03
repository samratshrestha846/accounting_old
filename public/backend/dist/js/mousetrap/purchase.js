var url = window.location.pathname;

if(url == "/billings/purchasecreate" || url == '/billings/billingsreport/2' || url == "/billings/unapprovedbillingsreport/2" || url=="/billings/cancelledbillingsreport/2"){

    Mousetrap.bind('ctrl+alt+i', function(e) {
        window.location.replace("/billings/billingsreport/2");
        return false;
    });
    Mousetrap.bind('ctrl+alt+u', function(e) {
        window.location.replace("/billings/unapprovedbillingsreport/2");
        return false;
    });

    Mousetrap.bind('ctrl+alt+c', function(e) {
        window.location.replace("/billings/cancelledbillingsreport/2");
        return false;
    });

}
