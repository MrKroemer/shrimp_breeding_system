<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        @yield('title_prefix',  config('adminlte.title_prefix', ''))
        @yield('title',         config('adminlte.title', 'AdminLTE 2'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>

    <!-- #################### BEGIN CSS libs ############################# -->
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css') }}">

    @if(config('adminlte.plugins.select2'))
        <!-- Select2 -->
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css">
    @endif

    <!-- Theme style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}">

    @if(config('adminlte.plugins.datatables'))
        <!-- DataTables -->
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    @endif

    <!-- iCheck 1.0.2 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/icheck/skins/flat/blue.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/icheck/skins/line/blue.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/icheck/skins/line/red.css') }}">

    <!-- Datepicker for Bootstrap v1.8.0 -->
    {{-- <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}"> --}}

    <!-- Daterangepicker v3.0.5 -->
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('vendor/daterangepicker/css/daterangepicker.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>

    <!-- Animate.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/animate/animate.css') }}">

    <!-- Application CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">

    @yield('adminlte_css')

    <!-- Google Font -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- #################### END CSS libs ############################# -->

    <!-- #################### BEGIN JavaScript libs #################### -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script type="text/javascript" src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript" src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.slimscroll.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    @if(config('adminlte.plugins.select2'))
        <!-- Select2 -->
        <script type="text/javascript" src="//cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    @endif

    @if(config('adminlte.plugins.datatables'))
        <!-- DataTables -->
        <script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    @endif

    @if(config('adminlte.plugins.chartjs'))
        <!-- ChartJS -->
        <script type="text/javascript" src="//cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.min.js"></script>
        <!-- chartjs-plugin-datalabels-->
        <script type="text/javascript" src="//cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
    @endif

    <!-- SweetAlert 2.1.0 -->
    <script src="https://unpkg.com/sweetalert@2.1.0/dist/sweetalert.min.js"></script>

    <!-- iCheck 1.0.2 -->
    <script type="text/javascript" src="{{ asset('vendor/icheck/icheck.min.js') }}"></script>

    <!-- jscolor 2.0.5 -->
    <script type="text/javascript" src="{{ asset('vendor/jscolor/jscolor.js') }}"></script>

    <!-- Datepicker for Bootstrap v1.8.0 -->
    {{-- <script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script> --}}
    {{-- <script type="text/javascript" src="{{ asset('vendor/bootstrap-datepicker/locales/bootstrap-datepicker.pt-BR.min.js') }}"></script> --}}

    <!-- Daterangepicker v3.0.5 -->
    {{-- <script type="text/javascript" src="{{ asset('vendor/daterangepicker/js/moment.min.js') }}"></script> --}}
    {{-- <script type="text/javascript" src="{{ asset('vendor/daterangepicker/js/daterangepicker.min.js') }}"></script> --}}
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- canvas-gauges v2.1.5 -->
    <script type="text/javascript" src="//cdn.rawgit.com/Mikhus/canvas-gauges/gh-pages/download/2.1.5/all/gauge.min.js"></script>

    @yield('adminlte_js')
    <!-- #################### END JavaScript libs #################### -->
</head>
<body class="hold-transition @yield('body_class')" style="@yield('body_style')">

<!-- Loading page spinner -->
<div class="loading" style="visibility: hidden;"></div>

@yield('body')

</body>
</html>
