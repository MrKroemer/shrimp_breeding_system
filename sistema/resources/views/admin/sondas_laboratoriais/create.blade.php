@extends('adminlte::page')

@section('title', 'Registro de Sondas Laboratoriais')

@section('content_header')
<h1>Cadastro de Sondas Laboratoriais</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Sondas Laboratoriais</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $nome           = !empty(session('nome'))           ? session('nome')           : old('nome');
            $marca          = !empty(session('marca'))          ? session('marca')          : old('marca');
            $numero_serie   = !empty(session('numero_serie'))   ? session('numero_serie')   : old('numero_serie');            
        @endphp
        <form action="{{ route('admin.sondas_laboratoriais.to_store') }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" placeholder="Nome" class="form-control" value="{{ $nome }}">
            </div>
            <div class="form-group">
                <label for="sigla">Marca:</label>
                <input type="text" name="marca" placeholder="marca" class="form-control" value="{{ $marca }}">
            </div>
            <div class="form-group">
                <label for="sigla">Nº Série:</label>
                <input type="text" name="numero_serie" placeholder="Sigla" class="form-control" value="{{ $numero_serie }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.sondas_laboratoriais') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection