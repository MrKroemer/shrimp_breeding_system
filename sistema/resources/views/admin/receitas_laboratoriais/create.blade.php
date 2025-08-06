@extends('adminlte::page')

@section('title', 'Registro de receitas laboratoriais')

@section('content_header')
<h1>Cadastro de receitas laboratoriais</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Receitas laboratoriais</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.receitas_laboratoriais.tipos.create')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $nome                         = session('nome')                         ?: old('nome');
            $descricao                    = session('descricao')                    ?: old('descricao');
            $unidade_medida_id            = session('unidade_medida_id')            ?: old('unidade_medida_id');
            $receita_laboratorial_tipo_id = session('receita_laboratorial_tipo_id') ?: old('receita_laboratorial_tipo_id');
        @endphp
        <form action="{{ route('admin.receitas_laboratoriais.to_store') }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" placeholder="Nome" class="form-control" value="{{ $nome }}" style="text-transform: uppercase;">
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <input type="text" name="descricao" placeholder="Descrição" class="form-control" value="{{ $descricao }}">
            </div>
            <div class="form-group">
                <label for="unidade_medida_id">Unidade de medida:</label>
                <select name="unidade_medida_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($unidades_medidas as $unidade_medida)
                        <option value="{{ $unidade_medida->id }}" {{ ($unidade_medida->id == $unidade_medida_id) ? 'selected' : '' }}>{{ $unidade_medida->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="receita_laboratorial_tipo_id">Tipo de receita:</label>
                <div class="input-group">
                    <select name="receita_laboratorial_tipo_id" class="form-control">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($receitas_laboratoriais_tipos as $receita_laboratorial_tipo)
                            <option value="{{ $receita_laboratorial_tipo->id }}" {{ ($receita_laboratorial_tipo->id == $receita_laboratorial_tipo_id) ? 'selected' : '' }}>{{ $receita_laboratorial_tipo->nome }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <a class="btn btn-success btn-flat" data-toggle="modal" data-target="#receitas_laboratoriais_tipos_modal">
                            <i class="fa fa-plus" aria-hidden="true"></i> Criar novo
                        </a>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.receitas_laboratoriais') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection