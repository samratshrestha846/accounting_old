import {primaryUnit, secondaryUnit} from "@/enums/ProductPurchase.js";
import { calculateTaxRate, calculateDiscountRate } from "./calculation";

export default class ProductSaleService {
    constructor(salesItems) {
      this.salesItems = salesItems;
      this.taxType = null;
      this.taxRate = null;
      this.discountType = null;
      this.discountValue = null;
      this.results = [];
    }

    setTaxRate(taxType, taxRate) {
        this.taxType = taxType;
        this.taxRate = taxRate;
    }

    setDiscountRate(discountType, discountValue) {
        this.discountType = discountType;
        this.discountValue = discountValue;
    }

    calculate() {

        let items = [];
        this.salesItems.forEach((product, index) => {

            console.log("the purhcase type is ", product.purchase_type);
            let subTotal = product.purchase_type == primaryUnit ? (product.product_price * product.quantity) : (product.secondary_unit_selling_price * product.quantity);
            let totalTax = 0;
            let totalDiscount = 0;
            let grossTotal = subTotal;

            //if individual sale item has discountValueand discountType
            if(product.value_discount && product.discount_type){
                totalDiscount = calculateDiscountRate(
                    subTotal,
                    product.discount_type.toLowerCase() || "",
                    Number(product.value_discount)
                );
            }

            //if sale item has taxValue and taxType without bulk tax
            if(!this.hasBulkTax() && product.tax && product.tax_type){

                totalTax = calculateTaxRate(
                    subTotal,
                    totalDiscount,
                    product.tax_type?.toLowerCase() || '',
                    Number(product.tax?.percent)
                )
            }

            items.push({
                suspended_item_id: product.suspended_bill_id || 0,
                product_id: product.product_id,
                product_name: product.product_name,
                product_code: product.product_code,
                product_price: product.product_price,
                quantity: product?.quantity || 1,
                purchase_type: product.purchase_type,
                purchase_unit: product.purchase_type == primaryUnit ?  product.primary_unit : product.secondary_unit,
                units: product.units,
                secondary_number: product.secondary_number,
                primary_stock: product.primary_stock,
                secondary_stock: product.secondary_stock,
                tax_type: product.tax_type,
                tax_rate_id: product.tax_rate_id,
                tax: product.tax,
                discount_type: product.discount_type,
                value_discount: product.value_discount,
                total_tax: totalTax,
                total_discount: totalDiscount,
                sub_total: subTotal,
                gross_total : grossTotal > 0 ? (grossTotal - totalDiscount + (product.tax_type?.toLowerCase() == "exclusive" ? totalTax : 0)) : grossTotal,
            });
        });

        return this.results = items;
    }

    getContents() {
        return this.results;
    }

    getTotalDiscount() {

    }

    getTotalTax() {

    }

    getTotalCost() {

    }

    getBulkTax() {

    }

    getBulkDiscount() {

    }

    hasBulkTax() {
        return (this.taxType && this.taxRate) ?  true : false
    }
}
