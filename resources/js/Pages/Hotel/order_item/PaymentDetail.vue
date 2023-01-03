<template>
    <div class="modal fade" id="payment_details" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Payment Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div v-if="!loadingData && billing">
                        <p>
                            <span class="badge badge-primary mr-2 p-1"><b>Total Amount:</b>Rs. {{grandTotal}}</span>
                            <span class="badge badge-success mr-2 p-1"><b>Paid Amount:</b>Rs. {{totalPaidAmount}}</span>
                            <span data-dueamount="0" class="badge badge-danger dueamount mr-2 p-1"><b>Due Amount:</b>Rs.{{dueAmount}}</span>
                        </p>
                        <ul id="myTab" role="tablist" class="nav nav-tabs">
                            <li class="nav-item">
                                <a id="details-tab" data-toggle="tab" href="#details" role="tab" class="nav-link active">Details</a>
                            </li>
                            <li class="nav-item">
                                <a id="create-tab" data-toggle="tab" href="#create" role="tab" class="nav-link">CreatePayment</a>
                            </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div id="details" role="tabpanel" class="tab-pane fade active show">
                                <div class="container p-3">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Payment Date</th>
                                                <th scope="col">Payment Type</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Paid To</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-if="billing.payment_infos && billing.payment_infos.length > 0"
                                                v-for="pinfo in billing.payment_infos"
                                                :key="pinfo.id"
                                            >
                                                <th scope="row">{{ pinfo.payment_date }}</th>
                                                <td>
                                                    <span v-if="pinfo.payment_type == 'paid'">Paid</span>
                                                    <span v-if="pinfo.payment_type == 'unpaid'">Unpaid</span>
                                                    <span v-if="pinfo.payment_type == 'partially_paid'">Partially Paid</span>
                                                </td>
                                                <td>{{ pinfo.total_paid_amount }}</td>
                                                <td>{{ billing.user_entry.name }}</td>
                                                <td><a :href="'/paymentinfo/'+pinfo.id+'/edit'"
                                                        class='edit btn btn-primary btn-sm mt-1'
                                                        data-toggle='tooltip' data-placement='top'
                                                        title='Edit'><i class='fa fa-edit'></i></a>
                                                </td>
                                            </tr>
                                            <tr v-else><td colspan="5">No any data.</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="create" role="tabpanel" aria-labelledby="create-tab" class="tab-pane fade">
                                <div class="container p-3">
                                    <p v-if="dueAmount < 0" class="bold">Fully Paid</p>
                                    <form v-else @submit.prevent="actionPayment">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input
                                                        type="hidden"
                                                        name="billing_id"
                                                        :value="billing.id"
                                                    >
                                                    <label for="payment_type">Payment Type</label>
                                                    <select
                                                        name="payment_type"
                                                        id="payment_type"
                                                        class="form-control"
                                                        required
                                                        v-model="form.payment_type"
                                                    >
                                                        <option value="paid">Paid</option>
                                                        <option value="partially_paid">Partially
                                                            Paid</option>
                                                        <option value="Unpaid">Unpaid</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="payment_date">Payment Date</label>
                                                    <input
                                                        type="date"
                                                        value=""
                                                        id="payment_date"
                                                        name="payment_date"
                                                        class="form-control"
                                                        required
                                                        v-model="form.payment_date"
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="payment_amount">Payment
                                                        Amount</label>
                                                    <input
                                                        type="text"
                                                        name="payment_amount"
                                                        id="payment_amount"
                                                        class="form-control"
                                                        placeholder="Enter Paid Amount"
                                                        v-model="form.payment_amount"
                                                        required
                                                    >
                                                    <p v-if="errorPaymentAmount" class="off text-danger">{{errorPaymentAmount}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="errorMessage" class="alert alert-danger">
                                            {{errorMessage}}
                                        </div>
                                        <LoadingButton
                                            type="submit"
                                            title="submit"
                                            class="btn btn-success submit"
                                            :loading="loadingBtn"
                                        >
                                        </LoadingButton>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="loadingData" class="loading text-center">
                        <div class="spinner-border text-secondary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import $ from "jquery"
import LoadingButton from '@/components/Ui/LoadingButton'

export default {
    props: ['billing_id'],
    components: {
        LoadingButton
    },
    data() {
        return {
            loadingData: false,
            loadingBtn: false,
            billing: null,
            form: {
                billling_id: null,
                payment_type: 'paid',
                payment_date: "",
                payment_amount: 0.00,
                errors: null,
            },
            errorMessage: null,
            errorPaymentAmount: null,
        }
    },
    computed: {
        grandTotal() {
            return Number(this.billing.grandtotal);
        },
        totalPaidAmount() {
            return this.billing.payment_infos.reduce((a, b) => +a + +b.total_paid_amount, 0).toFixed(2);
        },
        dueAmount() {
            return this.grandTotal - this.totalPaidAmount;
        },
    },
    mounted() {
        // this.findBilling();
    },
    methods: {
        openModal() {
            $('#payment_details').modal('show');
            this.loadingData =  true;
            this.findBilling();
        },
        closeModal() {
            $('#payment_details').modal('hide');
        },
        findBilling() {

            this.$store.dispatch('FIND_RESTAURANT_BILLING', this.billing_id)
                .then(response => {
                    this.loadingData = false;
                    this.billing = response.data.data;
                    this.form.billing_id = this.billing.id;
                })
                .catch(error => {
                    this.$toast.open({
                        message: 'Something went wrong',
                        type: 'success',
                        position: 'top-right'
                    });
                });
        },
        actionPayment() {
            this.loadingBtn = true;
            this.errorMessage = null;
            this.errors  = null;
            this.errorPaymentAmount = null;


            if(Number(this.form.payment_amount) > this.dueAmount) {
                this.errorPaymentAmount = "You cannot add payment amount more than due amount";
                return;
            }

            this.form.billing_id = this.billing.id;

            axios.post('/paymentinfo', this.form)
            .then(response => {
                this.$toast.open({
                    message: 'Payment successfully created',
                    type: 'success',
                    position: 'top-right'
                });
                this.closeModal();
            })
            .catch(error => {
                if(error.response.status === 422) {
                    this.form.errors = error.response.data.errors;
                }else {
                    this.errorMessage = "Something went wrong";
                }
            })
            .finally(() => {
                this.loading = false;
            });
        }
    }
}
</script>
