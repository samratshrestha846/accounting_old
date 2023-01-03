<template>
<div id="salereportModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Today Sale Report</h5>
                <button type="button" class="close text-white" @click="closeModal()">
                        <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table width="100%" class="stable table-bordered">
                    <tbody>
                        <tr>
                            <td colspan="2"><b style="font-size:15px;">Tuesday, November 30, 2021</b></td>
                        </tr>
                        <tr>
                            <td><b>Cash Payment:</b></td>
                            <td style="text-align:right;"><span style="display:block;text-align: right;">{{cashPayment}}</span></td>
                        </tr>
                        <tr>
                            <td><b>Cheque Payment:</b></td>
                            <td style="text-align:right;"><span style="display:block;text-align: right;">{{chequePayment}}</span></td>
                        </tr>
                        <tr>
                            <td><b>Credit Card Payment:</b></td>
                            <td style="text-align:right;"><span style="display:block;text-align: right;">{{creditcardPayment}}</span></td>
                        </tr>
                        <tr>
                            <td width="300px;"><b style="display:block;font-size:13px;font-weight:bold;">Total:</b></td>
                            <td width="200px;" style="text-align:right;"><span style="display:block;text-align: right;font-weight:bold;font-size:13px;">{{totalPayment}}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</template>
<script>
var moment = require('moment');
export default {
    props: {
        outlet: {
            type: Object,
            required: true,
        }
    },
    data() {
        return {
            todayDate: this.getTodayDate(),
            cashPayment: 0,
            chequePayment: 0,
            creditcardPayment: 0,
        }
    },
    computed: {
        totalPayment(){
            return parseFloat(this.cashPayment + this.chequePayment + this.creditcardPayment).toFixed(2);
        }
    },
    methods: {
        openModal(){
            this.todayDate = this.getTodayDate();
            this.fetchTodaySaleReport();
            $('#salereportModal').modal('show');
        },
        closeModal(){
            $('#salereportModal').modal('hide');
        },
        fetchTodaySaleReport() {
            this.$store.dispatch('GET_TODAY_SALES_REPORT', {
                outlet_id: this.outlet.id,
            })
            .then(response => {
                this.cashPayment = parseFloat(response.data.data.cash_payment).toFixed(2);
                this.chequePayment = parseFloat(response.data.data.cheque_payment).toFixed(2);
                this.creditcardPayment = parseFloat(response.data.data.creditcard_payment).toFixed(2);
            });
        },
        getTodayDate() {
            var now = new Date();

            return moment(now).format('dddd, DD MMMM YYYY');
        }
    }
}
</script>
