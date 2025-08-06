@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
@endsection

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

            <form class="form-centralized-data" action="{{ url(config('adminlte.password_email_url', 'password/email')) }}" method="post">
                {!! csrf_field() !!}
                <div class="form-group has-feedback {{ $errors->has('username') ? 'has-error' : '' }}">
                    <input type="text" name="username" class="form-control" value="{{ $username ?? old('username') }}" placeholder="Informe sua matrícula corporativa">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                    <small class="form-text text-muted">A matrícula pode ser encontrada no seu crachá de identificação.</small>
                </div>
                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" placeholder="Informe seu e-mail corporativo">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                        <!-- /.col -->
                    <div class="col-xs-12" style="margin:0 0 10px 0; !important">
                        <button type="submit" class="btn btn-primary btn-block btn-md">
                            {{ trans('adminlte::adminlte.send_password_reset_link') }}
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            {{-- O arquivo de mensagens de alerta, somente será incluso caso todos os campos sejam preenchidos --}}
            @if (! $errors->has('username') && ! $errors->has('email'))
                @include('admin.includes.alerts')
            @endif

        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
@endsection

@section('adminlte_js')
    @yield('js')
@endsection
