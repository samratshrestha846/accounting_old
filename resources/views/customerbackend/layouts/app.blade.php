<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        // $user = Auth::user()->id;
        // $usercompanies = \App\Models\UserCompany::where('user_id', $user)->get();
        // $currentcomp = \App\Models\UserCompany::where('user_id', $user)
        //     ->where('is_selected', 1)
        //     ->first();
        $setting = \App\Models\Setting::first();
    @endphp

    <title>{{ $setting->company_name }}</title>
    <link rel="shortcut icon" type="image/jpg" href="{{ Storage::disk('uploads')->url($setting->logo) }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="{{ asset('backend/dist/css/nunitogooglefont.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link rel="manifest" href="/manifest.json" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/sansgooglefont.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/line-awesome.min.css') }}">
    <link href="{{ asset('backend/dist/css/line-awesome.min.css') }}" rel="stylesheet">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/ionicons.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('backend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    {{-- <link rel="stylesheet" href="{{ asset('backend/plugins/jqvmap/jqvmap.min.css') }}"> --}}
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
    <!-- Nepali Date Picker -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/nepali.datepicker.v3.6.min.css') }}">
    <link href="{{ asset('backend/dist/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/dist/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    @stack('styles')
    <link href="{{ asset('backend/dist/css/metisMenu.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/dist/css/main.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div id="app">
        <div id="main" class="wrapper">
            <!-- Navbar -->

            @include('customerbackend.includes.header')
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            @include('customerbackend.includes.sidebar')

            <!-- Content Wrapper. Contains page content -->
            @yield('content')
        </div>

        <footer class="main-footer mt-3">
            {{-- <strong>Copyright &copy; {{ date('Y') }} <a
                    href="{{ route('home') }}">{{ $currentcomp->company->name }}</a>.</strong> All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Company Registration no:</b> {{ $currentcomp->company->registration_no }} | <b>PAN / VAT:</b>
                {{ $currentcomp->company->pan_vat }}
            </div> --}}
        </footer>
    </div>

    <!-- Scripts -->

    {{-- Check that service workers are supported --}}
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
    <script src="{{asset('js/axios.min.js')}}"></script>
    <script src="{{mix('js/activity_session.js')}}"></script>
    <!-- jQuery -->
    <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('backend/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('backend/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('backend/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    {{-- <script src="{{ asset('backend/plugins/jqvmap/jquery.vmap.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('backend/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script> --}}
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('backend/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('backend/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('backend/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('backend/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('backend/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('backend/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('backend/dist/js/demo.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('backend/dist/js/pages/dashboard.js') }}"></script>
    {{-- Print preview --}}
    <script src="{{ asset('backend/plugins/jquery/jquery.printPage.js') }}"></script>
    <script src="{{ asset('backend/dist/js/nepali.datepicker.v3.6.min.js') }}"></script>
    <script src="{{ asset('backend/dist/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/dist/js/dataTables.bootstrap4.min.js') }}"></script>
    {{-- Search in select option --}}
    <script src="{{ asset('backend/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('backend/dist/js/metisMenu.min.js') }}"></script>

    <script defer src="https://use.fontawesome.com/releases/v5.2.0/js/all.js"></script>
    <script>
        var deferredPrompt;
        window.addEventListener('beforeinstallprompt', function(event) {
            event.preventDefault();
            deferredPrompt = event;
            return false;
        });

        function addToHomeScreen() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function(choiceResult) {
                    console.log(choiceResult.outcome);
                    if (choiceResult.outcome === 'dismissed') {
                        console.log('User cancelled installation');
                    } else {
                        console.log('User added to home screen');
                    }
                });
                deferredPrompt = null;
            }
        }

        function searchFilterForm() {
            let search = $('#dataTableForm').find('input[name="search"]').val();

            window.location.href = "{{route('suspendedsale.index')}}" + '?search=' + search;
        }
    </script>
    @stack('scripts')
</body>

</html>
