@extends('adminlte::page')

@section('title', 'Registro de aplicações')

@section('content_header')
<h1>Cadastro de aplicações</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Preparações de cultivos</a></li>
    <li><a href="">Aplicações</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $data_aplicacao     = !empty(session('data_aplicacao'))     ? session('data_aplicacao')     : old('data_aplicacao');
            $quantidade         = !empty(session('quantidade'))         ? session('quantidade')         : old('quantidade');
            $produto_id         = !empty(session('produto_id'))         ? session('produto_id')         : old('produto_id');
            $preparacao_tipo_id = !empty(session('preparacao_tipo_id')) ? session('preparacao_tipo_id') : old('preparacao_tipo_id');
        @endphp
        <form action="{{ route('admin.preparacoes.aplicacoes.to_store', ['preparacao_id' => $preparacao->id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_preparacao">Data da preparação:</label>
                <input type="text" name="data_preparacao" placeholder="Data de aplicação" class="form-control" style="font-weight:bold;color:red;" value="{{ $preparacao->data_inicio() }}" disabled>
            </div>
            <div class="form-group">
                <label for="data_aplicacao">Data de aplicação:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_aplicacao" placeholder="Data de aplicação" class="form-control pull-right" id="date_picker" value="{{ $data_aplicacao }}">
                </div>
            </div>
            <div class="form-group">
                <label for="preparacao_tipo_id">Tipo de preparação:</label>
                <select name="preparacao_tipo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($preparacoes_tipos as $preparacao_tipo)
                        <option value="{{ $preparacao_tipo->id }}" {{ ($preparacao_tipo->id == $preparacao_tipo_id) ? 'selected' : '' }}>{{ $preparacao_tipo->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="produto_id">Produto:</label>
                <select name="produto_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($produtos as $item)
                        <option value="{{ $item->produto_id }}" {{ ($item->produto_id == $produto_id) ? 'selected' : '' }}>{{ $item->produto_nome }} (Und.: {{ $item->produto->unidade_saida->sigla }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" step="any" name="quantidade" placeholder="Quantidade" class="form-control" value="{{ $quantidade }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.preparacoes.aplicacoes', ['preparacao_id' => $preparacao->id]) }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection