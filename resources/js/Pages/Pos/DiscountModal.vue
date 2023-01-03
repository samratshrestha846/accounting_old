<template>
<div id="discountModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Discount on All Sale Items</h5>
                <button type="button" class="close text-white" @click="closeModal()">
                        <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-form">
                    <div class="row mb-3">
                        <div class="col-4 text-right">
                            <label>Discount type</label>
                        </div>
                        <div class="col-6">
                            <select
                                class="form-control"
                                name="taxRate"
                                v-model = "form.discountType"
                            >
                                <option value="">--None--</option>
                                <option v-for="discountType in discountTypes" :value="discountType">{{discountType}}</option>
                            </select>
                            <span v-if="form.errors && form.errors.discountType" class="text-red">{{form.errors.discountType}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-right">
                            <label>Discount</label>
                        </div>
                        <div class="col-6">
                            <input
                                type="number"
                                class="form-control"
                                name="discountValue"
                                v-model="form.discount"
                            >
                            <span v-if="form.errors && form.errors.discount" class="text-red">{{form.errors.discount}}</span>
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
import { calculateDiscount } from "@/assets/js/sale_calculation.js"

export default {
    props: {
        discountTypes: {
            type: Array,
        },
        canDiscount: {
            type: Boolean,
            default: false,
        },
        totalCost: {
            type: Number
        },
    },
    data() {
        return {
            form: {
                discountType: null,
                discount: "",
                errors: {
                    discountType: null,
                    discount: null,
                },
            }
        }
    },
    watch:{
        'form.discountType'(newVal){
            if(!newVal){
                this.form.discount = 0;
            }
        },
    },
    methods: {
        openModal(){
            $('#discountModal').modal('show');
        },
        closeModal(){
            $('#discountModal').modal('hide');
        },
        submit(){

            if(!this.canDiscount){
                alert("You cant add dicsount in sale item");
                return;
            }

            //if the form has discount type and not discount value then throw validation error
            if(this.form.discountType && !this.form.discount){
                this.form.errors.discount = "Discount value is required";
                return;
            }

            //validate discount value is greater than sale product sub total cost
            if(this.form.discountType && this.form.discount){

                //check if discount value is greater than total cost
                if(calculateDiscount(this.form.discountType, this.form.discount, this.totalCost) > this.totalCost){
                  this.form.errors.discount = "Discount value cannot be greater than sub total";
                  return;
                }
            }

            this.resetErrors();

            this.$emit('success', this.form.discountType, parseFloat(this.form.discount));
            this.closeModal();
        },
        resetErrors(){
            this.form.errors.discountType = null;
            this.form.errors.discount = null;
        }
    }
}
</script>
