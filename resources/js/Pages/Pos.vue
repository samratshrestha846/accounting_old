<template>
<div class="wrapper">
    <!-- overlay loading -->
    <OverlayLoading :loading="overlayLoading"></OverlayLoading>
    <!-- overlay loading End -->
    <!-- <Header></Header> -->
    <!-- Pos Main Part -->
    <section class="pos-main-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="pos-sidebar">
                        <form action="" method="get">
                            <div class="row margin mb-2">
                                <div class="col-12 padding">
                                    <div class="form-customs">
                                        <div class="form-group customer-search-box">
                                            <input
                                                v-if="!selected.customer"
                                                type="text"
                                                class="form-control"
                                                placeholder="Customer - Type 2 char for suggestions"
                                                v-model="field.search.customer_name"
                                            >
                                            <div
                                                v-else
                                                class="selected-input-box"
                                            >
                                                <span class="badge badge-primary">
                                                    {{selected.customer.name}}
                                                    <span
                                                        class="close-icon ml-2 text-white"
                                                        @click="removeSelectedCustomer()"
                                                    >
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </span>
                                                </span>
                                            </div>
                                            <div
                                                v-if="field.search.customer_name && field.search.customer_name.length > 1"
                                                class="card search-box"
                                            >
                                                <div class="card-body">
                                                    <ul v-if="!customerLoading" class="search-box__list">
                                                        <li
                                                            v-if="customers && customers.length > 0"
                                                            v-for="customer in customers"
                                                            class="search-box__list-item"
                                                            @click="addSelectedCustomer(customer)"
                                                        >
                                                            {{customer.name + ' (' + customer.phone + ')'}}
                                                        </li>
                                                        <li v-if="customers && customers.length <= 0">
                                                            <span>No Customers Found!!!</span>
                                                        </li>
                                                    </ul>
                                                    <div v-else class="loading">
                                                        <div class="spinner-border text-info mr-2" role="status" style="width:16px;height:16px;">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                        Loading...
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-bulk">
                                            <button
                                                type="button"
                                                class="btn btn-primary icon-btn ml-auto"
                                                @click="$refs.customerModal.openModal()"
                                            >
                                                <i class="las la-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 padding">
                                    <div class="form-group">
                                        <input
                                            type="text"
                                            name="code"
                                            class="form-control qr_barcode"
                                            placeholder="Barcode Scanner"
                                            autofocus
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6 padding">
                                    <div class="form-group position-relative">
                                        <input
                                            type="text"
                                            name="code"
                                            class="form-control"
                                            placeholder="Product Code"
                                            v-model="field.search.product_code"
                                        >

                                        <div
                                            v-if="field.search.product_code && field.search.product_code.length > 0"
                                            class="card search-box"
                                        >
                                            <div class="card-body">
                                                <ul v-if="!productsearchLoading" class="search-box__list">
                                                    <li
                                                        v-if="searchProducts && searchProducts.length > 0"
                                                        v-for="product in searchProducts"
                                                        class="search-box__list-item text-sm py-2"
                                                        @click="addSelectedProductItem(product)"
                                                    >
                                                        {{product.product_name + '(' + product.product_code +')'}}
                                                    </li>
                                                    <li v-if="searchProducts && searchProducts.length <= 0">
                                                        <span>No Product Found!!!</span>
                                                    </li>
                                                </ul>
                                                <div v-else class="loading">
                                                    <div class="spinner-border text-info mr-2" role="status" style="width:16px;height:16px;">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                    Loading...
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive first-table mt-2">
                            <table width="100%" class="table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:35px;"><i class="las la-trash-alt"></i></th>
                                        <th style="width:180px;">Product</th>
                                        <th style="width:70px;">Qty</th>
                                        <th style="width:90px;" v-if="showtaxrow">Tax</th>
                                        <th style="width:90px;" v-if="showdiscountrow">Discount</th>
                                        <th style="width:90px;">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(product,index) in salesItemsList" :key="index">
                                        <td style="width:35px;">
                                            <button class="trash" @click="removeSelectedProductItem(product, index)"><i class="las la-trash-alt"></i></button>
                                        </td>
                                        <td style="width:180px;">
                                            <a href="javascript:void(0)" @click="openDiscountAndTaxModal(product,index)">{{product.product_name}}</a>
                                        </td>
                                        <td style="width:80px;">
                                            <!-- <input
                                                :id="'input-quantity_' + index"
                                                type="number"
                                                class=""
                                                v-model="product.quantity"
                                                @change="updateSaleItemQunaity(index, product)"
                                                min="1"

                                            > -->
                                            <!-- {{product.quantity}} -->
                                            <KeyboardNumberPicker 
                                                :inputId="'input-quantity_' + index"
                                                :value="product.quantity"
                                                v-model="product.quantity"
                                                :maxValue="product.primary_stock"
                                                :errorMessage="'You cannot add quantity more than ' + product.primary_stock"
                                                @change="updateSaleItemQunaity(index, product)"
                                            />    
                                            <!-- :max="isPrimaryUnit(product.purchase_type) ? product.primary_stock : product.secondary_stock " -->
                                        </td>
                                        <td v-if="showtaxrow">{{product.total_tax.toFixed(2)}}</td>
                                        <td v-if="showdiscountrow">{{product.total_discount.toFixed(2)}}</td>
                                        <!-- <td>
                                            <select v-model="product.purchase_type" @change="updateSaleItemProductPurchaseUnit(index, product)">
                                                <option
                                                    v-for="unit in product.units"
                                                    :value="unit.purchase_type"
                                                    :disabled ="hasSelectedProductPurhcaseType(index, product.product_id, unit.purchase_type)"
                                                >{{unit.value}}</option>
                                            </select>
                                        </td> -->
                                        <td style="width:90px;">{{product.gross_total.toFixed(2)}}</td>
                                    </tr>
                                    <!--<tr>
                                        <td style="width:35px;"><button type="submit" class="trash"><i class="las la-trash-alt"></i></button></td>
                                        <td style="width:180px;"><a href="#">Tourist Kids -07</a></td>
                                        <td style="width:80px;"><input class="alignRight keyboard" type="text" value="1"></td>
                                        <td style="width:90px;">250.44</td>
                                    </tr>-->
                                </tbody>
                            </table>
                        </div>
                        <ul class="table-total">
                            <li>
                                <b style="width:180px;padding:2px 7px;">Total</b>
                                <b style="width:80px;padding:2px 7px;">{{totalSalesItems}}</b>
                                <span style="width:90px;padding:2px 7px;text-align:right;">{{totalSalesProductPrice.total_cost}}</span>
                            </li>
                            <li>
                                <b style="width:180px;padding:2px 7px;">Tax:</b>
                                <b style="width:80px;padding:2px 7px;">&nbsp;</b>
                                <span style="width:90px;padding:2px 7px;text-align:right;"><a href="javascript:void(0)" class="disabled-btn" @click="openTaxRateModal()">{{totalSalesProductPrice.total_tax}}</a></span>
                            </li>
                            <li>
                                <b style="width:180px;padding:2px 7px;">Bulk Discount:</b>
                                <b style="width:80px;padding:2px 7px;">&nbsp;</b>
                                <span style="width:90px;padding:2px 7px;text-align:right;"><input class="form-control" @click="openDiscountRateModal()" v-model="totalSalesProductPrice.bulk_discount"></span>
                            </li>
                            <li>
                                <b style="width:180px;padding:2px 7px;">Total Discount:</b>
                                <b style="width:80px;padding:2px 7px;">&nbsp;</b>
                                <span style="width:90px;padding:0 7px;text-align:right;">{{totalSalesProductPrice.total_discount}}</span>
                            </li>
                            <li>
                                <b style="width:180px;padding:2px 7px;">Total Cost:</b>
                                <b style="width:80px;padding:2px 7px;">&nbsp;</b>
                                <span style="width:90px;padding:2px 7px;text-align:right;font-weight:600;">{{defaultCurrency}} {{totalSalesProductPrice.gross_total.toFixed(2)}}</span>
                            </li>
                        </ul>
                        <div class="header-btns btn-bulk mt-2">
                            <button
                                type="submit"
                                class="btn btn-secondary flex-grow-1" @click="actionCancelSuspendSale"
                            >
                                <i class="las la-sync"></i>
                                Cancel
                            </button>
                            <a href="javascript:void(0)" class="btn btn-secondary btn-success flex-grow-1" @click="actionPrintBilling"><i class="las la-print"></i> Print</a>
                            <button
                                type="submit"
                                class="btn btn-primary flex-grow-1"
                                @click="actionSuspendProductItem"
                            >
                                <i class="las la-ban"></i>
                                Suspen
                            </button>
                            <button
                                type="submit"
                                class="btn btn-primary btn-success flex-grow-1"
                                @click="openPaymentModal()"
                            >
                            <i class="las la-credit-card"></i>
                                Payment
                            </button>
                        </div>
                        <!-- <div class="butk-btns">
                            <button type="submit" class="btn btn-danger" @click="actionCancelSuspendSale()">Cancel</button>
                            <a href="javascript:void(0)" class="btn btn-success" @click="actionPrintBilling()">Print</a>
                            <button type="submit" class="btn btn-warning" @click="actionSuspendProductItem()">Suspen</button>
                        </div>
                        <div class="pay-btn">
                            <button type="submit" class="btn btn-success" @click="openPaymentModal()">Payment</button>
                        </div> -->
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="pos-main">
                        <ul
                            v-if="categories && categories.length > 0"
                            class="nav nav-tabs"
                            id="myTab"
                            role="tablist"
                        >
                            <li
                                v-for="(category,index) in categories"
                                class="nav-item"
                                role="presentation"
                            >
                                <button
                                    class="nav-link"
                                    :class="{'active': selected.categoryId === category.id}"
                                    @click="selected.categoryId = category.id"
                                >
                                    {{category.category_name}}
                                </button>
                            </li>
                        </ul>
                        <div
                            v-if="!productLoading && products && products.length > 0"
                            class="row margin"
                        >
                            <div
                                v-for="product in products"
                                class="col-lg-2 col-md-3 col-sm-6 col-xs-6 padding"
                            >
                                <div class="pos-product-wrap">
                                    <button type="submit" @click="addSelectedProductItem(product)">
                                        <div class="pos-product-img">
                                            <img src="/img/basket.png" alt="images" style="height:56px;width:auto;">
                                        </div>
                                        <div class="pos-product-content">
                                            <span>{{product.product_name}}<p style="margin-bottom:0;margin-top:3px;font-weight: 600;color: rgb(116 116 116);">({{product.product_code}})</p></span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-if="productLoading" class="pos-loading">
                            <div class="row">
                                <div v-for="index in 30" class="col-lg-2 col-md-3 col-sm-6 col-xs-6 padding">
                                    <PosProductLoadingSkeleton></PosProductLoadingSkeleton>
                                </div>
                            </div>
                        </div>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li
                                    v-if="links.previous"
                                    class="page-item"
                                >
                                    <a class="page-link" href="javascript:void(0)" @click="previousProductList()"><i class="las la-long-arrow-alt-left"></i></a>
                                </li>
                                <li
                                    v-if="links.next"
                                    class="page-item"
                                >
                                    <a class="page-link" href="javascript:void(0)" @click="nextProductList()"><i class="las la-long-arrow-alt-right"></i></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Pos Main Part End -->
    <!-- Customer Modal -->
    <CustomerModal
        ref="customerModal"
        :customerTypes = "customer_types"
        @success="addSelectedCustomer"
    >
    </CustomerModal>
    <!-- Modal End -->
    <!-- Tax & Discount modal -->
    <TaxAndDiscountModal
        ref="taxDiscountModal"
        v-if="selected.product"
        :taxes = "taxes"
        :taxTypes = "tax_types"
        :discountTypes ="discount_types"
        :product="selected.product"
        :canDiscount = "CanDiscountIndividualProductItem"
        :canTax = "CanTaxIndividualProductItem"
        @addTaxRateToSaleProductItem = "addTaxRateToSelectedSaleProductItem"
        @addDiscountRateToSaleProductItem = "addDiscountRateToSelectedSaleProductItem"
    >
    </TaxAndDiscountModal>
    <!-- Modal End -->
    <!-- Tax Modal -->
    <TaxRateModal
        ref="taxModal"
        :taxTypes="tax_types"
        :taxes="taxes"
        :canTax = "canOpenTaxModal"
        @success="addTaxToAllSaleItems"
    >
    </TaxRateModal>
    <!-- Modal End -->
    <!-- Discount Modal -->
    <DiscountRateModal
        ref="discountModal"
        :discountTypes="discount_types"
        :canDiscount = "canBulkDiscount"
        :totalCost = "parseFloat(totalSalesProductPrice.total_cost)"
        @success="addDiscountToAllSaleItems"
    >
    </DiscountRateModal>
    <!-- Modal End -->
    <!-- Payment modal -->
    <PaymentModalForm
        v-if="selected.customer && outlet && selected.products.length > 0"
        ref="paymentModal"
        :outlet ="outlet"
        :salesItems="selected.products"
        :allTaxType="field.allTaxType"
        :allTax="field.allTax"
        :allDiscountType="field.allDiscountType"
        :allDiscount="field.allDiscount"
        :grossTotal = "totalSalesProductPrice.gross_total"
        :paymentTypes = "payment_types"
        :customer = "selected.customer"
        :defaultCurrency = "defaultCurrency"
        @success="actionSucessPurchaseSalesItem"
    >
    </PaymentModalForm>
    <PrintSaleBilling
        v-if="selected.customer && salesItemsList.length > 0"
        :salesItems="salesItemsList"
        :engDate="eng_date"
        :nepDate="nep_date"
        :userCompany="user_company"
        :customer="selected.customer"
        :subTotal = "parseFloat(totalSalesProductPrice.total_cost)"
        :bulkDiscount = "parseFloat(totalSalesProductPrice.bulk_discount)"
        :bulkTax = "parseFloat(totalSalesProductPrice.bulk_tax)"
        :taxType = "field.allTaxType"
        :grossTotal = "parseFloat(totalSalesProductPrice.gross_total)"
    >
    </PrintSaleBilling>
    </div>
