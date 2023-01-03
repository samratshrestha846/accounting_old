<template>
    <section class="pos-main-section">
        <div class="pos-main">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
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
                            <div class="col-md-2">
                                <div class="form-group" href="javascript:void(0)">
                                    <label>Status</label>
                                    <select class="form-control" v-model="filters.status">
                                        <option value="">Select Status</option>
                                        <option v-for="item in status" :value="item.value">
                                            {{item.name}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped global-table">
                        <thead>
                            <tr>
                                <th>Total</th>
                                <th>Pending</th>
                                <th>Served</th>
                                <th>Canceled</th>
                                <th>Suspended</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{defaultCurrency}} {{summary.total}}</td>
                                <td>{{defaultCurrency}} {{summary.pending}}</td>
                                <td>{{defaultCurrency}} {{summary.served}}</td>
                                <td>{{defaultCurrency}} {{summary.canceled}}</td>
                                <td>{{defaultCurrency}} {{summary.suspended}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="filter">
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
                                        <div class="form-group" href="javascript:void(0)">
                                            <label>Status</label>
                                            <select class="form-control" v-model="filters.status">
                                                <option value="">Select Status</option>
                                                <option v-for="status in statusList" :value="status">
                                                    {{$filters.getStatusNameByValue(status)}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <form class="form-inline ml-auto" @submit.prevent="fetchTodayOrder">
                                <div class="form-group mx-sm-3">
                                    <label for="search" class="sr-only">Search</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search" v-model="filters.search">
                                </div>
                                <button type="submit" class="btn btn-primary icon-btn"><i
                                        class="fa fa-search"></i></button>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered yajra-datatable text-center global-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">Order ID</th>
                                        <th class="text-center">Customer</th>
                                        <th class="text-center">Waiter</th>
                                        <th class="text-center">Table</th>
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
                                        <td>{{ item.order_at }}</td>
                                        <td>
                                            {{item.delivery_partner ? item.delivery_partner.name : '-' }}
                                        </td>
                                        <td><a href="javascript:void(0)" @click="openOrderDetailModal(item)">{{ item.total_items }}</a></td>
                                        <td>{{ item.total_cost }}</td>
                                        <td>
                                            <span v-if="item.status == status.cancled">
                                                Cancled
                                            </span>
                                            <span v-if="item.status == status.pending">
                                                Pending
                                            </span>
                                            <span v-if="item.status == status.ready">
                                                Ready
                                            </span>
                                            <span v-if="item.status == status.served">
                                                Served
                                            </span>
                                            <span v-if="item.status == status.suspended">
                                                Suspended
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-bulk justify-content-center">
                                                <a :href="'/order/pos_invoice/'+item.id+'/print_kot'" class='edit btn btn-primary icon-btn btn-sm btnprn' title='Print KOT'><i class='fa fa-print'></i></a>
                                                <a :href="'/hotel-order/'+item.id" class='edit btn btn-secondary icon-btn btn-sm' title='view'><i class='fa fa-eye'></i></a>
                                                <a v-if="item.billing_id" :href="'/order/billing/'+ item.billing_id +'/generateinvoice'" class="btn btn-primary btnprn icon-btn btn-sm" title="Print Invoice"><i class='fas fa-file-invoice'></i></a>
                                                <a v-else :href="'/order/pos_invoice/'+item.id+'/print_order_item_invoice'" class="btn btn-primary btnprn icon-btn btn-sm" title="Print Invoice"><i class='fas fa-file-invoice'></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="!loadingData && orderitems && orderitems.length <= 0"><td colspan="9">No Order Item yet.</td></tr>
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
import OrderDetailModal from "@/Pages/Hotel/pos/sections/OrderDetailModal"
import {status} from "@/enums/OrderItemStatus"
export default {
    props: {
        pos_setting: {
            type: Object,
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
        },
        status: {
            type: Array,
        }
    },
    components: {
        Pagination,
        OrderDetailModal,
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
            defaultCurrency: this.pos_setting?.default_currency || 'NAN',
            rooms: [],
            tables: [],
            filters: {
                floor_id: "",
                room_id: "",
                table_id: "",
                search: "",
                status: "",
            },
            statusList: [],
            summary: {
                total: 0,
                pending: 0,
                served: 0,
                canceled: 0,
                suspended: 0,
            }
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
        this.statusList = this.$filters.statusList;

        Promise.all([
            this.$store.dispatch('GET_RESTAURANT_ORDER_DETAIL'),
            this.fetchTodayOrder(),
            this.$store.dispatch('GET_RESTAURANT_FLOOR_ITEM'),
        ])
        .then((response) => {

            //set the summary detail
            this.summary.total = Number(response[0].data.data.total);
            this.summary.pending = Number(response[0].data.data.total_pending);
            this.summary.served = Number(response[0].data.data.total_served);
            this.summary.canceled = Number(response[0].data.data.total_cancled);
            this.summary.suspended = Number(response[0].data.data.total_suspended);
        })
        .catch(error => {
            this.$toast.open({
                message: error.response?.data?.message || 'Something went wrong while fettching data.',
                type: 'error',
                position: 'top-right'
            });
        });


    },
    watch:{
        'filters.status': function (newVal, oldVal){

            this.fetchTodayOrder();
            this.toogleFilterDropdown();
        },
        'filters.floor_id': function (newVal, oldVal){

            this.filters.room_id = ""
            this.filters.table_id = ""

            this.fetchRoom();

            this.fetchTodayOrder();

            this.toogleFilterDropdown();
        },
        'filters.room_id': function (newVal, oldVal){

            this.filters.table_id = ""

            this.fetchTable();

            this.fetchTodayOrder();

            this.toogleFilterDropdown();

        },
        'filters.table_id': function (newVal, oldVal){

            this.fetchTodayOrder();

            this.toogleFilterDropdown();
        },
        'filters.status': function (newVal, oldVal){

            this.fetchTodayOrder();

            this.toogleFilterDropdown();
        },
    },
    methods: {
        toogleFilterDropdown() {
            this.showFilterDropdown = !this.showFilterDropdown;
        },
        openOrderDetailModal(orderItem) {
            this.order_id = orderItem.id;
            setTimeout(()=> {
                this.$refs.orderDetailModal.openModal();
            }, 200);
        },
        paginate(page) {
            this.page = page;
            this.fetchTodayOrder();
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
        async fetchTodayOrder() {
            this.loadingData = true;

            let params = {
                per_page: this.perPage,
                page: this.page,
                order_at: this.todayDate(),
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

            if(this.filters.status.toString()) {
                params.status = this.filters.status;
            }

            return await this.$store.dispatch('GET_RESTAURANT_ORDER_ITEM', {
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
            .catch(error => {

            });
        },
        todayDate() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;

            return today;
        }
    }
}
</script>

