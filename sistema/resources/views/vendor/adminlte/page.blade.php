@extends('adminlte::master')

@php 
    $selected_module = filter_input(INPUT_GET, 'module');

    if (!empty($selected_module) && is_numeric($selected_module) && ($selected_module > 0)) {
        session(['_selected_module' => (int) $selected_module]);
    }
@endphp

@section('adminlte_css')
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css') }}">
    @stack('css')
    @yield('css')
@endsection

@section('body_class', 
    'skin-' . 
    config('adminlte.skin', 'blue') . 
    ' sidebar-mini ' . 
    (config('adminlte.layout') ? [
        'boxed' => 'layout-boxed',
        'fixed' => 'fixed',
        'top-nav' => 'layout-top-nav'
    ][config('adminlte.layout')] : '') . 
    (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : '') . 
    (request()->path() == config('adminlte.dashboard_url') ? ' animated fadeIn' : '')
)

@section('body')
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">
            @if(config('adminlte.layout') == 'top-nav')
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
                            {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
            @else
            <!-- Logo -->
            {{-- <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
            </a> --}}

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation" style="margin-left:0;">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" style="width:50px; text-align:center;">
                    <span class="sr-only">{{ trans('adminlte::adminlte.toggle_navigation') }}</span>
                </a>
            @endif
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">
                                <i class="fa fa-fw fa-home"></i> Página inicial
                            </a>
                        </li>
                        <li>
                            @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<'))
                                <a href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}">
                                    <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
                                </a>
                            @else
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
                                </a>
                                <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                                    @if(config('adminlte.logout_method'))
                                        {{ method_field(config('adminlte.logout_method')) }}
                                    @endif
                                    {{ csrf_field() }}
                                </form>
                            @endif
                        </li>
                    </ul>
                </div>

                @if(config('adminlte.layout') == 'top-nav')
                </div>
                @endif
            </nav>
        </header>

        @if(config('adminlte.layout') != 'top-nav')
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <div class="user-panel">
                    <a class="pull-left image" style="cursor: pointer;" data-toggle="modal" data-target="#user_image_modal" title="Clique para alterar a foto">
                        <img src="{{ auth()->user()->imagem ? asset('storage/'.auth()->user()->imagem) : asset('img/user.png') }}" class="img-circle" alt="User image">
                    </a><!-- /.show-modal-button -->
                    <div class="pull-left info">
                        <p>{{ auth()->user()->nome }}</p>
                        <a href="javascript:location.reload();"><i class="fa fa-circle text-warning"></i><span id="status_icon">Obtendo</span></a>
                        <a href="javascript:location.reload();"><i class="fa fa-clock-o"></i><span id="session_time">00:00:00</span></a>
                    </div>
                </div>
                
                <div class="sidebar-form" style="border:none;margin: 0 10px 10px;">
                    <div style="color:#FFF;">Filial:</div>
                    <input type="text" name="filial" class="form-control" style="color:#FFF;" value="{{ session('_filial')->nome }}" disabled>

                    <div style="color:#FFF;">Modulo:</div>
                    <select name="selected_module" class="form-control" onchange="onChangeModulos('{{ url(config('adminlte.dashboard_url')) }}');">
                    @foreach(session('_user_modules') as $module)
                        <option value="{{ $module->id }}" {{ (session('_selected_module') == $module->id) ? 'selected' : '' }}>{{ $module->nome }}</option>
                    @endforeach
                    </select>
                </div>
                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        @endif

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @if(config('adminlte.layout') == 'top-nav')
            <div class="container">
            @endif

            <!-- Content Header (Page header) -->
            <section class="content-header">
                @yield('content_header')
            </section>

            <!-- Main content -->
            <section class="content">

                @include('admin.usuarios.imagem.edit') {{-- Modal de alteração da imagem do usuario --}}

                @yield('content')

            </section>
            <!-- /.content -->
            @if(config('adminlte.layout') == 'top-nav')
            </div>
            <!-- /.container -->
            @endif
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <a href="{{ route('admin.versions') }}">
                    <b>v</b> {{ config('app.system_version') }} {{ config('app.system_release') }}
                </a>
            </div>
            <strong>
                Copyright © {{ config('app.system_copyright_year') }} <a href="http://{{ config('app.system_owner_site') }}/" target="_blank">{{ config('app.system_owner_name') }}</a>
            </strong> Todos os direitos reservados.
        </footer>

    </div>
    <!-- ./wrapper -->
@endsection

@section('adminlte_js')
    <!-- AdminLTE JS -->
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    <!-- Application JS -->
    <script id="function_js" src="{{ asset('js/functions.js') }}"></script>
    <script id="document_js" src="{{ asset('js/document.js') }}?session_time={{ env('SESSION_LIFETIME') }}"></script>

    @stack('js')
    @yield('js')
@endsection
