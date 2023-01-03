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


        // $(document).on('change', '.item', function() {
        //     inv.rate_init();
        // });


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
        this.calcDiscount();
        this.calcSubtotal();
        this.calcGrandTotal();
        this.getdata();
        // this.calculatePrice();
        // this.gettotqty();
    },
    // rate_init: function(){
    //     this.getrate();
    // },

    // getrate: function(){
    //     jQuery($.opt.parentClass).each(function (){
    //         var row = jQuery(this);
    //         let datarate = row.find($.opt.item).find(":selected").data("rate");
    //         row.find($.opt.rate).val(datarate);
    //     })
    //     return 1;
    // },

    /**
     * Calculate total price of an item.
     *
     * @returns {number}
     */

    // calculatePrice: function(){
    //     jQuery($.opt.parentClass).each(function(i){
    //         var row = jQuery(this);
    //         var original_vendor_price = row.find($.opt.original_vendor_price);
    //         var charging_rate = row.find($.opt.charging_rate);
    //         var final_vendor_price = row.find($.opt.final_vendor_price);
    //         var carrying_cost = row.find($.opt.carrying_cost);
    //         var transportation_cost = row.find($.opt.transportation_cost);
    //         var other_cost = row.find($.opt.other_cost);
    //         var product_cost = row.find($.opt.product_cost);
    //         var custom_duty = row.find($.opt.custom_duty);
    //         var after_custom = row.find($.opt.after_custom);
    //         var vat = row.find($.opt.vat_select).find(':selected').data('percent');
    //         var total_cost = row.find($.opt.total_cost);
    //         var margin_type = row.find($.opt.margin_type).find(':selected');
    //         var margin_value = row.find($.opt.margin_value);
    //         var product_price = row.find($.opt.product_price);

    //         var miscellaneous_percent = row.find($.opt.miscellaneous_percent);

    //         if (miscellaneous_percent.val() > 0)
    //         {
    //             var takeout = parseFloat(final_vendor_price.val()) + parseFloat(carrying_cost.val()) + parseFloat(transportation_cost.val());

    //             var percent_amount = takeout * (miscellaneous_percent.val() / 100);
    //             other_cost.val(percent_amount);
    //         }

    //         if (original_vendor_price.val() == "" || original_vendor_price == 0) {
    //             original_vendor_price.val(0);
    //             final_vendor_price.val(0);
    //             product_cost.val(0);
    //             after_custom.val(0);
    //             total_cost.val(0);
    //             product_price.val(0);
    //         } else if (original_vendor_price.val() > 0 && charging_rate.val() > 0) {
    //             var charging_rate_amount = charging_rate.val();
    //             var fvp = parseFloat(original_vendor_price.val()) * parseFloat(charging_rate_amount);
    //             final_vendor_price.val(fvp.toFixed(2));
    //             product_cost.val(final_vendor_price.val());
    //             after_custom.val(final_vendor_price.val());
    //             total_cost.val(final_vendor_price.val());
    //             product_price.val(product_cost.val());
    //         } else {
    //             final_vendor_price.val(original_vendor_price.val());
    //             product_cost.val(final_vendor_price.val());
    //             after_custom.val(final_vendor_price.val());
    //             total_cost.val(final_vendor_price.val());
    //             product_price.val(product_cost.val());
    //         }

    //         if (carrying_cost.val() > 0) {
    //             var pc = parseFloat(product_cost.val()) + parseFloat(carrying_cost.val());
    //             product_cost.val(pc.toFixed(2));
    //             after_custom.val(product_cost.val());
    //             total_cost.val(product_cost.val());
    //             product_price.val(product_cost.val());
    //         }

    //         if (transportation_cost.val() > 0) {
    //             var pct = parseFloat(product_cost.val()) + parseFloat(transportation_cost.val());
    //             product_cost.val(pct.toFixed(2));
    //             after_custom.val(product_cost.val());
    //             total_cost.val(product_cost.val());
    //             product_price.val(product_cost.val());
    //         }

    //         if (other_cost.val() > 0) {
    //             var new_cost = parseFloat(product_cost.val()) + parseFloat(other_cost.val());
    //             var percent_cost = parseFloat(final_vendor_price.val()) + parseFloat(carrying_cost.val()) + parseFloat(
    //                 transportation_cost.val());
    //             percent = (parseFloat(other_cost.val()) / percent_cost) * 100;
    //             miscellaneous_percent.val(percent.toFixed(2));
    //             product_cost.val(new_cost.toFixed(2));
    //             after_custom.val(new_cost.toFixed(2));
    //             total_cost.val(new_cost.toFixed(2));
    //             product_price.val(new_cost.toFixed(2));
    //         }

    //         if (custom_duty.val() > 0) {
    //             var custom_duty_amount = product_cost.val() * (custom_duty.val() / 100);
    //             var pc_cda = parseFloat(product_cost.val()) + parseFloat(custom_duty_amount);
    //             after_custom.val(pc_cda.toFixed(2));
    //             total_cost.val(pc_cda.toFixed(2));
    //             product_price.val(pc_cda.toFixed(2));
    //         }

    //         if (vat > 0) {
    //             var vat_amount = after_custom.val() * (vat / 100);
    //             var ac_va = parseFloat(after_custom.val()) + parseFloat(vat_amount);
    //             total_cost.val(ac_va.toFixed(2));
    //             product_price.val(ac_va.toFixed(2));
    //         }

    //         if (margin_value.val() > 0 && margin_type.val() == "percent") {
    //             var profit_margin_amount = total_cost.val() * (margin_value.val() / 100);
    //             var tc_pma = parseFloat(total_cost.val()) + parseFloat(profit_margin_amount);
    //             product_price.val(tc_pma.toFixed(2));
    //         }else if(margin_value.val() > 0 && margin_type.val() == "fixed"){
    //             var profit_margin_amount = margin_value.val();
    //             var tc_pma = parseFloat(total_cost.val()) + parseFloat(profit_margin_amount);
    //             product_price.val(tc_pma.toFixed(2));
    //         }
    //     })
    //     return 1;
    // },

    getdata: function(){
        jQuery($.opt.parentClass).each(function (i){
            var row = jQuery(this);

            // let datarate = row.find($.opt.item).find(":selected").data("rate");
            // row.find($.opt.rate).val(datarate);

            //data Unit
            dataunit = row.find($.opt.item).find(":selected").data("priunit");
            row.find($.opt.unit).val(dataunit);
        })
        return 1;
    },

    // gettotqty: function(){
    //     jQuery($.opt.parentClass).each(function (i){
    //         var row = jQuery(this);
    //         sum = 0;
    //         row.find($.opt.godown_qty).each(function(){
    //             sum += parseFloat($(this).val());
    //             row.find($.opt.qty).val(sum);
    //         });

    //     })
    //     return 1;
    // },

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
        // bulktax = 0;
        // if(jQuery($.opt.taxamount).val() == 0){
        //     bulktax = jQuery($.opt.gtaxamount).val();
        // }else{
        //     bulktax = 0;
        // }
        if(window.att == "inclusive"){
            var grandTotal = Number(jQuery($.opt.subtotal).val())
                        + Number(jQuery($.opt.shipping).val())
                        - Number(jQuery($.opt.discount).val());

            grandTotal = self.roundNumber(grandTotal, 2);

            jQuery($.opt.grandTotal).val(grandTotal);
        }else if(window.att == "exclusive"){
            var grandTotal = Number(jQuery($.opt.subtotal).val())
                        + Number(jQuery($.opt.gtaxamount).val())
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
        var taxes = window.taxes;
        var count = taxes.length;
        var godowns = window.godowns;
        var godowncount = godowns.length;
        var currentcomp = window.currentcomp;
        var is_importer = currentcomp.company.is_importer;
        if(is_importer == 1){
            var productinfo = `<div class="col-md-6">
            <label for="original_vendor_price">Original Supplier Price (Rs.) :</label>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input type="number" step="any" name="original_vendor_price[]"
                    class="form-control original_vendor_price" id="original_vendor_price" value="0"
                    placeholder="Original Supplier Price">
            </div>
        </div>

        <div class="col-md-6">
            <label for="charging_rate">Changing rate (%) (If any):</label>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input type="number" step="any" name="charging_rate[]"
                    class="form-control charging_rate" id="charging_rate" value="0"
                    placeholder="Changing rate in %">
            </div>
        </div>

        <div class="col-md-6">
            <label for="final_vendor_price">Final Supplier Price (Rs.) :</label>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input type="number" step="any" name="final_vendor_price[]"
                    class="form-control final_vendor_price" id="final_vendor_price" value="0"
                    placeholder="Final Supplier Price">
            </div>
        </div>

        <div class="col-md-6">
            <label for="carrying_cost">Carrying cost (Rs.) (If any):</label>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <input type="number" step="any" name="carrying_cost[]"
                    class="form-control carrying_cost" id="carrying_cost" value="0"
                    placeholder="Carrying cost for product">
            </div>
        </div>

        <div class="col-md-6">
            <label for="transportation_cost">Transportation cost (Rs.) (If
                any):</label>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <input type="number" step="any" name="transportation_cost[]"
                    class="form-control transportation_cost" id="transportation_cost" value="0"
                    placeholder="Transportation cost for product">
            </div>
        </div>

        <div class="col-md-6">
            <label for="miscellaneous_percent">Miscellaneous cost (If any):</label>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <input type="number" step="any"
                                    name="miscellaneous_percent[]"
                                    class="form-control miscellaneous_percent"
                                    id="miscellaneous_percent" value="0"
                                    placeholder="In percent (%)"
                                    oninput="setRupees()">

                            </div>
                        </div>
                        <div class="col-md-2 pl-0">
                            <p style="font-size: 20px; font-weight:bold;">%</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="number" step="any" name="other_cost[]"
                            class="form-control other_cost" id="other_cost" value="0"
                            placeholder="In Rupees">

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <label for="product_cost">Cost of Product (Rs.) :</label>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <input type="number" step="any" name="product_cost[]"
                    class="form-control product_cost" id="product_cost" value="0"
                    placeholder="Product cost">
            </div>
        </div>

        <div class="col-md-6">
            <label for="custom_duty">Custom Duty (%) :</label>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <input type="number" step="any" name="custom_duty[]"
                    class="form-control custom_duty" id="custom_duty" value="0"
                    placeholder="Custom Duty">
            </div>
        </div>

        <div class="col-md-6">
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <input type="number" step="any" name="after_custom[]"
                    class="form-control after_custom" id="after_custom" value="0"
                    placeholder="After Custom">
            </div>
        </div>

        <div class="col-md-6">
            <label for="vat">Tax (VAT) :</label>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <select name="product_tax[]" class="form-control vat_selected vat_select"
                    id="vat_select">
                    "${option(window.options)}"
                </select>
            </div>
        </div>


        <div class="col-md-6">
            <label for="total_cost">Total Cost (Rs.)
                :</label>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <input type="number" step="any" name="total_cost[]"
                    class="form-control total_cost" id="total_cost" value="0"
                    placeholder="Total cost">
            </div>
        </div>

        <div class="col-md-6">
            <label for="margin_profit">Profit margin :</label>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <select name="margin_type[]" class="form-control margin_type" id="margin_type">
                            <option value="percent">Percent</option>
                            <option value="fixed">Fixed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="number" step="any" name="margin_value[]"
                            class="form-control margin_value" id="margin_value" value="0"
                            placeholder="Profit Margin Value">

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <label for="product_price">Selling Price (Rs.) :</label>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <input type="number" step="any" name="product_price[]"
                    class="form-control product_price" id="product_price" value="0"
                    placeholder="Product Price in Rs.">
            </div>
        </div>`;
        }else{
            var productinfo =`<div class="col-md-6">
            <label for="original_vendor_price">Purchase Price (Rs.) :</label>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input type="number" step="any" name="original_vendor_price[]"
                    class="form-control original_vendor_price" id="original_vendor_price" value="0"
                    placeholder="Purchase Price">

            </div>
        </div>

        <div class="col-md-6" style="display: none;">
            <label for="charging_rate">Changing rate (%) (If any):</label>
        </div>
        <div class="col-md-6" style="display: none;">
            <div class="form-group">
                <input type="number" step="any" name="charging_rate[]"
                    class="form-control charging_rate" id="charging_rate" value="0"
                    placeholder="Changing rate in %">

            </div>
        </div>

        <div class="col-md-6" style="display: none;">
            <label for="final_vendor_price">Final Supplier Price (Rs.) :</label>
        </div>
        <div class="col-md-6" style="display: none;">
            <div class="form-group">
                <input type="number" step="any" name="final_vendor_price[]"
                    class="form-control final_vendor_price" id="final_vendor_price" value="0"
                    placeholder="Final Supplier Price">

            </div>
        </div>

        <div class="col-md-6" style="display: none;">
            <label for="carrying_cost">Carrying cost (Rs.) (If any):</label>
        </div>

        <div class="col-md-6" style="display: none;">
            <div class="form-group">
                <input type="number" step="any" name="carrying_cost[]"
                    class="form-control carrying_cost" id="carrying_cost" value="0"
                    placeholder="Carrying cost for product">

            </div>
        </div>

        <div class="col-md-6" style="display: none;">
            <label for="transportation_cost">Transportation cost (Rs.) (If
                any):</label>
        </div>

        <div class="col-md-6" style="display: none;">
            <div class="form-group">
                <input type="number" step="any" name="transportation_cost[]"
                    class="form-control transportation_cost" id="transportation_cost" value="0"
                    placeholder="Transportation cost for product"
                >

            </div>
        </div>

        <div class="col-md-6" style="display: none;">
            <label for="miscellaneous_percent">Miscellaneous cost (If any):</label>
        </div>
        <div class="col-md-6" style="display: none;">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <input type="number" step="any"
                                    name="miscellaneous_percent[]"
                                    class="form-control miscellaneous_percent"
                                    id="miscellaneous_percent" value="0"
                                    placeholder="In percent (%)"
                                    oninput="setRupees()" onblur="calculate()">

                            </div>
                        </div>
                        <div class="col-md-2 pl-0">
                            <p style="font-size: 20px; font-weight:bold;">%</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="number" step="any" name="other_cost[]"
                            class="form-control other_cost" id="other_cost" value="0"
                            placeholder="In Rupees">

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6" style="display: none;">
            <label for="product_cost">Cost of Product (Rs.) :</label>
        </div>

        <div class="col-md-6" style="display:none;">
            <div class="form-group">
                <input type="number" step="any" name="product_cost[]"
                    class="form-control product_cost" id="product_cost" value="0"
                    placeholder="Product cost">

            </div>
        </div>

        <div class="col-md-6" style="display: none;">
            <label for="custom_duty">Custom Duty (%) :</label>
        </div>

        <div class="col-md-6" style="display:none;">
            <div class="form-group">
                <input type="number" step="any" name="custom_duty[]"
                    class="form-control custom_duty" id="custom_duty" value="0"
                    placeholder="Custom Duty">

            </div>
        </div>



        <div class="col-md-6" style="display:none;">
            <div class="form-group">
                <input type="number" step="any" name="after_custom[]"
                    class="form-control after_custom" id="after_custom" value="0"
                    placeholder="After Custom">

            </div>
        </div>

        <div class="col-md-6" style="display: none;">
            <label for="vat">Tax (VAT) :</label>
        </div>

        <div class="col-md-6" style="display:none;">
            <div class="form-group">
                <select name="tax[]" class="form-control vat_selected vat_select"
                    id="vat_select" onchange="calculate()">
                    "${option(window.options)}"
                </select>
            </div>

        </div>

        <div class="col-md-6" style="display: none;">
            <label for="total_cost">Total Cost (Rs.)
                :</label>
        </div>

        <div class="col-md-6" style="display:none;">
            <div class="form-group">
                <input type="number" step="any" name="total_cost[]"
                    class="form-control total_cost" id="total_cost" value="0"
                    placeholder="Total cost">

            </div>
        </div>

        <div class="col-md-6">
            <label for="margin_profit">Profit margin :</label>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <select name="margin_type[]" class="form-control margin_type" id="margin_type" onchange="calculate()">
                        <option value="percent">Percent</option>
                        <option value="fixed">Fixed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="number" step="any" name="margin_value[]"
                            class="form-control margin_value" id="margin_value" value="0"
                            placeholder="Profit Margin Value" onkeyup="calculate()">

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <label for="product_price">Selling Price (Rs.) :</label>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <input type="number" step="any" name="product_price[]"
                    class="form-control product_price" id="product_price" value="0"
                    placeholder="Selling Price in Rs.">

            </div>
        </div>`;
        }

        function option(options) {
            var options = "";
            for (let x = 0; x < count; x++) {
                options +=
                    "<option value =" +
                    taxes[x].id +
                    " data-percent = "+taxes[x].percent+">" +
                    taxes[x].title +
                    "(" +
                    taxes[x].percent +
                    "%)" +
                    "</option>";
            }
            return options;
        }

        function multigodown(multi){
            var multi = '';
            for(let x = 0; x<godowncount; x++){
                var godown_name = godowns[x].godown_name;
                var godown_id = godowns[x].id;
                multi += `
                <div class="form-group forProductWithoutSerialNo">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <input type="text" name="godown_name[]" class="form-control godown" value="${godown_name}" readonly="readonly">
                            <input type="hidden" name="godown_id[]" class="form-control godown" value="${godown_id}" >
                        </div>
                        <div class="col-md-6 mb-2 forProductHavingSerialNo">
                            <input type="text" name="serial_product[${godown_id}]" class="form-control serial_product" placeholder="eg;-abcd,1234">
                        </div>
                        <div class="col-md-3 mb-2">
                            <input type="number" name="godown_qty[${godown_id}][]" class="form-control godown_qty" step=".01" value="0">
                        </div>
                    </div>
                </div>



                `;
            }
            return multi;
        }
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
                    for(let j=0; j<productcount; j++)
                    {
                        if (products[j].product_images ) {
                            var image = products[j].product_images[0].location;
                        }else{
                            var image = '';
                        }

                        prodoptions += `<option value="${products[j].id }"
                                            data-rate="${products[j].total_cost}"
                                            data-stock="${products[j].stock}"
                                            data-priunit = "${products[j].primary_unit}"
                                            data-image = "${image}"
                                            data-has_serial_number="${products[j].has_serial_number}">
                                            ${products[j].product_name}(${products[j].product_code}) ${products[j].brand ? '-' : ''} ${products[j].brand.brand_name}
                                        </option>`;
                    }
                // cateoptions += `<option value='' class='title' disabled>${catename}</option>
                //                     ${prodoptions}
                //                 `;
                                cateoptions += `
                                    ${prodoptions}
                                `;
                }
                return cateoptions;
            }


            //for validating Products
