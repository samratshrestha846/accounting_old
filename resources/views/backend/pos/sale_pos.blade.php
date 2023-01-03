<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{ mix('css/pos.css') }}">
        <link rel="stylesheet" href="{{ asset('css/demo.pos.css') }}">
        <link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="{{asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('backend/dist/css/adminlte.min.css')}}">
        <!-- keyboard -->
        <link href="{{ asset('css/keyboard.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/metisMenu.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('css/line-awesome.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/animate.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/dist/css/main.css') }}" rel="stylesheet">

        <style>
            .brand-logo{
                max-height: 40px;
                padding: 0 16px;
            }
        </style>

        <!-- Scripts -->
        {{-- <script src="{{asset('js/custom.js')}}"></script> --}}
        <script>
            document.addEventListener("keypress", function(e) {
                // console.log("i am here");
                // if (e.target.tagName !== "INPUT") {
                //     var input = document.querySelector(".my-input");
                //     input.focus();
                //     input.value = e.key;
                //     e.preventDefault();
                // }
            });
        </script>
    </head>
    @php
        $productAlertCount = auth()->user()->outletAlertCount($outlet->id);
        $outlets = auth()->user()->getOutlets();
    @endphp

    <body class="font-sans antialiased">
        <div id="app">
            <!-- Header -->
            <header id="header" class="header">
                <div class="container-fluid">
                    <div class="h-wrap">
                        <div class="logo">
                            <a href="/"><img src="/img/logo.png" alt="images"></a>
                            <div class="time desks-only">
                                @php
                                $weekMap = [
                                    0 => 'Sunday',
                                    1 => 'Monday',
                                    2 => 'Tesday',
                                    3 => 'Wednesday',
                                    4 => 'Thrusday',
                                    5 => 'Friday',
                                    6 => 'Saturday',
                                ];
                                $weekday = $weekMap[\Carbon\Carbon::now()->dayOfWeek];
                                @endphp
                                <span class="pos-date">{{$weekday.', '.now()->format('d').' '.now()->format('M').' '.now()->format('Y').', '.now()->format('g:i A')}}</span>
                            </div>
                        </div>
                        <div class="header-right desks-only">
                            <div class="navigation">

                                <nav class="navbar navbar-expand-md">
                                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                                        <ul class="navbar-nav">
                                            {{-- <li class="nav-item active">
                                                <a class="nav-link" href="/">Home</a>
                                            </li> --}}
                                            <li class="nav-item dropdown">
                                                <a href="#" class="nav-link dropdown-toggle">Oulets</a>
                                                <userdropdownoutetlist
                                                    :selected_outlet="{{json_encode($outlet)}}"
                                                    :outlets = "{{json_encode($outlets)}}"
                                                >
                                                </userdropdownoutetlist>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{route('posSettings.index')}}">POS Settings</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Sales
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                    <li><a class="dropdown-item" href="{{route('pos.sales')}}">Sales</a></li>
                                                    <li><a class="dropdown-item" href="/suspendedsale">Suspended Sales</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                            <div class="header-btns">
                                <div class="btn-bulk">
                                    <a href="javascript:void(0)" class="btn btn-secondary btn-info" @click="$refs.todaySaleReportModal.openModal()"><i class="las la-chart-line"></i> Today's Sales</a>
                                    <a href="{{route('outlettransfer.index').'?stock=low'}}" class="btn btn-primary" target="_blank"><i class="las la-bell"></i> {{$productAlertCount}} Product Alerts</a>
                                </div>
                                <div class="accounts desks-only">
                                    <nav class="navbar navbar-expand-md p-0">
                                        <div class="collapse navbar-collapse" id="navbarNavDropdown">
                                            <ul class="navbar-nav">
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Hi, {{auth()->user()->name}}
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                        <!-- <li><a class="dropdown-item" href="#">Change Passeord</a></li> -->
                                                        <li>
                                                            <form id="logoutform" method="POST" action="{{route('logout')}}">
                                                            @csrf
                                                                <a
                                                                    type="submit"
                                                                    class="dropdown-item"
                                                                    href="javascript:void(0)"
                                                                    onclick="$('#logoutform').trigger('submit');"
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
                        <div class="hr-mob">
                            <div class="accounts mobs-only">
                                <nav class="navbar navbar-expand-md p-0">
                                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                                        <ul class="navbar-nav">
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="las la-user-check"></i>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                    <!-- <li><a class="dropdown-item" href="#">Change Passeord</a></li> -->
                                                    <li>
                                                        <form id="mobilelogoutForm" method="POST" action="{{route('logout')}}">
                                                            @csrf
                                                            <a
                                                                type="submit"
                                                                class="dropdown-item"
                                                                href="javascript:void(0)"
                                                                onclick="$('#mobilelogoutForm').trigger('submit');"
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
                            <div class="toggles-btn">
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
                        </div>
                    </div>
                </div>
            </header>
            <!-- Header End -->

            <!-- Mobile Menu -->
            <div id="mySidenav1" class="sidenav1">
                <div class="mobile-logo">
                    <a href="index.html"><img src="/img/logo1.png" alt="logo"></a>
                    <a href="javascript:void(0)" id="close-btn1" class="closebtn">&times;</a>
                </div>
                <div class="no-bdr1">
                    <div class="time mobs-only">
                        <span class="pos-date">{{ date('F j, Y h:i:s a') }}</span>
                    </div>
                    <div class="header-right mobile-only">
                        <div class="header-btns">
                            <div class="main-btns">
                                <a href="javascript:void(0)" @click="$refs.todaySaleReportModal.openModal()"><i class="las la-chart-line"></i> Today's Sales</a>
                                <a href="{{route('outlettransfer.index').'?stock=low'}}" target="_blank"><i class="las la-bell"></i> {{$productAlertCount}} Product Alerts</a>
                            </div>
                        </div>
                    </div>
                    <ul id="menu2">
                        <li>
                            <a href="#" class="has-arrow">Oulets</a>
                                <userdropdownoutetlist
                                    :ismobile="true"
                                    :selected_outlet="{{json_encode($outlet)}}"
                                    :outlets = "{{json_encode($outlets)}}"
                                >
                                </userdropdownoutetlist>
                        </li>
                        <li><a href="{{route('posSettings.index')}}">POS Settings</a></li>
                        <li>
                            <a href="#" class="has-arrow">Sales</a>
                            <ul>
                                <li>
                                    <a href="{{route('pos.sales')}}">Sales</a>
                                </li>
                                <li>
                                    <a href="/suspendedsale">Suspended Sales</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div class="g-btns-mobile header-btns">
                        <div class="btn-bulk">
                            <a href="javascript:void(0)" class="btn btn-secondary btn-info" @click="$refs.todaySaleReportModal.openModal()"><i class="las la-chart-line"></i> Today's Sales</a>
                            <a href="{{route('outlettransfer.index').'?stock=low'}}" class="btn btn-primary" target="_blank"><i class="las la-bell"></i> {{$productAlertCount}} Product Alerts</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu End -->

            <pos-sale
                :payment_types = "{{json_encode($paymentTypes)}}"
                :customer_types = "{{json_encode($customerTypes)}}"
                :categories="{{json_encode($categories)}}"
                :tax_types = "{{json_encode($taxTypes)}}"
                :discount_types = "{{json_encode($discountTypes)}}"
                :taxes = "{{json_encode($taxes)}}"
                :suspendedsale = "{{json_encode(isset($suspendedSale) ? $suspendedSale : null)}}"
                :pos_setting = "{{json_encode($posSetting)}}"
                :eng_date = "{{json_encode($engDate)}}"
                :nep_date = "{{json_encode($nepDate)}}"
                :user_company = "{{json_encode($userCompany)}}"
                :outlet = "{{json_encode($outlet)}}"
            >
            </pos-sale>

            <pos-todaysalereport-modal
                ref="todaySaleReportModal"
                :outlet="{{json_encode($outlet)}}"
            >
            </pos-todaysalereport-modal>
        </div>
        <script>
            window.data = {
                session_time: "{{$session_time}}",
            }
            if ('serviceWorker' in navigator) {
                // Use the window load event to keep the page load performant
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/service-worker.js');
                });
            }
        </script>
        <script src="{{mix('js/activity_session.js')}}"></script>
        <script src="{{ mix('js/app.js') }}"></script>
        <!-- jQuery -->
        <script src="{{asset('backend/plugins/jquery/jquery.min.js')}}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('js/jquery.keyboard.js')}}"></script>
        <script src="{{asset('js/metisMenu.min.js')}}"></script>
        <script src="{{asset('js/custom.js')}}"></script>
        <script src="{{asset('backend/plugins/moment/moment.min.js')}}"></script>
        <script src="{{asset('js/jQuery.print.js')}}"></script>

        <script>
            // jQuery(document).ready(function($) {
            //     $('.keyboard')
            //     .keyboard({
            //     layout : 'num',
            //         restrictInput : true, // Prevent keys not in the displayed keyboard from being typed in
            //         preventPaste : true,  // prevent ctrl-v and right click
            //         autoAccept : true,
            //         alwaysOpen: false,
            //         usePreview: false
            //     });
            // });

            setInterval(function() {
                var now = new Date();

                let date = moment(now).format('dddd, DD MMMM YYYY, hh:mm:ss A');

                $('.pos-date').text(date);
            }, 1000);

            // document.addEventListener("keydown", function(e) {
            //     if(interval)
            //         clearInterval(interval);
            //     if(e.code == 'Enter'){
            //         if(barcode)
            //             handlebardode(barcode);
            //         barcode = '';
            //         return;
            //     }
            //     if(e.code != 'Shift')
            //         barcode += e.key;
            //     interval = setInterval(() => barcode = '', 20);
            // });

            // function handlebardode(scanned_barcode){
            //     $('.pos-date').text(scanned_barcode);
            // }
        </script>
        <script>
            // Mpbole Nav
                $("#menu2").metisMenu();
            // MObile Nav End

            // Side menubar
            $("#close-btn1, .toggles-btn").click(function() {
                $("#mySidenav1, body").toggleClass("active");
            });
            // Sidebar Nav End
        </script>
    </body>
</html>
