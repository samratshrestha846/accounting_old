<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    @php
    //Low Stocks
        $lowstockproductnotifications = \App\Models\ProductNotification::latest()
            ->where('status', 0)
            ->where('noti_type', 'low_stock')
            ->take(4)
            ->get();
        $lowstockproductnotificationscount = count($lowstockproductnotifications);
        $lowstockunreadproductnotifications = \App\Models\ProductNotification::latest()
            ->where('status', 0)
            ->where('noti_type', 'low_stock')
            ->count();
        //Out Of Stocks
        $stockproductnotifications = \App\Models\ProductNotification::latest()
            ->where('status', 0)
            ->where('noti_type', 'stock')
            ->take(4)
            ->get();
        $stockproductnotificationscount = count($stockproductnotifications);
        $stockunreadproductnotifications = \App\Models\ProductNotification::latest()
            ->where('status', 0)
            ->where('noti_type', 'stock')
            ->count();
        //Stocks About to Expire
        $expiryproductnotifications = \App\Models\ProductNotification::latest()
            ->where('status', 0)
            ->where('noti_type', 'expiry')
            ->take(4)
            ->get();
        $expiryproductnotificationscount = count($expiryproductnotifications);
        $expiryunreadproductnotifications = \App\Models\ProductNotification::latest()
            ->where('status', 0)
            ->where('noti_type', 'expiry')
            ->count();
        //Stocks Expired
        $expiredproductnotifications = \App\Models\ProductNotification::latest()
            ->where('status', 0)
            ->where('noti_type', 'expired')
            ->take(4)
            ->get();
        $expiredproductnotificationscount = count($expiredproductnotifications);
        $expiredunreadproductnotifications = \App\Models\ProductNotification::latest()
            ->where('status', 0)
            ->where('noti_type', 'expired')
            ->count();
        $user = Auth::user()->id;
        $usercompanies = \App\Models\UserCompany::where('user_id', $user)->get();
        $currentcomp = \App\Models\UserCompany::where('user_id', $user)
            ->where('is_selected', 1)
            ->first();

        $billingcreditNotification = App\Models\BillingCredit::with('vendor','client')->with(['billing'=>function($query){
            $query->select('id','reference_no','created_at');
        }])->select('billing_id','credit_amount','vendor_id','client_id','billing_type_id')->where('notified',0)->where('due_date_eng',date('Y-m-d'))->where('is_sale_service',null)->get();

        $billingcreditSalesNotification = App\Models\BillingCredit::with('client')->with(['sale_bill'=>function($query){
            $query->select('id','reference_no','created_at');
        }])->select('billing_id','credit_amount','client_id')->where('notified',0)->where('due_date_eng',date('Y-m-d'))->where('is_sale_service',1)->get();
    @endphp

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link tog-btn" data-widget="pushmenu" href="#" role="button"><i class="las la-bars"></i></a>
        </li>

        {{-- <li class="text-center" style="margin-left:50px;">
            <span
                style="font-size:18px;color:#5e5e5e;">{{ $currentcomp->company->name }}{{ $currentcomp->branch->is_headoffice == 1 ? ' (Head Office)' : ' (' . $currentcomp->branch->name . ')' }}</span>
        </li> --}}
        <li class="nav-item dropdown c-name">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false" v-pre>
                    <i class="las la-building m-only-icon"></i>
                    <span class="c-names">
                        {{ $currentcomp->company->name }}{{ $currentcomp->branch->is_headoffice == 1 ? ' (Head Office)' : ' (' . $currentcomp->branch->name . ')' }}
                    </span>
                </a>

                <div class="dropdown-menu dropdown-menu-right company-dropdown" aria-labelledby="navbarDropdown">
                    @foreach ($usercompanies as $usercompany)
                        <a class="dropdown-item" href="{{ route('switch.selected', $usercompany->id) }}">
                            <i class="las la-building"></i> {{ $usercompany->company->name }}{{ $usercompany->branch->is_headoffice == 0 ? ' (' . $usercompany->branch->name . ')' : ' (Head Office)' }}
                        </a>
                    @endforeach

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
        </li>

        <li class="text-center pos-head">
            <a href="{{ route('pos.index') }}" class="btn btn-primary">
               <span class="uk-margin-small-right" data-uk-icon="icon: plus"></span>
               POS
            </a>
        </li>

        {{-- <li class="text-center" style="margin-left:100px;">
            <a href="https://wa.me/9779861819014?text=Namaste!!%20Can%20I%20have%20your%20more%20information??" class="btn" style="color: white; background-color: #01e675" target="_blank">
               <span class="uk-margin-small-right" data-uk-icon="icon: plus"></span>
                <i class="fab fa-whatsapp fa-2x"></i>
            </a>
        </li> --}}
    </ul>
    <div class="global-search">
        <form action="">
            <input type="text" name="global_search" class="form-control search" placeholder="Search here....." required>
            <button type="submit" class="searchbutton"><i class="las la-search"></i></button>
        </form>
        <div class="name_list_dropdown" style="display:none;">

        </div>

    </div>



    <!-- Right navbar links -->
    <ul class="navbar-nav right-icons">

        <!-- Notifications Dropdown Menu -->
        {{-- Low Stocks --}}
        <li class="nav-item dropdown notification-icons">
            <a class="nav-link" data-toggle="dropdown" href="#" title="Follow Up Alerts">
                {{-- <i class="far fa-bell mr-2 mt-2" style="font-size: 20px"></i> --}}
                <i class="las la-rss"></i>
               @if($followupscount > 0) <span class="badge badge-warning navbar-badge">{{ $followupscount }}</span> @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="width: 300px;">
                <span class="dropdown-item dropdown-header">{{ $followupscount }} Follow Up Alerts</span>
                @if ($followupscount < 1)
                    <i style="padding: 0 15px">No Follow Up Alerts</i>
                @else
                    @foreach ($followups as $followup)
                        <a href="{{ route('billings.show', $followup->billing_id) }}" class="dropdown-item">
                            <p>{{$followup->followup_title}} at {{$followup->followup_date}}
                            </p>
                        </a>
                        <div class="dropdown-divider"></div>
                    @endforeach
                @endif
            </div>
        </li>
        <li class="nav-item dropdown notification-icons">
        {{-- //notification billingcredit --}}

        <li class="nav-item dropdown notification-icons">
            <a class="nav-link" data-toggle="dropdown" href="#" title="Billing Credit Notification">

                <i class="las la-wallet"></i>
                @if($billingcreditNotification->count() + $billingcreditSalesNotification->count() > 0)<span class="badge badge-warning navbar-badge">{{ $billingcreditNotification->count() + $billingcreditSalesNotification->count() }}</span> @endif
            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="width: 300px;">
                <span class="dropdown-item dropdown-header">{{ $billingcreditNotification->count() + $billingcreditSalesNotification->count() }} credits due date for today</span>
                @if ($billingcreditNotification->count() + $billingcreditSalesNotification->count() < 1)
                    <i style="padding: 0 15px">No Billing credit </i>
                @else
                @if(count($billingcreditNotification) > 0)  <span class="dropdown-item dropdown-header"> Product Credits</span> @endif


                    @foreach($billingcreditNotification as $item)
                    @php
                        if(!isset($item->vendor->company_name) || !isset($item->client->name)){
                            continue;
                        }
                        $billlingcrediturl = route('billing.billingcredits',['billing_type_id='.$item->billing_type_id]);
                    @endphp

                        <a href="{{ $billlingcrediturl }}" class="dropdown-item">
                            <p>{{ $item->billing->reference_no ?? 'Not Mentioned' }} is credited by {{$item->credit_amount}}
                                ({{$item->vendor->company_name ?? $item->client->name}})

                            </p>

                        </a>


                        <div class="dropdown-divider"></div>
                    @endforeach
                    @if(count($billingcreditSalesNotification) > 0) <span class="dropdown-item dropdown-header"> Service Credits</span> @endif

                    @foreach($billingcreditSalesNotification as $item)

                        <a href="#" class="dropdown-item">
                            <p>{{ $item->sale_bill->reference_no ?? '' }} is credited by {{$item->credit_amount}}
                                ({{$item->client->name ?? 'Not Mentioned'}})
                            </p>
                        </a>
                        <div class="dropdown-divider"></div>

                    @endforeach
                @endif

            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" title="No Unread Notification">
                {{-- <i class="far fa-bell mr-2 mt-2" style="font-size: 20px"></i> --}}
                <i class="las la-layer-group"></i>
                @if($lowstockunreadproductnotifications > 0)<span class="badge badge-warning navbar-badge">{{ $lowstockunreadproductnotifications }}</span> @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="width: 300px;">
                <span class="dropdown-item dropdown-header">{{ $lowstockunreadproductnotifications }} Unread
                    Notifications</span>
                @if ($lowstockproductnotificationscount < 1)
                    <i style="padding: 0 15px">Low Stock Products</i>
                @else
                    @foreach ($lowstockproductnotifications as $item)
                        <a href="{{ route('productnotification.show', $item->id) }}" class="dropdown-item">
                            <p>{{ $item->product->product_name . '('.$item->product->product_code.')' }} is low in stock on
                                {{ $item->godown->godown_name }}
                            </p>
                            <span
                                class="float-right text-muted text-sm">{{ $item->created_at->diffforHumans() }}</span>
                        </a>
                        <div class="dropdown-divider"></div>
                    @endforeach
                @endif
                <a href="{{ route('productnoti.type', 'low_stock') }}" class="dropdown-item dropdown-footer">Low Stock Products</a>
            </div>
        </li>
        {{-- Out of Stocks --}}
        <li class="nav-item dropdown notification-icons">
            <a class="nav-link" data-toggle="dropdown" href="#" title="Out of Stock Products">
                {{-- <i class="far fa-bell mr-2 mt-2" style="font-size: 20px"></i> --}}
                <i class="las la-bell"></i>
                @if($stockunreadproductnotifications > 0)<span class="badge badge-warning navbar-badge">{{ $stockunreadproductnotifications }}</span>@endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="width: 300px;">
                <span class="dropdown-item dropdown-header">{{ $stockunreadproductnotifications }} Unread
                    Notifications</span>
                @if ($stockproductnotificationscount < 1)
                    <i style="padding: 0 15px">No Unread Notification</i>
                @else
                    @foreach ($stockproductnotifications as $item)
                        <a href="{{ route('productnotification.show', $item->id) }}" class="dropdown-item">
                            <p>{{ $item->product->product_name . '('.$item->product->product_code.')' }} is out in stock on
                                {{ $item->godown->godown_name }}
                            </p>
                            <span
                                class="float-right text-muted text-sm">{{ $item->created_at->diffforHumans() }}</span>
                        </a>
                    @endforeach
                @endif
                <div class="dropdown-divider"></div>
                <a href="{{ route('productnoti.type', 'stock') }}" class="dropdown-item dropdown-footer">Out of Stock Products</a>
            </div>
        </li>
        {{-- Expiry Product --}}
        <li class="nav-item dropdown notification-icons">
            <a class="nav-link" data-toggle="dropdown" href="#" title="Expiring Products">
                {{-- <i class="far fa-bell mr-2 mt-2" style="font-size: 20px"></i> --}}
                <i class="las la-sync-alt"></i>
                @if($expiryunreadproductnotifications > 0)<span class="badge badge-warning navbar-badge">{{ $expiryunreadproductnotifications }}</span>@endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="width: 300px;">
                <span class="dropdown-item dropdown-header">{{ $expiryunreadproductnotifications }} Unread
                    Notifications</span>
                @if ($expiryproductnotificationscount < 1)
                    <i style="padding: 0 15px">No Unread Notification</i>
                @else
                    @foreach ($expiryproductnotifications as $item)
                        <a href="{{ route('productnotification.show', $item->id) }}" class="dropdown-item">
                            <p>{{ $item->product->product_name . '('.$item->product->product_code.')' }} is about to expire in
                                {{ $item->godown->godown_name }}
                            </p>
                            <span
                                class="float-right text-muted text-sm">{{ $item->created_at->diffforHumans() }}</span>
                        </a>
                    @endforeach
                @endif
                <div class="dropdown-divider"></div>
                <a href="{{ route('productnoti.type', 'expiry') }}" class="dropdown-item dropdown-footer">Expiring Products</a>
            </div>
        </li>
        {{-- Expired Product --}}
        <li class="nav-item dropdown notification-icons">
            <a class="nav-link" data-toggle="dropdown" href="#" title="Expired Products">
                {{-- <i class="far fa-bell mr-2 mt-2" style="font-size: 20px"></i> --}}
                <i class="las la-hourglass-start"></i>
                @if($expiredunreadproductnotifications > 0)<span class="badge badge-warning navbar-badge">{{ $expiredunreadproductnotifications }}</span>@endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="width: 300px;">
                <span class="dropdown-item dropdown-header">{{ $expiredunreadproductnotifications }} Unread
                    Notifications</span>
                @if ($expiredproductnotificationscount < 1)
                    <i style="padding: 0 15px">No Unread Notification</i>
                @else
                    @foreach ($expiredproductnotifications as $item)
                        <a href="{{ route('productnotification.show', $item->id) }}" class="dropdown-item">
                            <p>{{ $item->product->product_name . '('.$item->product->product_code.')' }} is expired in
                                {{ $item->godown->godown_name }}
                            </p>
                            <span
                                class="float-right text-muted text-sm">{{ $item->created_at->diffforHumans() }}</span>
                        </a>
                    @endforeach
                @endif
                <div class="dropdown-divider"></div>
                <a href="{{ route('productnoti.type', 'expired') }}" class="dropdown-item dropdown-footer">Expired Products</a>
            </div>
        </li>

        <li class="nav-item dropdown c-name c-name-login">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" v-pre>
                <div class="image pf-img">
                    <img src="{{ Storage::disk('uploads')->url($currentcomp->company->company_logo) }}"
                        class="img-circle elevation-2" alt="{{ Auth::user()->name }}">
                </div>
                <span class="c-names">{{ Auth::user()->name }}</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('user.edit', Auth::user()->id) }}">
                    {{ __('Profile') }}
                </a>

                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
        <span class="sub-toggle-btn mobs-toogs" title="Shortcut Keys"><i class="las la-angle-right"></i></span>
        <div class="toggle-btn">
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
        </div>
    </ul>
</nav>
{{-- <div class="searchdatas">
</div> --}}
{{-- @php
dd($billingcreditSalesNotification);
@endphp --}}
@push('scripts')
<script>
    $('input[name="global_search"]').keyup(function(){
        var searchkey = $(this).val();

        if(searchkey.length == 0){
            $('.name_list_dropdown').hide();
        }else{
            $('.searchbutton').html('<i class="las la-spinner la-spin"></i>');
              $('.name_list_dropdown').show();
            $.get('{{route('globalSearch')}}',{searchkey:searchkey},function(response){

                $('.name_list_dropdown').html(response);
                $('.searchbutton').html('<i class="las la-search"></i>');
            })

        }
    })
    $('.content-wrapper').on('click',function(){

        $('.name_list_dropdown').hide();
    })
    $('input[name="global_search"]').click(function(){
        $('input[name="global_search"]').trigger('keyup');
    })

    </script>
@endpush
