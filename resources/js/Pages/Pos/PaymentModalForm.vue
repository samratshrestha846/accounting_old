<template>
    <form @submit.prevent="submit()">
        <div id="paymentModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Finalize Sale</h5>
                        <button type="button" class="close text-white" @click="closeModal()">
                                <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-form">
                            <!--<div class="row mb-3">
                                <div class="col-4 text-right">
                                    <label>Warehouse</label>
                                </div>
                                <div class="col-6">
                                    <select
                                        class="form-control"
                                        name="taxRate"
                                        v-model.trim = "form.godown"
                                    >
                                        <option value="">--None--</option>
                                        <option v-for="godown in godowns" :value="godown.id">{{godown.godown_name}}</option>
                                    </select>
                                    <div class="error text-red" v-if="!$v.form.godown.required">Please select a warehouse</div>
                                </div>
                            </div>-->
                            <!--<div class="row mb-3">
                                <div class="col-4 text-right">
                                    <label>Biller</label>
                                </div>
                                <div class="col-6">
                                    <select
                                        class="form-control"
                                        name="taxRate"
                                        v-model.trim = "form.biller"
                                    >
                                        <option value="">--None--</option>
                                        <option v-for="biller in billers" :value="biller.id">{{biller.name}}</option>
                                    </select>
                                    <div class="error text-red" v-if="!$v.form.biller.required">Please select a biller</div>
                                </div>
                            </div> -->
                            <div class="row mb-2">
                                <div class="col-4 text-right">
                                    <label>Total Payable</label>
                                </div>
                                <div class="col-6">
                                <span class="badge badge-danger text-sm">{{defaultCurrency}} {{parseFloat(grossTotal).toFixed(2)}}</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-right">
                                    <label>Total Items</label>
                                </div>
                                <div class="col-6">
                                <span class="badge badge-danger text-sm">{{totalItems}}</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-right">
                                    <label>Paid By</label>
                                </div>
                                <div class="col-6">
                                    <select
                                        class="form-control"
                                        name="taxRate"
                                        v-model.trim = "form.payment_type"
                                    >
                                        <option v-for="paymentType in paymentTypes" :value="paymentType">
                                            <span v-if="parseInt(paymentType) === 1">Cash</span>
                                            <span v-else-if="parseInt(paymentType) === 2">Cheque</span>
                                            <span v-else-if="parseInt(paymentType) === 3">Bank Deposite</span>
                                        </option>
                                    </select>
                                    <div class="error text-red" v-if="!$v.form.payment_type.required">Please select a payment type</div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-right">
                                    <label>Paid</label>
                                </div>
                                <div class="col-6">
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model.trim ="form.payment_amount"
                                    >
                                    <div class="error text-red" v-if="!$v.form.payment_amount.required">Payment amount is required</div>
                                    <div class="error text-red" v-if="!$v.form.payment_amount.decimal">Payment amount should be in number</div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-right">
                                    <label>Remarks <span class="text-muted">(Optional)</span></label>
                                </div>
                                <div class="col-6">
                                    <textarea v-model="form.remarks" class="form-control" style="height:50px;"></textarea>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-right">
                                    <label>Change</label>
                                </div>
                                <div class="col-6">
                                <span class="badge badge-danger text-sm">{{defaultCurrency}} {{changeAmount}}</span>
                                </div>
                            </div>
                            <div v-if="errorMessage" class="row text-center">
                                <div class="col-12">
                                    <span class="text-red">{{errorMessage}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <LoadingButton
                            type="  "
                            title="submit"
                            class="btn btn-primary"
                            style="border-radius:.2rem !important;"
                            :loading="submitLoading"
                        ></LoadingButton>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
<script>
import {mapGetters} from 'vuex'
import { required, numeric, decimal } from 'vuelidate/lib/validators'
import LoadingButton from '@/components/Ui/LoadingButton'

export default {
    props: {
        customer: {
            type: Object,
            required: true,
        },
        billers: {
            type: Array,
        },
        paymentTypes: {
            type: Array,
        },
        outlet: {
            type: Object,
            required: true,
        },
        salesItems: {
            type: Array
        },
        allTaxType: {
            type: String,
        },
        allTax: {
            type: Number
        },
        allDiscountType: {
            type: String
        },
        allDiscount: {
            type: Number
        },
        grossTotal: {
            type: Number
        },
        defaultCurrency: {
            type: String,
            required: true
        }
    },
    components: {
        LoadingButton
    },
    data() {
        return {
            submitLoading: false,
            errorMessage: "",
            form: {
                biller: "",
                godown: "",
                payment_type: 1,
                payment_amount: null,
                remarks: null,
            }
        }
    },
    computed: {
        ...mapGetters(['godowns']),
        changeAmount(){
            if(!this.form.payment_amount)
                return 0;

            if(this.form.payment_amount <= this.grossTotal)
                return 0;

            return parseFloat(this.form.payment_amount - this.grossTotal).toFixed(2);
        },
        totalItems(){
            return this.salesItems.reduce((a, b) => +a + +b.quantity, 0);
        }
    },
    mounted(){

    },
    methods: {
        openModal(){
            $('#paymentModal').modal('show');
        },
        closeModal(){
            $('#paymentModal').modal('hide');
        },
        submit(){

            this.$v.form.$touch();
            if (this.$v.$invalid) {
                return;
            }

            if(this.form.payment_amount <= 0 ){
                alert("Payment amount cannot be negative number");
            }

            this.submitLoading = true;
            this.errorMessage = "";

            axios.post('/api/pos/outlets/'+this.outlet.id+'/saleitems/purchase',{
                outlet_id: this.outlet.id,
                customer_id: this.customer?.id,
                // godown: this.form.godown,
                products: this.salesItems,
                alltaxtype : this.allTaxType,
                alltax : this.allTax,
                alldiscounttype : this.allDiscountType,
                alldiscountvalue : this.allDiscount,
                biller: this.form.biller,
                payment_mode: this.form.payment_type,
                payment_amount: this.form.payment_amount,
                gross_total: this.grossTotal,
                remarks: this.form.remarks,
            }).then(response => {
                this.submitLoading = false;

                this.closeModal();
                this.$emit('success', response.data.data);
                window.location.href = '/pos/billing/'+response.data.data.id+'/view-invoice';
            })
            .catch(error => {
                this.submitLoading = false;
                this.errorMessage = "Something went wrong. Please reload page...";
            });

        },
        resetErrors(){
            this.form.errors.discountType = null;
            this.form.errors.discount = null;
        }
    },
    validations: {
        form: {
            // godown: {
            //     required
            // },
            // biller: {
            //     required
            // },
            payment_type: {
                required,
            },
            payment_amount: {
                required,
                decimal
            }
        },
    }
}
</script>
