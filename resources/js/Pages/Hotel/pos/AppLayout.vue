<template>
    <div>
        <!-- Header -->
        <header id="header" class="header">
            <div class="container-fluid">
                <div class="h-wrap">
                    <div class="logo">
                        <a href="/"><img src="/img/logo.png" alt="images" style="max-width:170px;"></a>
                        <div class="time desks-only">
                            <span class="pos-date">Friday, 19 November 2021, 04:15:26 PM</span>
                        </div>
                    </div>
                    <div class="header-right desks-only">
                        <div class="header-btns">
                            <div class="btn-bulk">
                                <a href="javascript:void(0)" class="btn btn-primary btn-info" :class="{'active' : currentTab == 1}" @click="currentTab = 1"><i class="las la-home"></i> New</a>
                                <a href="javascript:void(0)" class="btn btn-secondary" :class="{'active' : currentTab == 2}" @click="currentTab = 2"><i class="las la-hourglass-start"></i> Ongoing <span>{{totalPending}}</span></a>
                                <a href="javascript:void(0)" class="btn btn-primary" :class="{'active' : currentTab == 3}" @click="currentTab = 3"><i class="las la-ban"></i> Suspended <span>{{totalSuspended}}</span></a>
                                <a href="javascript:void(0)" class="btn btn-secondary btn-success" :class="{'active' : currentTab == 4}" @click="currentTab = 4"><i class="las la-chart-line"></i> Today's Order <span>{{totalTodayOrders}}</span></a>
                                <a href="javascript:void(0)" class="btn btn-secondary btn-info" :class="{'active' : currentTab == 5}" @click="currentTab = 5"><i class="las la-hand-holding-heart"></i> Take Away <span>{{totalTakeAway}}</span></a>
                                <a href="javascript:void(0)" class="btn btn-secondary btn-success" :class="{'active' : currentTab == 6}" @click="currentTab = 6"><i class="las la-truck"></i> Online Delivery <span>{{totalOnlineDelivery}}</span></a>
                                <a href="javascript:void(0)" class="btn btn-primary" :class="{'active' : currentTab == 7}" @click="currentTab = 7"><i class="las la-bookmark"></i> Table Order <span>{{totalTableOrder}}</span></a>
                            </div>
                            <div class="accounts desks-only">
                                <nav class="navbar navbar-expand-md p-0">
                                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                                        <ul class="navbar-nav">
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Hi, {{userName}}
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                    <li>
                                                        <a href="/pos-settings" class="dropdown-item">Pos Setting</a>
                                                    </li>
                                                    <li>
                                                        <form id="logoutform" method="POST" action="">
                                                            <a
                                                                type="submit"
                                                                class="dropdown-item"
                                                                href="javascript:void(0)"
                                                                @click="actionLogout"

                                                            >
                                                                Logout
                                                            </a>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="hr-mobile">
                        <div class="accounts mobs-only">
                            <nav class="navbar navbar-expand-md p-0">
                                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                                    <ul class="navbar-nav">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="las la-user-check"></i>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                <li>
                                                        <a href="/pos-settings" class="dropdown-item">Pos Setting</a>
                                                    </li>
                                                <li>
                                                    <form id="mobilelogoutForm" method="POST" action="#">

                                                        <a
                                                            type="submit"
                                                            class="dropdown-item"
                                                            href="javascript:void(0)"
                                                            @click="actionLogout"
                                                        >
                                                            Logout
                                                        </a>
                                                    </form>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                        <!-- <div class="toggles-btn">
                            <div class="toggle-wrap">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <div class="toggle-wrap">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <div class="toggle-wrap">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </header>
        <!-- Header End -->

        <!-- Mobile Menu -->
        <div id="mySidenav" class="sidenav">
            <div class="mobile-logo">
                <a href="index.html"><img src="/img/logo.png" alt="logo"></a>
                <a href="javascript:void(0)" id="close-btn" class="closebtn">&times;</a>
            </div>
            <div class="no-bdr1">
                <div class="time mobs-only">
                    <span class="pos-date">Friday, 19 November 2021, 04:15:26 PM</span>
                </div>
                <ul id="menu1">
                    <!-- <li><a href="#">POS Setting</a></li>
                    <li>
                        <a href="#" class="has-arrow">Sales</a>
                        <ul>
                            <li>
                                <a href="#">Sales</a>
                            </li>
                            <li>
                                <a href="#">Suspended Sales</a>
                            </li>
                        </ul>
                    </li> -->
                </ul>
            </div>
        </div>
        <!-- Mobile Menu End -->

        <!-- mobile view tab start-->
        <div class="col-md-12 mobs-only pb-1">
            <div class="header-right">
                <div class="header-btns">
                    <div class="btn-bulk">
                        <a href="javascript:void(0)" class="btn btn-primary btn-info" :class="{'active' : currentTab == 1}" @click="currentTab = 1"><i class="las la-home"></i> New</a>
                        <a href="javascript:void(0)" class="btn btn-secondary" :class="{'active' : currentTab == 2}" @click="currentTab = 2"><i class="las la-hourglass-start"></i> Ongoing <span>{{totalPending}}</span></a>
                        <a href="javascript:void(0)" class="btn btn-primary" :class="{'active' : currentTab == 3}" @click="currentTab = 3"><i class="las la-ban"></i> Suspended <span>{{totalSuspended}}</span></a>
                        <a href="javascript:void(0)" class="btn btn-secondary btn-success" :class="{'active' : currentTab == 4}" @click="currentTab = 4"><i class="las la-chart-line"></i> Today's Order <span>{{totalTodayOrders}}</span></a>
                        <a href="javascript:void(0)" class="btn btn-secondary btn-info" :class="{'active' : currentTab == 5}" @click="currentTab = 5"><i class="las la-hand-holding-heart"></i> Take Away <span>{{totalTakeAway}}</span></a>
                        <a href="javascript:void(0)" class="btn btn-secondary btn-success" :class="{'active' : currentTab == 6}" @click="currentTab = 6"><i class="las la-truck"></i> Online Delivery <span>{{totalOnlineDelivery}}</span></a>
                        <a href="javascript:void(0)" class="btn btn-primary" :class="{'active' : currentTab == 7}" @click="currentTab = 7"><i class="las la-bookmark"></i> Table Order <span>{{totalTableOrder}}</span></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- mobile view tab end-->

        <PosOrder
            v-show="currentTab == 1"
            :payment_types = "payment_types"
            :order_types="order_types"
            :customer_types = "customer_types"
            :categories= "categories"
            :tax_types = "tax_types"
            :discount_types = "discount_types"
            :taxes = "taxes"
            :suspendedsale = "suspendedsale"
            :pos_setting = "pos_setting"
            :eng_date = "eng_date"
            :nep_date = "nep_date"
            :user_company = "user_company"
            :orderitem = "orderitem"
            @fetchOrderDetail="fetchOrderDetail"
        >
        </PosOrder>
        <OngoingOrder
            v-if="currentTab == 2"
            :status="status"
            :payment_types = "payment_types"
            :tax_types = "tax_types"
            :discount_types = "discount_types"
            :taxes = "taxes"
            @fetchOrderDetail="fetchOrderDetail"
        >
        </OngoingOrder>
        <SuspendedOrderList
            v-if="currentTab == 3"
            :payment_types = "payment_types"
            :tax_types = "tax_types"
            :discount_types = "discount_types"
            :taxes = "taxes"
            @fetchOrderDetail="fetchOrderDetail"
        >
        </SuspendedOrderList>
        <TodayOrderList
            v-if="currentTab == 4"
            :status ="status"
            :payment_types = "payment_types"
            :tax_types = "tax_types"
            :discount_types = "discount_types"
            :taxes = "taxes"
            :pos_setting = "pos_setting"
        >
        </TodayOrderList>
        <TakeAwayOrder
            v-if="currentTab == 5"
            :payment_types = "payment_types"
            :tax_types = "tax_types"
            :discount_types = "discount_types"
            :status ="status"
            :taxes = "taxes"
            :pos_setting = "pos_setting"
            @fetchOrderDetail="fetchOrderDetail"
        >
        </TakeAwayOrder>
        <OnlineDeliveryOrderList
            v-if="currentTab == 6"
            :status="status"
            :payment_types = "payment_types"
            :tax_types = "tax_types"
            :discount_types = "discount_types"
            :taxes = "taxes"
            :pos_setting = "pos_setting"
            @fetchOrderDetail="fetchOrderDetail"
        >
        </OnlineDeliveryOrderList>
        <TableOrderList
            v-if="currentTab == 7"
            :status="status"
            :payment_types = "payment_types"
            :tax_types = "tax_types"
            :discount_types = "discount_types"
            :taxes = "taxes"
            :pos_setting = "pos_setting"
            @fetchOrderDetail="fetchOrderDetail"
        >

        </TableOrderList>
    </div>
