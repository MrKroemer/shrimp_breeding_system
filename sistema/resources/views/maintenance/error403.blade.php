@extends('adminlte::page')

@section('title', '403 Forbidden')

@section('content')

<div class="error-page">
    <h2 class="headline text-yellow"> 403</h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-yellow"></i> Você não possui permissão para acessar esta página, ou executar esta ação.</h3>
        <p>Entre em contato com os administradores do sistema para obter mais informações.</p>
        <a href="{{ $redirectBack }}" class="btn btn-primary">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para tela anterior
        </a>
    </div>
    <!-- /.error-content -->
</div>

@endsection