<template>
<div class="wrapper">
    <img class="brand-logo" :src="baseUrl + '/uploads/' + company.company_logo" alt="Biller logo">
    <h3 class="company-name">{{company.name}}</h3>
    <p class="company-address">{{company.local_address}}</p>
    <div class="col">
        <div class="col-6 text-left">
            <span>TAX/PAN Number: {{company.pan_vat}}</span>
        </div>
        <div class="col-6 text-right">
            <span>Tel: {{company.phone}}</span>
        </div>
    </div>
    <div class="col">
        <div class="col-12 text-left">
            <span>Reference No: {{billing.reference_no}}</span>
        </div>
    </div>
    <div class="col">
        <div class="col-12 text-left">
            <span>Pos Header Name: Nectargit</span>
        </div>
    </div>
    <div class="col">
        <div class="col-6 text-left">
            <span>Customer: {{customer.name}}</span>
        </div>
        <div class="col-6 text-right">
            <span>Date: {{billing.eng_date}}</span>
        </div>
    </div>
    <div class="">
        <table class="table" cellspacing="0" border="0">
            <thead>
                <tr class="product-table">
                    <th><em>#</em></th>
                    <th>Description</th>
                    <th>Qty</th>
                    <!-- <th>Unit</th> -->
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(billingExtra,index) in billingExtras">
                    <td style="text-align:center; width:30px;">{{index+1}}</td>
                    <td style="text-align:left; width:180px;">7005 Tourist</td>
                    <td style="text-align:center; width:50px;">{{billingExtra.quantity}}</td>
                    <td style="text-align:right; width:55px; ">{{billingExtra.rate}}</td>
                    <td style="text-align:right; width:65px;">{{billingExtra.total}}</td>
                </tr>
            </tbody>
        </table>
        <table class="totals" cellspacing="0" border="0">
            <tbody>
                <tr>
                    <td style="text-align:left;">Total Items</td>
                    <td style="text-align:right; padding-right:1.5%; border-right: 1px solid #999;font-weight:bold;">{{totalItems}}</td>
                    <td style="text-align:left; padding-left:1.5%;">Total</td>
                    <td style="text-align:right;font-weight:bold;">{{billing.subtotal}}</td>
                </tr>
                <tr>
                    <td></td> <td></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:left;">Discount</td>
                    <td colspan="2" style="text-align:right;font-weight:bold;">{{billing.discountamount}}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:left;">Tax</td>
                    <td colspan="2" style="text-align:right;font-weight:bold;">{{billing.taxamount}}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:left; font-weight:bold; border-top:1px solid #000; padding-top:10px;">Total Payable</td>
                    <td colspan="2" style="border-top:1px solid #000; padding-top:10px; text-align:right; font-weight:bold;">{{billing.grandtotal}}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">Paid</td>
                    <td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">500.00</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">Change</td>
                    <td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">30.00</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="buttons" style="padding-top:10px; text-transform:uppercase;">
        <div class="col">
            <span class="left" style="width:60%;margin-right:10px;">
                <button type="button" class="btn btn-success" onclick="window.print();return false;">Print</button>
            </span>
            <span class="right" style="width:40%;">
                <button type="button" class="btn btn-warning" onclick="window.print();return false;">Print</button>
            </span>
        </div>
        <div style="clear:both;"></div>
        <a href="#" class="btn btn-primary">Back to POS</a>
        <div style="clear:both;"></div>
        <div style="background:#F5F5F5; padding:10px;">
            <p style="font-weight:bold;">Please don't forget to disble the header and footer in browser print settings.</p>
            <p style="text-transform: capitalize;">
                <strong>FF:</strong> File &gt; Print Setup &gt; Margin &amp; Header/Footer Make all --blank--
            </p>
            <p style="text-transform: capitalize;">
                <strong>chrome:</strong> Menu &gt; Print &gt; Disable Header/Footer in Option &amp; Set Margins to None
            </p>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>
</template>
<style scoped>
.wrapper{
    width: 280px;
    margin: 0 auto;
}
.text-right{
    text-align: right;
}
.text-left{
    text-align: left;
}
p{
    font-size: 12px;
}
table {
    width: 100%;
}
.brand-logo{
    max-width: 250px;
    width: auto;
}
.company-name{
    text-transform:uppercase;
    font-size: 0.9rem;
    font-weight: bold;
    text-align: center;
}
.col{
    width: 100%;
    display: flex;
    padding-left: 0;
    padding-right: 0;
    margin-bottom: 4px;
}
.col .col-6{
    flex-grow: 1;
    padding-left: 0;
}
.col .col-12{
    padding-left: 0;
}
.product-table{
    border-bottom: 1px solid #111;
}
.product-table th{
    padding-top: 2px;
    padding-bottom: 2px;
    border: none;
    border-bottom: 1px solid #111;
}

.btn {
    width:100%;
    cursor:pointer;
    font-size:12px;
    text-align: center;
    border:1px solid #FFA93C;
    padding: 10px 1px;
    font-weight:bold;
    border-radius: 0;
}
.btn-success {
    color: rgb(0, 0, 0);
    background-color: rgb(79, 169, 80);
    border: 2px solid rgb(79, 169, 80);
}
.btn-warning {
    background-color: rgb(255, 169, 60);
    color: rgb(0, 0, 0);
    border: 1px solid rgb(255, 169, 60);
}

.btn-primary{
    color: rgb(255, 255, 255);
    background-color: rgb(0, 127, 255);
    border: 2px solid rgb(0, 127, 255);
}
</style>
<script>
export default {
    props: {
        billing: {
            type: Object,
            required: true
        },
        userCompany: {
            type: Object,
            required: true,
        }
    },
    data(){
        return {
            baseUrl: window.location.origin,
        }
    },
    computed: {
        company(){
            return this.userCompany?.company;
        },
        customer(){
            return this.billing.client || null;
        },
        billingExtras(){
            return this.billing.billingextras || [];
        },
        totalItems(){
            return this.billingExtras.reduce((a, b) => +a + +b.quantity, 0);
        }
    }
}
</script>
