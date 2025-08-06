@extends('adminlte::page')

@section('title', 'Registro de períodos de utilização')

@section('content_header')
<h1>Cadastro de períodos de utilização</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Receitas laboratoriais</a></li>
    <li><a href="">Períodos de utilização</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $periodo    = session('periodo')    ?: old('periodo');
            $dia_base   = session('dia_base')   ?: old('dia_base');
            $quantidade = session('quantidade') ?: old('quantidade');
        @endphp
        <form action="{{ route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.to_store', ['receita_laboratorial_id' => $receita_laboratorial->id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label>Receita:</label>
                <h5>{{ $receita_laboratorial->nome }}</h5>
            </div>
            <div class="form-group">
                <label for="periodo">Período:</label>
                <select name="periodo" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($periodos as $key => $value)
                        <option value="{{ $key }}" {{ ($key == $periodo) ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="dia_base">Dia base:</label>
                <input type="text" name="dia_base" placeholder="Dia base" class="form-control" value="{{ $dia_base }}">
            </div>
            <div class="form-group">
                <label for="quantidade">Qtd. em ({{ $receita_laboratorial->unidade_medida->sigla }}) / Kg de ração:</label>
                <input type="text" name="quantidade" placeholder="Quantidade" class="form-control" value="{{ $quantidade }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos', ['receita_laboratorial_id' => $receita_laboratorial->id]) }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection