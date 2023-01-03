<template>
    <div id="printPosBilling" class="row">
        <div class="w-50 m-auto">
            <img class="logo" :src="company.company_logo ? ( baseUrl + '/uploads/' + company.company_logo) : (baseUrl +'/uploads/logo.png')" alt="LekhaBidhi">
            <h1 class="text-center">{{company.name}}</h1>
            <p class="bold text-center">{{company.local_address}}, {{company.districts.dist_name}}, Nepal</p>
            <p class="bold text-center">{{company.phone}}, {{company.email}}</p>
            <p class="bold">Date: {{engdate}}/{{nepdate}} </p>
            <p class="bold">Customer Name: {{customer.name}}</p>
            <p v-if="table" class="bold">Table: {{table.name}}</p>
            <table class="table">
                <thead>
                    <tr>
                        <td class="text-left"><b>Item</b></td>
                        <td><b>Rate</b></td>
                        <td><b>Qty</b></td>
                        <!-- <td>Discount</td> -->
                        <!-- <td>Tax</td> -->
                        <td><b>Amount</b></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="salesItem in salesitems">
                        <td class="text-left">{{salesItem.product_name}}</td>
                        <td>{{salesItem.product_price}}</td>
                        <td>{{salesItem.quantity}}</td>
                        <!-- <td>{{parseFloat(salesItem.total_discount).toFixed(2)}}</td> -->
                        <!-- <td>{{parseFloat(salesItem.total_tax).toFixed(2)}}</td> -->
                        <td>{{parseFloat(salesItem.gross_total).toFixed(2)}}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="1" class="text-left"><b>Total</b></td>
                        <td>{{totalQuantity}}</td>
                        <td></td>
                        <!-- <td>0.00</td> -->
                        <td>{{subtotal.toFixed(2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-left"><b>Bulk Discount</b></td>
                        <td>{{bulkdiscount.toFixed(2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-left"><b>Bulk Tax {{taxtype ? ('(' + taxtype + ')'): ''}}</b></td>
                        <td>{{bulktax}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-left"><b>Service Charge({{servicechargerate}}%)</b></td>
                        <td>{{bulkservicecharge}}</td>
                    </tr>
                    <tr v-if="iscabin">
                        <td colspan="3" class="text-left"><b>Cabin Charge</b></td>
                        <td>{{cabinchargeamount}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-left"><b>Grand Total</b></td>
                        <td>{{grosstotal.toFixed(2)}}</td>
                    </tr>
                </tfoot>
            </table>
            <p class="text-center">Electronically generated copy</p>
        </div>
    </div>
</template>
<style scoped>
#printPosBilling{
    display: none;
    text-align: center;
    color: #000;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
}

#printPosBilling .logo{
    max-width: 250px;
    width: auto;
}

.text-center{
    text: center;
}

.text-left {
    text-align: left;
}

.w-50{
    width: 50%;
}

.m-auto{
    margin: auto 0;
}

.table  tr{
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
}

table {
    table-layout: fixed;
}
.table td, .table th {
    word-wrap: break-word;
}

.table thead th{
    padding: 0;
}


</style>
<script>
export default {
    props: {
        engdate: {
            type: String,
            required: true,
        },
        nepdate: {
            type: String,
            required: true,
        },
        usercompany: {
            type: Object,
            required: true
        },
        customer: {
            type: Object,
            required: true,
        },
        table: {
            type: Object,
        },
        salesitems: {
            type: Array,
            required: true,
        },
        subtotal: {
            type: Number
        },
        bulkdiscount: {
            type: Number
        },
        bulktax: {
            type: Number
        },
        servicechargerate: {
            type: Number
        },
        bulkservicecharge: {
            type: Number
        },
        iscabin: {
            type: Boolean
        },
        cabinchargeamount: {
            type: Number
        },
        taxtype: {
            type: String
        },
        grosstotal: {
            type: Number
        }
    },
    data(){
        return {
            baseUrl: window.location.origin,
        }
    },
    computed: {
        company(){
            return this.usercompany?.company;
        },
        totalQuantity(){
            return this.salesitems.reduce((a, b) => +a + +b.quantity, 0);
        },
    }
}
</script>
