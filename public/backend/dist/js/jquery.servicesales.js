(function (jQuery) {
    $.opt = {}; // jQuery Object

    jQuery.fn.invoice = function (options) {
        var ops = jQuery.extend({}, jQuery.fn.invoice.defaults, options);
        $.opt = ops;

        var inv = new Invoice();
        inv.init();

        jQuery("body").on("click", function (e) {
            var cur = e.target.id || e.target.className;
            if (cur == $.opt.addRow.substring(1)) inv.newRow();
            if (cur == $.opt.delete.substring(1)) inv.deleteRow(e.target);
            inv.init();
        });

        jQuery("body").on("change", function (e) {
            var cur = e.target.id || e.target.className;
            // console.log("the cur is ", cur);
            // console.log("?? is ", $.opt.item.substring(1));
            // console.log("equal ", cur.includes($.opt.item.substring(1)));

            if (cur.includes($.opt.item.substring(1))) {
                inv.init();
                inv.getdata();
            }

            // inv.init();
        });

        jQuery("body").on("keyup", function (e) {
            inv.init();
        });

        return this;
    };
})(jQuery);

function Invoice() {
    self = this;
}

Invoice.prototype = {
    constructor: Invoice,

    init: function () {
        this.calcdiscountamt();
        this.calcindTax();
        // this.calcTotal();
        this.calcTotalQty();
        this.calcitemTax();
        this.calcTotalTax();
        this.calcDiscount();
        this.calcService();
        // this.calculateService();
        this.calcSubtotal();
        this.calcGrandTotal();
    },

    /**
     * Calculate total price of an item.
     *
     * @returns {number}
     */
    getdata: function (cur) {
        jQuery($.opt.parentClass).each(function (i) {
            var row = jQuery(this);

            //datarate
            datarate = row.find($.opt.item).find(":selected").data("rate");
            // row.find($.opt.rate).val(datarate);
        });
        return 1;
    },

    calcdiscountamt: function () {

        jQuery($.opt.parentClass).each(function (i) {
            row = jQuery(this);
            rate = row.find($.opt.rate).val();
            distamt = row.find($.opt.dtamt).val();
            var rdiscounttype = row.find($.opt.discounttype);
            var selected = rdiscounttype.find(":selected").val();
            if (typeof selected == "undefined") {
                var damount = (rate * distamt) / 100;
                rounddistamt = damount.toFixed(2);
                row.find($.opt.discountamt).val(rounddistamt);
            } else if (selected == "percent") {
                var damount = (rate * distamt) / 100;
                rounddistamt = damount.toFixed(2);
                row.find($.opt.discountamt).val(rounddistamt);
            } else if (selected == "fixed") {
                var damount = Number(distamt);
                rounddistamt = damount.toFixed(2);
                row.find($.opt.discountamt).val(rounddistamt);
            }
        });
        return 1;
    },
    calcindTax: function () {
        totalTax = 0;
        var alltaxamt = 0;
        jQuery($.opt.parentClass).each(function (i) {
            row = jQuery(this);
            rate = row.find($.opt.rate).val();
            discount = row.find($.opt.discountamt).val();
            taxableamount = Number(rate)-Number(discount);
            var rtaxper = row.find($.opt.taxper);
            var selectedtaxper = rtaxper.find(":selected").val();
            var taxtype = row.find($.opt.taxtype).find(':selected').val();
            if(typeof taxtype == "undefined" || taxtype == "exclusive"){
                var txamt = (taxableamount * selectedtaxper) / 100;
            }else if(taxtype == "inclusive"){
                var txamt = (taxableamount * selectedtaxper) / (100 + Number(selectedtaxper));
            }

            roundtaxamt = txamt.toFixed(2);
            row.find($.opt.taxamt).val(roundtaxamt);

            // itemtax = txamt * row.find($.opt.qty).val();
        });

        return 1;
    },
    calcTotalTax: function () {
        var totalTax = 0;
        jQuery($.opt.itemtax).each(function (i) {
            var indtax = jQuery(this).val();
            if (!isNaN(indtax)) totalTax += Number(indtax);
        });
        totalTax = self.roundNumber(totalTax, 2);

        console.log(totalTax);
        if (totalTax > 0) {
            jQuery($.opt.taxamount).val(totalTax);
            jQuery($.opt.taxamount).attr("readonly", "readonly");
            jQuery($.opt.taxamount).removeClass("off");
            jQuery($.opt.taxamount).addClass("on");
            jQuery($.opt.gtaxamount).removeClass("on");
            jQuery($.opt.gtaxamount).addClass("off");
            jQuery($.opt.gtaxamount).attr("disabled", true);
        } else if (totalTax == 0) {
            jQuery($.opt.taxamount).val(totalTax);
            jQuery($.opt.taxamount).removeAttr("readonly", "readonly");
            // jQuery($.opt.taxamount).addClass('alltaxamount');
            jQuery($.opt.taxamount).removeClass("on");
            jQuery($.opt.taxamount).addClass("off");
            jQuery($.opt.gtaxamount).removeClass("off");
            jQuery($.opt.gtaxamount).addClass("on");
            jQuery($.opt.gtaxamount).attr("disabled", false);

            var allttype = jQuery($.opt.alltaxtype).find(":selected").val();
            var alltper = jQuery($.opt.alltaxper).find(":selected").val();
            var subtotal = jQuery($.opt.subtotal).val();
            var alldiscount = $('.alldiscount').val();
            var service_charge = $('#service_charge').val();
            var taxableamount = Number(subtotal) - Number(alldiscount) + Number(service_charge);
            if (typeof allttype == "undefined" || allttype == "exclusive") {
                var alltaxamt = (taxableamount * alltper) / 100;
                alltaxamt = alltaxamt.toFixed(2);
                jQuery($.opt.gtaxamount).val(alltaxamt);
                window.att = allttype;
            } else if (allttype == "inclusive") {
                var alltaxamt = (taxableamount * alltper) / (100 + Number(alltper));
                alltaxamt = alltaxamt.toFixed(2);
                jQuery($.opt.gtaxamount).val(alltaxamt);
                window.att = allttype;
            }
        }

        return 1;
    },
    calcitemTax: function () {
        jQuery($.opt.parentClass).each(function (i) {
            var row = jQuery(this);
            var tax = row.find($.opt.taxamt).val();
            var qty = row.find($.opt.qty).val();

            var itemTax = Number(tax) * Number(qty);
            itemTax = self.roundNumber(itemTax, 2);
            row.find($.opt.itemtax).val(itemTax);
            // console.log(itemTax);
        });
    },

    calcTotal: function () {
        jQuery($.opt.parentClass).each(function (i) {
            var row = jQuery(this);
            var total = row.find($.opt.rate).val() * row.find($.opt.qty).val();
            total = self.roundNumber(total, 2);
            row.find($.opt.total).val(total);
        });
    },

    /***
     * Calculate total quantity of an order.
     *
     * @returns {number}
     */
    calcTotalQty: function () {
        var totalQty = 0;
        jQuery($.opt.qty).each(function (i) {
            var qty = jQuery(this).val();
            if (!isNaN(qty)) totalQty += Number(qty);
        });
        totalQty = self.roundNumber(totalQty, 2);
        jQuery($.opt.totalQty).html(totalQty);
        return 1;
    },

    /***
     * Calculate subtotal of an order.
     *
     * @returns {number}
     */
    calcSubtotal: function () {
        var subtotal = 0;
        jQuery($.opt.total).each(function (i) {
            var total = jQuery(this).val();
            if (!isNaN(total)) subtotal += Number(total);
        });

        subtotal = self.roundNumber(subtotal, 2);

        jQuery($.opt.subtotal).val(subtotal);

        return 1;
    },

    calcTax: function () {
        var allttype = jQuery($.opt.alltaxtype).find(":selected").val();
        var alltper = jQuery($.opt.alltaxper).find(":selected").val();
        var alltaxamt = (jQuery($.opt.subtotal).val() * alltper) / 100;
        alltaxamt = alltaxamt.toFixed(2);
        jQuery($.opt.gtaxamount).val(alltaxamt);
        if (typeof allttype == "undefined" || allttype == "exclusive") {
            window.att = allttype;
        } else if (allttype == "inclusive") {
            window.att = allttype;
        }
        return 1;
    },

    calcDiscount: function () {

        var alldisttype = $(".alldiscounttype").find(":selected").val();

        var alldistval = $(".alldtamt").val();
        if (typeof alldisttype == "undefined") {
            var discountamt =
                (Number(jQuery($.opt.subtotal).val()) * Number(alldistval)) /
                100;

            discountamt = self.roundNumber(discountamt, 2);

            jQuery($.opt.discount).val(discountamt);
        } else if (alldisttype == "percent") {
            var discountamt =
                (Number(jQuery($.opt.subtotal).val()) * Number(alldistval)) /
                100;

            discountamt = self.roundNumber(discountamt, 2);

            jQuery($.opt.discount).val(discountamt);
        } else if (alldisttype == "fixed") {
            var discountamt = Number(alldistval);

            discountamt = self.roundNumber(discountamt, 2);

            jQuery($.opt.discount).val(discountamt);
        }

        return 1;
    },

    calcService: function () {

        var discounttypeservice = jQuery($.opt.alldiscounttypeservice).val();

        var servicechageval = jQuery($.opt.alldtamtservice).val()
        //  alert(alldisttype);
        if (typeof discounttypeservice == "undefined") {
            var serviceamt =
                (((Number(jQuery($.opt.subtotal).val()- Number(jQuery($.opt.discount).val())) * Number(servicechageval)) /
                100) );

                serviceamt = self.roundNumber(serviceamt, 2);

            jQuery($.opt.serviceamt).val(serviceamt);
        } else if (discounttypeservice == "percent") {
            var serviceamt =
            (((Number(jQuery($.opt.subtotal).val() - Number(jQuery($.opt.discount).val())) * Number(servicechageval)) /
            100) );

                serviceamt = self.roundNumber(serviceamt, 2);

            jQuery($.opt.serviceamt).val(serviceamt);
        } else if (discounttypeservice == "fixed") {
            var serviceamt = Number(servicechageval);

            serviceamt = self.roundNumber(serviceamt, 2);

            jQuery($.opt.serviceamt).val(serviceamt);
        }

        return 1;
    },

    /**
     * Calculate grand total of an order.
     *
     * @returns {number}
     */
    calcGrandTotal: function () {
        // console.log(jQuery($.opt.serviceamt).val());
        // console.log(window.att);
        // bulktax = 0;
        // if(jQuery($.opt.taxamount).val() == 0){
        //     bulktax = jQuery($.opt.gtaxamount).val();
        // }else{
        //     bulktax = 0;
        // }
        bulktax = 0;
        if(jQuery($.opt.taxamount).val() == 0){
            bulktax = jQuery($.opt.gtaxamount).val();
        }else{
            bulktax = 0;
        }
        if(typeof window.att == "undefined"){
            var grandTotal = Number(jQuery($.opt.subtotal).val()) +
                    Number(jQuery($.opt.shipping).val()) -
                    Number(jQuery($.opt.discount).val()) +
                    Number(jQuery($.opt.serviceamt).val());

            grandTotal = self.roundNumber(grandTotal, 2);

            jQuery($.opt.grandTotal).val(grandTotal);
        }
        else if (window.att == "inclusive") {
            var grandTotal =
                Number(jQuery($.opt.subtotal).val()) +
                Number(jQuery($.opt.shipping).val()) -
                Number(jQuery($.opt.discount).val()) +
                Number(jQuery($.opt.serviceamt).val());

            grandTotal = self.roundNumber(grandTotal, 2);

            jQuery($.opt.grandTotal).val(grandTotal);
        } else if (window.att == "exclusive") {
            var grandTotal =
                Number(jQuery($.opt.subtotal).val()) +
                Number(jQuery($.opt.gtaxamount).val()) +
                Number(jQuery($.opt.shipping).val()) -
                Number(jQuery($.opt.discount).val())+
                Number(jQuery($.opt.serviceamt).val());

            grandTotal = self.roundNumber(grandTotal, 2);

            jQuery($.opt.grandTotal).val(grandTotal);
        }

        return 1;
    },

    /**
     * Add a row.
     *
     * @returns {number}
     */
    newRow: function () {
        var taxes = window.taxes;
        // console.log(taxes);
        var taxcount = taxes.length;
        var count = $('#myTable tr').length;
        var categories = window.categories;
        var catecount = categories.length;
        count = count + 1;
        function cateoption(cateoptions) {
            var cateoptions = "";
            for (let a = 0; a < catecount; a++) {
                var catename = categories[a].category_name;
                var serviceUri = '/apiServiceFromCategories/'+categories[a].id;
                $.ajax({
                    url: serviceUri,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var services = response;
                        var service_options = "";
                        for (let j = 0; j < services.length; j++)
                        {
                            service_options += `<option value="${services[j].id}">
                                                    ${services[j].service_name} (${services[j].service_code})
                                                </option>`;
                        }
                        cateoptions += `<option value='' class='title' disabled>${catename}</option>
                                            ${service_options}
                                        `;

                        document.getElementById('item_'+count).innerHTML = `<option value="">--Select an option--
                                        </option><option value="secondoption" class="coloradd">
                                         + Add new Service
                                    </option>`+ cateoptions;
                    }
                })
            }
        }


        function option(options) {
            var options = "";
            for (let x = 0; x < taxcount; x++) {
                options +=
                    "<option value =" +
                    taxes[x].percent +
                    ">" +
                    taxes[x].title +
                    "(" +
                    taxes[x].percent +
                    "%)" +
                    "</option>";
            }
            return options;
        }


        $(function() {
            $('.item').change(function() {
                let productID = this.value;
                let avalProduct = window.categories
                let particulars = $("select[name='particulars[]']").map(function(){return $(this).val()}).get();
                particulars = particulars.sort();


                var reportCheckDuplicate = [];
                for (var i = 0; i < particulars.length - 1; i++) {
                    if (particulars[i + 1] == particulars[i]) {
                        reportCheckDuplicate.push(particulars[i]);
                    }


                }
                if(reportCheckDuplicate.length > 0){
                    $(this).val("").change()
                    if(this.value == ''){
                        $(this).closest('tr').addClass("select-error");
                    $('#addRow').addClass("disabled");
                    $('.submit').attr('disabled', true);
                    }

                }else{
                    if(this.value != ''){
                    $('#addRow').removeClass("disabled");
                    $(this).closest('tr').removeClass("select-error");
                    $('.submit').attr('disabled', false);
                    }
                }
            });
        });

        jQuery(".item-row:last").after(`<tr id="tr_row_${count}" class="item-row">
        <td class="item-name">
            <div class="delete-btn">
                <select data-id="${count}" name="particulars[]" class="form-control item" data-id="${count}" id="item_${count}" onchange="showrate(${count})" required>
                    ${cateoption()}
                </select>
                <a class="${$.opt.delete.substring(
                    1
                )}" href="javascript:;" title="Remove row">X</a>
            </div>
        </td>
        <td><input class="form-control qty" placeholder="Quantity" type="text" name="quantity[]" value=""> </td>
        <td><input class="form-control unit" placeholder="Unit" type="text" name="unit[]" value=""></td>
        <td><input class="form-control rate" placeholder="Rate" type="text" name="rate[]" id="rate_${count}"></td>
        <td><input class="form-control discountamt" placeholder="Discount per unit" type="text" name="discountamt[]" value="0">
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Discount Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-3">
                                    <label for="discounttype" class="txtleft">Discount Type:</label>
                                </div>
                                <div class="col-9">
                                    <select name="discounttype[]" class="form-control discounttype">
                                        <option value="percent">Percent %</option>
                                        <option value="fixed">Fixed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-3">
                                    <label for="dtamt">Discount:</label>
                                </div>
                                <div class="col-9">
                                    <input type="text" name="dtamt[]" class="form-control dtamt" placeholder="Discount" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
        </td>
        <td>
            <input type="text" name="taxamt[]" class="form-control taxamt"  value="0">
            <input type="text" name="itemtax[]" class="form-control itemtax" value="0" style="display: none;">
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Individual Tax Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-3">
                                    <label for="taxtype" class="txtleft">Tax Type:</label>
                                </div>
                                <div class="col-9">
                                    <select name="taxtype[]" class="form-control taxtype">
                                        <option value="exclusive">Exclusive</option>
                                        <option value="inclusive">Inclusive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-3">
                                    <label for="dtamt">Tax%:</label>
                                </div>
                                <div class="col-9">
                                    <select name="tax[]" class="form-control taxper">
                                        "${option(window.options)}"
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
        </td>
        <td><input name="total[]" class="form-control total" value="0"></td>
        </tr>`);
        if (jQuery($.opt.delete).length > 0) {
            jQuery($.opt.delete).show();
        }
        $(".item").select2({
            templateResult: function (data, container) {
            if (data.element) {
                $(container).addClass($(data.element).attr("class"));
            }
            return data.text;
            }
        });

        return 1;
    },

    /**
     * Delete a row.
     *
     * @param elem   current element
     * @returns {number}
     */
    deleteRow: function (elem) {
        jQuery(elem).parents($.opt.parentClass).remove();

        if (jQuery($.opt.delete).length < 2) {
            // jQuery($.opt.delete).hide();
        }
        jQuery('body').trigger('click');
        jQuery('body').trigger('click');
        return 1;
    },

    /**
     * Round a number.
     * Using: http://www.mediacollege.com/internet/javascript/number/round.html
     *
     * @param number
     * @param decimals
     * @returns {*}
     */
    roundNumber: function (number, decimals) {
        var newString; // The new rounded number
        decimals = Number(decimals);

        if (decimals < 1) {
            newString = Math.round(number).toString();
        } else {
            var numString = number.toString();

            if (numString.lastIndexOf(".") == -1) {
                // If there is no decimal point
                numString += "."; // give it one at the end
            }

            var cutoff = numString.lastIndexOf(".") + decimals; // The point at which to truncate the number
            var d1 = Number(numString.substring(cutoff, cutoff + 1)); // The value of the last decimal place that we'll end up with
            var d2 = Number(numString.substring(cutoff + 1, cutoff + 2)); // The next decimal, after the last one we want

            if (d2 >= 5) {
                // Do we need to round up at all? If not, the string will just be truncated
                if (d1 == 9 && cutoff > 0) {
                    // If the last digit is 9, find a new cutoff point
                    while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
                        if (d1 != ".") {
                            cutoff -= 1;
                            d1 = Number(
                                numString.substring(cutoff, cutoff + 1)
                            );
                        } else {
                            cutoff -= 1;
                        }
                    }
                }

                d1 += 1;
            }

            if (d1 == 10) {
                numString = numString.substring(0, numString.lastIndexOf("."));
                var roundedNum = Number(numString) + 1;
                newString = roundedNum.toString() + ".";
            } else {
                newString = numString.substring(0, cutoff) + d1.toString();
            }
        }

        if (newString.lastIndexOf(".") == -1) {
            // Do this again, to the new string
            newString += ".";
        }

        var decs = newString.substring(newString.lastIndexOf(".") + 1).length;

        for (var i = 0; i < decimals - decs; i++) newString += "0";
        //var newNumber = Number(newString);// make it a number if you like

        return newString; // Output the result to the form field (change for your purposes)
    },
};

