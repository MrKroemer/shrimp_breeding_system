@extends('adminlte::page')

@section('title', 'Registro de setores')

@section('content_header')
<h1>Cadastro de setores</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Setores</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $nome  = !empty(session('nome'))  ? session('nome')  : old('nome');
            $sigla = !empty(session('sigla')) ? session('sigla') : old('sigla');
            $tipo  = !empty(session('tipo'))  ? session('tipo')  : old('tipo');
        @endphp
        <form action="{{ route('admin.setores.to_store') }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" placeholder="Nome" class="form-control" value="{{ $nome }}">
            </div>
            <div class="form-group">
                <label for="sigla">Sigla:</label>
                <input type="text" name="sigla" placeholder="Sigla" class="form-control" value="{{ $sigla }}">
            </div>
            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <select name="tipo" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach ($tipos as $key => $value)
                        <option value="{{ $key }}" {{ ($key == $tipo) ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.setores') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection