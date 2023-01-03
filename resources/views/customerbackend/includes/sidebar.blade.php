<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    @php
        $user = Auth::user()->id;
        $usercompanies = \App\Models\DealerUserCompany::withoutGlobalScope(Multicompany::class)->where('dealer_user_id', $user)->get();
        $currentcomp = \App\Models\DealerUserCompany::withoutGlobalScope(Multicompany::class)->with('dealeruser')->where('dealer_user_id', $user)
            ->where('is_selected', 1)
            ->first();
        $client_id = $currentcomp->dealeruser->client_id;
        $client = DB::table('clients')->where('id', $client_id)->first();
        // dd($client);
        $setting = \App\Models\Setting::first();
    @endphp
    <div class="user-drpp">
        <a href="{{ route('customer.home') }}" class="brand-link">
            <img src="{{ asset('logo/logo1.png') }}" alt="{{ $setting->company_name }}" class="brand-image">
            <span class="brand-text font-weight-bold" style="color:#fff;">&nbsp;</span>
        </a>
        <a class="nav-link tog-btn mob-only" data-widget="pushmenu" href="#" role="button"><i
                class="las la-bars"></i></a>
        {{-- <div class="customdropdown text-center">
            <ul>
                @foreach ($usercompanies as $usercompany)
                    <li><a
                            href="{{ route('switch.selectedcustomer', $usercompany->id) }}">{{ $usercompany->company->name }}{{ $usercompany->branch->is_headoffice == 0 ? ' (' . $usercompany->branch->name . ')' : ' (Head Office)' }}</a>
                    </li>
                @endforeach
            </ul>
        </div> --}}
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel d-flex">
            <div class="image">
                <img src="{{ Storage::disk('uploads')->url($client->logo) }}"
                    class="img-circle elevation-2" alt="{{ $client->name }}">
            </div>
            <div class="info">
                <a href="javascript:void(0)"
                    class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('purchaseOrder*') ? 'active' : '' }}">
                        <i class="las la-shopping-cart"></i>
                        <p>
                            Purchase Orders
                            <i class="las la-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="padding-left: 15px;">
                        <li class="nav-item {{ request()->routeIs('purchaseOrder.customerindex') ? 'active' : '' }}">
                            <a href="{{ route('purchaseOrder.customerindex') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>All Purchase Orders</p>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('purchaseOrder.customercreate') ? 'active' : '' }}">
                            <a href="{{ route('purchaseOrder.customercreate') }}" class="nav-link">
                                <i class="las la-circle-notch"></i>
                                <p>Create Purchase Order</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{route('client.purchases')}}" class="nav-link {{request()->routeIs('client.purchases')?'active' : ''}}">
                        <i class="las la-shopping-basket"></i>
                        <p> My Purchases</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('client.purchasereturns')}}" class="nav-link {{request()->routeIs('client.purchasereturns')?'active' : ''}}">
                        <i class="las la-cart-arrow-down"></i>
                        <p> My Purchase Returns</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('client.quotations')}}" class="nav-link {{request()->routeIs('client.quotations')?'active' : ''}}">
                        <i class="las la-money-bill-wave"></i>
                        <p>Quotations</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
