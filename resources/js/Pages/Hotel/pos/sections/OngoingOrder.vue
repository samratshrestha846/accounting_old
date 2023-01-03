<template>
    <section class="pos-main-section">
        <div class="pos-main">
            <div class="container-fluid">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-2">
                                <div class="form-group" href="javascript:void(0)">
                                    <label>Floor</label>
                                    <select class="form-control" v-model="filters.floor_id">
                                        <option value="">Select Floor</option>
                                        <option v-for="floor in floors" :value="floor.id">{{floor.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" href="javascript:void(0)">
                                    <label>Room</label>
                                    <select class="form-control" v-model="filters.room_id">
                                        <option value="">Select Room</option>
                                        <option v-for="room in rooms" :value="room.id">{{room.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" href="javascript:void(0)">
                                    <label>Table</label>
                                    <select class="form-control" v-model="filters.table_id">
                                        <option value="">Select Table</option>
                                        <option v-for="table in tables" :value="table.id">{{table.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="filter m-0">
                            <!-- <div class="dropdown">
                                <button class="global-btn" @click="toogleFilterDropdown">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                                <div
                                    v-if="showFilterDropdown"
                                    class="card search-box"
                                    style="width: 300px;"
                                >
                                    <div class="card-body">
                                        <div class="form-group" href="javascript:void(0)">
                                            <label>Floor</label>
                                            <select class="form-control" v-model="filters.floor_id">
                                                <option value="">Select Floor</option>
                                                <option v-for="floor in floors" :value="floor.id">{{floor.name}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group" href="javascript:void(0)">
                                            <label>Room</label>
                                            <select class="form-control" v-model="filters.room_id">
                                                <option value="">Select Room</option>
                                                <option v-for="room in rooms" :value="room.id">{{room.name}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group" href="javascript:void(0)">
                                            <label>Table</label>
                                            <select class="form-control" v-model="filters.table_id">
                                                <option value="">Select Table</option>
                                                <option v-for="table in tables" :value="table.id">{{table.name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <form class="form-inline ml-auto" @submit.prevent="fetchOngoingOrder">
                                <div class="form-group mx-sm-3">
                                    <label for="search" class="sr-only">Search</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search" v-model="filters.search">
                                </div>
                                <button type="submit" class="btn btn-primary icon-btn"><i
                                        class="fa fa-search"></i></button>
                            </form>
                        </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-bordered yajra-datatable text-center global-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">Order ID</th>
                                        <th class="text-center">Customer</th>
                                        <th class="text-center">Waiter</th>
                                        <th class="text-center">Table</th>
                                        <th class="text-center">Order Type</th>
                                        <th class="text-center">Ordered At</th>
                                        <th class="text-center">Delivery Partner</th>
                                        <th class="text-center">Total Item</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="position-relative">
                                    <tr v-if="orderitems && orderitems.length > 0" v-for="item in orderitems">
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.customer ? item.customer.name : '-' }}</td>
                                        <td>{{ item.waiter ? item.waiter.name : '-' }}</td>
                                        <td>
                                            <span
                                                v-for="table in item.tables"
                                                :key="'row_table_'+table.id"
                                                class="d-block"
                                            >
                                            {{ table.floor ? table.floor.name : '-' }} /
                                            {{ table.room ? table.room.name : '-' }} /
                                            {{ table.name }}
                                            </span>
                                            <span v-if="item.tables && item.tables.length <= 0">
                                            —
                                            </span>
                                        </td>
                                        <td>{{ item.order_type ? item.order_type.name : "-" }}</td>
                                        <td>{{ item.order_at }}</td>
                                        <td>
                                            {{item.delivery_partner ? item.delivery_partner.name : '-' }}
                                        </td>
                                        <td><a href="javascript:void(0)" @click="openOrderDetailModal(item)">{{ item.total_items }}</a></td>
                                        <td>{{ item.total_cost }}</td>
                                        <td>
                                            <span
                                                v-if="item.status == 0"
                                                class="badge badge-danger"
                                            >
                                                Cancled
                                            </span>
                                            <span
                                                v-else-if="item.status == 1"
                                                class="badge badge-primary"
                                            >
                                                Pending
                                            </span>
                                            <span
                                                v-else-if="item.status == 3"
                                                class="badge badge-success"
                                            >
                                                Served
                                            </span>
                                            <span
                                                v-else-if="item.status == 4"
                                                class="badge badge-danger"
                                            >
                                                Suspended
                                            </span>
                                        </td>
                                        <td style="width: 120px;">
                                            <div class="btn-bulk justify-content-center">
                                                <a :href="'/hotel-order/'+item.id" class='edit btn btn-primary icon-btn btn-sm' title='view'><i class='fa fa-eye'></i></a>
                                                <a :href="'/order/pos_invoice/'+item.id+'/print_kot'" class='btn btn-secondary icon-btn btn-sm btnprn' title='Print KOT'><i class='fa fa-print'></i></a>
                                                <a :href="'/order/pos_invoice/'+item.id" class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                <a :href="'/order/pos_invoice/'+item.id+'/print_order_item_invoice'" class="btn btn-secondary btnprn icon-btn btn-sm btnprn" title="Print Invoice"><i class='fas fa-file-invoice'></i></a>
                                                <a href="javascript:void(0)" class="btn btn-primary icon-btn btn-sm" @click="openCreatePaymentModal(item)" title="Make Payment"><i class='fas fa-credit-card'></i></a>
                                                <button class="btn btn-secondary icon-btn btn-sm" title="Cancle Order"  @click="actionCancleOrder(item.id)">
                                                    <i class="fa fa-ban"></i>
                                                </button>
                                                <button class="btn btn-primary icon-btn btn-sm" title="Suspend Order"  @click="actionSuspendOrder(item.id)">
                                                    <i class="fas fa-sync"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="!loadingData && orderitems && orderitems.length <= 0"><td colspan="10">No Order Item yet.</td></tr>
                                    <tr v-if="loadingData" class="d-flex justify-content-center" style="position:absolute;top:0;bottom:0;width:100%;height:100%;background-color: rgba(0,0,0,0.1);">
                                        <div class="mt-3">
                                            <span class="d-block text-bold">Processing....</span>
                                            <div class="spinner-border text-secondary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>

                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <div class="row position">
                                    <div class="col-md-8">
                                        <p class="text-sm">
                                            Showing <strong>{{firstItem}}</strong> to
                                            <strong>{{lastItem}} </strong> of <strong>
                                                {{totalItems}}</strong>
                                            entries
                                            <!-- <span> | Takes <b>0.9</b>
                                                seconds to
                                                render</span> -->
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="pagination-sm m-0 float-right">
                                            <pagination
                                                v-model="page"
                                                :records="totalItems"
                                                :per-page="perPage"
                                                @paginate="paginate"
                                            />
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- create new payment -->
        <make-payment-modal
            v-if="order_id"
            class="mr-2"
            ref="makePaymentModal"
            :payment_types= "payment_types"
            :tax_types = "tax_types"
            :discount_types = "discount_types"
            :taxes = "taxes"
            :order_id = "order_id"
            @success ="onSuccessPayment"
        >
        </make-payment-modal>
        <!-- end of action create payment -->
        <!-- cancle order item modal -->
        <CancleOrderItemModal
            v-if="order_id"
            ref="cancleOrderItemModal"
            :order_id ="order_id"
            @success = "onSucessCancleOrder"
        >
        </CancleOrderItemModal>
        <!-- end of cancle order item -->
        <!-- cancle order item modal -->
        <SuspendOrderItemModal
            v-if="order_id"
            ref="suspendOrderItemModal"
            :order_id ="order_id"
            @success = "onSucessSuspendOrder"
        >
        </SuspendOrderItemModal>
        <!-- end of cancle order item -->
        <!-- order detail modal -->
        <OrderDetailModal
            v-if="order_id"
            ref="orderDetailModal"
            :order_id ="order_id"
        >
        </OrderDetailModal>
        <!-- end of order detail -->
    </section>
</template>
<style scoped>

</style>
<script>
import Pagination from 'vue-pagination-2';
import SuspendOrderItemModal from "@/Pages/Hotel/order_item/SuspendOrderItem"
import CancleOrderItemModal from "@/Pages/Hotel/order_item/CancleOrderItem"
import OrderDetailModal from "@/Pages/Hotel/pos/sections/OrderDetailModal"
import {status} from "@/enums/OrderItemStatus"

export default {
    props: {
        status: {
            type: Array
        },
        payment_types: {
            type: Array
        },
        tax_types: {
            type: Array
        },
        discount_types: {
            type: Array,
        },
        taxes: {
            type: Array
        }
    },
    components: {
        Pagination,
        CancleOrderItemModal,
        SuspendOrderItemModal,
        OrderDetailModal
    },
    data() {
        return {
            loadingData: false,
            showFilterDropdown: false,
            order_id: null,
            orderitems: [],
            perPage: 50,
            page: 1,
            pageCount: 0,
            totalItems: 0,
            numberOfPages: 0,
            rooms: [],
            tables: [],
            filters: {
                status: "",
                floor_id: "",
                room_id: "",
                table_id: "",
                search: ""
            },
        }
    },
    computed: {
        floors() {
            return this.$store.getters.floors;
        },
        firstItem() {
            return this.perPage * (this.page - 1) + 1;
        },
        lastItem() {
            return this.perPage * this.page;
        },
    },
    mounted() {
        this.fetchOngoingOrder();

        this.$store.dispatch('GET_RESTAURANT_FLOOR_ITEM');
    },
    watch:{
        'filters.floor_id': function (newVal, oldVal){

            this.filters.room_id = ""
            this.filters.table_id = ""

            this.fetchRoom();

            this.fetchOngoingOrder();

            this.toogleFilterDropdown();
        },
        'filters.room_id': function (newVal, oldVal){

            this.filters.table_id = ""

            this.fetchTable();

            this.fetchOngoingOrder();

            this.toogleFilterDropdown();

        },
        'filters.table_id': function (newVal, oldVal){

            this.fetchOngoingOrder();

            this.toogleFilterDropdown();
        },
    },
    methods: {
        openCreatePaymentModal(orderItem) {
            this.order_id = orderItem.id;
            setTimeout(()=> {
                this.$refs.makePaymentModal.openModal();
            }, 200);
        },
        openOrderDetailModal(orderItem) {
            this.order_id = orderItem.id;
            setTimeout(()=> {
                this.$refs.orderDetailModal.openModal();
            }, 200);
        },
        toogleFilterDropdown() {
            this.showFilterDropdown = !this.showFilterDropdown;
        },
        paginate(page) {
            this.page = page;
            this.fetchOngoingOrder();
        },
        fetchRoom(){
            let params = {
                floor_id: this.filters.floor_id
            }
            this.$store.dispatch("GET_RESTAURANT_ROOM_ITEM", {
                params: params
            })
                .then(response => {
                    this.rooms = response.data.data;
                });
        },
        fetchTable() {
            let params = {
                room_id: this.filters.room_id
            }
            this.$store.dispatch('GET_RESTAURANT_TABLE_ITEM', {
                params: params
            })
            .then(response => {
                this.tables = response.data.data
            });
        },
        fetchOngoingOrder() {
            this.loadingData = true;

            let params = {
                per_page: this.perPage,
                page: this.page,
                status: status.pending,
                sort_by: 'desc',
            }

            if(this.filters.search) {
                params.search = this.filters.search;
            }

            if(this.filters.floor_id.toString()) {
                params.floor_id = this.filters.floor_id;
            }

            if(this.filters.room_id.toString()) {
                params.room_id = this.filters.room_id;
            }

            if(this.filters.table_id.toString()) {
                params.table_id = this.filters.table_id;
            }

            this.$store.dispatch('GET_RESTAURANT_ORDER_ITEM', {
                params: params
            })
            .then(response => {
                this.orderitems = response.data.data;
                this.totalItems = response.data.meta?.total || this.orderitems.length;
                this.numberOfPages = response.data.meta.last_page || 1;
                this.loadingData = false;

                setTimeout(()=>{
                    window.initializeBtnPring();
                }, 200)

            })
            .catch();
        },
        actionCancleOrder(order_id) {
            this.order_id = order_id;

            setTimeout(()=>{
                this.$refs.cancleOrderItemModal.openModal();
            }, 200)
        },
        actionSuspendOrder(order_id) {
            this.order_id = order_id;

            setTimeout(()=>{
                this.$refs.suspendOrderItemModal.openModal();
            }, 200)
        },
        onSuccessPayment() {
            this.page = 1;
            this.totalItems = 0;
            this.order_id = null;
            this.fetchOngoingOrder();

            this.$emit('fetchOrderDetail'); //increment the totalsuspended count

        },
        onSucessCancleOrder(){
            this.page = 1;
            this.totalItems = 0;
            this.order_id = null;
            this.fetchOngoingOrder();

            this.$emit('fetchOrderDetail'); //increment the totalsuspended count
        },
        onSucessSuspendOrder() {
            this.onSucessCancleOrder();

            this.$emit('fetchOrderDetail'); //increment the totalsuspended count
        }
    }
}
</script>