/**
 *  Publicly accessible defaults.
 */
jQuery.fn.invoice.defaults = {
    addRow: "#addRow",
    delete: ".delete",
    parentClass: ".item-row",

    item: ".item",
    price: ".price",
    qty: ".qty",
    discountamt: "input.discountamt",
    serviceamt: "input.servicecharge",
    discounttype: "select.discounttype",

    dtamt: "input.dtamt",
    taxamt: ".taxamt",
    taxtype: ".taxtype",
    taxper: ".taxper",
    itemtax: ".itemtax",
    total: ".total",
    totalQty: "#totalQty",

    subtotal: "#subtotal",
    discountpercent: "#discountpercent",
    alldiscounttype: ".alldiscounttype",
    alldtamt: ".alldtamt",
    alldiscounttypeservice: ".alldiscounttypeservice",
    alldtamtservice: ".alldtamtservice",
    gtaxamount: ".gtaxamount",
    alltaxtype: ".alltaxtype",
    alltaxper: ".alltaxper",
    discount: "#discount",
    tax: "#tax",
    taxamount: "#taxamount",
    shipping: "#shipping",
    grandTotal: "#grandTotal",
};


$('input[name="alldtamt"]').keyup(function(){
    $('body').trigger('click');
})
$('input[name="servicecharge"]').keyup(function(){
    $('body').trigger('click');
})
$(document).on('change','select[name="alldiscounttype"]',function(){
    $('body').trigger('click');
})
$(document).on('change','select[name="alldiscounttypeservice"]',function(){
    $('body').trigger('click');
})

