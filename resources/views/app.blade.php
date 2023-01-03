<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
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

        <!-- Scripts -->
        @routes
        {{-- <script src="{{asset('js/custom.js')}}"></script> --}}
    </head>
    <body class="font-sans antialiased">
        @inertia

        @env ('local')
            <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>
        @endenv
        <script src="{{ mix('js/app.js') }}" defer></script>
        <!-- jQuery -->
        <script src="{{asset('backend/plugins/jquery/jquery.min.js')}}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('js/jquery.keyboard.js')}}"></script>
        <script src="{{asset('js/metisMenu.min.js')}}"></script>
        <script src="{{asset('js/custom.js')}}"></script>
        <script>
            jQuery(document).ready(function($) {
                $('.keyboard')
                .keyboard({
                layout : 'num',
                    restrictInput : true, // Prevent keys not in the displayed keyboard from being typed in
                    preventPaste : true,  // prevent ctrl-v and right click
                    autoAccept : true,
                    alwaysOpen: false,
                    usePreview: false
                });
            });

        </script>
    </body>
</html>
