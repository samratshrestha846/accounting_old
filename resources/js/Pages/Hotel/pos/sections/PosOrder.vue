<template>
<div class="wrapper">
    <!-- overlay loading -->
    <OverlayLoading :loading="overlayLoading"></OverlayLoading>
    <!-- overlay loading End -->
    <!-- <Header></Header> -->
    <!-- Pos Main Part -->
    <section class="pos-main-section pos-main-part">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="pos-sidebar">
                        <form action="" method="get">
                            <div class="row margin">
                                <div class="col-md-6 com-sm-6 padding">
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
                                            <button type="button" class="btn btn-primary icon-btn ml-auto"  @click="$refs.customerModal.openModal()"><i class="las la-plus"></i></button>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6 com-sm-6 padding">
                                    <div class="form-group">
                                        <select class="form-control" v-model="selected.orderTypeId">
                                            <option value="">Select Customer Type</option>
                                            <option v-for="type in order_types" :value="type.id">{{type.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div v-if="selected.orderTypeId == 1" class="col-md-6 padding">
                                    <div class="form-group">
                                        <div class="dropdown">
                                            <!-- <select
                                                name="table"
                                                class="form-control"
                                                placeholder="Table"
                                                v-model="field.search.table_id"
                                                data-toggle="dropdown"
                                            >
                                                <option value="">Select Table</option>
                                                <option v-for="table in restaurantTableList" :value="table.id">{{table.name}} ({{table.code}})</option>
                                            </select> -->
                                            <button
                                                class="btn btn-default dropdown-toggle form-control d-flex align-items-center justify-content-between"
                                                type="button"
                                                data-toggle="dropdown"
                                                @click="$refs.tableSelectionModal.openModal()"
                                            >
                                                <span v-if="selected.tables && selected.tables.length > 0">
                                                    (+{{selected.tables.length}}) table selected
                                                </span>
                                                <span v-else>
                                                    Select Table
                                                </span>
                                                <span class="caret"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="selected.orderTypeId == 2" class="col-md-6 padding">
                                    <div class="form-group">
                                        <select class="form-control" v-model="selected.deliveryPartnerId">
                                            <option value="">Select Delivery Partner</option>
                                            <option
                                                v-for="(deliveryPartner, key) in restaurantDeliveryPartnerList"
                                                :key="'select_delivery_partner_' + key"
                                                :value="deliveryPartner.id"
                                            >
                                            {{deliveryPartner.name}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 padding">
                                    <div class="form-group position-relative">
                                        <input
                                            type="text"
                                            name="code"
                                            class="form-control"
                                            placeholder="Food Name"
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
                                                        {{product.product_name}}
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
                        <div class="table-responsive first-table mt-3">
                            <table width="100%" class="table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:180px;">Item</th>
                                        <th style="width:80px;">Qty</th>
                                        <th v-if="showtaxrow">Tax</th>
                                        <th v-if="showdiscountrow">Discount</th>
                                        <th style="width:90px;">Price</th>
                                        <th style="width:35px;"><i class="las la-trash-alt"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(product,index) in salesItemsList" :key="index">
                                        <td style="width:180px;">
                                            <a href="javascript:void(0)" @click="openDiscountAndTaxModal(product,index)">{{product.product_name}}</a>
                                        </td>
                                        <td style="width:80px;">
                                            <input
                                                :id="'input-quantity_' + index"
                                                type="number"
                                                v-model="product.quantity"
                                                @change="updateSaleItemQunaity($event, index, product)"
                                                min="1"
                                                @keyup="$filters.isNumber($event)"
                                            >
                                            <!-- :max="isPrimaryUnit(product.purchase_type) ? product.primary_stock : product.secondary_stock " -->
                                        </td>
                                        <td v-if="showtaxrow">{{product.total_tax.toFixed(2)}}</td>
                                        <td v-if="showdiscountrow">{{product.total_discount.toFixed(2)}}</td>
                                        <td style="width:90px;">{{product.gross_total.toFixed(2)}}</td>
                                        <td style="width:35px;">
                                            <button class="trash" @click="removeSelectedProductItem(product, index)"><i class="las la-trash-alt"></i></button>
                                        </td>
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
                        <div class="table-reponsive">
                            <table width="100%" class="second-table">
                                <tbody>
                                    <tr>
                                        <td style="text-align:left;width:180px;"><b>Total:</b></td>
                                        <td style="text-align:left;width:80px;"><b style="margin-left:32px">{{totalSalesItems}}</b></td>
                                        <td style="text-align:left;width:90px;"><b>{{totalSalesProductPrice.total_cost}}</b></td>
                                        <td style="width:35px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="width:180px;">&nbsp;</td>
                                        <td style="text-align:left;width:100px;"><b>Tax:</b></td>
                                        <td colspan="2" style="width:90px;text-align:right;">
                                            <b><a href="javascript:void(0)" class="disabled-btn" @click="openTaxRateModal()">{{totalSalesProductPrice.total_tax}}</a></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:180px;">&nbsp;</td>
                                        <td style="text-align:left;width:100px;"><b>Bulk Discount:</b></td>
                                        <td colspan="2" style="text-align:right;width:90px;">
                                            <input class="form-control" @click="openDiscountRateModal()" v-model="totalSalesProductPrice.bulk_discount" style="width:50px;margin-left:auto;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:180px;">&nbsp;</td>
                                        <td style="text-align:left;width:120px;"><b>Service Charge(%):</b></td>
                                        <td colspan="2" style="text-align:right;width:90px;">
                                            <input type="text" class="form-control text-left" max="100" v-model="field.service_charge" @keypress="$filters.isNumber($event)" style="width:50px;margin-left:auto;">
                                        </td>
                                    </tr>
                                    <tr v-if="selected.tables && selected.tables.length > 0">
                                        <td style="width:180px;">&nbsp;</td>
                                        <td style="text-align:left;width:100px;"><b>Cabin Charge:</b></td>
                                        <td colspan="2" style="text-align:right;width:90px;">
                                            <input
                                            type="text"
                                            class="form-control text-left"
                                            max="100"
                                            v-model="totalCabinCharge"
                                            style="width:50px;margin-left:auto;"
                                            readonly
                                            >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:180px;">&nbsp;</td>
                                        <td style="text-align:left;width:100px;"><b>Total Discount:</b></td>
                                        <td colspan="2" style="text-align:right;width:90px;"><b>{{totalSalesProductPrice.total_discount}}</b></td>
                                    </tr>
                                    <tr>
                                        <td style="width:180px;">&nbsp;</td>
                                        <td style="text-align:left;width:100px;"><b>Total Cost:</b></td>
                                        <td colspan="2" style="text-align:right;width:90px;"><b>{{defaultCurrency}} {{totalSalesProductPrice.gross_total.toFixed(2)}}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="header-btns btn-bulk mt-2">
                            <button
                                type="submit"
                                class="btn btn-secondary" @click="actionCancleOrderItem"
                                :class="{'disabled': disabledCancleBtn}"
                            >
                            <i class="las la-sync"></i>
                                Cancel
                            </button>
                            <a href="javascript:void(0)" class="btn btn-secondary btn-success" @click="actionPrintBilling"><i class="las la-print"></i> Print</a>
                            <button
                                type="submit"
                                class="btn btn-primary"
                                :class="{'disabled': disabledSuspendBtn}"
                                @click="actionSuspendOrderItem"
                            >
                            <i class="las la-ban"></i>
                                Suspen
                            </button>
                            <button type="submit" class="btn btn-primary btn-info" @click="actionPlaceOrderItems(1)"><i class="las la-luggage-cart"></i> {{ orderitem ? 'Update Order' : 'Place Order' }}</button>
                            <button
                                type="submit"
                                class="btn btn-primary btn-success"
                                :class="{'disabled': disabledPaymentBtn}"
                                @click="openPaymentModal()"
                            >
                            <i class="las la-credit-card"></i>
                                Payment
                            </button>
                        </div>
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
                                    @click="updateCategory(category)"
                                >
                                    {{category.category_name}}
                                </button>
                            </li>
                        </ul>

                        <!--- Zero Level Menu Start -->
                        <div v-if="selected.category && filterProductByCategoryId(selected.categoryId) > 0" class="product-cols">
                            <div
                                v-if="!productLoading && products && products.length > 0"
                                class="row margin"
                            >
                                <div
                                    v-for="product in filterProductByCategoryId(selected.categoryId)"
                                    class="col-lg-2 col-md-3 col-sm-6 col-xs-6 padding"
                                >
                                    <div class="pos-product-wrap">
                                        <button type="submit" @click="addSelectedProductItem(product)">
                                            <div class="pos-product-img">
                                                <img src="/img/product.png" alt="images" style="height:50px;width: auto;">
                                                <!-- <img :src="product.food_image ? product.food_image : '/img/basket.png'" alt="images"> -->
                                            </div>
                                            <div class="pos-product-content">
                                                <span>{{product.product_name}}</span>
                                                <b>Rs. 150</b>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--- Zero Level Menu Start End -->

                        <!-- Multi Level Menu Start -->
                        <div
                        v-if="selected.category && selected.category.children_categories.length > 0"
                        v-for="category in selected.category.children_categories"
                        class="product-row"
                        >
                            <!-- One Level Menu Start -->
                            <div v-if="category.categories.length <= 0" class="product-cols">
                                <div class="p-cats-title">
                                    <h3>{{category.category_name}}</h3>
                                </div>
                                <div
                                    v-if="!productLoading && products && products.length > 0"
                                    class="row margin"
                                >
                                    <div
                                        v-for="product in filterProductByCategoryId(category.id)"
                                        class="col-lg-2 col-md-3 col-sm-6 col-xs-6 padding"
                                    >
                                        <div class="pos-product-wrap">
                                            <button type="submit" @click="addSelectedProductItem(product)">
                                                <div class="pos-product-img">
                                                    <img src="/img/product.png" alt="images" style="height:50px;width: auto;">
                                                    <!-- <img :src="product.food_image ? product.food_image : '/img/basket.png'" alt="images"> -->
                                                </div>
                                                <div class="pos-product-content">
                                                    <span>{{product.product_name}}</span>
                                                    <b>Rs. 150</b>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- One Level Menu Start End -->

                            <!-- Two Level Menu Start -->
                            <!-- <v-app
                            id="inspire"
                            > -->
                                <v-expansion-panels
                                    accordion
                                    v-if="category.categories.length > 0"
                                    class="mb-3"
                                >
                                    <v-expansion-panel
                                    class="accordian-card"
                                    v-show="true"
                                    >
                                        <v-expansion-panel-header
                                        class="accordian-titles"
                                        style="">
                                        {{category.category_name}}
                                        </v-expansion-panel-header>
                                        <v-expansion-panel-content class="accordian-content">
                                            <div v-for="category1 in category.categories" class="product-cols">
                                                <div class="p-cats-title">
                                                    <h3>{{category1.category_name}}</h3>
                                                </div>
                                                <div
                                                    v-if="!productLoading && products && products.length > 0"
                                                    class="row margin"
                                                >
                                                    <div
                                                        v-for="product in filterProductByCategoryId(category1.id)"
                                                        class="col-lg-2 col-md-3 col-sm-6 col-xs-6 padding"
                                                    >
                                                        <div class="pos-product-wrap">
                                                            <button type="submit" @click="addSelectedProductItem(product)">
                                                                <div class="pos-product-img">
                                                                    <img src="/img/product.png" alt="images" style="height:50px;width: auto;">
                                                                    <!-- <img :src="product.food_image ? product.food_image : '/img/basket.png'" alt="images"> -->
                                                                </div>
                                                                <div class="pos-product-content">
                                                                    <span>{{product.product_name}}</span>
                                                                    <b>Rs. 150</b>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                                </v-expansion-panels>
                            <!-- </v-app> -->
                            <!-- Two Level Menu End -->

                        </div>
                        <!-- Multi Level Menu End -->

                        <div v-if="!productLoading && products.length <= 0">
                            <p style="font-size:18px;text-align:center;">No Product Found</p>
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
        v-if="selected.customer && selected.products.length > 0 && selected.orderTypeId"
        ref= "paymentModal"
        :salesItems= "selected.products"
        :servicecharge = "Number(field.service_charge)"
        :allTaxType= "field.allTaxType"
        :allTax= "field.allTax"
        :allDiscountType= "field.allDiscountType"
        :allDiscount= "field.allDiscount"
        :grossTotal = "totalSalesProductPrice.gross_total"
        :paymentTypes = "payment_types"
        :orderTypeId="selected.orderTypeId"
        :customer = "selected.customer"
        :deliveryPartnerId="selected.deliveryPartnerId"
        :tables= "selected.tables"
        :orderitem = "orderitem"
        :defaultCurrency = "defaultCurrency"
        @success= "actionSucessPurchaseSalesItem"
    >
    </PaymentModalForm>
    <print-order-item-invoice
        v-if="selected.customer && salesItemsList.length > 0"
        :engdate ="eng_date"
        :nepdate ="nep_date"
        :usercompany ="user_company"
        :customer ="selected.customer"
        :salesitems ="salesItemsList"
        :subtotal ="parseFloat(totalSalesProductPrice.total_cost)"
        :bulktax ="parseFloat(totalSalesProductPrice.bulk_tax)"
        :bulkdiscount="parseFloat(totalSalesProductPrice.bulk_discount)"
        :servicechargerate ="parseFloat(field.service_charge)"
        :bulkservicecharge ="parseFloat(totalSalesProductPrice.total_service_charge)"
        :cabinchargeamount ="totalCabinCharge"
        :grosstotal="parseFloat(totalSalesProductPrice.gross_total)"
        :taxtype="field.allTaxType"
    >
    </print-order-item-invoice>

    <print-kot-billing
        v-if="selected.customer && salesItemsList.length > 0"
        :orderitems="salesItemsList"
        :engdate="eng_date"
        :nepdate="nep_date"
        :usercompany="user_company"
        :customer="selected.customer"
        :tables="selected.tables"
        :subTotal = "parseFloat(totalSalesProductPrice.total_cost)"
        :bulkDiscount = "parseFloat(totalSalesProductPrice.bulk_discount)"
        :bulkTax = "parseFloat(totalSalesProductPrice.bulk_tax)"
        :taxType = "field.allTaxType"
        :grossTotal = "parseFloat(totalSalesProductPrice.gross_total)"
    >
    </print-kot-billing>

    <TableSectionModal
        ref="tableSelectionModal"
        @change="(data) => {selected.tables = data}"
    >
    </TableSectionModal>
    <PaymentDetailModal
        ref="paymentDetailModal"
        v-if="hasOrderItemBilling"
        :billing_id="orderitem.billing.id"
    >
    </PaymentDetailModal>
    </div>
</template>
<style>
.dropdown-submenu {
  position: relative;
}

.dropdown-submenu .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -1px;
}
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
import PaymentModalForm from "@/Pages/Hotel/pos/sections/PaymentModal"
import TableSectionModal from "@/Pages/Hotel/pos/sections/TableSelectionModal"
import PaymentDetailModal from "@/Pages/Hotel/order_item/PaymentDetail"

import {primaryUnit, secondaryUnit} from "@/enums/ProductPurchase.js"
import ProductSaleService from "@/services/ProductSaleService";
import { calculateTaxRate } from "@/services/calculation";
import {status as order_status} from "@/enums/OrderItemStatus.js"

import printJS from 'print-js'

let globalTotalQuantity = 0;

export default {
    props: {
        payment_types: {
            type: Array,
        },
        order_types: {
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
        eng_date: {
            type: String
        },
        nep_date: {
            type: String
        },
        user_company: {
            type: Object
        },
        orderitem: {
            type: Object,
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
        TableSectionModal,
        PaymentDetailModal
    },
    data() {
        return {
            loadedDom: false,
            customerLoading: false,
            productLoading: true,
            productsearchLoading: false,
            overlayLoading: false,
            submitCutomerLoading: false,
            disabledCancleBtn: false,
            disabledSuspendBtn: false,
            disabledPaymentBtn: false,
            hasOrderItemBilling: false,
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
                orderTypeId: "",
                categoryId: this.pos_setting?.default_category || null,
                category: this.categories.find( item => item.id == parseInt(this.pos_setting?.default_category || null)),
                products: [],
                customer: this.pos_setting?.customer || null,
                product: null,
                deliveryPartnerId: "",
                tables: [],
            },
            field: {
                search: {
                    customer_name: "",
                    product_code: "",
                    barcode_code: "",
                    table_id: "",
                },
                allTaxType: null,
                allTax: null,
                allDiscountType: null,
                allDiscount: null,
                service_charge: 0.00,
            },
            purchase_billing: null,
            panels: [],
        }
    },
    computed: {
        ...mapGetters(["customers","restaurantTableList","restaurantDeliveryPartnerList"]),
        canPrintKot() {
            return this.pos_setting.kot_print_after_placing_order ? true : false;
        },
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
        totalCabinCharge() {
            return this.restaurantTableList
                .filter(table => (table.is_cabin == 1 || table.is_cabin == true) && this.selected.tables.includes(table.id))
                .reduce((a, b) => +a + +b.cabin_charge, 0);
        },
        totalSalesProductPrice(){

            let bulkDiscount = 0;
            let bulkTax = 0;
            let totalTax = 0;
            let totalDiscount = 0;
            let totalCost = this.salesItemsList.reduce((a, b) => +a + +b.gross_total, 0);
            let totalServiceCharge = (totalCost * Number(this.field.service_charge || 0))/100;

            if(this.CanTaxIndividualProductItem){
                totalTax = this.salesItemsList.reduce((a, b) => +a + +b.total_tax, 0);
            }else if(this.canTaxBulkSalesItem){
                let tax = this.taxes.find(item => item.id === this.field.allTax);
                totalTax = calculateTaxRate(totalCost, this.field.allTaxType?.toLowerCase(), Number(tax?.percent))
                bulkTax = totalTax;
            }

            if(this.CanDiscountIndividualProductItem){
                totalDiscount = this.salesItemsList.reduce((a, b) => +a + +b.total_discount, 0);
            }

            if(this.canDiscountBulkSalesItem){
                if(this.field.allDiscountType.toLowerCase() === "fixed"){
                    bulkDiscount = this.field.allDiscount;
                }else if (this.field.allDiscountType.toLowerCase() === "percentage"){
                    bulkDiscount = (this.field.allDiscount * totalCost)/100;
                }
            }

            totalDiscount = parseFloat(totalDiscount) + parseFloat(bulkDiscount);

            return {
                total_discount: parseFloat(totalDiscount).toFixed(2),
                total_tax: parseFloat(totalTax).toFixed(2),
                total_cost: parseFloat(totalCost).toFixed(2),
                bulk_discount: parseFloat(bulkDiscount).toFixed(2),
                bulk_tax: parseFloat(bulkTax).toFixed(2),
                total_service_charge: parseFloat(totalServiceCharge).toFixed(2),
                gross_total: this.field.allTaxType?.toLowerCase() === "exclusive" ? (totalCost + totalServiceCharge + this.totalCabinCharge + totalTax - bulkDiscount) : (totalCost + totalServiceCharge + this.totalCabinCharge - bulkDiscount),
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


            // console.log("the sales item is ", JSON.stringify(productSaleService.getContents()));

            return productSaleService.getContents();
        },
        watchOnChangeData() {
            const { selected, field } = this
            return {
                selected,
                field
            }
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
            this.fetchFoodList();
        },
        'selected.orderTypeId': function(newVal, oldVal) {
            if(this.selected.orderTypeId == 2) {
                this.$store.dispatch('GET_RESTAURANT_DELIVERY_PARTNER_ITEM');
            }
        },
        watchOnChangeData: {
            handler: function(val) {

                //in the case for update order item only
                if(this.loadedDom && this.orderitem) {
                    this.disabledPaymentBtn = true;
                }
            },
            deep: true
        }
    },
    mounted(){
        // this.updateCategory(
        //     this.categories.find( item => item.id == parseInt(this.selected.categoryId))
        // );


        if(this.orderitem){
            this.hasOrderItemBilling = (this.orderitem && this.orderitem.billing_id) ? true : false;
            this.selected.orderTypeId = this.orderitem.order_type_id;
            this.selected.customer = this.orderitem.customer;
            this.selected.tables = this.orderitem.tables;
            this.selected.deliveryPartnerId = this.orderitem.delivery_partner_id;
            this.field.service_charge = this.orderitem.service_charge;
            this.field.allTax = this.orderitem.tax_rate_id;
            this.field.allTaxType = this.orderitem.tax_type;
            this.field.allDiscountType = this.orderitem.discount_type;
            this.field.allDiscount = this.orderitem.discount_value;
            this.disabledCancleBtn = (this.orderitem.status == 3) ? true : (this.orderitem.status == 0 ? true : false);
            this.disabledSuspendBtn = (this.orderitem.status == 3) ? true : (this.orderitem.status == 4 ? true : false);
            this.orderitem.order_items.forEach(item => {
                this.addSelectedProductItem({
                    id: item.food_id,
                    product_name: item.food_name,
                    product_price: item.unit_price,
                    quantity: item.quantity,
                });
            });

            this.$nextTick(function () {
                this.loadedDom = true;

                this.selected.tables.forEach(table => {
                    this.$refs.tableSelectionModal.addSelectTableId(table.id);
                });
            })
        }

        this.fetchFoodList();
        this.fetchProvienceList();

        this.initializedSetup();
    },
    methods: {
        filterProductByCategoryId(categoryId) {
            return this.products.filter(product => product.category_id == categoryId);
        },
        updateCategory(category) {
            this.selected.category = category;
            this.selected.categoryId = category.id;

            // this.panels = this.selected.category.children_categories.map((value, key) => key);
        },
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
        updateSaleItemQunaity(event, index, product){


            //check if customer has successfully paid sales items bill then return
            if(this.hasCustomerSuccessfullyPaidBill())
                return;

            let totalQuantity = product.quantity;

            //validate product qunatity
            if(!this.validateProductQuantity(totalQuantity)) {
                 $('#input-quantity_' + index).val(1);
                 return;
            }

            this.selected.products[index].quantity =  parseInt(totalQuantity);
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
            this.fetchFoodList();
        },
        previousProductList(){
            this.page = this.page - 1;
            this.overlayLoading = true;
            this.fetchFoodList();
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

            //in the case of updating order item only, if the user has updated the contents
            if(this.disabledPaymentBtn) {
                this.$swal.fire({
                    title: "Please update the order item first before making payment",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            //if biller has not selected customer type or order type
            if(!this.selected.orderTypeId){
                this.$swal.fire({
                    title: "Please select a customer Type before placing order items.",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            //if biller has not selected customer
            if(!this.selected.customer){
                this.$swal.fire({
                    title: "Please select a customer before payment of sales items. Thank you!",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            //if biller has selected customer in dine then validate the table
            if(parseInt(this.selected.orderTypeId) === 1) {
                if(!(this.selected.tables && this.selected.tables.length > 0)){
                    this.$swal.fire({
                        title: "Please select a table before placing order items.",
                        showDenyButton: false,
                        confirmButtonText: `Ok`,
                    });
                    return;
                }
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

            if(this.orderitem && this.orderitem.billing) {
                //if  the order item has billing then open payment detail modal
                this.$refs.paymentDetailModal.openModal();
            }else{
                //otherwise open payment modal
                this.$refs.paymentModal.openModal();
            }
        },
        // fetchCategoryList(){
        //     this.$store.dispatch('GET_CATEGORY_LIST').then(response => {
        //         // console.log("the category is ", this.categories);
        //     });
        // },
        fetchFoodList(){


            let categoryIds = [];

            this.categories.filter(item => item.id == this.selected.categoryId)
                .forEach(item => {
                    item.children_categories?.forEach(item1 => {
                        if(!categoryIds.includes(item1.id)) {
                            categoryIds.push(item1.id);
                        }

                        item1.categories.forEach(item2 => {
                            if(!categoryIds.includes(item2.id)) {
                                categoryIds.push(item2.id);
                            }
                        })
                    })
                });

            console.log("the child categories is ", categoryIds);

            let params = {
                'include' : '',
                'per_page': this.perPage,
                'page': this.page,
                'status' : 1,
                'category_id': categoryIds
            };

            // if(this.selected.categoryId){
            //     params.category_id = this.selected.categoryId;
            // }

            this.$store.dispatch('GET_RESTAURANT_FOOD_LIST', {
                params: params
            }).then(response => {
                this.products = response.data.data.map(item => {
                    return {
                        id: item.id,
                        product_name: item.food_name,
                        product_price: item.food_price,
                        category_id: item.category_id,
                        kitchen_id: item.kitchen_id,
                    }
                });
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
                food_name: this.field.search.product_code,
            };

            this.$store.dispatch('GET_RESTAURANT_FOOD_LIST', {
                params: params
            }).then(response => {
                this.searchProducts = response.data.data.map(item => {
                    return {
                        id: item.id,
                        product_name: item.food_name,
                        product_price: item.food_price,
                    }
                });
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
        actionCancleOrderItem() {
            if(this.disabledCancleBtn){
                this.$swal.fire({
                    title: "You cannot cancle this order item",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            if(!this.selected.orderTypeId){
                this.$swal.fire({
                    title: "Please select a customer Type before placing order items.",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            if(!this.selected.customer){
                this.$swal.fire({
                    title: "Please select a customer before cancle order items.",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            if(parseInt(this.selected.orderTypeId) === 1) {
                if(!(this.selected.tables && this.selected.tables.length > 0)){
                    this.$swal.fire({
                        title: "Please select a table before placing order items.",
                        showDenyButton: false,
                        confirmButtonText: `Ok`,
                    });
                    return;
                }
            }

            if(this.selected.products && this.selected.products.length <= 0){
                this.$swal.fire({
                    title: "Please add sale items first before cancle order items.",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            this.$swal.fire({
                title: "Are you sure you want to cancle order item?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: `Sure`,
                denyButtonText: `Cancle`,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.actionPlaceOrderItems(0);
                }
            });
        },
        actionSuspendOrderItem() {

            if(this.disabledSuspendBtn){
                this.$swal.fire({
                    title: "You cannot suspend this order item",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            if(!this.selected.orderTypeId){
                this.$swal.fire({
                    title: "Please select a customer Type before placing order items.",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            if(!this.selected.customer){
                this.$swal.fire({
                    title: "Please select a customer before suspend order items.",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            if(parseInt(this.selected.orderTypeId) === 1) {
                if(!(this.selected.tables && this.selected.tables.length > 0)){
                    this.$swal.fire({
                        title: "Please select a table before placing order items.",
                        showDenyButton: false,
                        confirmButtonText: `Ok`,
                    });
                    return;
                }
            }

            if(this.selected.products && this.selected.products.length <= 0){
                this.$swal.fire({
                    title: "Please add sale items first before suspend order items.",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            this.$swal.fire({
                title: "Are you sure you want to suspend order item?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: `Sure`,
                denyButtonText: `Cancle`,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.actionPlaceOrderItems(4);
                }
            });
        },
        actionPlaceOrderItems(status = 1) {

            if(!this.selected.orderTypeId){
                this.$swal.fire({
                    title: "Please select a customer Type before placing order items.",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            if(!this.selected.customer){
                this.$swal.fire({
                    title: "Please select a customer before placing order items.",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            if(parseInt(this.selected.orderTypeId) === 1) {
                if(!(this.selected.tables && this.selected.tables.length > 0)){
                    this.$swal.fire({
                        title: "Please select a table before placing order items.",
                        showDenyButton: false,
                        confirmButtonText: `Ok`,
                    });
                    return;
                }
            }

            if(this.selected.products && this.selected.products.length <= 0){
                this.$swal.fire({
                    title: "Please add sale items first before placing order items.",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            this.overlayLoading = true;

            let payload = {
                order_type_id: this.selected.orderTypeId,
                customer_id: this.selected.customer.id,
                tables: this.selected.tables,
                service_charge: this.field.service_charge,
                alltaxtype : this.field.allTaxType,
                alltax : this.field.allTax,
                delivery_partner_id: this.selected.deliveryPartnerId,
                alldiscounttype : this.field.allDiscountType,
                alldiscountvalue : this.field.allDiscount,
                items: this.selected.products.map(item => {
                    return {
                        food_id: item.product_id,
                        quantity: item.quantity,
                        tax_rate_id: item.tax_rate_id,
                        tax_type: item.tax_type,
                        discount_type: item.discount_type,
                        discount_value: item.value_discount,
                    }
                }),
                status: (this.orderitem && this.orderitem.status == 3) ? 3 : status,
            }

            if(this.orderitem) {
                //update the order item
                axios.patch('/api/hotel/order/fooditems/'+ this.orderitem.id, payload)
                .then(res => {

                    this.disabledPaymentBtn = false;

                     //cancled
                    if(status == order_status.cancled){
                        this.$toast.open({
                            message: 'Order Item cancled successfully',
                            type: 'success',
                            position: 'top-right'
                        });
                        this.disabledCancleBtn = true;
                        this.disabledSuspendBtn = false;
                    }

                    //pending
                    if(status == order_status.pending) {
                        this.$toast.open({
                            message: 'Order Item updated successfully',
                            type: 'success',
                            position: 'top-right'
                        });
                    }

                    //suspended
                    if(status == order_status.suspended) {
                        this.$toast.open({
                            message: 'Order Item suspended successfully',
                            type: 'success',
                            position: 'top-right'
                        });
                        this.disabledCancleBtn = false;
                        this.disabledSuspendBtn = true;
                    }

                    this.$emit('fetchOrderDetail'); //increment the totalsuspended count

                })
                .catch(err => {
                    this.$toast.open({
                        message: 'Something went wrong while placing order',
                        type: 'error',
                        position: 'top-right'
                    });
                })
                .finally(() => {
                    this.overlayLoading = false;
                })

            } else {
                //create new order item
                axios.post('/api/hotel/order/fooditems/place', payload)
                .then(res => {

                    this.disabledPaymentBtn = false;

                    //cancled
                    if(status == order_status.cancled){
                        this.$toast.open({
                            message: 'Order Item cancled successfully',
                            type: 'success',
                            position: 'top-right'
                        });
                    }

                    //pending
                    if(status == order_status.pending) {
                        this.$toast.open({
                            message: 'Order Item placed successfully',
                            type: 'success',
                            position: 'top-right'
                        });
                    }

                    //suspended
                    if(status == order_status.suspended) {
                        this.$toast.open({
                            message: 'Order Item suspended successfully',
                            type: 'success',
                            position: 'top-right'
                        });
                    }

                    this.$emit('fetchOrderDetail'); //increment the totalsuspended count

                    //if only print kot setting is enabled
                    if(this.canPrintKot){
                        this.actionKotPrintBilling();
                    }

                    //clear all data
                    this.selected.tables = [];
                    this.selected.products = [];
                    this.allTaxType = null;
                    this.allTax = null;
                    this.allDiscountType = null;
                    this.allDiscount =null;
                    this.service_charge = 0.00;
                    this.$refs.tableSelectionModal.clearSelectedTable();


                })
                .catch(err => {
                    this.$toast.open({
                        message: 'Something went wrong while placing order',
                        type: 'error',
                        position: 'top-right'
                    });
                })
                .finally(() => {
                    this.overlayLoading = false;
                })
            }

        },
        actionSucessPurchaseSalesItem(billing)
        {
            if(this.orderitem) {
                this.hasOrderItemBilling = true;
            }

            // console.log("i am here", JSON.stringify(billing));
            this.purchase_billing = billing;
            // setTimeout(()=>{
            //     this.actionPrintBilling();
            // }, 100);
        },
        async actionPrintBilling(){

            if(!this.selected.orderTypeId){
                this.$swal.fire({
                    title: "Please select a customer Type before placing order items.",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            if(!this.selected.customer){
                this.$swal.fire({
                    title: "Please select a customer before printing billing. Thank you!",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            if(parseInt(this.selected.orderTypeId) === 1) {
                if(!(this.selected.tables && this.selected.tables.length > 0)){
                    this.$swal.fire({
                        title: "Please select a table before placing order items.",
                        showDenyButton: false,
                        confirmButtonText: `Ok`,
                    });
                    return;
                }
            }

            if(this.selected.products && this.selected.products.length <= 0){
                this.$swal.fire({
                    title: "Please add food items first before printing billing. Thank you!",
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
        async actionKotPrintBilling(){

            if(!this.selected.customer){
                this.$swal.fire({
                    title: "Please select a customer before printing billing. Thank you!",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            if(!this.selected.orderTypeId){
                this.$swal.fire({
                    title: "Please select a customer Type before placing order items.",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            if(parseInt(this.selected.orderTypeId) === 1) {
                if(!(this.selected.tables && this.selected.tables.length > 0)){
                    this.$swal.fire({
                        title: "Please select a table before placing order items.",
                        showDenyButton: false,
                        confirmButtonText: `Ok`,
                    });
                    return;
                }
            }

            if(this.selected.products && this.selected.products.length <= 0){
                this.$swal.fire({
                    title: "Please add food items first before printing billing. Thank you!",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
                return;
            }

            await printJS({
                printable: "printHotelPosBilling",
                type: "html",
                targetStyles: ["*"],
                style: "#printHotelPosBilling { display: block !important; }"
            });
        },
        playAudioSound(){
            new Audio('/audio/202530__kalisemorrison__scanner-beep.wav').play();
        },
        initializedSetup(){
            window.onbeforeunload = function(){
                return 'You will lose sale data. Press OK to leave and Cancel to Stay on this page.';
            };
        },
        validateProductQuantity(totalQuantity) {
            return totalQuantity <= 0 ? false : true;
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

