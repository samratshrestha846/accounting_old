const status = {
    'cancled' : 0,
    'pending': 1,
    'ready' : 2,
    'served' : 3,
    'suspended' : 4,
}

let getStatusNameByValue = (value) => {
    let statusName = "-";

    if(!value)
        return statusName;

    switch(value) {
        case status.cancled:
            statusName = "Cancled";
            break;
        case status.pending:
            statusName = "Pending";
            break;
        case status.ready:
            statusName = "Ready";
            break;
        case status.served:
            statusName = "Served";
            break;
        case status.suspended:
            statusName = "Suspended";
            break;
    }

    return statusName;
}

const statusList = [
    status.cancled,
    status.pending,
    status.served,
    status.suspended
];

export {status, getStatusNameByValue, statusList}
