@extends('adminlte::page')

@section('title', 'Registro de baixas justificadas')

@section('content_header')
<h1>Cadastro de produtos de baixas justificadas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Baixas justificadas</a></li>
    <li><a href="">Produtos</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $quantidade = !empty(session('quantidade')) ? session('quantidade') : old('quantidade');
            $produto_id = !empty(session('produto_id')) ? session('produto_id') : old('produto_id');
        @endphp
        <form action="{{ route('admin.baixas_justificadas.produtos.to_store', ['baixa_justificada_id' => $baixa_justificada_id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="produto_id">Produto:</label>
                <select name="produto_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($produtos as $produto)
                        <option value="{{ $produto->produto_id }}" {{ ($produto->produto_id == $produto_id) ? 'selected' : '' }}>{{ $produto->produto_nome }} (Und.: {{ $produto->unidade_saida->sigla }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" step="any" name="quantidade" placeholder="Quantidade" class="form-control" value="{{ $quantidade }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> Salvar
                </button>
                <a href="{{ route('admin.baixas_justificadas.produtos', ['baixa_justificada_id' => $baixa_justificada_id]) }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection