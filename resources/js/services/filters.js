import {getStatusNameByValue, statusList} from "../enums/OrderItemStatus"

let checkBoolean = (value) => {
    return (value === 'true' || value === true || value === "1" || value === 1);
}

let isNumber = (evt)=> {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        evt.preventDefault();;
    } else {
        return true;
    }
}

let todayDate = () => {
    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    let yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd;

    return today;
}

export default {checkBoolean, isNumber, todayDate, getStatusNameByValue, statusList}
