const calculateDiscount = (discountType, discountValue, totalCost) => {

    if(discountType && discountValue && totalCost){

        if(discountType.toLowerCase() === 'fixed'){
            return discountValue;
        } else if (discountType.toLowerCase() === "percentage"){
            return (totalCost * discountValue)/100;
        }
    }

    return 0;
}


const calculateTax = (taxValue, totalCost) => {

    if(taxValue && totalCost){

        return (totalCost * taxValue)/100;
    }

    return 0;
}

export { calculateDiscount, calculateTax }
