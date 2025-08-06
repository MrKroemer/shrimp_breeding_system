@extends('adminlte::page')

@section('title', 'Registro de notas de entrada')

@section('content_header')
<h1>Cadastro de notas de entrada</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Notas de entrada</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $chave           = !empty(session('chave'))           ? session('chave')           : old('chave');
            $numero          = !empty(session('numero'))          ? session('numero')          : old('numero');
            $data_emissao    = !empty(session('data_emissao'))    ? session('data_emissao')    : old('data_emissao');
            $data_movimento  = !empty(session('data_movimento'))  ? session('data_movimento')  : old('data_movimento');
            $valor_total     = !empty(session('valor_total'))     ? session('valor_total')     : old('valor_total');
            $valor_frete     = !empty(session('valor_frete'))     ? session('valor_frete')     : old('valor_frete');
            $valor_desconto  = !empty(session('valor_desconto'))  ? session('valor_desconto')  : old('valor_desconto');
            $icms_desonerado = !empty(session('icms_desonerado')) ? session('icms_desonerado') : old('icms_desonerado');
            $fornecedor_id   = !empty(session('fornecedor_id'))   ? session('fornecedor_id')   : old('fornecedor_id');
            $cliente_id      = !empty(session('cliente_id'))      ? session('cliente_id')      : old('cliente_id');
        @endphp
        <form action="{{ route('admin.notas_fiscais_entradas.to_store', ['redirectBack' => $redirectBack]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="chave">Chave de acesso:</label>
                <input type="text" name="chave" maxlength="44" placeholder="Chave de acesso" class="form-control" value="{{ $chave }}">
            </div>
            <div class="form-group">
                <label for="numero">Número da NF:</label>
                <input type="text" name="numero" placeholder="Número da NF" class="form-control" value="{{ $numero }}">
            </div>
            <div class="form-group">
                <label for="data_emissao">Data de emissão:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_emissao" placeholder="Data de emissão" class="form-control pull-right" id="date_picker" value="{{ $data_emissao }}">
                </div>
            </div>
            <div class="form-group">
                <label for="valor_total">Valor total da NF:</label>
                <input type="number" step="any" name="valor_total" placeholder="Valor total da NF" class="form-control" value="{{ $valor_total }}">
            </div>
            <div class="form-group">
                <label for="valor_frete">Valor do frete:</label>
                <input type="number" step="any" name="valor_frete" placeholder="Valor do frete" class="form-control" value="{{ $valor_frete }}">
            </div>
            <div class="form-group">
                <label for="valor_desconto">Valor total dos descontos:</label>
                <input type="number" step="any" name="valor_desconto" placeholder="Valor total dos descontos" class="form-control" value="{{ $valor_desconto }}">
            </div>
            <div class="form-group">
                <label for="icms_desonerado">Valor do ICMS desonerado:</label>
                <input type="number" step="any" name="icms_desonerado" placeholder="Valor do ICMS desonerado" class="form-control" value="{{ $icms_desonerado }}">
            </div>
            <div class="form-group">
                <label for="fornecedor_id">Fornecedor:</label>
                <select name="fornecedor_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($fornecedores as $fornecedor)
                        <option value="{{ $fornecedor->id }}" {{ ($fornecedor->id == $fornecedor_id) ? 'selected' : '' }}>{{ mb_strtoupper($fornecedor->nome) }} (CNPJ: {{ $fornecedor->cnpj }}) (Razão: {{ mb_strtoupper($fornecedor->razao) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="cliente_id">Cliente:</label>
                <select name="cliente_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ ($cliente->id == $cliente_id) ? 'selected' : '' }}>{{ mb_strtoupper($cliente->nome) }} (CNPJ: {{ $cliente->cnpj }}) (Razão: {{ mb_strtoupper($cliente->razao) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="data_movimento">Data de entrada no estoque:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_movimento" placeholder="Data de entrada em estoque" class="form-control pull-right" id="date_picker" value="{{ $data_movimento }}">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                @if ($redirectBack == 'yes')
                    <a href="{{ route('admin.notas_fiscais_entradas.redirect_to.lotes.to_create') }}" class="btn btn-default">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Lotes de pós-larvas
                    </a>
                @else
                    <a href="{{ route('admin.notas_fiscais_entradas') }}" class="btn btn-success">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection