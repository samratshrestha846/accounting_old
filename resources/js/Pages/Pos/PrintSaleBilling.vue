<template>
    <div id="printPosBilling" class="row">
        <div class="w-50 m-auto">
            <img class="logo" :src="baseUrl +'/uploads/logo.png'" alt="LekhaBidhi">
            <h1 class="text-center">{{company.name}}</h1>
            <p class="bold text-center">{{company.local_address}}, {{company.districts.dist_name}}, Nepal</p>
            <p class="bold text-center">{{company.phone}}, {{company.email}}</p>
            <p class="bold">Date: {{engDate}}/{{nepDate}} </p>
            <p class="bold">Customer Name: {{customer.name}}</p>
            <table class="table">
                <thead>
                    <tr>
                        <td>Product</td>
                        <td>Rate</td>
                        <td>Qty</td>
                        <td>Unit</td>
                        <!-- <td>Discount</td> -->
                        <!-- <td>Tax</td> -->
                        <td>Amount</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="salesItem in salesItems">
                        <td class="text-left">{{salesItem.product_name}}</td>
                        <td>{{salesItem.product_price}}</td>
                        <td>{{salesItem.quantity}}</td>
                        <td>{{salesItem.purchase_unit}}</td>
                        <!-- <td>{{parseFloat(salesItem.total_discount).toFixed(2)}}</td> -->
                        <!-- <td>{{parseFloat(salesItem.total_tax).toFixed(2)}}</td> -->
                        <td>{{parseFloat(salesItem.gross_total).toFixed(2)}}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-left">Total</td>
                        <td>{{totalQuantity}}</td>
                        <!-- <td>0.00</td> -->
                        <!-- <td>0.00</td> -->
                        <td colspan="2" class="text-right">{{subTotal.toFixed(2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-left">Bulk Discount</td>
                        <td>{{bulkDiscount.toFixed(2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-left">Bulk Tax {{taxType ? ('(' + taxType + ')'): ''}}</td>
                        <td>{{bulkTax}}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-left">Grand Total</td>
                        <td>{{grossTotal.toFixed(2)}}</td>
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
        engDate: {
            type: String
        },
        nepDate: {
            type: String
        },
        userCompany: {
            type: Object,
            required: true
        },
        customer: {
            type: Object,
            required: true,
        },
        salesItems: {
            type: Array,
            required: true,
        },
        subTotal: {
            type: Number
        },
        bulkDiscount: {
            type: Number
        },
        bulkTax: {
            type: Number
        },
        taxType: {
            type: String
        },
        grossTotal: {
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
            return this.userCompany?.company;
        },
        totalQuantity(){
            return this.salesItems.reduce((a, b) => +a + +b.quantity, 0);
        },
    }
}
</script>
