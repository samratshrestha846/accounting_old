<template>
<div id="taxRateModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Tax to all sale items</h5>
                <button type="button" class="close text-white" @click="closeModal()">
                        <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-form">
                    <div class="row mb-3">
                        <div class="col-4 text-right">
                            <label>Tax type</label>
                        </div>
                        <div class="col-6">
                            <select
                                class="form-control"
                                name="taxRate"
                                v-model = "form.taxType"
                                required
                            >
                                <option value="">--None--</option>
                                <option v-for="taxType in taxTypes" :value="taxType">{{taxType}}</option>
                            </select>
                            <span v-if="form.errors && form.errors.taxType" class="text-red">{{form.errors.taxType}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">
                            <label>Tax Rate</label>
                        </div>
                        <div class="col-6">
                            <select
                                class="form-control"
                                name="taxRate"
                                v-model="form.tax"
                                required
                            >
                                <option v-for="tax in taxes" :value="tax.id">{{tax.title + '(' + tax.percent + '%)'}}</option>
                            </select>
                            <span v-if="form.errors && form.errors.tax" class="text-red">{{form.errors.tax}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="submit()">Save</button>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
    props: {
        taxes: {
            type: Array,
        },
        taxTypes: {
            type: Array
        },
        canTax: {
            type: Boolean,
            default: false,
        }
    },
    data() {
        return {
            form: {
                tax: null,
                taxType: "",
                errors: {
                    tax: null,
                    taxType: null
                }
            }
        }
    },
    watch:{
        'form.taxType'(newVal){
            if(!newVal){
                this.form.tax = null;
            }
        },
    },
    methods: {
        openModal(){
            $('#taxRateModal').modal('show');
        },
        closeModal(){
            $('#taxRateModal').modal('hide');
        },
        submit(){
            if(!this.canTax){
                alert("You cant add tax for all sales item");
                return;
            }

            //if the form has discount type and not discount value then throw validation error
            if(this.form.taxType && !this.form.tax){
                this.form.errors.tax = "Tax value is required";
                return;
            }

            this.resetErrors();

            this.$emit('success', this.form.taxType, parseFloat(this.form.tax));
            this.closeModal();
        },
        resetErrors(){
            this.form.errors.taxType = null;
            this.form.errors.tax = null;
        }
    }
}
</script>
