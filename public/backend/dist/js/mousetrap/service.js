var url = window.location.pathname;
var billing_type_id = getUrlParameter('billing_type_id');


if(url == "/service_sales/create" || url == "/service_sales"){


    Mousetrap.bind('ctrl+alt+i', function(e) {
        window.location.replace("/service_sales");
        return false;
    });

     if(billing_type_id == 1 || billing_type_id == 6 || billing_type_id == ''){
        Mousetrap.bind('ctrl+alt+u', function(e) {
            window.location.replace("/unapprovedServiceBills?billing_type_id=6");
            return false;
        });

        Mousetrap.bind('ctrl+alt+c', function(e) {
            window.location.replace("/cancelledServiceBills?billing_type_id=1");
            return false;
        });
    }

}

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};
