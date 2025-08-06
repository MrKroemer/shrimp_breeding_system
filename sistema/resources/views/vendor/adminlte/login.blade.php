@extends('adminlte::master')

@section('adminlte_css')
    <!-- AdminLTE Auth CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
@endsection

{{-- <body class="hold-transition login-page" style="overflow:hidden;"> --}}

@section('body_class', 'login-page')
@section('body_style', 'overflow:hidden;background:url(' . asset('img/system_bg.jpg') . ') no-repeat center bottom;')

@section('body')
    <div class="login-box shadow-lg">
        <div class="login-box-body animated fadeInUp">
            <div class="login-logo">
                <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">
                    <img src="{{ asset('img/system_logo.png') }}" alt="{{ config('app.system_owner_name') }}" width="248">
                </a>
            </div>

            <form class="form-centralized-data" action="{{ url(config('adminlte.login_url', 'login')) }}" method="POST">
                {!! csrf_field() !!}
                <div class="form-group has-feedback {{ $errors->has('username') ? 'has-error' : '' }}">
                    <input type="text" name="username" class="form-control" placeholder="Informe seu número de identificação" value="{{ old('username') }}">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input type="password" name="password" class="form-control" placeholder="Informe sua senha de acesso">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('filial') ? 'has-error' : '' }}">
                    <select name="filial" class="form-control">
                        <option value="">. . : : Selecione a filial : : . .</option>
                        @foreach($filiais as $filial)
                            <option value="{{ $filial->id }}">{{ $filial->nome }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('filial'))
                        <span class="help-block">
                            <strong>{{ $errors->first('filial') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-xs-12" style="margin:0 0 10px 0 !important;">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            {{ trans('adminlte::adminlte.sign_in') }}
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            {{-- O arquivo de mensagens de alerta, somente será incluso caso todos os campos sejam preenchidos --}}
            @if (! $errors->has('username') && ! $errors->has('password') && ! $errors->has('filial'))
                @include('admin.includes.alerts')
            @endif

            <div class="row">
                <div class="col-xs-12" style="font-size:16px;text-align:center;">
                    <a href="{{ route('password.request') }}">Esqueci minha senha</a>
                </div>
            </div>

        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
@endsection
{{-- </body> --}}

@section('adminlte_js')
    @yield('js')
@endsection
