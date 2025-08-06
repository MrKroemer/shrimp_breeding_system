@extends('adminlte::page')

@section('title', 'Registro de tipos de aplicações de arraçoamentos')

@section('content_header')
<h1>Cadastro de tipos de aplicações de arraçoamentos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Tipos de aplicações de arraçoamentos</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $nome = !empty(session('nome')) ? session('nome') : old('nome');
        @endphp
        <form action="{{ route('admin.arracoamentos_aplicacoes_tipos.to_store') }}" method="POST">
            {!! csrf_field() !!}  
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" placeholder="Nome" class="form-control" value="{{ $nome }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.arracoamentos_aplicacoes_tipos') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection