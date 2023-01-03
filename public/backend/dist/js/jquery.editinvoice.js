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
        this.calcdiscountamt();
        this.calcindTax();
        // this.calcTotal();
        this.calcTotalQty();
        this.calcitemTax();
        this.calcTotalTax();
        this.calcDiscount();
        this.calcSubtotal();
        this.calcGrandTotal();
        this.getdata();
    },

    // rate_init: function(){
    //     this.getrate();
    // },

    // getrate: function(){
    //     jQuery($.opt.parentClass).each(function (){
    //         var row = jQuery(this);
    //         clientratepercenttype = typeof(jQuery($.opt.client).find(':selected').data('dealer_percent'));
    //         if(clientratepercenttype == 'undefined'){
    //             datarate = row.find($.opt.item).find(":selected").data("rate");
    //             row.find($.opt.rate).val(datarate);
    //             row.find($.opt.spanrate).html(datarate);
    //         }else{
    //             clientratepercent = jQuery($.opt.client).find(':selected').data('dealer_percent');
    //             datarate = row.find($.opt.item).find(":selected").data("rate");
    //             rate_after_dealer_percent = datarate - datarate * clientratepercent/100;
    //             row.find($.opt.rate).val(rate_after_dealer_percent);
    //             row.find($.opt.spanrate).html(rate_after_dealer_percent);
    //         }
    //     })
    //     return 1;
    // },

    /**
     * Calculate total price of an item.
     *
     * @returns {number}
     */
    getdata: function () {
        jQuery($.opt.parentClass).each(function (i){
            row = jQuery(this);
            //product_image
            product_image = row.find($.opt.item).find(":selected").data("image");
            row.find($.opt.product_image).attr('src', product_image);
            //datarate
            // clientratepercenttype = typeof(jQuery($.opt.client).find(':selected').data('dealer_percent'));
            // if(clientratepercenttype == 'undefined'){
            //     datarate = row.find($.opt.item).find(":selected").data("rate");
            //     row.find($.opt.rate).val(datarate);
            //     row.find($.opt.spanrate).html(datarate);
            // }else{
            //     clientratepercent = jQuery($.opt.client).find(':selected').data('dealer_percent');
            //     datarate = row.find($.opt.item).find(":selected").data("rate");
            //     rate_after_dealer_percent = datarate - datarate * clientratepercent/100;
            //     row.find($.opt.rate).val(rate_after_dealer_percent);
            //     row.find($.opt.spanrate).html(rate_after_dealer_percent);
            // }

            //data Unit
            dataunit = row.find($.opt.item).find(":selected").data("priunit");
            row.find($.opt.unit).val(dataunit);
            dataunit = row.find($.opt.item).find(":selected").data("priunit");
            row.find($.opt.spanunit).html(dataunit);
            //data Stock
            datastock = row.find($.opt.item).find(":selected").data("stock");
            row.find($.opt.stock).html(datastock);
            //data Brand
            databrand = row.find($.opt.item).find(":selected").data("brand");
            row.find($.opt.brand).html(databrand);
            //data Model
            datamodel = row.find($.opt.item).find(":selected").data("model");
            row.find($.opt.model).html(datamodel);

            // if(Number(row.find($.opt.stock).html()) < Number(row.find($.opt.qty).val())){
            //     row.find($.opt.danger).html('Quantity is more than stock');
            //     $('.submit').prop("disabled", true);
            // }else{
            //     row.find($.opt.danger).html('');
            //     $('.submit').prop("disabled", false);
            // }
        });
    },

    calcdiscountamt: function () {
        jQuery($.opt.parentClass).each(function (i) {
            row = jQuery(this);
            rate = row.find($.opt.rate).val();
            distamt = row.find($.opt.dtamt).val();
            var rdiscounttype = row.find($.opt.discounttype);
            var selected = rdiscounttype.find(":selected").val();
            if(typeof selected == "undefined"){
                var damount = rate * distamt/100;
                rounddistamt = damount.toFixed(2);
                row.find($.opt.discountamt).val(rounddistamt);
            }
            else if(selected == "percent"){
                var damount = rate * distamt/100;
                rounddistamt = damount.toFixed(2);
                row.find($.opt.discountamt).val(rounddistamt);
            }else if(selected == "fixed"){
                var damount = Number(distamt);
                rounddistamt = damount.toFixed(2);
                row.find($.opt.discountamt).val(rounddistamt);
            }

        })
        return 1;
    },
    calcindTax: function() {
        totalTax = 0;
        var alltaxamt = 0
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


        // if (!isNaN(itemtax)) totalTax += Number(itemtax);
        //     console.log(totalTax);
        //     totalTax = self.roundNumber(totalTax, 2);
            // if(totalTax>0){
            //     jQuery($.opt.taxamount).val(totalTax);
            //     jQuery($.opt.taxamount).attr('readonly', "readonly");
            //     jQuery($.opt.taxamount).removeClass('alltaxamount');
            // }else if(totalTax == 0){
            //     jQuery($.opt.taxamount).val(totalTax);
            //     jQuery($.opt.taxamount).removeAttr('readonly', "readonly");
            //     jQuery($.opt.taxamount).addClass('alltaxamount');

            //     var allttype = jQuery($.opt.alltaxtype).find(":selected").val();
            //     var alltper = jQuery($.opt.alltaxper).find(":selected").val();
            //     var alltaxamt = jQuery($.opt.total).val() * alltper/100;
            //     alltaxamt = alltaxamt.toFixed(2);
            //     jQuery($.opt.taxamount).val(alltaxamt);
            //     if(typeof allttype == "undefined" || allttype == "exclusive"){
            //         window.att = allttype;
            //     }else if(allttype == "inclusive"){
            //         window.att = allttype;
            //     }
            // }

        return 1;
    },
    calcTotalTax: function() {
        var totalTax = 0;
        jQuery($.opt.itemtax).each(function (i) {
            var indtax = jQuery(this).val();
            if (!isNaN(indtax)) totalTax += Number(indtax);
        });
        totalTax = self.roundNumber(totalTax, 2);
        if(totalTax>0){
            jQuery($.opt.taxamount).val(totalTax);
            jQuery($.opt.taxamount).attr('readonly', "readonly");
            jQuery($.opt.taxamount).removeClass('off');
            jQuery($.opt.taxamount).addClass('on');
            jQuery($.opt.gtaxamount).removeClass('on');
            jQuery($.opt.gtaxamount).addClass('off');

            jQuery($.opt.gtaxamount).attr('disabled', true);
        }else if(totalTax == 0){
            jQuery($.opt.taxamount).val(totalTax);
            jQuery($.opt.taxamount).removeAttr('readonly', "readonly");
            // jQuery($.opt.taxamount).addClass('alltaxamount');
            jQuery($.opt.taxamount).removeClass('on');
            jQuery($.opt.taxamount).addClass('off');
            jQuery($.opt.gtaxamount).removeClass('off');
            jQuery($.opt.gtaxamount).addClass('on');
            jQuery($.opt.gtaxamount).attr('disabled', false);

            var allttype = jQuery($.opt.alltaxtype).find(":selected").val();
            var alltper = jQuery($.opt.alltaxper).find(":selected").val();
            var subtotal = jQuery($.opt.subtotal).val();
            var alldiscount = $('.alldiscount').val();
            var taxableamount = Number(subtotal) - Number(alldiscount);
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
        jQuery($.opt.parentClass).each(function (i){
            var row = jQuery(this);
            var tax = row.find($.opt.taxamt).val();
            var qty = row.find($.opt.qty).val();

            var itemTax = Number(tax) * Number(qty);
            itemTax = self.roundNumber(itemTax, 2);
            row.find($.opt.itemtax).val(itemTax);
            // console.log(itemTax);
        });
    },

    // calcTotal: function () {
    //      jQuery($.opt.parentClass).each(function (i) {
    //         var row = jQuery(this);
    //         var rtaxper = row.find($.opt.taxper);
    //         var selectedtaxper = rtaxper.find(":selected").val();
    //         var taxtype = row.find($.opt.taxtype);
    //         var selectedtaxtype = taxtype.find(":selected").val();

    //         if(selectedtaxtype == 'exclusive'){
    //             var txamt = row.find($.opt.rate).val() * selectedtaxper/100;
    //             roundtaxamt = Number(txamt.toFixed(2));
    //             var total = (row.find($.opt.rate).val() * row.find($.opt.qty).val())-(row.find($.opt.discountamt).val()* row.find($.opt.qty).val()) + parseFloat(roundtaxamt*row.find($.opt.qty).val());
    //             total = self.roundNumber(total, 2);

    //             row.find($.opt.total).val(total);
    //         }else if(selectedtaxtype == 'inclusive'){
    //             var total = (row.find($.opt.rate).val() - row.find($.opt.discountamt).val()) * row.find($.opt.qty).val();

    //             total = self.roundNumber(total, 2);

    //             row.find($.opt.total).val(total);
    //         }
    //      });

    //      return 1;
    //  },

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
        // console.log(window.att);
        if(typeof window.att == "undefined"){
            var grandTotal = Number(jQuery($.opt.subtotal).val())
                        + Number(jQuery($.opt.shipping).val())
                        - Number(jQuery($.opt.discount).val());

            grandTotal = self.roundNumber(grandTotal, 2);

            jQuery($.opt.grandTotal).val(grandTotal);
        }
        else if(window.att == "inclusive"){
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
    var taxes = window.taxes;
    var count = taxes.length;
    var quotation = window.quotationsetting;
        var selgodown = $('#godown').find(":selected").val();
        var warehouses = window.warehouses;
        var warehousecount = warehouses.length;
        function wareproducts(){

            for(let i=0; i<warehousecount; i++)
            {
                if(warehouses[i].id == selgodown)
                {
                    var godownpros = warehouses[i].godownproduct;
                    var gdpcount = godownpros.length;
                    var prodoptions = '';
                    for(let s = 0; s<gdpcount; s++)
                    {
                        var stock = godownpros[s].stock;
                        var proname = godownpros[s].product.product_name;
                        var rate = godownpros[s].product.product_price;
                        var primary_unit = godownpros[s].product.primary_unit;
                        var procode = godownpros[s].product.product_code;
                        var proid = godownpros[s].product_id;
                        var probrand = godownpros[s].product.brand.brand_name;
                        var promodel = godownpros[s].product.series.series_name;

                        var pimg = godownpros[s].product.product_images[0].location;

                        var img_url = window.aurl+'uploads/'+pimg;

                        prodoptions += `<option value="${proid}"
                                            data-rate="${rate}"
                                            data-stock="${stock}"
                                            data-priunit = "${primary_unit}"
                                            data-brand = "${probrand}"
                                            data-model = "${promodel}"
                                            data-image = "${img_url}">
                                            ${proname}(${procode})
                                        </option>`;
                    }

                }
            }
            return prodoptions;
        }

    // var categories = window.categories;
    // var catecount = categories.length;
    // function cateoption(cateoptions){
    //     var cateoptions = '';
    //     for(let a=0; a<catecount; a++){
    //         var catename = categories[a].category_name;
    //         var products = categories[a].products;
    //         // console.log(products);
    //         var productcount = products.length;
    //         var prodoptions = '';
    //             for(let j=0; j<productcount; j++){
    //                 // var productname = products[j].product_name;
    //                 prodoptions += `<option value="${products[j].id }"
    //                                     data-rate="${products[j].product_price}"
    //                                     data-stock="${products[j].stock}"
    //                                     data-priunit = "${products[j].primary_unit}">
    //                                     ${products[j].product_name}(${products[j].product_code})
    //                                 </option>`;
    //             }
    //         cateoptions += `<option value='' class='title' disabled>${catename}</option>
    //                             ${prodoptions}
    //                         `;
    //         }
    //         return cateoptions;
    //     }



        function option(options)
        {
            var options = '';
            for(let x=0; x<count; x++){
                options += "<option value ="+taxes[x].percent+">"+ taxes[x].title+"("+taxes[x].percent+"%)"+"</option>"
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

        jQuery(".item-row:last").after(`<tr class="item-row">
        <td class="item-name">
            <div class="delete-btn">
                <select name="particulars[]" class="form-control item">
                <option value="">--Select Option--</option>
                ${wareproducts()}
                </select>
                <a class="${ $.opt.delete.substring(1) }" href="javascript:;" title="Remove row">X</a>
            </div>
        </td>
        <td style="${quotation.show_picture == 0 ? 'display:none' : ''}"><img src="" style="max-height:50px;" class="product_image"></td>
        <td style="${quotation.show_brand == 0 ? 'display:none' : ''}">
        <span class="text-center brand"></span>
        </td>
        <td style="${quotation.show_model == 0 ? 'display:none' : ''}">
        <span class="text-center model"></span>
        </td>
        <td><input class="form-control qty" placeholder="Quantity" type="text" name="quantity[]"> </td>
        <td style="${selected_filter_array.includes('stock') == false ? 'display:none' : ''}">
            <span class="stock"></span>
            <p class="text-danger danger"></p>
        </td>

        <td style="${selected_filter_array.includes('unit') == false ? 'display:none' : ''}">
            <input class="form-control unit" placeholder="Unit" type="hidden"
                name="unit[]" value="{{$billingextra->unit}}" readonly>
            <span class="unit"></span>
        </td>
        <td style="${selected_filter_array.includes('rate') == false ? 'display:none' : ''}">
            <input class="form-control rate" placeholder="Rate" name="rate[]">
        </td>
        <td style="${selected_filter_array.includes('discount') == false ? 'display:none' : ''}"><input class="form-control discountamt" placeholder="Discount per unit" type="text" name="discountamt[]" value="0">
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
        <td style="${selected_filter_array.includes('tax') == false ? 'display:none' : ''}">
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
        $(".item").select2();

        return 1;
    },

    /**
     * Delete a row.
     *
     * @param elem   current element
     * @returns {number}
     */
    deleteRow: function (elem)
    {
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
    roundNumber: function (number, decimals)
    {
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

    godown:"#godown",
    item: ".item",
    product_image: ".product_image",
    brand: '.brand',
    model: '.model',
    stock:".stock",
    danger:".danger",
    spanunit: 'span.unit',
    spanrate: 'span.rate',
    price: ".price",
    qty: ".qty",
    discountamt : 'input.discountamt',
    discounttype : 'select.discounttype',
    dtamt : 'input.dtamt',
    taxamt: '.taxamt',
    taxtype: '.taxtype',
    taxper : '.taxper',
    total: ".total",
    totalQty: "#totalQty",
    alltaxtype: ".alltaxtype",
    alltaxper : ".alltaxper",
    itemtax : ".itemtax",
    gtaxamount: ".gtaxamount",

    subtotal: "#subtotal",
    discount: "#discount",
    shipping: "#shipping",
    grandTotal: "#grandTotal",
    submit: ".submit"
};
