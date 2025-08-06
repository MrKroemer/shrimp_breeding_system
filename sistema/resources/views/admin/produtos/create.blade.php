@extends('adminlte::page')

@section('title', 'Registro de produtos')

@section('content_header')
<h1>Cadastro de produtos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Produtos</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.produtos.tipos.create')
@include('admin.unidades_medidas.create')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $codigo_externo     = !empty(session('codigo_externo'))     ? session('codigo_externo')     : old('codigo_externo');
            $nome               = !empty(session('nome'))               ? session('nome')               : old('nome');
            $sigla              = !empty(session('sigla'))              ? session('sigla')              : old('sigla');
            $codigo_barras      = !empty(session('codigo_barras'))      ? session('codigo_barras')      : old('codigo_barras');
            $unidade_entrada_id = !empty(session('unidade_entrada_id')) ? session('unidade_entrada_id') : old('unidade_entrada_id');
            $unidade_saida_id   = !empty(session('unidade_saida_id'))   ? session('unidade_saida_id')   : old('unidade_saida_id');
            $unidade_razao      = !empty(session('unidade_razao'))      ? session('unidade_razao')      : old('unidade_razao');
            $produto_tipo_id    = !empty(session('produto_tipo_id'))    ? session('produto_tipo_id')    : old('produto_tipo_id');
        @endphp
        <form action="{{ route('admin.produtos.to_store') }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="codigo_externo">Código externo:</label>
                <input type="text" name="codigo_externo" placeholder="Código externo" class="form-control" value="{{ $codigo_externo }}">
            </div>
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" placeholder="Nome" class="form-control" value="{{ $nome }}">
            </div>
            <div class="form-group">
                <label for="sigla">Sigla:</label>
                <input type="text" name="sigla" placeholder="Sigla" class="form-control" value="{{ $sigla }}">
            </div>
            <div class="form-group">
                <label for="produto_tipo_id">Tipo do produto:</label>
                <div class="input-group">
                    <select name="produto_tipo_id" class="form-control">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($produtos_tipos as $produto_tipo)
                            <option value="{{ $produto_tipo->id }}" {{ ($produto_tipo->id == $produto_tipo_id) ? 'selected' : '' }}>{{ $produto_tipo->nome }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <a class="btn btn-success btn-flat" data-toggle="modal" data-target="#produtos_tipos_modal">
                            <i class="fa fa-plus" aria-hidden="true"></i> Criar novo
                        </a>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="unidade_entrada_id">Unidade de entrada:</label>
                <div class="input-group">
                    <select name="unidade_entrada_id" class="form-control">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($unidades_entradas as $unidade_entrada)
                            <option value="{{ $unidade_entrada->id }}" {{ ($unidade_entrada->id == $unidade_entrada_id) ? 'selected' : '' }}>{{ $unidade_entrada->nome }} ({{ $unidade_entrada->sigla }})</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <a class="btn btn-success btn-flat" data-toggle="modal" data-target="#unidades_medidas_modal">
                            <i class="fa fa-plus" aria-hidden="true"></i> Criar novo
                        </a>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="unidade_saida_id">Unidade de saída:</label>
                <div class="input-group">
                    <select name="unidade_saida_id" class="form-control">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($unidades_saidas as $unidade_saida)
                            <option value="{{ $unidade_saida->id }}" {{ ($unidade_saida->id == $unidade_saida_id) ? 'selected' : '' }}>{{ $unidade_saida->nome }} ({{ $unidade_saida->sigla }})</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <a class="btn btn-success btn-flat" data-toggle="modal" data-target="#unidades_medidas_modal">
                            <i class="fa fa-plus" aria-hidden="true"></i> Criar novo
                        </a>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="unidade_razao">Razão entre unidades:</label>
                <input type="text" name="unidade_razao" placeholder="Razão entre unidades" class="form-control" value="{{ $unidade_razao }}">
            </div>
            <div class="form-group">
                <label for="codigo_barras">Código de barras:</label>
                <input type="text" name="codigo_barras" placeholder="Código de barras" class="form-control" value="{{ $codigo_barras }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.produtos') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection