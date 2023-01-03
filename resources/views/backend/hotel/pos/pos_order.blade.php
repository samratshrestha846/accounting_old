<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        @php
            $setting = \App\Models\Setting::first();
        @endphp
        <title>{{ $setting->company_name }}</title>
        <link rel="shortcut icon" type="image/jpg" href="{{ Storage::disk('uploads')->url($setting->logo) }}"/>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{ mix('css/pos.css') }}">
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
        $productAlertCount =0;
        $outlets = auth()->user()->getOutlets();
    @endphp

    <body class="font-sans antialiased">
        <div id="app">
            <hotel-pos-order
                :auth_user = "{{json_encode(auth()->user())}}"
                :order_types = "{{json_encode($orderTypes)}}"
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
                :orderitem = "{{json_encode(isset($orderItem) ? $orderItem : null)}}"
                :total_records ="{{json_encode($totalRecords)}}"
            >
            </hotel-pos-order>
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
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script src="{{ asset('backend/plugins/jquery/jquery.printPage.js') }}"></script>



        <script>

            window.initializeBtnPring =  function() {
                $(document).ready(function() {
                    $('.btnprn').printPage();
                });
            }

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
    </body>
</html>
