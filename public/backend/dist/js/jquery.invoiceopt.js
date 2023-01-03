(function (jQuery) {
    $.opt = {};  // jQuery Object

    jQuery.fn.invoice = function (options) {
        var ops = jQuery.extend({}, jQuery.fn.invoice.defaults, options);
        $.opt = ops;

        var inv = new Invoice();
        inv.init();

        jQuery('body').on('click', function (e) {
            var cur = e.target.id || e.target.className;

            if (cur == $.opt.addRow.substring(1))
                inv.newRow();

            if (cur == $.opt.delete.substring(1))
                inv.deleteRow(e.target);

            inv.init();
        });

        jQuery('body').on('keyup', function (e) {
            inv.init();
        });

        return this;
    };
}(jQuery));

function Invoice() {
    self = this;
}

Invoice.prototype = {
    constructor: Invoice,

    init: function () {
        this.calcTax();
        this.calcDiscount();
        this.calcDiscount();
        this.calcSubtotal();
        this.calcGrandTotal();
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
        var subtotal = jQuery($.opt.subtotal).val();
        var alldiscount = $('.alldiscount').val();
        var taxableamount = Number(subtotal) - Number(alldiscount);
        jQuery($.opt.gtaxamount).val(alltaxamt);
        if(typeof allttype == "undefined" || allttype == "exclusive"){
            var alltaxamt = (taxableamount * alltper) / 100;
            alltaxamt = alltaxamt.toFixed(2);
            jQuery($.opt.gtaxamount).val(alltaxamt);
            window.att = allttype;
        }else if(allttype == "inclusive"){
            var alltaxamt = (taxableamount * alltper) / (100 + Number(alltper));
            alltaxamt = alltaxamt.toFixed(2);
            jQuery($.opt.gtaxamount).val(alltaxamt);
            window.att = allttype;
        }

        return 1
     },

     calcDiscount: function () {
        var alldisttype = $('.alldiscounttype').find(':selected').val();
        var alldistval = $(".alldtamt").val();
        if(typeof alldisttype == "undefined"){
           var discountamt = Number(jQuery($.opt.subtotal).val())*Number(alldistval)/100;

           discountamt = self.roundNumber(discountamt, 2);

           jQuery($.opt.discount).val(discountamt);
        }else if(alldisttype == "percent"){
           var discountamt = Number(jQuery($.opt.subtotal).val())*Number(alldistval)/100;

           discountamt = self.roundNumber(discountamt, 2);

           jQuery($.opt.discount).val(discountamt);
        }else if(alldisttype == "fixed"){
           var discountamt = Number(alldistval);

           discountamt = self.roundNumber(discountamt, 2);

           jQuery($.opt.discount).val(discountamt);
        }

         return 1
     },
    /**
     * Calculate grand total of an order.
     *
     * @returns {number}
     */
    calcGrandTotal: function () {
        bulktax = 0;
        if(jQuery($.opt.taxamount).val() == 0){
            bulktax = jQuery($.opt.gtaxamount).val();
        }else{
            bulktax = 0;
        }
        if(window.att == "inclusive"){
            var grandTotal = Number(jQuery($.opt.subtotal).val())
                        + Number(jQuery($.opt.shipping).val())
                        - Number(jQuery($.opt.discount).val());

            grandTotal = self.roundNumber(grandTotal, 2);

            jQuery($.opt.grandTotal).val(grandTotal);
        }else if(window.att == "exclusive"){
            var grandTotal = Number(jQuery($.opt.subtotal).val())
                        + Number(bulktax)
                        + Number(jQuery($.opt.shipping).val())
                        - Number(jQuery($.opt.discount).val());

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
        jQuery(".item-row:last").after('<tr class="item-row"><td class="item-name"><div class="delete-btn"><input type="text" class="form-control item" placeholder="Particulars" type="text" name="particulars[]"><a class=' + $.opt.delete.substring(1) + ' href="javascript:;" title="Remove row">X</a></div></td><td><input class="form-control narration" placeholder="Narration" type="text" name="narration[]"> </td><td><input class="form-control particular_cheque_no" placeholder="Cheque No" type="text" name="particular_cheque_no[]"></td><td><input class="form-control total" placeholder="Price" type="text" name="total[]"></tr>');
        if (jQuery($.opt.delete).length > 0) {
            jQuery($.opt.delete).show();
        }

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
        var newString;// The new rounded number
        decimals = Number(decimals);

        if (decimals < 1) {
            newString = (Math.round(number)).toString();
        } else {
            var numString = number.toString();

            if (numString.lastIndexOf(".") == -1) {// If there is no decimal point
                numString += ".";// give it one at the end
            }

            var cutoff = numString.lastIndexOf(".") + decimals;// The point at which to truncate the number
            var d1 = Number(numString.substring(cutoff, cutoff + 1));// The value of the last decimal place that we'll end up with
            var d2 = Number(numString.substring(cutoff + 1, cutoff + 2));// The next decimal, after the last one we want

            if (d2 >= 5) {// Do we need to round up at all? If not, the string will just be truncated
                if (d1 == 9 && cutoff > 0) {// If the last digit is 9, find a new cutoff point
                    while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
                        if (d1 != ".") {
                            cutoff -= 1;
                            d1 = Number(numString.substring(cutoff, cutoff + 1));
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
                newString = roundedNum.toString() + '.';
            } else {
                newString = numString.substring(0, cutoff) + d1.toString();
            }
        }

        if (newString.lastIndexOf(".") == -1) {// Do this again, to the new string
            newString += ".";
        }

        var decs = (newString.substring(newString.lastIndexOf(".") + 1)).length;

        for (var i = 0; i < decimals - decs; i++)
            newString += "0";
        //var newNumber = Number(newString);// make it a number if you like

        return newString; // Output the result to the form field (change for your purposes)
    }
};

/**
 *  Publicly accessible defaults.
 */
jQuery.fn.invoice.defaults = {
    addRow: "#addRow",
    delete: ".delete",
    parentClass: ".item-row",

    price: ".price",
    qty: ".qty",
    total: ".total",
    totalQty: "#totalQty",

    subtotal: "#subtotal",
    discountpercent: "#discountpercent",
    alldiscounttype: '.alldiscounttype',
    alldtamt: '.alldtamt',
    gtaxamount: ".gtaxamount",
    alltaxtype: ".alltaxtype",
    alltaxper: '.alltaxper',
    discount: "#discount",
    tax: "#tax",
    taxamount: "#taxamount",
    shipping: "#shipping",
    grandTotal: "#grandTotal"
};