</template>
<style>
.loading{
    display: flex;
    align-items: center;
    justify-content: center;
}
.selected-input-box{
    display: block;
    width: 100%;
    height: calc(2.25rem + 2px);
    padding: .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    box-shadow: inset 0 0 0 transparent;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.selected-input-box > span {

}
.selected-input-box .close-icon {
    cursor: pointer;
}
.customer-search-box{
    position: relative;
}

.position-relative {
    position: relative;
}

.search-box{
    position: absolute;
    width: 100%;
    z-index: 100;
    max-height: 300px;
    overflow-x: hidden;
    overflow-y: auto;
}
.search-box__list .search-box__list-item:hover,
.search-box__list .search-box__list-item:focus
{
    cursor: pointer;
    color: #dc3f26;
}
</style>
<script>
import { mapGetters } from "vuex";
import _debounce from 'lodash.debounce';
import alert_mixins from '@/mixins/alert.js'
import Header from '@/Layouts/pos/Header'
import PosProductLoadingSkeleton from '@/components/Ui/PosProductLoadingSkeleton'
import OverlayLoading from '@/components/Ui/OverlayLoading'
import KeyboardNumberPicker from '@/components/inputs/KeyboardNumberPicker'
import LoadingButton from '@/components/Ui/LoadingButton'
import CustomerModal from "@/Pages/Pos/CustomerModalForm"
import TaxAndDiscountModal from "@/Pages/Pos/TaxAndDiscountModal"
import TaxRateModal from "@/Pages/Pos/TaxModal"
import DiscountRateModal from "@/Pages/Pos/DiscountModal"
import PaymentModalForm from "@/Pages/Pos/PaymentModalForm"
import PrintSaleBilling from "@/Pages/Pos/PrintSaleBilling"

import {primaryUnit, secondaryUnit} from "@/enums/ProductPurchase.js"
import ProductSaleService from "@/services/ProductSaleService";
import { calculateTaxRate, calculateDiscountRate } from "@/services/calculation";

import printJS from 'print-js'

let globalTotalQuantity = 0;

export default {
    props: {
        payment_types: {
            type: Array,
        },
        customer_types: {
            type: Array,
        },
        tax_types: {
            type: Array
        },
        discount_types: {
            type: Array
        },
        taxes: {
            type: Array
        },
        categories: {
            type: Array
        },
        pos_setting: {
            type: Object,
        },
        outlet: {
            type: Object,
            required: true,
        },
        eng_date: {
            type: String
        },
        nep_date: {
            type: String
        },
        user_company: {
            type: Object
        },
        suspendedsale: {
            type: Object
        },
        canIndividualTax: {
            type: Boolean,
            default: true,
        },
        canIndividualDiscount: {
            type: Boolean,
            default: true,
        },
        canBulkTax: {
            type: Boolean,
            default: true
        },
        canBulkDiscount: {
            type: Boolean,
            default: true
        }
    },
    mixins: [alert_mixins],
    components: {
        Header,
        PosProductLoadingSkeleton,
        OverlayLoading,
        KeyboardNumberPicker,
        LoadingButton,
        CustomerModal,
        TaxAndDiscountModal,
        TaxRateModal,
        DiscountRateModal,
        PaymentModalForm,
        PrintSaleBilling
    },
    data() {
        return {
            customerLoading: false,
            productLoading: true,
            productsearchLoading: false,
            overlayLoading: false,
            submitCutomerLoading: false,
            showtaxrow: this.pos_setting?.show_tax ? true : false,
            showdiscountrow: this.pos_setting?.show_discount ?  true : false,
            defaultCurrency: this.pos_setting?.default_currency || 'NAN',
            page: 1,
            perPage: this.pos_setting?.display_products || 40,
            totalItems: 0,
            products: [],
            searchProducts: [],
            links: {
                next: null,
                previous: null
            },
            selected: {
                categoryId: this.pos_setting?.default_category || null,
                products: [],
                customer: this.pos_setting?.customer || null,
                product: null,
            },
            field: {
                search: {
                    customer_name: "",
                    product_code: "",
                    barcode_code: "",
                },
                allTaxType: null,
                allTax: null,
                allDiscountType: null,
                allDiscount: null,
            },
            purchase_billing: null,
            dummyField: "100"
        }
    },
    computed: {
        ...mapGetters(["customers","suspendedSaleItems"]),
        CanDiscountIndividualProductItem(){
            return this.canIndividualDiscount;
        },
        CanTaxIndividualProductItem(){
            return (this.canIndividualTax && !this.field.allTaxType && !this.field.allTax) ? true : false;
        },
        canTaxBulkSalesItem(){
            return (this.canBulkTax && this.field.allTax && this.field.allTaxType) ? true : false;
        },
        canDiscountBulkSalesItem(){
            return (this.canBulkDiscount && this.field.allDiscountType && this.field.allDiscount) ? true : false;
        },
        canOpenTaxModal(){
            return this.canBulkTax && !this.selected.products.some(function(item,index){
                if(item.tax_rate_id && item.tax){
                    return true;
                }
            });
        },
        totalSalesItems(){
            return this.selected.products.reduce((a, b) => +a + +b.quantity, 0);
        },
        totalSalesProductPrice(){

            let bulkDiscount = 0;
            let bulkTax = 0;
            let totalTax = 0;
            let totalDiscount = 0;
            let subTotal = this.salesItemsList.reduce((a, b) => +a + +b.gross_total, 0);


            if(this.canDiscountBulkSalesItem) {

                bulkDiscount = calculateDiscountRate(
                    subTotal,
                    this.field.allDiscountType.toLowerCase(),
                    this.field.allDiscount
                );
            }

            if(this.CanTaxIndividualProductItem){
                totalTax = this.salesItemsList.reduce((a, b) => +a + +b.total_tax, 0);
            }else if(this.canTaxBulkSalesItem){

                let tax = this.taxes.find(item => item.id === this.field.allTax);

                totalTax = calculateTaxRate(
                    subTotal,
                    bulkDiscount,
                    this.field.allTaxType,
                    Number(tax?.percent)
                );
                bulkTax = totalTax;
            }


            if(this.CanDiscountIndividualProductItem){
                totalDiscount = this.salesItemsList.reduce((a, b) => +a + +b.total_discount, 0);
            }

            totalDiscount = parseFloat(totalDiscount) + parseFloat(bulkDiscount);

            return {
                total_discount: parseFloat(totalDiscount).toFixed(2),
                total_tax: parseFloat(totalTax).toFixed(2),
                total_cost: parseFloat(subTotal).toFixed(2),
                bulk_discount: parseFloat(bulkDiscount).toFixed(2),
                bulk_tax: parseFloat(bulkTax).toFixed(2),
                gross_total: this.field.allTaxType?.toLowerCase() === "exclusive" ? (subTotal + totalTax - bulkDiscount) : (subTotal - bulkDiscount),
            };
        },
        salesItemsList(){
            let items = [];

            let productSaleService = (new ProductSaleService(this.selected.products));

            if(this.canTaxBulkSalesItem){
                productSaleService.setTaxRate(this.field.allTaxType, this.field.allTax);
            }

            if(this.canDiscountBulkSalesItem){
                productSaleService.setDiscountRate(this.field.allDiscountType, this.field.allDiscount);
            }

            productSaleService.calculate();


            console.log("the sales item is ", JSON.stringify(productSaleService.getContents()));

            return productSaleService.getContents();
        }
    },
    watch:{
        'field.search.customer_name': function (newVal, oldVal){

            if(!newVal || newVal.length <= 1) return;

            this.fetchCustomerList();
        },
        'field.search.product_code': function (newVal, oldVal){

            if(!newVal || newVal.length <= 0) return;

            this.fetchSearchProductListByProductCode();
        },
        'selected.categoryId': function (newVal, oldVal){
            this.overlayLoading = true;
            this.page = 1;
            this.totalItems = 0;
            this.links.next = null;
            this.links.previous = null;
            this.fetchProductList();
        },
    },
    mounted(){

        if(this.suspendedsale){
            this.selected.customer = this.suspendedsale.customer;
            this.field.allTax = this.suspendedsale.tax_rate_id;
            this.field.allTaxType = this.suspendedsale.tax_type;
            this.field.allDiscountType = this.suspendedsale.discount_type;
            this.field.allDiscount = this.suspendedsale.discount_value;
            this.fetchSuspendedProductList(this.suspendedsale.id);
        }
        this.fetchProductList();
        this.fetchProvienceList();

        this.initializedSetup();
    },
    methods: {
        addSelectedCustomer(customer){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            this.selected.customer = customer;
            this.field.search.customer_name = null;
        },
        removeSelectedCustomer(){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            this.selected.customer = null;
        },
        updateSaleItemQunaity(index, product){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;


            let totalQuantity = product.quantity;

            // let index = this.selected.products.findIndex(item => item.product_id === product.product_id);
            let selectedProduct = this.selected.products[index];

            //validate the quantity
            if (product.purchase_type === primaryUnit && parseFloat(totalQuantity) > parseFloat(selectedProduct.primary_stock) ){
                alert("You cannot add quantity more than " + product.primary_stock);
                totalQuantity = selectedProduct.primary_stock;
            } else if (product.purchase_type === secondaryUnit && parseFloat(totalQuantity) > parseFloat(selectedProduct.secondary_stock)) {
                alert("You cannot add quantity more than " + selectedProduct.secondary_stock);
                totalQuantity = selectedProduct.secondary_stock;
            }

            $('#input-quantity_' + index).val(totalQuantity);

            // console.log("the qunatityafter is ", totalQuantity);

            this.playAudioSound();


            this.selected.products[index].quantity =  parseInt(totalQuantity);
        },
        updateSaleItemProductPurchaseUnit(index, product){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            // let index = this.selected.products.findIndex(item => item.product_id === product.product_id);
            this.selected.products[index].purchase_type = product.purchase_type;

            this.updateSaleItemQunaity(index, product);
        },
        addSelectedProductItem(product){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            this.field.search.product_code = "";
            this.playAudioSound();

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
                suspended_item_id: product.suspended_bill_id || 0,
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

            // console.log("the selected quantity is ", selectedProduct?.quantity);
            // console.log("the item toatal quantity is ", item.quantity);


            if(selectedProduct && product.purchase_type) {

                // console.log("the product pruchase is ", product.purchase_type);
                // console.log("the prucahse type is ", purchaseType);

                let updatedPurchaseType = "";

                if(product.purchase_type === primaryUnit && purchaseType === primaryUnit) {
                    totalQuantity = item.quantity + selectedProduct.quantity;
                    updatedPurchaseType = primaryUnit;
                }

                if(product.purchase_type === primaryUnit && purchaseType === secondaryUnit) {
                    totalQuantity = item.quantity * parseInt(product.secondary_number || 0) + selectedProduct.quantity;
                    updatedPurchaseType = secondaryUnit;
                }

                if(product.purchase_type === secondaryUnit && purchaseType === primaryUnit) {
                    totalQuantity = item.quantity + selectedProduct.quantity * parseInt(product.secondary_number || 0);
                    updatedPurchaseType = secondaryUnit;
                }

                if(product.purchase_type === secondaryUnit && purchaseType === secondaryUnit) {
                    totalQuantity = item.quantity + selectedProduct.quantity;
                    updatedPurchaseType = secondaryUnit;
                }

                purchaseType = updatedPurchaseType;

            }else{
               totalQuantity = selectedProduct ? (selectedProduct.quantity + 1) : totalQuantity;
            }

            // validate the quantity
            if (purchaseType === primaryUnit && parseFloat(totalQuantity) > parseFloat(primaryStock) ) {
                alert("You cannot add quantity more than " + primaryStock);
                return;
            } else if (purchaseType === secondaryUnit && parseFloat(totalQuantity) > parseFloat(secondaryStock)) {
                alert("You cannot add quantity more than " + secondaryStock);
                return;
            }

            //if the product already exist in the selected product item then update that product quantity & pruchase type
            if(selectedProduct){
                selectedProduct.quantity = parseInt(totalQuantity);
                selectedProduct.purchase_type = purchaseType;
            }else{
                this.selected.products.push(item);
            }


            // console.log("the json data is ", JSON.stringify(this.selected.products));
        },
        removeSelectedProductItem(product, index){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            this.playAudioSound();
            this.selected.products.splice(index, 1);
        },
        addTaxToAllSaleItems(taxType, taxId){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            if(!taxType){
                this.field.allTaxType = "";
                this.field.allTax = "";
            }else{
                this.field.allTaxType = taxType;
                this.field.allTax = taxId;
            }

        },
        addDiscountToAllSaleItems(discountType, discountValue){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            if(!discountType){
                this.field.allDiscountType = "";
                this.field.allDiscount = 0;
            }else{
                this.field.allDiscountType = discountType;
                this.field.allDiscount = discountValue;
            }

        },
        addTaxRateToSelectedSaleProductItem(taxType, tax, product){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            this.selected.products.forEach((item,index) => {
                if(item.product_id === product?.product_id){

                    if(taxType){
                        this.selected.products[index].tax_type = taxType;
                        this.selected.products[index].tax_rate_id = tax.id;
                        this.selected.products[index].tax = tax;
                    }else{
                        this.selected.products[index].tax_type = "";
                        this.selected.products[index].tax_rate_id = null;
                        this.selected.products[index].tax = null;
                    }

                }
            });
        },
        addDiscountRateToSelectedSaleProductItem(discountType, discountValue, product){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            this.selected.products.forEach((item,index) => {
                if(item.product_id === product?.product_id){

                    if(discountType){
                        this.selected.products[index].discount_type = discountType;
                        this.selected.products[index].value_discount = discountValue;
                    }else {
                        this.selected.products[index].discount_type = "";
                        this.selected.products[index].value_discount = 0;
                    }

                }
            });
        },
        nextProductList(){
            this.page = this.page + 1;
            this.overlayLoading = true;
            this.fetchProductList();
        },
        previousProductList(){
            this.page = this.page - 1;
            this.overlayLoading = true;
            this.fetchProductList();
        },
        openDiscountAndTaxModal(productSaleItem, tax){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            if(this.CanDiscountIndividualProductItem || this.CanTaxIndividualProductItem){
                this.selected.product = productSaleItem
                setTimeout(()=> {
                    this.$refs.taxDiscountModal.openModal();
                }, 100);
            }

        },
        openTaxRateModal(){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            if(!this.canOpenTaxModal) return;

            this.$refs.taxModal.openModal();
        },
        openDiscountRateModal(){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            if(!this.canBulkDiscount) return;

            this.$refs.discountModal.openModal();
        },
        openPaymentModal(){


            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            //if biller has not selected customer
            if(!this.selected.customer){
                this.$swal.fire({
                    title: "Please select a customer before payment of sales items. Thank you!",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            //if the biller has not selected sales item
            if(this.selected.products && this.selected.products.length <= 0){
                this.$swal.fire({
                    title: "Please add sale items first. Thank you!",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            this.$refs.paymentModal.openModal();
        },
        // fetchCategoryList(){
        //     this.$store.dispatch('GET_CATEGORY_LIST').then(response => {
        //         // console.log("the category is ", this.categories);
        //     });
        // },
        fetchProductList(){

            let params = {
                'include' : '',
                'per_page': this.perPage,
                'page': this.page,
                'status' : 1,
            };

            if(this.selected.categoryId){
                params.category_id = this.selected.categoryId;
            }

            this.$store.dispatch('GET_POS_PRODUCT_LIST_BY_OUTLET_ID', {
                outlet_id : this.outlet.id,
                params: params
            }).then(response => {
                this.products = response.data.data;
                this.totalItems = response.data.meta?.total;
                this.links.next = response.data.links?.next;
                this.links.previous = response.data.links?.prev;
                this.productLoading = false;
                this.overlayLoading = false;
            });
        },
        fetchSearchProductListByProductCode: _.debounce(function() {
            this.productsearchLoading = true;

            let params = {
                'include' : '',
                // 'per_page': this.perPage,
                // 'page': this.page,
                product_code: this.field.search.product_code,
            };

            this.$store.dispatch('GET_POS_PRODUCT_LIST_BY_OUTLET_ID', {
                outlet_id: this.outlet.id,
                params: params
            }).then(response => {
                this.searchProducts = response.data.data;
                this.productsearchLoading = false;
            });
        }, 700),
        fetchProvienceList(){
            this.$store.dispatch('GET_PROVINCE_LIST').then(response => {
                // console.log("the provience is ", this.provinces);
            });
        },
        fetchCustomerList: _.debounce(function() {
            this.customerLoading = true;
            this.$store.dispatch('GET_CUSTOMER_LIST', {
                params: {
                    name: this.field.search.customer_name
                }
            }).then(response => {
                this.customerLoading = false;
            });
        }, 700),
        fetchSuspendedProductList(suspended_bill_id){
            this.$store.dispatch('GET_SUSPENDED_PRODUCT_LIST', {
                suspended_bill_id: suspended_bill_id
            }).then(response => {

                this.suspendedSaleItems.forEach(suspendedItem => {

                    let item = {
                        suspended_item_id: suspendedItem.suspended_item_id,
                        id: suspendedItem.product_id,
                        product_name: suspendedItem.product_name || 'Undefined',
                        product_code: suspendedItem.product_code || 'Undefined',
                        product_price: suspendedItem.product_price || 'Undefined',
                        primary_stock: parseInt(suspendedItem.primary_stock) || 0,
                        secondary_stock: parseFloat(suspendedItem.secondary_stock) || 0,
                        quantity: suspendedItem.quantity || 1,
                        purchase_type: suspendedItem.purchase_type || primaryUnit,
                        secondary_unit: suspendedItem.secondary_unit,
                        primary_unit: suspendedItem.primary_unit,
                        secondary_unit_selling_price: suspendedItem.secondary_unit_selling_price,
                        tax: this.taxes.find(item => item.id === suspendedItem.tax_rate_id),
                        tax_type: suspendedItem.tax_type,
                        tax_rate_id: suspendedItem.tax_rate_id,
                        tax_value: suspendedItem.tax_value || 0,
                        discount_type: suspendedItem.discount_type,
                        discount_value: suspendedItem.discount_value,
                    }

                    this.addSelectedProductItem(item);
                });
            });
        },
        actionSuspendProductItem(){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            if(this.selected.products.length <= 0){
                this.$swal.fire({
                    title: "Please add product before suspending the sale. Thank you!",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            if(!this.selected.customer){
                this.$swal.fire({
                    title: "Please select customer before suspending sale. Thank you!",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            this.$swal.fire({
                title: "Are you sure you want to suspend sale?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: `Sure`,
                denyButtonText: `Cancle`,
            }).then((result) => {

                if (result.isConfirmed) {
                    this.overlayLoading = true;

                    let payload= {
                        'outlet_id' : this.outlet.id,
                        'suspended_bill_id' : this.suspendedsale?.id,
                        'customer_id' : this.selected.customer.id,
                        'products' : this.selected.products,
                        'alltaxtype' : this.field.allTaxType,
                        'alltax' : this.field.allTax,
                        'alldiscounttype' : this.field.allDiscountType,
                        'alldiscount' : this.field.allDiscount,
                    }


                    //if suspendedsale exist then update otherwise create a new one
                    if(this.suspendedsale){
                        this.$store.dispatch("UPDATE_SUSPEND_SALE",payload)
                        .then(response => {
                            this.overlayLoading = false;
                            window.location.href = '/suspendedsale?id='+ this.suspendedsale.id;
                        })
                        .catch(error => {
                            this.overlayLoading = false;
                            alert("Something weng wrong");
                        });
                    }else {
                        this.$store.dispatch('SUSPEND_SALE', payload).then(response => {
                            this.overlayLoading = false;
                            window.location.href = '/suspendedsale';
                        })
                        .catch(error => {
                            this.overlayLoading = false;
                            alert("Something weng wrong");
                        });
                    }

                }
            });
        },
        actionCancelSuspendSale(){

            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            this.$swal.fire({
                title: "Are you sure you want to cancle sale?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: `Sure`,
                denyButtonText: `Cancle`,
            }).then((result) => {

                if (result.isConfirmed) {
                    if(this.suspendedsale){
                        this.$store.dispatch('CANCLE_SUSPEND_PRODUCT_SALE',{
                            suspended_bill_id: this.suspendedsale.id
                        })
                        .then(response => {
                            this.overlayLoading = true;
                            window.location.href = '/suspendedsale?id='+ this.suspendedsale.id;
                        })
                        .catch(response => {
                            this.overlayLoading = false;
                            alert("Something weng wrong");
                        });
                    } else{
                        window.location.href = '/';
                    }
                }
            });

        },
        actionSucessPurchaseSalesItem(billing)
        {
            console.log("i am here", JSON.stringify(billing));
            this.purchase_billing = billing;
            // setTimeout(()=>{
            //     this.actionPrintBilling();
            // }, 100);
        },
        async actionPrintBilling(){

            if(!this.selected.customer){
                this.$swal.fire({
                    title: "Please select a customer before printing billing. Thank you!",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            if(this.selected.products && this.selected.products.length <= 0){
                this.$swal.fire({
                    title: "Please add sale items first before printing billing. Thank you!",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            await printJS({
                printable: "printPosBilling",
                type: "html",
                targetStyles: ["*"],
                style: "#printPosBilling { display: block !important; }"
            });
        },
        playAudioSound(){
            new Audio('/audio/202530__kalisemorrison__scanner-beep.wav').play();
        },
        initializedSetup(){
            window.onbeforeunload = function(){
                return 'You will lose sale data. Press OK to leave and Cancel to Stay on this page.';
            };

            let self = this;
            let code = "";
            let reading = false;

            $('.qr_barcode').keypress(function(e) {
                //usually scanners throw an 'Enter' key at the end of read
                if (e.keyCode === 13) {

                    if(code.length > 10) {
                        console.log(code);
                        /// code ready to use
                        code = "";
                    }
                } else {
                    code += e.key; //while this is not an 'enter' it stores the every key
                }

                console.log("the code is", code);

                //run a timeout of 200ms at the first read and clear everything
                if(!reading) {
                    reading = true;
                    setTimeout(() => {

                        self.barcodeProductSearch(code);
                        code = "";
                        reading = false;
                    }, 200);  //200 works fine for me but you can adjust it
                }
            });
        },
        barcodeProductSearch(code) {
            this.overlayLoading = true;

            let payload = {
                outlet_id: this.outlet.id,
                product_code: code,
            };

            this.$store.dispatch('FIND_POS_PRODUCT_BY_OUTLET_ID_AND_PRODUCT_CODE', payload)
            .then(response => {
                let product = response.data.data;

                if(String(product.product_code).trim().toLowerCase() == String(payload.product_code).trim().toLowerCase()){
                    product.purchase_type = primaryUnit;
                } else{
                    product.purchase_type = secondaryUnit;
                }

                this.addSelectedProductItem(product);
                $('.qr_barcode').val(payload.product_code);
                this.overlayLoading = false;
            })
            .catch(error => {

                if(error.response.status === 404){
                    alert("Barcode Product Not Found");
                }
                $('.qr_barcode').val(payload.product_code);
                this.overlayLoading = false;
            });
        },
        generateSaleItemUniqueId(){

        },
        hasCustomerSuccessfullyPaidBill(){
            if(this.purchase_billing){
                this.$swal.fire({
                    title: "Customer has successfully paid sales items. Please click sure button to reload page?",
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: `Sure`,
                    denyButtonText: `Cancle`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                        return;
                    }
                });
            }

            return this.purchase_billing ?  true : false;
        },
        hasSelectedProductPurhcaseType(indexPosition, productId, purchaseType) {
            return this.selected.products.some((item,index) => {

                if(indexPosition === index)
                    return  false;
                return item.product_id === productId && String(item.purchase_type).trim().toLowerCase() === String(purchaseType).trim().toLowerCase();
            });
        },
        isPrimaryUnit(value){
            return value === primaryUnit ? true : false;
        }
    }
}

</script>
