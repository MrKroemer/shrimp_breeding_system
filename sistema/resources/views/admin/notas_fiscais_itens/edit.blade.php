@extends('adminlte::page')

@section('title', 'Registro de itens de notas fiscais')

@section('content_header')

<h1>{{ $situacao ? 'Edição' : 'Visualização' }} de itens de notas fiscais</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Notas fiscais</a></li>
    <li><a href="">Itens de notas fiscais</a></li>
    <li><a href="">{{ $situacao ? 'Edição' : 'Visualização' }}</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

@php
    $params['nota_fiscal_id'] = $nota_fiscal_id;
    $params['id'] = $id;
    
    if ($redirectBack == 'yes') {
        $params['redirectBack'] = $redirectBack;
    }
@endphp

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
    @if ($situacao)
        <form action="{{ route("admin.{$route}.notas_fiscais_itens.to_update", $params) }}" method="POST">
            {!! csrf_field() !!}
    @else
        <form>
    @endif
            @php
                unset($params['nota_fiscal_id']);
                $params['id'] = $nota_fiscal_id; // Adição do ID da nota fiscal
            @endphp

            <div class="form-group">
                <label>Nota fiscal:</label>
                <h5 class="box-title">
                    Nota fiscal Nº {{ $nota_fiscal->numero }} / Chave de acesso: <a class="btn btn-default btn-xs" onclick="consultaNfe('{{ $nota_fiscal->chave }}');">{{ $nota_fiscal->chave }}</a>
                    @if ($redirectBack == 'yes')
                        <a href="{{ route('admin.notas_fiscais_entradas.to_edit', $params) }}" class="btn btn-warning btn-xs">
                            <i class="fa fa-edit" aria-hidden="true"></i> Editar
                        </a>
                    @endif
                </h5>
            </div>
            <div class="form-group">
                <label for="produto_id">Produto:</label>
                <select name="produto_id" class="form-control" {{ $situacao ? '' : 'disabled' }}>
                    <option value="">..:: Selecione ::..</option>
                    @foreach($produtos as $produto)
                        <option value="{{ $produto->id }}" {{ ($produto->id == $produto_id) ? 'selected' : '' }}>{{ $produto->nome }} (Und.: {{ $produto->unidade_entrada->sigla }}) </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" step="any" name="quantidade" placeholder="Quantidade" class="form-control" value="{{ $quantidade }}" {{ $situacao ? '' : 'disabled' }}>
            </div>
            <div class="form-group">
                <label for="valor_unitario">Valor unitário:</label>
                <input type="number" step="any" name="valor_unitario" placeholder="Valor unitário" class="form-control" value="{{ $valor_unitario }}" {{ $situacao ? '' : 'disabled' }}>
            </div>
            <div class="form-group">
                <label for="valor_frete">Valor do frete:</label>
                <input type="number" step="any" name="valor_frete" placeholder="Valor do frete" class="form-control" value="{{ $valor_frete }}" {{ $situacao ? '' : 'disabled' }}>
            </div>
            <div class="form-group">
                <label for="valor_desconto">Valor de desconto:</label>
                <input type="number" step="any" name="valor_desconto" placeholder="Valor de desconto" class="form-control" value="{{ $valor_desconto }}" {{ $situacao ? '' : 'disabled' }}>
            </div>
            
            @php
                unset($params['id']); // Remoção do ID da nota fiscal
                $params['nota_fiscal_id'] = $nota_fiscal_id;
            @endphp

            <div class="form-group">
            @if ($situacao)
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route("admin.{$route}.notas_fiscais_itens", $params) }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
                @if ($redirectBack == 'yes')
                    <a href="{{ route('admin.notas_fiscais_entradas.redirect_to.lotes.to_create') }}" class="btn btn-default">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Lotes de pós-larvas
                    </a>
                @endif
            @endif
            </div>
        </form>
    </div>
</div>
@endsection