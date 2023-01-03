<template>
    <div id="printHotelPosBilling" class="row">
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
                    <tr class="product-table">
                        <td class="text-left"><b>Item</b></td>
                        <td><b>Qty</b></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in orderitems">
                        <td class="text-left">{{item.product_name}}</td>
                        <td>{{item.quantity}}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="1" class="text-left"><b>Total</b></td>
                        <td>{{totalQuantity}}</td>
                    </tr>
                </tfoot>
            </table>
            <p class="text-center">Electronically generated copy</p>
        </div>
    </div>
</template>
<style scoped>
#printHotelPosBilling{
    display: none;
    text-align: center;
    color: #000;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 13px;
}

#printHotelPosBilling .logo{
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

.product-table{
    border-bottom: 1px solid #111;
}
.product-table th{
    padding-top: 2px;
    padding-bottom: 2px;
    border: none;
    border-bottom: 1px solid #111;
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
            type: String
        },
        nepdate: {
            type: String
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
        orderitems: {
            type: Array,
            required: true
        },
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
            return this.orderitems.reduce((a, b) => +a + +b.quantity, 0);
        },
    }
}
</script>
