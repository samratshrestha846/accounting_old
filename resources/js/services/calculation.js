let calculateTaxRate = (subTotal, totalDiscount, taxType, taxValue) => {

    let totalTax = 0;
    if(taxType.toLowerCase() === "exclusive"){
        totalTax = (taxValue * (subTotal - totalDiscount))/100;
    }
    else if(taxType.toLowerCase() === "inclusive"){
        totalTax = (subTotal * (taxValue - totalDiscount))/(100 + taxValue);
    }

    return totalTax;
}

let calculateDiscountRate = ( subTotal, discountType, discountValue) => {

    let totalDiscount = 0;

    if(discountType.toLowerCase() === "fixed"){
        totalDiscount = discountValue;
    }
    else if(discountType.toLowerCase() === "percentage"){
        totalDiscount = (subTotal * discountValue)/100;
    }

    return totalDiscount;
}

export {calculateTaxRate, calculateDiscountRate}
