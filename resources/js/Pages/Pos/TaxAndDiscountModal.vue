<template>
<div v-if="product" id="taxDicountModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{product.product_name}}</h5>
                <button type="button" class="close text-white" @click="closeModal()">
                        <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#taxTab">Tax</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#discountTab">Discount</a>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="myTabContent">
                    <div class="tab-pane fade show active" id="taxTab">
                        <form @submit.prevent="taxSubmit()">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Tax type</label>
                                    <select
                                        class="form-control"
                                        name="taxRate"
                                        v-model = "form.taxType"
                                    >
                                        <option value="">--None--</option>
                                        <option v-for="taxType in taxTypes" :value="taxType">{{taxType}}</option>
                                    </select>
                                    <span v-if="form.errors && form.errors.taxType" class="text-red">{{form.errors.taxType}}</span>
                                </div>
                            
                                <div class="col-md-6">
                                    <label>Tax Rate</label>
                                    <select
                                        class="form-control"
                                        name="taxRate"
                                        v-model="form.tax"
                                    >
                                        <option v-for="tax in taxes" :value="tax.id">{{tax.title + '(' + tax.percent + '%)'}}</option>
                                    </select>
                                    <span v-if="form.errors && form.errors.tax" class="text-red">{{form.errors.tax}}</span>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="discountTab">
                        <form @submit.prevent="discountSubmit()">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Discount type</label>
                                    <select
                                        class="form-control"
                                        name="discountType"
                                        v-model = "form.discountType"
                                    >
                                        <option value="">--None--</option>
                                        <option v-for="discountType in discountTypes" :value="discountType">{{discountType}}</option>
                                    </select>
                                    <span v-if="form.errors && form.errors.discountType" class="text-red">{{form.errors.discountType}}</span>
                                </div>
                            
                                <div class="col-md-6">
                                    <label>Discount</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="discount"
                                        v-model="form.discount"
                                    >
                                    <span v-if="form.errors && form.errors.discount" class="text-red">{{form.errors.discount}}</span>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group d-flex justify-content-end mt-2">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import { calculateDiscount, calculateTax } from "@/assets/js/sale_calculation.js"
export default {
    props: {
        taxes: {
            type: Array,
        },
        taxTypes: {
            type: Array,
        },
        discountTypes: {
            type: Array
        },
        product: {
            type: Object,
            required: true
        },
        canDiscount: {
            type: Boolean,
            default: false,
        },
        canTax: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {
            form: {
                tax: null,
                taxType: "",
                discountType: null,
                discount: "",
                errors: {
                    tax: null,
                    taxType: null,
                    discountType: null,
                    discount: null,
                }
            }
        }
    },
    mounted(){
        this.setData();
    },
    watch:{
        product: {
            handler(val){
                this.setData();
            },
            deep: true
        },
        'form.taxType'(newVal){
            if(!newVal){
                this.form.tax = null;
            }
        },
        'form.discountType'(newVal){
            if(!newVal){
                this.form.discount = null;
            }
        },
    },
    methods: {
        openModal(){
            $('#taxDicountModal').modal('show');
        },
        closeModal(){
            $('#taxDicountModal').modal('hide');
        },
        taxSubmit(){

            if(!this.canTax){
                alert("You cant give tax in individaul sale item ");
                return;
            }

            //if form has taxtype and tax value then throw error
            if(this.form.taxType && !this.form.tax){
                this.form.errors.tax = "Tax value is required";
                return;
            }

            this.resetTaxFormData();

            let tax = this.taxes.find(item => item.id === this.form.tax );
            this.$emit('addTaxRateToSaleProductItem', this.form.taxType, tax, this.product);
            // this.$emit('addTaxRateToSaleProductItem', {
            //     taxType: this.form.taxType,
            //     tax: this.taxes.find(item => item.id === this.form.tax ),
            //     product: this.product
            // });
            this.closeModal();
        },
        discountSubmit(){

            if(!this.canDiscount){
                alert("You cant give discount in individaul sale item ");
                return;
            }

            //validate discount form
            if(this.form.discountType && !this.form.discount){
                this.form.errors.discount = "Discount value is required";
                return;
            }

            //validate discount value is greater than sale product sub total cost
            if(this.form.discountType && this.form.discount){
                let totalCost = parseInt(this.product.gross_total) || 0;

                //check if discount value is greater than sub total
                if(calculateDiscount(this.form.discountType, this.form.discount, totalCost) > totalCost){
                  this.form.errors.discount = "Discount value cannot be greater than sub total";
                  return;
                }
            }

            this.resetDiscountFormData();

            this.$emit('addDiscountRateToSaleProductItem', this.form.discountType, this.form.discount, this.product);
            this.closeModal();
        },
        setData(){
            this.form.taxType = this.product?.tax_type;
            this.form.tax = this.product?.tax_rate_id;
            this.form.discountType = this.product?.discount_type;
            this.form.discount = this.product?.value_discount;
        },
        resetDiscountFormData(){
            this.form.errors.discountType = null;
            this.form.errors.discount = null;
        },
        resetTaxFormData(){
            this.form.errors.taxType = null;
            this.form.errors.tax = null;
        },
    }
}
</script>
