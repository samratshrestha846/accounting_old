<template>
    <div id="orderDetailModal" class="modal fade">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Order ID: {{order_id}}</h5>
                    <button type="button" class="close text-white" @click="closeModal()">
                            <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-form">
                        <div v-if="!loadingData && orderitem">
                            <div class="row">
                                <div class="col-md-4">
                                    <ul class="list-group">
                                        <div class="list-group-item card-header text-bold">Order Information</div>
                                        <li class="list-group-item d-flex justify-content-between">
                                            Order Type <span id="Floor">{{orderitem.order_type ? orderitem.order_type.name : '-' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            Table
                                            <span>
                                                <span
                                                    v-for="table in orderitem.tables"
                                                    :key="'row_table_'+table.id"
                                                    class="d-block"
                                                >
                                                {{ table.floor ? table.floor.name : '-' }} /
                                                {{ table.room ? table.room.name : '-' }} /
                                                {{ table.name }}
                                                </span>
                                                <span v-if="orderitem.tables && orderitem.tables.length <= 0">
                                                —
                                                </span>
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            Waiter <span id="TableName">{{orderitem.waiter ? orderitem.waiter.name : '-' }}</span>
                                        </li>
                                        <li v-if="orderitem.order_type_id == 2" class="list-group-item d-flex justify-content-between">
                                            Delivery Partner <span id="TableName">{{orderitem.delivery_partner ? orderitem.delivery_partner.name : '-' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            Status <span id="TableName">{{getStatusName}}</span>
                                        </li>
                                        <li v-if="orderitem.billing_id" class="list-group-item d-flex justify-content-between">
                                            Billing <span id="TableName"><a :href="'/hotel-sales-report/single/'+orderitem.id" target="__blank">{{orderitem.billing_id}}</a></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-8">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Discount (per unit)</th>
                                                    <th>Tax (per unit)</th>
                                                    <th>Total Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr
                                                    v-if="orderitem && orderitem.order_items.length > 0"
                                                    v-for="item in orderitem.order_items"
                                                >
                                                    <td>{{item.food_name}}</td>
                                                    <td>{{item.quantity}}</td>
                                                    <td>{{item.unit_price}}</td>
                                                    <td>{{item.total_discount}}</td>
                                                    <td>{{item.total_tax}}</td>
                                                    <td>{{item.total_cost}}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Sub Total</b></td>
                                                    <td>{{orderitem.sub_total}}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Discount Amount</b></td>
                                                    <td>{{orderitem.total_discount}}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Tax Amount({{orderitem.tax && orderitem.tax_type ? (orderitem.tax_type + ' ' + orderitem.tax_value+ '%') : ''}})</b></td>
                                                    <td>{{orderitem.total_tax}}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Service Charge({{orderitem.service_charge ? orderitem.service_charge : 0}}%)</b></td>
                                                    <td>{{orderitem.total_service_charge ? orderitem.total_service_charge : 0}}</td>
                                                </tr>
                                                <tr v-if="orderitem.is_cabin">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Cabin Charge</b></td>
                                                    <td>{{orderitem.cabin_charge ? orderitem.cabin_charge : 0}}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Shipping</b></td>
                                                    <td>-</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Grand Total</b></td>
                                                    <td>{{orderitem.total_cost}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="loadingData" class="loading text-center">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import $ from "jquery"
import {status} from "@/enums/OrderItemStatus"

export default {
    props: {
        order_id: {
            type: Number,
            required: true,
        }
    },
    data() {
        return {
            loadingData: false,
            orderitem: null,
        }
    },
    computed: {
        getStatusName() {
            let statusName = "-";

            if(!this.orderitem)
                return statusName;

            switch(this.orderitem.status) {
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
    },
    methods: {
        openModal() {
            this.findOrderItem();
            $('#orderDetailModal').modal('show');
        },
        closeModal() {
            $('#orderDetailModal').modal('hide');
        },
        async findOrderItem() {
            this.loadingData = true;
            await this.$store.dispatch('FIND_RESTAURANT_ORDER_ITEM', {
                order_id: this.order_id,
            })
                .then(response => {
                    this.loadingData = false;
                    this.orderitem = response.data.data;
                })
                .catch(error => {
                    this.$toast.open({
                        message: 'Something went wrong',
                        type: 'success',
                        position: 'top-right'
                    });
                });
        }
    },

}
</script>
