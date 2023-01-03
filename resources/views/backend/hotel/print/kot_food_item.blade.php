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

        <style>
            .brand-logo{
                max-height: 40px;
                padding: 0 16px;
            }
        </style>

    </head>


    <body class="font-sans antialiased">
        <div id="app">
            @php
                $order_items = collect($orderItem->order_items)->map(function($item){
                    return [
                        "product_name" => $item->food_name,
                        "quantity" => $item->quantity,
                        "product_price" => $item->unit_price,
                    ];
                });
            @endphp
            <print-kot-billing
                class="d-block"
                :engdate = "{{json_encode($engDate)}}"
                :nepdate = "{{json_encode($nepDate)}}"
                :usercompany = "{{json_encode($userCompany)}}"
                :customer ="{{json_encode($orderItem->customer)}}"
                :table ="{{json_encode($orderItem->table)}}"
                :orderitems = "{{json_encode($order_items)}}"
            >
            </print-kot-billing>
        </div>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