$(function() {

    $('.item').change(function() {

        let productID = this.value;
       let avalProduct = window.categories;
       console.log(avalProduct);
       let particulars = $("select[name='particulars[]']").map(function(){return $(this).val()}).get();
      particulars = particulars.sort();
      console.log(particulars);

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


        jQuery(".item-row:last").after(`<tr class="item-row">
        <td class="item-name">
            <div class="delete-btn">
                <select name="particulars[]" class="form-control item">

                <option value="">--Select Option--</option>
                <option value="addproductoption" class="coloradd"> + Add New Product </option>
                ${cateoption(window.cateoptions)}
                </select>
                <a class="${ $.opt.delete.substring(1) }" href="javascript:;" title="Remove row">X</a>
            </div>
        </td>
        <td><input class="form-control qty" placeholder="Quantity" type="text" name="quantity[]" value="0">
        <div class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <input type="hidden" name="has_serial_number[]" value="">
        <div class="modal-dialog modalstock" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Stock Per Godown</h5>
                    <button type="button" class="close"
                        data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                   ${(multigodown(window.multi))}
                </div>
            </div>
        </div>
        </td>
        <td><input class="form-control unit" placeholder="Unit" type="text" name="unit[]" readonly></td>
        <td><input class="form-control rate" placeholder="Rate" type="text" name="rate[]">
        <div class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Price of Products</h5>
                        <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            ${productinfo}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </td>
        <td><input name="total[]" class="form-control total" value="0" readonly="readonly"></td>
        </tr>`);
        if (jQuery($.opt.delete).length > 0) {
            jQuery($.opt.delete).show();
        }
        $(".item").select2({
            // containerCssClass: "pink",
            templateResult: function (data, container) {
            if (data.element) {
                $(container).addClass($(data.element).attr("class"));
            }
            return data.text;
            }
        });
        $(document).on('focus', '.rate', function() {
            $(this).siblings('.modal').modal({
                show: true
            });
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
        $('#addRow').removeClass("disabled");
        $(this).closest('tr').removeClass("select-error");
        $('.submit').attr('disabled', false);
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
    price: ".price",
    godown: ".godown",
    godown_qty: '.godown_qty',
    qty: ".qty",
    total: ".total",
    totalQty: "#totalQty",

    // original_vendor_price: '.original_vendor_price',
    // charging_rate: '.charging_rate',
    // final_vendor_price: '.final_vendor_price',
    // carrying_cost: '.carrying_cost',
    // transportation_cost: '.transportation_cost',
    // miscellaneous_percent: '.miscellaneous_percent',
    // other_cost: '.other_cost',
    // product_cost: '.product_cost',
    // custom_duty: '.custom_duty',
    // after_custom: '.after_custom',
    // vat_select: '.vat_select',
    // total_cost: '.total_cost',
    // margin_type: '.margin_type',
    // margin_value: '.margin_value',
    // product_price: '.product_price',

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