</template>

<script>
import PosOrder from "@/Pages/Hotel/pos/sections/PosOrder"
import OngoingOrder from "@/Pages/Hotel/pos/sections/OngoingOrder"
import TodayOrderList from "@/Pages/Hotel/pos/sections/TodayOrderList"
import SuspendedOrderList from "@/Pages/Hotel/pos/sections/SuspendedOrderList"
import TakeAwayOrder from "@/Pages/Hotel/pos/sections/TakeAwayOrderList"
import OnlineDeliveryOrderList from "@/Pages/Hotel/pos/sections/OnlineDeliveryOrderList"
import TableOrderList from "@/Pages/Hotel/pos/sections/TableOrderList"
import $ from "jquery"
export default {
    props: {
        auth_user: {
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
        order_types: {
            type: Array,
        },
        payment_types: {
            type: Array
        },
        customer_types: {
            type: Array
        },
        categories: {
            type: Array
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
        suspendedsale: {
            type: Object
        },
        pos_setting: {
            type: Object
        },
        total_records: {
            type: Object
        },
        orderitem: {
            type: Object
        },
    },
    components: {
        PosOrder,
        OngoingOrder,
        TodayOrderList,
        SuspendedOrderList,
        TakeAwayOrder,
        OnlineDeliveryOrderList,
        TableOrderList,
    },
    data(){
        return {
            currentTab: 1,
            totalPending:this.total_records.total_pending || 0,
            totalSuspended: this.total_records.total_suspended || 0,
            totalTodayOrders: this.total_records.total_today_order || 0,
            totalTakeAway: this.total_records.total_take_away || 0,
            totalOnlineDelivery: this.total_records.total_online_delivery || 0,
            totalTableOrder: this.total_records.total_table_order || 0,
            status: [
                {
                    value: 0,
                    name: 'Cancled',
                },
                {
                    value: 1,
                    name: 'Pending',
                },
                {
                    value: 3,
                    name: 'Served',
                },
                {
                    value: 4,
                    name: 'Suspended',
                },
            ]
        }
    },
    computed: {
        userName() {
            return this.auth_user.name;
        },
    },
    mounted() {
        this.setTimer();
    },
    methods: {
        setTimer(){
            setInterval(function() {
                var now = new Date();

                let date = moment(now).format('dddd, DD MMMM YYYY, hh:mm:ss A');

                $('.pos-date').text(date);
            }, 1000);
        },
        fetchOrderDetail(){
            axios.get('/api/hotel/order/get_order_detail')
            .then(response => {
                this.totalPending = response.data.data.total_pending || 0;
                this.totalSuspended = response.data.data.total_suspended || 0;
                this.totalTodayOrders = response.data.data.total_today_order || 0;
                this.totalTakeAway = response.data.data.total_take_away || 0;
                this.totalOnlineDelivery = response.data.data.total_online_delivery || 0;
                this.totalTableOrder = response.data.data.total_table_order || 0;
            })
            .catch(error =>  {
                this.$toast.open({
                    message: error.response.data.message,
                    type: 'error',
                    position: 'top-right'
                });
            });
        },
        actionLogout() {
            this.$swal.fire({
                title: "Are you sure you want to logout?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: `Sure`,
                denyButtonText: `Cancle`,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$store.dispatch('LOGOUT')
                    .then(response => {
                        window.location.href = "/login";
                    })
                    .catch(error => {
                        this.$toast.open({
                            message: error.response.data.message || "Something went wrong....",
                            type: 'error',
                            position: 'top-right'
                        });
                    });
                }
            });

        }
    }
}
</script>

