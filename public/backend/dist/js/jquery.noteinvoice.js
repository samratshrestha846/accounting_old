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
        this.item();
        this.calcTotal();
        this.calcTotalQty();
        this.calcTax();
        this.calcDiscount();
        this.calcSubtotal();
        this.calcGrandTotal();
    },

    /**
     * Calculate total price of an item.
     *
     * @returns {number}
     */
    item: function () {
        jQuery($.opt.parentClass).each(function (i) {
            var row = jQuery(this);
            var selecteditem = row.find($.opt.item).find(":selected").val();
            row.find($.opt.itemid).val(selecteditem);

        });

        return 1;
    },
    calcTotal: function () {
         jQuery($.opt.parentClass).each(function (i) {
             var row = jQuery(this);
             var total = row.find($.opt.rate).val() * row.find($.opt.qty).val();

             total = self.roundNumber(total, 2);

             row.find($.opt.total).val(total);
         });

         return 1;
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
        // console.log(window.att);
        bulktax = 0;
        if (jQuery($.opt.taxamount).val() == 0 || typeof(jQuery($.opt.taxamount).val()) == 'undefined') {
            bulktax = jQuery($.opt.gtaxamount).val();
        } else {
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
        var categories = window.categories;
        var catecount = categories.length;
        function cateoption(cateoptions){
            var cateoptions = '';
            for(let a=0; a<catecount; a++){
                var catename = categories[a].category_name;
                var products = categories[a].products;
                // console.log(products);
                var productcount = products.length;
                var prodoptions = '';
                    for(let j=0; j<productcount; j++){
                        // var productname = products[j].product_name;
                        prodoptions += `<option value="${products[j].id }"
                                            data-rate="${products[j].product_price}"
                                            data-stock="${products[j].stock}"
                                            data-priunit = "${products[j].primary_unit}">
                                            ${products[j].product_name}(${products[j].product_code})
                                        </option>`;
                    }
                cateoptions += `<option value='' class='title' disabled>${catename}</option>
                                    ${prodoptions}
                                `;
                }
                return cateoptions;
            }
        jQuery(".item-row:last").after(`<tr class="item-row">
        <td class="item-name">
            <div class="delete-btn">
                <select name="particulars[]" class="form-control item">
                <option value="">--Select Option--</option>
                ${cateoption(window.cateoptions)}
                </select>
                <a class="${ $.opt.delete.substring(1) }" href="javascript:;" title="Remove row">X</a>
            </div>
        </td>
        <td><input class="form-control qty" placeholder="Quantity" type="text" name="quantity[]"> </td>
        <td><input class="form-control unit" placeholder="Unit" type="text" name="unit[]"></td>
        <td><input class="form-control rate" placeholder="Rate" type="text" name="rate[]"></td>
        <td><input name="total[]" class="form-control total" value="0" readonly="readonly"></td>
        </tr>`);
        if (jQuery($.opt.delete).length > 0) {
            jQuery($.opt.delete).show();
        }
        $(".item").select2();

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

    item : ".item",
    itemid : ".itemid",
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
