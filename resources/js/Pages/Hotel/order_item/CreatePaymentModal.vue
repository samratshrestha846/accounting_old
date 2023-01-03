<template>
    <form @submit.prevent="actionPayment">
        <modal ref="modal">
            <template v-slot:header>
                <h5
                    class="modals-title">
                    Make Payment
                </h5>
            </template>
            <template v-slot:body>
                <div v-if="!loadingData && orderitem">
                    <div class="row text-left">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div  class="form-group">
                                        <label>Discount Type</label>
                                        <select
                                            class="form-control"
                                            v-model="form.allDiscountType"
                                        >
                                            <option>------</option>
                                            <option v-for="type in discount_types" :value="type">{{type}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div  class="form-group">
                                        <label>Discount</label>
                                        <input
                                            class="form-control"
                                            v-model="form.allDiscount"
                                            @keypress="$filters.isNumber($event)"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div  class="form-group">
                                        <label>Tax Type</label>
                                        <select
                                            class="form-control"
                                            v-model="form.allTaxType"
                                        >
                                            <option>------</option>
                                            <option v-for="type in tax_types" :value="type">{{type}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tax</label>
                                        <select
                                            class="form-control"
                                            v-model="form.allTax"
                                        >
                                            <option>------</option>
                                            <option v-for="tax in taxes" :value="tax.id">{{tax.title + '(' + tax.percent + '%)'}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Service Charge(%)</label>
                                            <input
                                                class="form-control"
                                                v-model="form.service_charge"
                                                @keypress="$filters.isNumber($event)"
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div v-if="orderitem && orderitem.is_cabin" class="col-12 row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cabin Charge</label>
                                            <input
                                                class="form-control"
                                                :value="totalSalesProductPrice.total_cabin_charge"
                                                @keypress="$filters.isNumber($event)"
                                                readonly
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Payment Mode <span class="text-danger">*</span></label>
                                        <select
                                            class="form-control"
                                            v-model="form.payment_mode"
                                        >
                                            <option value="">Select Payment</option>
                                            <option v-for="paymentType in payment_types" :value="paymentType.id">{{paymentType.payment_mode}}</option>
                                        </select>
                                        <p v-if="form.errors && form.errors.payment_mode" class="text-danger">
                                            {{form.errors.payment_mode[0]}}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Payment Amount <span class="text-danger">*</span></label>
                                        <input
                                            class="form-control"
                                            v-model="form.payment_amount"
                                            @keypress="$filters.isNumber($event)"
                                        >
                                        <p v-if="form.errors && form.errors.payment_amount" class="text-danger">
                                            {{form.errors.payment_amount[0]}}
                                        </p>
                                    </div>
                                </div>
                                <div v-if="errorMessage" class="col-md-12 row">
                                    <div class="col-12">
                                        <span class="text-red">{{errorMessage}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <table  class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Sub Total</td>
                                        <td>{{sub_total}}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Due</td>
                                        <td>{{totalSalesProductPrice.gross_total.toFixed(2)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Payable amount</td>
                                        <td>{{payableAmount}}</td>
                                    </tr>
                                    <tr>
                                        <td>Change</td>
                                        <td>{{changeAmount}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 text-right">
                            <LoadingButton
                                type="submit"
                                title="Pay Now & Print Invoice"
                                class="btn btn-primary w-auto float-right"
                                :loading="submitLoading"
                            ></LoadingButton>
                        </div>
                    </div>

                </div>
                <div v-if="loadingData" class="loading text-center">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </template>
        </modal>
    </form>
</template>
<script>
import Modal from "@/components/Modal"
import LoadingButton from '@/components/Ui/LoadingButton'
import ProductSaleService from "@/services/ProductSaleService";
import {primaryUnit, secondaryUnit} from "@/enums/ProductPurchase.js"

export default {
    props: {
        order_id: {
            type: Number,
            required: true,
        },
        payment_types: {
            type: Array,
            required: true,
        },
        tax_types: {
            type: Array,
            required: true,
        },
        discount_types: {
            type: Array,
            required: true,
        },
        taxes: {
            type: Array
        }
    },
    components: {
        Modal,
        LoadingButton,
    },
    data() {
        return {
            loadingData: false,
            submitLoading: false,
            orderitem: null,
            errorMessage: "",
            sub_total: 0,
            form: {
                allDiscountType: "",
                allDiscount: "",
                allTaxType: "",
                allTax: "",
                service_charge: "",
                payment_mode: "",
                payment_amount: "",
                remarks: "",
                errors: null,
            },
            selected: {
                products: [],
            }
        }
    },
    computed: {
        CanDiscountIndividualProductItem(){
            return true;
        },
        CanTaxIndividualProductItem(){
            return (!this.form.allTaxType && !this.form.allTax) ? true : false;
        },
        canTaxBulkSalesItem(){
            return (this.form.allTax && this.form.allTaxType) ? true : false;
        },
        canDiscountBulkSalesItem(){
            return (this.form.allDiscountType && this.form.allDiscount) ? true : false;
        },
        payableAmount()
        {
            let payableAmount = this.totalSalesProductPrice.gross_total -  Number(this.form.payment_amount);

            return payableAmount < 0 ? 0 : payableAmount.toFixed(2);
        },
        changeAmount() {
            let grossTotal = this.totalSalesProductPrice.gross_total;

            if(!this.form.payment_amount)
                return 0;

            if(this.form.payment_amount <= grossTotal)
                return 0;

            return Number(this.form.payment_amount - grossTotal).toFixed(2);
        },
        totalSalesProductPrice(){

            let bulkDiscount = 0;
            let bulkTax = 0;
            let totalTax = 0;
            let totalDiscount = 0;
            let totalCost = Number(this.orderitem.sub_total);
            let totalServiceCharge = (totalCost * Number(this.form.service_charge || 0))/100;
            let totalCabinCharge = this.orderitem.is_cabin ? Number(this.orderitem.cabin_charge || 0) : 0;

            if(this.CanTaxIndividualProductItem){
                totalTax = this.salesItemsList.reduce((a, b) => +a + +b.total_tax, 0);
            }else if(this.canTaxBulkSalesItem){
                let tax = this.taxes.find(item => item.id === this.form.allTax);
                totalTax = (tax?.percent * totalCost)/100;
                bulkTax = totalTax;
            }

            if(this.CanDiscountIndividualProductItem){
                totalDiscount = this.salesItemsList.reduce((a, b) => +a + +b.total_discount, 0);
            }

            if(this.canDiscountBulkSalesItem){
                if(this.form.allDiscountType.toLowerCase() === "fixed"){
                    bulkDiscount = this.form.allDiscount;
                }else if (this.form.allDiscountType.toLowerCase() === "percentage"){
                    bulkDiscount = (this.form.allDiscount * totalCost)/100;
                }
            }

            totalDiscount = parseFloat(totalDiscount) + parseFloat(bulkDiscount);

            return {
                total_discount: Number(totalDiscount).toFixed(2),
                total_tax: Number(totalTax).toFixed(2),
                total_cost: Number(totalCost).toFixed(2),
                bulk_discount: Number(bulkDiscount).toFixed(2),
                bulk_tax: Number(bulkTax).toFixed(2),
                total_cabin_charge: totalCabinCharge,
                gross_total: this.form.allTaxType?.toLowerCase() === "exclusive" ? (totalCost + totalServiceCharge + totalTax - bulkDiscount + totalCabinCharge) : (totalCost + totalServiceCharge - bulkDiscount + totalCabinCharge),
            };
        },
        salesItemsList(){
            let items = [];

            let productSaleService = (new ProductSaleService(this.selected.products));

            if(this.canTaxBulkSalesItem){
                productSaleService.setTaxRate(this.form.allTaxType, this.form.allTax);
            }

            if(this.canDiscountBulkSalesItem){
                productSaleService.setDiscountRate(this.form.allDiscountType, this.form.allDiscount);
            }

            productSaleService.calculate();


            console.log("the sales item is ", JSON.stringify(productSaleService.getContents()));

            return productSaleService.getContents();
        }
    },
    methods: {
        openModal() {
            this.findOrderItem();
            this.$refs.modal.openModal();
        },
        closeModal() {
            this.$refs.modal.closeModal();
        },
        async findOrderItem() {
            this.loadingData = true;
            await this.$store.dispatch('FIND_RESTAURANT_ORDER_ITEM', {
                order_id: this.order_id
            })
            .then(response => {
                this.orderitem = response.data.data;
                this.initData();
                this.orderitem.order_items.forEach(item => {
                    this.addSelectedProductItem({
                        product_id: item.food_id,
                        product_name: item.food_name,
                        quantity: item.quantity || 0,
                        product_price: item.unit_price,
                        tax_type: item.tax_type,
                        tax_rate_id: item.tax_rate_id,
                        discount_type: item.discount_type,
                        value_discount: parseFloat(item.discount_value) || 0,
                    });
                });
                this.loadingData = false;
            })
            .catch(error => {

            });

        },
        addSelectedProductItem(product){

            let totalQuantity = product.quantity || 1;
            let primaryStock = product.primary_stock;
            let secondaryStock = product.secondary_stock;
            let purchaseType = product.purchase_type || primaryUnit;
            let units = [];

            //if the product has zero primary stock then check for secondary stock
            // if(parseFloat(product.primary_stock) <= 0){
            //     purchaseType = secondaryUnit;
            // }

            if(product.primary_unit){
                units.push({
                    purchase_type: primaryUnit,
                    value: product.primary_unit,
                });
            }

            // if(product.secondary_unit && product.secondary_unit_selling_price){
            //     units.push({
            //         purchase_type: secondaryUnit,
            //         value: product.secondary_unit,
            //     });
            // }

            let item = {
                product_id: product.id,
                product_name: product.product_name,
                product_code: product.product_code,
                product_price: parseFloat(product.product_price),
                secondary_unit_selling_price: parseFloat(product.secondary_unit_selling_price),
                quantity: parseInt(totalQuantity),
                purchase_type: purchaseType,
                primary_unit: product.primary_unit,
                secondary_unit: product.secondary_unit,
                units: units,
                primary_stock: parseInt(primaryStock),
                secondary_number: parseInt(product.secondary_number),
                secondary_stock: parseFloat(secondaryStock),
                tax_type: product.tax_type,
                tax_rate_id: product.tax_rate_id,
                tax: product.tax,
                discount_type: product.discount_type,
                value_discount: parseFloat(product.discount_value) || 0,
                total_tax: parseFloat(product.total_tax) || 0,
                total_discount: parseFloat(product.total_discount) || 0,
                gross_total : parseFloat(product.gross_total) || 0,
            }

            let selectedProduct = this.selected.products.find(value => {
                return value.product_id === product.id && String(value.purchase_type).trim().toLowerCase() === String(item.purchase_type).trim().toLowerCase();
            });

            // totalQuantity = selectedProduct ? (selectedProduct.quantity + 1) : totalQuantity;
            primaryStock = selectedProduct ? selectedProduct.primary_stock : primaryStock;
            secondaryStock = selectedProduct ? selectedProduct.secondary_stock : secondaryStock;
            purchaseType = selectedProduct ? selectedProduct.purchase_type : purchaseType;
            totalQuantity = selectedProduct ? (selectedProduct.quantity + 1) : totalQuantity;

            //if the product already exist in the selected product item then update that product quantity & pruchase type
            if(selectedProduct){
                selectedProduct.quantity = parseInt(totalQuantity);
                selectedProduct.purchase_type = purchaseType;
            }else{
                this.selected.products.push(item);
            }


            console.log("the json data is ", JSON.stringify(this.selected.products));
        },
        actionPayment() {

            this.form.errors = null;
            this.submitLoading = true;
            this.errorMessage = "";

            let payload = {
                service_charge: this.form.service_charge,
                alltaxtype : this.form.allTaxType,
                alltax : this.form.allTax,
                alldiscounttype : this.form.allDiscountType,
                alldiscountvalue : this.form.allDiscount,
                payment_mode: this.form.payment_mode,
                payment_amount: this.form.payment_amount,
                gross_total: this.totalSalesProductPrice.gross_total,
                remarks: this.form.remarks,
            }

            axios.post('/api/hotel/order/fooditems/'+ this.orderitem.id + '/payment', payload)
                .then(response => {
                    this.submitLoading = false;

                    this.closeModal();
                    this.$emit('success', response.data.data);
                    window.location.href = '/order/billing/'+response.data.data.id+'/view-invoice';
                })
                .catch(error => {
                    if(error.response.status === 422) {
                        this.form.errors = error.response.data.errors;
                    }else {
                        this.errorMessage = "Something went wrong. Please reload page1111...";
                    }
                })
                .finally(() => {
                    this.submitLoading = false;
                });
        },
        initData(){
            this.sub_total = this.orderitem.sub_total;
            this.form.allDiscountType = this.orderitem.discount_type || "";
            this.form.allDiscount = this.orderitem.discount_value;
            this.form.allTaxType = this.orderitem.tax_type || "";
            this.form.allTax = this.orderitem.tax_rate_id;
            this.form.service_charge = this.orderitem.service_charge;
        },
    }
}
</script>
