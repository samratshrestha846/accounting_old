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
        this.calcTotal();
        this.calcTotalQty();
        this.calcTax();
        this.calcSubtotal();
        this.calcGrandTotal();
        // this.getdata();
    },

    /**
     * Calculate total price of an item.
     *
     * @returns {number}
     */
    // getdata: function(){
    //     jQuery($.opt.parentClass).each(function (i){
    //         var row = jQuery(this);
    //         // datarate
    //         datarate = row.find($.opt.item).find(":selected").data("rate");
    //         row.find($.opt.rate).val(datarate);


    //         //data Unit
    //         dataunit = row.find($.opt.item).find(":selected").data("priunit");
    //         row.find($.opt.unit).val(dataunit);
    //     })
    //     return 1;
    // },

    calcTotal: function () {
         jQuery($.opt.parentClass).each(function (i) {
             var row = jQuery(this);
             var total = row.find($.opt.rate).val() * row.find($.opt.qty).val();

             ntotal = total.toFixed(2);

             row.find($.opt.total).val(ntotal);
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
        var alltaxamt = jQuery($.opt.subtotal).val() * alltper/100;
        alltaxamt = alltaxamt.toFixed(2);
        jQuery($.opt.gtaxamount).val(alltaxamt);
        if(typeof allttype == "undefined" || allttype == "exclusive"){
            window.att = allttype;
        }else if(allttype == "inclusive"){
            window.att = allttype;
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
        if(jQuery($.opt.taxamount).val() == 0){
            bulktax = jQuery($.opt.gtaxamount).val();
        }else{
            bulktax = 0;
        }
        if(window.att == "inclusive"){
            var grandTotal = Number(jQuery($.opt.subtotal).val());

            grandTotal = self.roundNumber(grandTotal, 2);

            jQuery($.opt.grandTotal).val(grandTotal);
        }else if(window.att == "exclusive"){
            var grandTotal = Number(jQuery($.opt.subtotal).val())
                        + Number(bulktax);

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
        var units = window.units;
        var unitcount = units.length;
        function unitoption(unitoptions){
            var unitoptions = '';
            for(let a=0; a<unitcount; a++){
                var unitname = units[a].unit;
                unitoptions += `<option value='${unitname}'>${unitname}(${units[a].short_form})</option>`;
                }
                return unitoptions;
            }
        jQuery(".item-row:last").after(`<tr class="item-row">
        <td class="item-name">
            <div class="delete-btn">
            <input type="text" name="particulars[]" class="form-control item" placeholder="Enter Product Name">
                <a class="${ $.opt.delete.substring(1) }" href="javascript:;" title="Remove row">X</a>
            </div>
        </td>
        <td><input class="form-control qty" placeholder="Quantity" type="number" name="quantity[]"> </td>
        <td>
            <select name="unit[]" class="form-control unit">
                ${unitoption(window.unitoptions)}
            </select>
        </td>
        <td><input class="form-control rate" placeholder="Rate" type="number" name="rate[]" min="0" value="0" step=".01"></td>
        <td><input name="total[]" class="form-control total" value="0" readonly="readonly"></td>
        </tr>`);
        if (jQuery($.opt.delete).length > 0) {
            jQuery($.opt.delete).show();
        }
        $(".unit").select2();

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

    item: ".item",
    rate: ".rate",
    qty: ".qty",
    unit: ".unit",
    total: ".total",
    totalQty: "#totalQty",

    subtotal: "#subtotal",
    gtaxamount: ".gtaxamount",
    alltaxtype: ".alltaxtype",
    alltaxper: '.alltaxper',
    tax: "#tax",
    taxamount: "#taxamount",
    grandTotal: "#grandTotal"
};
