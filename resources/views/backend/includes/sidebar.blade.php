<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    @php
        $user = Auth::user()->id;
        $usercompanies = \App\Models\UserCompany::where('user_id', $user)->get();
        $currentcomp = \App\Models\UserCompany::where('user_id', $user)
            ->where('is_selected', 1)
            ->first();
        $setting = \App\Models\Setting::first();
    @endphp
    <div class="user-drpp">
        <a href="{{ route('home') }}" class="brand-link">
            <img src="{{ asset('logo/logo1.png') }}" alt="{{ $setting->company_name }}" class="brand-image">
            <span class="brand-text font-weight-bold" style="color:#fff;">&nbsp;</span>
        </a>
        <a class="nav-link tog-btn mob-only" data-widget="pushmenu" href="#" role="button"><i
                class="las la-bars"></i></a>
        {{-- <div class="customdropdown text-center">
            <ul>
                @foreach ($usercompanies as $usercompany)
                    <li><a
                            href="{{ route('switch.selected', $usercompany->id) }}">{{ $usercompany->company->name }}{{ $usercompany->branch->is_headoffice == 0 ? ' (' . $usercompany->branch->name . ')' : ' (Head Office)' }}</a>
                    </li>
                @endforeach
            </ul>
        </div> --}}
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        {{-- <div class="user-panel d-flex">
            <div class="image">
                <img src="{{ Storage::disk('uploads')->url($currentcomp->company->company_logo) }}"
                    class="img-circle elevation-2" alt="{{ Auth::user()->name }}">
            </div>
            <div class="info">
                <a href="{{ route('user.edit', Auth::user()->id) }}"
                    class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div> --}}

        <!-- Sidebar Menu -->
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="las la-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>



                @if (Auth::user()->can('manage-quotations'))
                    <li class="nav-item {{selectMenuwithroute(url('/billings/billingsreport/7'),route('billings.quotationcreate'))}}">
                        <a href="#" class="nav-link {{ request()->is('/billings/billingsreport/7') || request()->routeIs('billings.quotationcreate') ? 'active' : '' }}">
                            <i class="las la-quote-right"></i>
                            <p>
                                Quotation
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            <li class="nav-item {{ request()->is('/billings/billingsreport/7') ? 'active' : '' }}">
                                <a href="{{ route('billings.report', 7) }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>View All Quotation</p>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('billings.quotationcreate') ? 'active' : '' }}">
                                <a href="{{ route('billings.quotationcreate') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Create New Quotation</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- @if ( Auth::user()->can('manage-purchases') || Auth::user()->can('manage-purchase-orders') || Auth::user()->can('manage-debit-note') || Auth::user()->can('view-supplier') || Auth::user()->can('create-supplier') || Auth::user()->can('edit-supplier') || Auth::user()->can('remove-supplier') || Auth::user()->can('manage-payment'))
                    <li class="nav-item {{selectMenuwithroute(url('/billings/billingsreport/5'),url('/billings/billingsreport/2'),route('billings.purchasecreate'),route('billings.paymentcreate'),url('/billings/billingsreport/4'),url('/purchaseOrder')) }}{{selectedMenu('purchaseOrder','vendors')}}">
                        <a href="#" class="nav-link
                            {{ request()->is('/billings/billingsreport/5')
                                || request()->is('/billings/billingsreport/2')
                                || request()->routeIs('billings.purchasecreate')
                                || request()->routeIs('billings.debitnotecreate')
                                || request()->routeIs('vendors*')
                                || request()->routeIs('purchaseOrder*')
                                || request()->is('/billings/billingsreport/4')
                                || request()->routeIs('billings.paymentcreate')
                                ? 'active' : ''
                            }}">
                            <i class="las la-shopping-cart"></i>
                            <p>
                                Purchase
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 10px;">
                            @if (Auth::user()->can('manage-purchases'))
                                <li class="nav-item {{ request()->is('/billings/billingsreport/2') ? 'active' : '' }}">
                                    <a href="{{ route('billings.report', 2) }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>View All Purchase</p>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->routeIs('billings.purchasecreate') ? 'active' : '' }}">
                                    <a href="{{ route('billings.purchasecreate') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Create New Purchase</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-debit-note'))
                                <li class="nav-item {{ request()->is('/billings/billingsreport/5') || request()->routeIs('billings.debitnotecreate') ? 'active' : '' }}">
                                    <a href="{{ route('billings.report', 5) }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>View All Debit Note</p>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->routeIs('service_sales.index') ? 'active' : '' }}">
                                    <a href="{{ route('service_sales.index',['billing_type_id'=>2]) }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>View Service Purchase</p>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->routeIs('service.purchasecreate') ? 'active' : '' }}">
                                    <a href="{{ route('service.purchasecreate') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Create New Service Purchase</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-purchase-orders'))
                                <li class="nav-item {{selectMenuwithroute(url('purchaseOrder'),url('purchaseOrder/create'))}}">
                                    <a href="#" class="nav-link {{ request()->routeIs('purchaseOrder*') ? 'active' : '' }}">
                                        <i class="las la-sort"></i>
                                        <p>
                                            Purchase Orders
                                            <i class="las la-angle-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="padding-left: 15px;">
                                        <li class="nav-item {{ request()->routeIs('purchaseOrder.client') ? 'active' : '' }}">
                                            <a href="{{ route('purchaseOrder.client') }}" class="nav-link">
                                                <i class="las la-circle-notch"></i>
                                                <p>Client Purchase Orders</p>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('purchaseOrder.index') ? 'active' : '' }}">
                                            <a href="{{ route('purchaseOrder.index') }}" class="nav-link">
                                                <i class="las la-circle-notch"></i>
                                                <p>All Purchase Orders</p>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('purchaseOrder.create') ? 'active' : '' }}">
                                            <a href="{{ route('purchaseOrder.create') }}" class="nav-link">
                                                <i class="las la-circle-notch"></i>
                                                <p>Create Purchase Order</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-payment'))
                                <li class="nav-item {{selectMenuwithroute(url('/billings/billingsreport/4'),url('/billings/paymentcreate'))}} ">
                                    <a href="#" class="nav-link
                                    {{ request()->is('/billings/billingsreport/4')
                                        || request()->routeIs('billings.paymentcreate')
                                        ? 'active' : ''
                                    }}">
                                        <i class="las la-hand-holding-usd"></i>
                                        <p>
                                            Payments
                                            <i class="las la-angle-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="padding-left: 20px;">
                                        <li class="nav-item
                                        {{ request()->is('/billings/billingsreport/4')
                                            ? 'active' : ''
                                        }}">
                                            <a href="{{ route('billings.report', 4) }}" class="nav-link">
                                                <i class="las la-circle-notch"></i>
                                                <p>View All Payments</p>
                                            </a>
                                        </li>
                                        <li class="nav-item
                                        {{ request()->routeIs('billings.paymentcreate')
                                            ? 'active' : ''
                                        }}">
                                            <a href="{{ route('billings.paymentcreate') }}" class="nav-link">
                                                <i class="las la-circle-notch"></i>
                                                <p>Create New Payment</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            @if (Auth::user()->can('view-supplier') || Auth::user()->can('create-supplier') || Auth::user()->can('edit-supplier') || Auth::user()->can('remove-supplier'))
                                <li class="nav-item {{selectedMenu('vendors')}}">
                                    <a href="#" class="nav-link
                                    {{ request()->routeIs('vendors*')
                                        ? 'active' : ''
                                    }}">
                                        <i class="las la-user-nurse"></i>
                                        <p>
                                            Suppliers
                                            <i class="las la-angle-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="padding-left: 20px;">
                                        @if (Auth::user()->can('view-supplier'))
                                            <li class="nav-item
                                                {{ request()->routeIs('vendors.index')
                                                    ? 'active' : ''
                                                }}">
                                                <a href="{{ route('vendors.index') }}" class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>View Suppliers</p>
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->can('create-supplier'))
                                            <li class="nav-item
                                                {{ request()->routeIs('vendors.create')
                                                    ? 'active' : ''
                                                }}">
                                                <a href="{{ route('vendors.create') }}" class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>Add New Supplier</p>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif --}}




                {{-- @if ( Auth::user()->can('manage-sales') || Auth::user()->can('manage-sales-invoices') || Auth::user()->can('manage-credit-note') || Auth::user()->can('view-customer') || Auth::user()->can('create-customer') || Auth::user()->can('edit-customer') || Auth::user()->can('remove-customer') || Auth::user()->can('manage-receipts') || Auth::user()->can('manage-service-sales'))
                    <li class="nav-item {{selectMenuwithroute(url('/billings/billingsreport/1'),url('billings/salescreate'),url('billings/billingsreport/6'),url('billings/billingsreport/3'),url('/billings/receiptcreate'))}}{{selectedMenu('service_sales','client')}}">
                        <a href="#" class="nav-link
                            {{ request()->is('/billings/billingsreport/6')
                                || request()->is('/billings/billingsreport/1')
                                || request()->routeIs('billings.salescreate')
                                || request()->routeIs('billings.creditnotecreate')
                                || request()->routeIs('service_sales.index')
                                || request()->routeIs('service_sales.create')
                                || request()->routeIs('service_sales.edit')
                                || request()->routeIs('client*')
                                || request()->routeIs('salesinvoice*')
                                || request()->routeIs('salesInvoiceCreate')
                                || request()->is('/billings/billingsreport/3')
                                || request()->routeIs('billings.receiptcreate')
                                ? 'active' : ''
                            }}">
                            <i class="las la-chart-bar"></i>
                            <p>
                                Sales
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 10px;">

                            @if (Auth::user()->can('manage-sales'))
                                <li class="nav-item {{ request()->is('/billings/billingsreport/1') ? 'active' : '' }}">
                                    <a href="{{ route('billings.report', 1) }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>View All Sales</p>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->routeIs('billings.salescreate') ? 'active' : '' }}">
                                    <a href="{{ route('billings.salescreate') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Create New Sales</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-credit-note'))
                                <li class="nav-item {{ request()->is('/billings/billingsreport/6') || request()->routeIs('billings.creditnotecreate') ? 'active' : '' }}">
                                    <a href="{{ route('billings.report', 6) }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>View All Credit Note</p>
                                    </a>
                                </li>
                            @endif

                            {{-- @if (Auth::user()->can('manage-sales-invoices'))
                                <li class="nav-item">
                                    <a href="#" class="nav-link {{ request()->routeIs('salesinvoice*') || request()->routeIs('salesInvoiceCreate') ? 'active' : '' }}">
                                        <i class="las la-receipt"></i>
                                        <p>
                                            Sales Invoice
                                            <i class="las la-angle-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="padding-left: 20px;">
                                        <li class="nav-item">
                                            <a href="{{ route('salesinvoice', 1) }}" class="nav-link {{ request()->routeIs('salesinvoice*') ? 'active' : '' }}">
                                                <i class="las la-circle-notch"></i>
                                                <p>View Sales Invoice</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('salesInvoiceCreate') }}" class="nav-link {{ request()->routeIs('salesInvoiceCreate') ? 'active' : '' }}">
                                                <i class="las la-circle-notch"></i>
                                                <p>Create New Invoice</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif --}}

                            {{-- @if (Auth::user()->can('manage-service-sales'))
                                <li class="nav-item {{selectedMenu('service_sales')}}">
                                    <a href="#" class="nav-link {{ request()->routeIs('service_sales.index')
                                                                    || request()->routeIs('service_sales.create')
                                                                    || request()->routeIs('service_sales.edit')
                                                                    ? 'active' : '' }}">
                                        <i class="las la-money-bill"></i>
                                        <p>
                                            Service Sales
                                            <i class="las la-angle-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="padding-left: 20px;">
                                        <li class="nav-item {{ request()->routeIs('service_sales.index') ? 'active' : '' }}">
                                            <a href="{{ route('service_sales.index') }}" class="nav-link">
                                                <i class="las la-circle-notch"></i>
                                                <p>View All Sales</p>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('service_sales.create') ? 'active' : '' }}">
                                            <a href="{{ route('service_sales.create') }}" class="nav-link">
                                                <i class="las la-circle-notch"></i>
                                                <p>Create New Sales</p>
                                            </a>
                                        </li>


                                    </ul>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif --}}

                {{-- @if (Auth::user()->can('view-products') || Auth::user()->can('create-product') || Auth::user()->can('edit-product') || Auth::user()->can('delete-product') || Auth::user()->can('manage-product-categories') || Auth::user()->can('manage-product-images'))
                    <li class="nav-item {{selectedMenu('product','category','fileManager')}}">
                        <a href="#" class="nav-link {{
                                request()->routeIs('product*')
                                || request()->routeIs('category*')
                                || request()->routeIs('fileManager*')
                             ? 'active' : '' }}">
                            <i class="lab la-product-hunt"></i>
                            <p>
                                Products
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            @if (Auth::user()->can('manage-product-categories'))
                                <li class="nav-item {{ request()->routeIs('category*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('category.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Product Categories</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('view-products') || Auth::user()->can('create-product'))
                                <li class="nav-item {{ request()->routeIs('product.index') ? 'active' : '' }}">
                                    <a href="{{ route('product.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>View All Products</p>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->routeIs('product.create') ? 'active' : '' }}">
                                    <a href="{{ route('product.create') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Create New Product</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-product-images'))
                                <li class="nav-item {{request()->routeIs('fileManager*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('fileManager') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>
                                            Upload Product Images
                                        </p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif --}}

                @if (Auth::user()->can('manage-godown-information') || Auth::user()->can('manage-brand-information') || Auth::user()->can('manage-series-information') || Auth::user()->can('manage-damaged-product') || Auth::user()->can('manage-product-report'))
                    <li class="nav-item {{selectedMenu('godown','brand','series','damaged_products','stockout','transferReport')}}{{selectMenuwithroute(url('product/stockreport'))}}">
                        <a href="#" class="nav-link
                        {{ request()->routeIs('godown*')
                            || request()->routeIs('brand*')
                            || request()->routeIs('series*')
                            || request()->routeIs('damaged_products*')
                            || request()->routeIs('stockreportcreate')
                            || request()->routeIs('transferReport*')
                            || request()->routeIs('stockout*')
                            ? 'active' : ''
                        }}">
                            <i class="las la-chart-line"></i>
                            <p>
                                Stock Management
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            @if (Auth::user()->can('manage-godown-information'))
                                <li class="nav-item {{ request()->routeIs('godown*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('godown.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Godown Information</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-brand-information'))
                                <li class="nav-item {{ request()->routeIs('brand*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('brand.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Brand Information</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-series-information'))
                                <li class="nav-item {{ request()->routeIs('series*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('series.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Series Information</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-damaged-product'))
                                <li class="nav-item {{ request()->routeIs('damaged_products*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('damaged_products.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Damaged Products</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-product-report'))
                                <li class="nav-item {{ request()->routeIs('stockreportcreate')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('stockreportcreate') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Product Stock Report</p>
                                    </a>
                                </li>

                                <li class="nav-item {{ request()->routeIs('transferReport*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('transferReport.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Product Transfer Report</p>
                                    </a>
                                </li>

                                <li class="nav-item {{ request()->routeIs('stockout*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('stockout.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Product Stock Out</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->can('manage-service-categories') || Auth::user()->can('manage-services'))
                    <li class="nav-item {{selectedMenu('service_category','service')}}">
                        <a href="#" class="nav-link
                        {{ request()->routeIs('service_category*')
                            || request()->routeIs('service.index')
                            || request()->routeIs('service.create')
                            || request()->routeIs('service.edit')
                            ? 'active' : ''
                        }}">
                            <i class="las la-concierge-bell"></i>
                            <p>
                                Service Management
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            @if (Auth::user()->can('manage-service-categories'))
                                <li class="nav-item
                                {{ request()->routeIs('service_category*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('service_category.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Service Categories</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-services'))
                                <li class="nav-item
                                {{ request()->routeIs('service.index')
                                || request()->routeIs('service.create')
                                || request()->routeIs('service.edit')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('service.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>All Services</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->can('manage-pos') || Auth::user()->can('manage-suspended-bill'))
                    <li class="nav-item {{selectedMenu('outlettransfer','outlet','biller','suspendedsale','pos')}}">
                        <a href="#" class="nav-link
                        {{ request()->routeIs('outlettransfer*')
                            || request()->routeIs('outlet*')
                            || request()->routeIs('biller*')
                            || request()->routeIs('pos.sales')
                            || request()->routeIs('suspendedsale*')
                            ? 'active' : ''
                        }}">
                            <i class="las la-exchange-alt"></i>
                            <p>
                                POS Management
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            @if (Auth::user()->can('manage-pos'))
                                <li class="nav-item
                                {{ request()->routeIs('outlettransfer*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('outlettransfer.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Transfer to Outlet</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-pos'))
                                <li class="nav-item
                                {{ request()->routeIs('outlet*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('outlet.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Outlets</p>
                                    </a>
                                </li>

                                <li class="nav-item
                                {{ request()->routeIs('biller*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('biller.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Billers</p>
                                    </a>
                                </li>

                                <li class="nav-item
                                {{ request()->routeIs('pos.sales')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('pos.sales') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>POS Sales Bills Report</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-suspended-bill'))
                                <li class="nav-item
                                {{ request()->routeIs('suspendedsale*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('suspendedsale.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Suspended Sales</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @canany(['hotel-food-view', 'hotel-floor-view', 'hotel-room-view', 'hotel-table-view','hotel-kitchen-view','cabin-type-view'])
                <li class="nav-item {{ selectedMenu('hotel-floor','hotel-room','hotel-table','hotel-kitchen','hotel-food','cabintype')}}">

                    <a href="#" class="nav-link">
                        <i class="las la-bacon"></i>
                        <p>
                            Food Management
                            <i class="las la-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="padding-left: 20px;">
                        @can('hotel-food-view')
                        <li class="nav-item">
                            <a href="{{ route('hotel-food.index') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Food</p>
                            </a>
                        </li>
                        @endcan

                        @can('hotel-floor-view')
                        <li class="nav-item">
                            <a href="{{ route('hotel-floor.index') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Floor</p>
                            </a>
                        </li>
                        @endcan

                        @can('hotel-room-view')
                        <li class="nav-item">
                            <a href="{{ route('hotel-room.index') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Room</p>
                            </a>
                        </li>
                        @endcan

                        @can('hotel-table-view')
                        <li class="nav-item">
                            <a href="{{ route('hotel-table.index') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Table</p>
                            </a>
                        </li>
                        @endcan

                        @can('hotel-kitchen-view')
                        <li class="nav-item">
                            <a href="{{ route('hotel-kitchen.index') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Kitchen</p>
                            </a>
                        </li>
                        @endcan
                        @can('cabin-type-view')
                        <li class="nav-item">
                            <a href="{{ route('cabintype.index') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Cabin Type</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @canany(['hotel-reservation-view','hotel-reservation-create','hotel-reservation-edit'])

                <li class="nav-item {{selectedMenu('hotel-reservation')}}">
                    <a href="#" class="nav-link">
                        <i class="las la-utensils"></i>
                        <p>
                            Hotel Reservation
                            <i class="las la-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="padding-left: 20px;">
                        @can('hotel-reservation-view')
                        <li class="nav-item">
                            <a href="{{ route('hotel-reservation.index') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>List Reservation</p>
                            </a>
                        </li>
                        @endcan
                        @can('hotel-reservation-create')
                        <li class="nav-item">
                            <a href="{{ route('hotel-reservation.create') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Create Reservation</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany
                @canany(['hotel-order-view','hotel-order-invoice'])

                <li class="nav-item {{selectedMenu('hotel-order','hotel_order')}}">
                    <a href="#" class="nav-link">
                        <i class="las la-shopping-cart"></i>
                        <p>
                            Order Management
                            <i class="las la-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="padding-left: 20px;">
                        @can('hotel-order-invoice')
                        <li class="nav-item">
                            <a href="{{route('hotel_order.pos_invoice')}}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Pos Invoice</p>
                            </a>
                        </li>
                        @endcan
                        @can('hotel-order-view')
                        <li class="nav-item">
                            <a href="{{ route('hotel-order.index') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Order List</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('hotel_order.ongoing_order') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Pending Orders</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('hotel_order.complete_order') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Complete Orders</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('hotel_order.cancled_order') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Cancel Order</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hotel_order.suspended_order') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Suspense Order</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany
                {{-- @if (Auth::user()->can('manage-company-shares') || Auth::user()->can('manage-personal-shares'))
                    <li class="nav-item">
                        <a href="#" class="nav-link
                        {{ request()->routeIs('company_share*')
                            || request()->routeIs('personal_share*')
                            ? 'active' : ''
                        }}">
                            <i class="las la-chart-pie"></i>
                            <p>
                                Share Management
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            @if (Auth::user()->can('manage-company-shares'))
                                <li class="nav-item
                                {{ request()->routeIs('company_share*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('company_share.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Company Shares</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-personal-shares'))
                                <li class="nav-item
                                {{ request()->routeIs('personal_share*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('personal_share.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Personal Shares</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif --}}

                @if (Auth::user()->can('manage-credit'))
                    <li class="nav-item {{selectedMenu('selectedMenu')}}">
                        <a href="{{ route('credit.index') }}" class="nav-link
                        {{ request()->routeIs('credit*')
                            ? 'active' : ''
                        }}">
                            <i class="las la-credit-card"></i>
                            <p>
                                Credit Management
                            </p>
                        </a>
                    </li>
                @endif



                {{-- @if (Auth::user()->can('loan-management'))
                    <li class="nav-item">
                        <a href="#" class="nav-link
                        {{ request()->routeIs('loan*')
                            ? 'active' : ''
                        }}">
                            <i class="las la-hand-holding-usd"></i>
                            <p>
                                Loan Management
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            <li class="nav-item
                            {{ request()->routeIs('loan.index')
                                ? 'active' : ''
                            }}">
                                <a href="{{ route('loan.index') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Manage Loans</p>
                                </a>
                            </li>

                            <li class="nav-item
                            {{ request()->routeIs('loan.create')
                                ? 'active' : ''
                            }}">
                                <a href="{{ route('loan.create') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Create Loan Info</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif --}}

                {{-- @if (Auth::user()->can('manage-positions') || Auth::user()->can('manage-department') || Auth::user()->can('manage-staffs') || Auth::user()->can('manage-attendance') || Auth::user()->can('manage-attendance-report') || Auth::user()->can('manage-payroll'))
                    <li class="nav-item">
                        <a href="#" class="nav-link
                        {{ request()->routeIs('department*')
                            || request()->routeIs('position*')
                            || request()->routeIs('staff*')
                            || request()->routeIs('attendance*')
                            || request()->routeIs('report')
                            || request()->routeIs('payrollIndex')
                            || request()->routeIs('payrollReport')
                            ? 'active' : ''
                        }}">
                            <i class="las la-user-check"></i>
                            <p>
                                Staff Management
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            @if (Auth::user()->can('manage-department'))
                                <li class="nav-item
                                {{ request()->routeIs('department*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('department.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Department</p>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->can('manage-positions'))
                                <li class="nav-item
                                {{ request()->routeIs('position*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('position.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Positions</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-staffs'))
                                <li class="nav-item
                                {{ request()->routeIs('staff*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('staff.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Staffs</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-attendance'))
                                <li class="nav-item
                                {{ request()->routeIs('attendance*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('attendance.create') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Today's Attendance</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-attendance-report'))
                                <li class="nav-item
                                {{ request()->routeIs('report')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('report') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Attendance Report</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-payroll'))
                                <li class="nav-item
                                {{ request()->routeIs('payrollIndex')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('payrollIndex') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Payroll</p>
                                    </a>
                                </li>

                                <li class="nav-item
                                {{ request()->routeIs('payrollReport')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('payrollReport') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Payroll Report</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif --}}

                @if (Auth::user()->can('create-bank-info') || Auth::user()->can('view-bank-info') || Auth::user()->can('edit-bank-info') || Auth::user()->can('delete-bank-info'))
                    <li class="nav-item {{selectedMenu('bank')}}">
                        <a href="#" class="nav-link
                        {{ request()->routeIs('bank*')
                            ? 'active' : ''
                        }}">
                            <i class="las la-university"></i>
                            <p>
                                Bank Information
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            @if (Auth::user()->can('view-bank-info'))
                                <li class="nav-item
                                {{ request()->routeIs('bank.index')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('bank.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>View Bank Info</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('create-bank-info'))
                                <li class="nav-item
                                {{ request()->routeIs('bank.create')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('bank.create') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Create Bank Info</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->can('manage-online-payment'))
                    <li class="nav-item {{selectedMenu('onlinepayment')}}">
                        <a href="#" class="nav-link
                        {{ request()->routeIs('onlinepayment*')
                            ? 'active' : ''
                        }}">
                            <i class="lab la-paypal"></i>
                            <p>
                                Online Payment
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            <li class="nav-item
                            {{ request()->routeIs('onlinepayment.index')
                                ? 'active' : ''
                            }}">
                                <a href="{{ route('onlinepayment.index') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>View Portals</p>
                                </a>
                            </li>

                            <li class="nav-item
                            {{ request()->routeIs('onlinepayment.create')
                                ? 'active' : ''
                            }}">
                                <a href="{{ route('onlinepayment.create') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Create New Portal</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- @if (Auth::user()->can('view-supplier') || Auth::user()->can('create-supplier') || Auth::user()->can('edit-supplier') || Auth::user()->can('remove-supplier'))
                    <li class="nav-item">
                        <a href="#" class="nav-link
                        {{ request()->routeIs('vendors*')
                            ? 'active' : ''
                        }}">
                            <i class="las la-user-nurse"></i>
                            <p>
                                Suppliers
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            @if (Auth::user()->can('view-supplier'))
                                <li class="nav-item
                                    {{ request()->routeIs('vendors.index')
                                        ? 'active' : ''
                                    }}">
                                    <a href="{{ route('vendors.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>View Suppliers</p>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->can('create-supplier'))
                                <li class="nav-item
                                    {{ request()->routeIs('vendors.create')
                                        ? 'active' : ''
                                    }}">
                                    <a href="{{ route('vendors.create') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Add New Supplier</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif --}}

                {{-- @if (Auth::user()->can('view-customer') || Auth::user()->can('create-customer') || Auth::user()->can('edit-customer') || Auth::user()->can('remove-customer'))
                    <li class="nav-item">
                        <a href="#" class="nav-link
                        {{ request()->routeIs('client*')
                            ? 'active' : ''
                        }}">
                            <i class="las la-user-injured"></i>
                            <p>
                                Customer
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            @if (Auth::user()->can('view-customer'))
                                <li class="nav-item
                                    {{ request()->routeIs('client.index')
                                        ? 'active' : ''
                                    }}">
                                    <a href="{{ route('client.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>View Customers</p>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->can('create-customer'))
                                <li class="nav-item
                                    {{ request()->routeIs('client.create')
                                        ? 'active' : ''
                                    }}">
                                    <a href="{{ route('client.create') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Add New Customer</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif --}}

                @if (Auth::user()->can('view-daily-expenses') || Auth::user()->can('create-daily-expenses') || Auth::user()->can('edit-daily-expenses') || Auth::user()->can('remove-daily-expenses'))
                    <li class="nav-item {{selectedMenu('dailyexpenses')}}">
                        <a href="#" class="nav-link
                            {{ request()->routeIs('dailyexpenses*')
                                ? 'active' : ''
                            }}">
                            <i class="las la-id-card"></i>
                            <p>
                                Day Book
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            @if (Auth::user()->can('view-daily-expenses'))
                                <li class="nav-item
                                    {{ request()->routeIs('dailyexpenses.index')
                                        ? 'active' : ''
                                    }}">
                                    <a href="{{ route('dailyexpenses.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>View Expenses</p>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->can('create-daily-expenses'))
                                <li class="nav-item
                                    {{ request()->routeIs('dailyexpenses.create')
                                        ? 'active' : ''
                                    }}">
                                    <a href="{{ route('dailyexpenses.create') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Record Expense</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->can('view-user') || Auth::user()->can('create-user') || Auth::user()->can('edit-user') || Auth::user()->can('remove-user') || Auth::user()->can('view-role') || Auth::user()->can('create-role') || Auth::user()->can('edit-role') || Auth::user()->can('remove-role'))
                    <li class="nav-item {{selectedMenu('user','roles')}}">
                        <a href="" class="nav-link
                            {{ request()->routeIs('user*')
                               || request()->routeIs('roles*')
                                ? 'active' : ''
                            }}">
                            <i class="las la-users-cog"></i>
                            <p>
                                User Management
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 10px;">
                            @if (Auth::user()->can('view-user') || Auth::user()->can('create-user') || Auth::user()->can('edit-user') || Auth::user()->can('remove-user'))
                                <li class="nav-item  {{selectedMenu('user')}}">
                                    <a href="#" class="nav-link
                                    {{ request()->routeIs('user*')
                                        ? 'active' : ''
                                    }}">
                                        <i class="las la-user-friends"></i>
                                        <p>
                                            Users
                                            <i class="las la-angle-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="padding-left: 20px;">
                                        @if (Auth::user()->can('view-user'))
                                            <li class="nav-item
                                                {{ request()->routeIs('user.index')
                                                    ? 'active' : ''
                                                }}">
                                                <a href="{{ route('user.index') }}" class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>View All Users</p>
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->can('create-user'))
                                            <li class="nav-item
                                            {{ request()->routeIs('user.create')
                                                ? 'active' : ''
                                            }}">
                                                <a href="{{ route('user.create') }}" class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>Create New User</p>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif

                            @if (Auth::user()->can('view-role') || Auth::user()->can('create-role') || Auth::user()->can('edit-role') || Auth::user()->can('remove-role'))
                                <li class="nav-item  {{selectedMenu('roles')}}">
                                    <a href="#" class="nav-link
                                    {{ request()->routeIs('roles*')
                                        ? 'active' : ''
                                    }}">
                                        <i class="las la-user-tag"></i>
                                        <p>
                                            Roles
                                            <i class="las la-angle-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="padding-left: 20px;">
                                        @if (Auth::user()->can('view-role'))
                                            <li class="nav-item
                                                {{ request()->routeIs('roles.index')
                                                    ? 'active' : ''
                                                }}">
                                                <a href="{{ route('roles.index') }}" class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>View All Roles</p>
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->can('create-role'))
                                            <li class="nav-item
                                                {{ request()->routeIs('roles.create')
                                                    ? 'active' : ''
                                                }}">
                                                <a href="{{ route('roles.create') }}" class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>Create New Role</p>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->can('manage-trial-balance') || Auth::user()->can('manage-profit-and-loss') || Auth::user()->can('manage-balance-sheet') || Auth::user()->can('manage-ledgers') || Auth::user()->can('manage-reconciliation-statement') || Auth::user()->can('manage-sales-register') || Auth::user()->can('manage-sales-return-register') || Auth::user()->can('manage-purchase-register') || Auth::user()->can('manage-purchase-return-register') || Auth::user()->can('manage-vat-refund') || Auth::user()->can('manage-tax-information'))
                    <li class="nav-item {{selectedMenu('supplierLedgers','customersLedgers','bankLedgers','trialbalance','profitandloss','balancesheet')}}{{selectedMenu('ledgers','bankReconciliationStatement','salesRegister','salesReturnRegister','purchaseRegister','purchaseReturnRegister')}}{{selectMenuwithroute(url('/billings/vatrefund'),url('/taxinfo'))}}">
                        <a href="#" class="nav-link
                            {{ request()->routeIs('journals.trialbalance')
                                || request()->routeIs('journals.profitandloss')
                                || request()->routeIs('journals.balancesheet')
                                || request()->routeIs('ledgers')
                                || request()->routeIs('generateledgers')
                                || request()->routeIs('bankReconciliationStatement')
                                || request()->routeIs('salesRegister')
                                || request()->routeIs('salesReturnRegister')
                                || request()->routeIs('purchaseRegister')
                                || request()->routeIs('purchaseReturnRegister')
                                || request()->routeIs('billing.vatrefund')
                                || request()->routeIs('taxinfo*')
                                ? 'active' : ''
                            }}">
                            <i class="las la-file-medical-alt"></i>
                            <p>
                                Reports
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            <li class="nav-item {{selectedMenu('supplierLedgers','customersLedgers','bankLedgers')}}">
                                <a href="#" class="nav-link
                                            {{ request()->routeIs('journals.trialbalance')
                                                || request()->routeIs('journals.profitandloss')
                                                || request()->routeIs('journals.balancesheet')
                                                || request()->routeIs('ledgers')
                                                || request()->routeIs('generateledgers')
                                                || request()->routeIs('bankReconciliationStatement')
                                                ? 'active' : ''
                                            }}">
                                    <i class="las la-atlas"></i>
                                    <p>
                                        Ledger Reports
                                        <i class="las la-angle-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview" style="padding-left: 20px;">
                                    @if (Auth::user()->can('manage-trial-balance'))
                                        <li class="nav-item
                                            {{ request()->routeIs('supplierLedgers')
                                                ? 'active' : ''
                                            }}">
                                            <a href="{{ route('supplierLedgers') }}"
                                                class="nav-link">
                                                <i class="las la-circle-notch"></i>
                                                <p>Suppliers Ledgers</p>
                                            </a>
                                        </li>
                                    @endif

                                    @if (Auth::user()->can('manage-trial-balance'))
                                        <li class="nav-item
                                            {{ request()->routeIs('customersLedgers')
                                                ? 'active' : ''
                                            }}">
                                            <a href="{{ route('customersLedgers') }}"
                                                class="nav-link">
                                                <i class="las la-circle-notch"></i>
                                                <p>Customers Ledgers</p>
                                            </a>
                                        </li>
                                    @endif

                                    @if (Auth::user()->can('manage-trial-balance'))
                                        <li class="nav-item
                                            {{ request()->routeIs('bankLedgers')
                                                ? 'active' : ''
                                            }}">
                                            <a href="{{ route('bankLedgers') }}"
                                                class="nav-link">
                                                <i class="las la-circle-notch"></i>
                                                <p>Bank Ledgers</p>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                            {{-- @if (Auth::user()->can('manage-trial-balance') || Auth::user()->can('manage-profit-and-loss') || Auth::user()->can('manage-balance-sheet') || Auth::user()->can('manage-ledgers') || Auth::user()->can('manage-reconciliation-statement'))
                                <li class="nav-item {{selectedMenu('trialbalance','profitandloss','balancesheet','ledgers','bankReconciliationStatement')}}">
                                    <a href="#" class="nav-link
                                                {{ request()->routeIs('journals.trialbalance')
                                                    || request()->routeIs('journals.profitandloss')
                                                    || request()->routeIs('journals.balancesheet')
                                                    || request()->routeIs('ledgers')
                                                    || request()->routeIs('generateledgers')
                                                    || request()->routeIs('bankReconciliationStatement')
                                                    ? 'active' : ''
                                                }}">
                                        <i class="las la-book"></i>
                                        <p>
                                            Accounting Reports
                                            <i class="las la-angle-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="padding-left: 20px;">
                                        @if (Auth::user()->can('manage-trial-balance'))
                                            <li class="nav-item
                                                {{ request()->routeIs('journals.trialbalance')
                                                    ? 'active' : ''
                                                }}">
                                                <a href="{{ route('journals.trialbalance') }}"
                                                    class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>Trial Balance</p>
                                                </a>
                                            </li>
                                        @endif

                                        @if (Auth::user()->can('manage-profit-and-loss'))
                                            <li class="nav-item
                                            {{ request()->routeIs('journals.profitandloss')
                                                ? 'active' : ''
                                            }}">
                                                <a href="{{ route('journals.profitandloss') }}"
                                                    class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>Profit and Loss A/C</p>
                                                </a>
                                            </li>
                                        @endif

                                        @if (Auth::user()->can('manage-balance-sheet'))
                                            <li class="nav-item
                                            {{ request()->routeIs('journals.balancesheet')
                                                ? 'active' : ''
                                            }}">
                                                <a href="{{ route('journals.balancesheet') }}"
                                                    class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>Balance Sheet</p>
                                                </a>
                                            </li>
                                        @endif

                                        @if (Auth::user()->can('manage-ledgers'))
                                            <li class="nav-item
                                            {{ request()->routeIs('ledgers')
                                                || request()->routeIs('generateledgers')
                                                ? 'active' : ''
                                            }}">
                                                <a href="{{ route('ledgers') }}" class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>Accounting Ledgers</p>
                                                </a>
                                            </li>
                                        @endif

                                        @if (Auth::user()->can('manage-reconciliation-statement'))
                                            <li class="nav-item">
                                                <a href="{{ route('bankReconciliationStatement.index') }}"
                                                    class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>Reconciliation Statement</p>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif --}}

                            @if (Auth::user()->can('manage-sales-register') || Auth::user()->can('manage-sales-return-register') || Auth::user()->can('manage-purchase-register') || Auth::user()->can('manage-purchase-return-register'))
                                <li class="nav-item {{selectedMenu('salesRegister','salesReturnRegister','purchaseRegister','purchaseReturnRegister')}}">
                                    <a href="#" class="nav-link
                                    {{ request()->routeIs('salesRegister')
                                        || request()->routeIs('salesReturnRegister')
                                        || request()->routeIs('purchaseRegister')
                                        || request()->routeIs('purchaseReturnRegister')
                                        ? 'active' : ''
                                    }}">
                                        <i class="las la-cash-register"></i>
                                        <p>
                                            Registers
                                            <i class="las la-angle-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="padding-left: 20px;">

                                        @if (Auth::user()->can('manage-sales-register'))
                                            <li class="nav-item
                                            {{ request()->routeIs('salesRegister')
                                                ? 'active' : ''
                                            }}">
                                                <a href="{{ route('salesRegister') }}" class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>Sales Register</p>
                                                </a>
                                            </li>
                                        @endif

                                        @if (Auth::user()->can('manage-sales-return-register'))
                                            <li class="nav-item
                                                {{ request()->routeIs('salesReturnRegister')
                                                    ? 'active' : ''
                                                }}">
                                                <a href="{{ route('salesReturnRegister') }}" class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>Sales Return Register</p>
                                                </a>
                                            </li>
                                        @endif

                                        @if (Auth::user()->can('manage-purchase-register'))
                                            <li class="nav-item
                                                {{ request()->routeIs('purchaseRegister')
                                                    ? 'active' : ''
                                                }}">
                                                <a href="{{ route('purchaseRegister') }}" class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>Purchase Register</p>
                                                </a>
                                            </li>
                                        @endif

                                        @if (Auth::user()->can('manage-purchase-return-register'))
                                            <li class="nav-item
                                                {{ request()->routeIs('purchaseReturnRegister')
                                                    ? 'active' : ''
                                                }}">
                                                <a href="{{ route('purchaseReturnRegister') }}"
                                                    class="nav-link">
                                                    <i class="las la-circle-notch"></i>
                                                    <p>Purchase Return Register</p>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-vat-refund'))
                                <li class="nav-item
                                {{ request()->routeIs('billing.vatrefund')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('billing.vatrefund') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>VAT Refund Report</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-tax-information'))
                                <li class="nav-item
                                {{ request()->routeIs('taxinfo*')
                                    ? 'active' : ''
                                }}">
                                    <a href="{{ route('taxinfo.index') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>TAX Information</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- @if (Auth::user()->can('budget-setup') || Auth::user()->can('manage-budget-report'))
                    <li class="nav-item {{selectedMenu('budgetsetup','budgetinfo')}}">
                        <a href="#" class="nav-link
                        {{ request()->routeIs('budgetsetup')
                            || request()->routeIs('budgetinfo')
                            ? 'active' : ''
                        }}">
                            <i class="las la-coins"></i>
                            <p>
                                Budget & Expenditure
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            @if (Auth::user()->can('budget-setup'))
                                <li class="nav-item
                                    {{ request()->routeIs('budgetsetup')
                                        ? 'active' : ''
                                    }}">
                                    <a href="{{ route('budgetsetup') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Budget Setup</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('manage-budget-report'))
                                <li class="nav-item
                                    {{ request()->routeIs('budgetinfo')
                                        ? 'active' : ''
                                    }}">
                                    <a href="{{ route('budgetinfo') }}" class="nav-link">
                                        <i class="las la-circle-notch"></i>
                                        <p>Budget Report</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif --}}
                @if (Auth::user()->can('lab-view') || Auth::user()->can('appointments-view') ||
                    Auth::user()->can('patient-view') ||
                    Auth::user()->can('designation-view')

                    )
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="las la-hospital-alt"></i>
                        <p>
                            Management
                            <i class="las la-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="padding-left: 20px;">
                        @if (Auth::user()->can('lab-view'))
                            <li class="nav-item">
                                <a href="{{ route('lab.index') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Lab</p>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->can('patient-view'))
                            <li class="nav-item">
                                <a href="{{ route('patients.index') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Patients</p>
                                </a>
                            </li>
                        @endif

                        {{-- @if (Auth::user()->can('medical-history-view'))
                        <li class="nav-item">
                            <a href="{{ route('medical-history.create',['patient'=>'patient']) }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Medical History</p>
                            </a>
                        </li>
                        @endif --}}
                        @if (Auth::user()->can('designation-view'))
                        <li class="nav-item">
                            <a href="{{ route('hospital-designation.index') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Designation</p>
                            </a>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a href="{{ route('hospital-staff.index') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Hospital Staff</p>
                            </a>
                        </li>


                        @if (Auth::user()->can('appointments-view'))
                        <li class="nav-item">
                            <a href="{{ route('allAppointments') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Appointment</p>
                            </a>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a href="{{ route('test-type.index') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Test Type</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (Auth::user()->can('manage-trash'))
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="las la-trash-alt"></i>
                            <p>
                                Trash
                                <i class="las la-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="padding-left: 20px;">
                            <li class="nav-item">
                                <a href="{{ route('deletedusers') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>User and Roles</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('deletedindex') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Accounts</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('deletedGodownInfo') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Godown Info</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('deletedbrand') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Brand</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('deletedseries') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Series</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('deletedcategory') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Categories</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('deletedproduct') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Products</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('deletedservice') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Services</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('deletedDamagedProducts') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Damaged Products</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('deletedvendor') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Suppliers</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('deletedexpenses') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Daily Expenses</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('deletedPersonalShares') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Personal Shares</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('deletedcompanyShares') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Company Shares</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('deletedBankReconciliation') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Bank Reconciliation</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('deletedBankInfo') }}" class="nav-link">
                                    <i class="las la-circle-notch"></i>
                                    <p>Bank Info</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                {{-- @include('lab.includes._lab') --}}
            </ul>

        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<aside class="sub-sidebar">
    <div class="sub-sidebar-title">
        <h3>Shortcut Keys</h3>
        <span class="sub-toggle-btn"><i class="las la-times"></i></span>
    </div>
    <ul>
        <li><b>?</b> help</li>
        <li><b>/</b> Search</li>
        <li><b>Enter</b> Focus Input</li>
        <li><b>Ctrl + ↑</b> Highlight Menu</li>
        <li><b>Ctrl + ↓</b> Highlight Menu</li>
        <li><b>Ctrl + ←</b> Highlight Menu</li>
        <li><b>Ctrl + →</b> Highlight Menu</li>
        <li><b>Ctrl+Alt + ←</b> Previous Page</li>
        <li><b>Ctrl+Alt + →</b> Current Page</li>
        <li><b>Ctrl+Alt+J</b> Journal Entry</li>
        <li><b>Ctrl+Alt+P</b> Purchase</li>
        <li><b>Ctrl+Alt+T</b> Trial Balance</li>
        <li class="active"><b>Ctrl+Alt+H</b> Home</li>
        <li><b>Ctrl+Alt+Q</b> Quotation</li>
        <li><b>Ctrl+Alt+S</b> Sales</li>
        <li><b>Ctrl+Alt+D</b> Service Sales </li>
        <li><b>Ctrl+Alt+B</b> Balance Sheet</li>
        <li><b>Ctrl+Alt+L</b> P/L A/C</li>
        <li><b>Ctrl+Alt+S</b> Submit</li>
        <li><b>Ctrl+Shift+Alt+P</b> Product</li>
    </ul>
</aside>
@push('scripts')
<script>
    $('.sub-toggle-btn').click(function(){
        $('.sub-sidebar, .content-wrapper, .main-header, .main-footer, .content-head').toggleClass('hides');
        $('.mobs-toogs').toggleClass('shows');
    });
</script>
@endpush
