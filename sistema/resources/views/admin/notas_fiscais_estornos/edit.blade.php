@extends('adminlte::page')

@section('title', 'Registro de notas de estornos')

@section('content_header')
<h1>Visualização de notas de estornos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Notas de estornos</a></li>
    <li><a href="">Visualização</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form enctype="multipart/form-data">
            <div class="form-group">
                <label for="chave">Chave de acesso:</label>
                <input type="text" name="chave" placeholder="Chave de acesso" class="form-control" value="{{ $chave }}" disabled>
            </div>
            <div class="form-group">
                <label for="numero">Número da NF:</label>
                <input type="text" name="numero" placeholder="Numero da NF" class="form-control" value="{{ $numero }}" disabled>
            </div>
            <div class="form-group">
                <label for="data_emissao">Data de emissão:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_emissao" placeholder="Data de emissão" class="form-control pull-right" id="date_picker" value="{{ $data_emissao }}" disabled>
                </div>
            </div>
            <div class="form-group">
                <label for="valor_total">Valor total da NF:</label>
                <input type="text" name="valor_total" placeholder="Valor total da NF" class="form-control" value="{{ $valor_total }}" disabled>
            </div>
            <div class="form-group">
                <label for="valor_frete">Valor do frete:</label>
                <input type="text" name="valor_frete" placeholder="Valor do frete" class="form-control" value="{{ $valor_frete }}" disabled>
            </div>
            <div class="form-group">
                <label for="valor_desconto">Valor total dos descontos:</label>
                <input type="text" name="valor_desconto" placeholder="Valor total dos descontos" class="form-control" value="{{ $valor_desconto }}" disabled>
            </div>
            <div class="form-group">
                <label for="icms_desonerado">Valor do ICMS desonerado:</label>
                <input type="text" name="icms_desonerado" placeholder="Valor do ICMS desonerado" class="form-control" value="{{ $icms_desonerado }}" disabled>
            </div>
            <div class="form-group">
                <label for="fornecedor_id">Fornecedor:</label>
                <select name="fornecedor_id" class="form-control" disabled>
                    <option value="">..:: Selecione ::..</option>
                    @foreach($fornecedores as $fornecedor)
                        <option value="{{ $fornecedor->id }}" {{ ($fornecedor->id == $fornecedor_id) ? 'selected' : '' }}>{{ mb_strtoupper($fornecedor->nome) }} (CNPJ: {{ $fornecedor->cnpj }}) (Razão: {{ mb_strtoupper($fornecedor->razao) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="cliente_id">Cliente:</label>
                <select name="cliente_id" class="form-control" disabled>
                    <option value="">..:: Selecione ::..</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ ($cliente->id == $cliente_id) ? 'selected' : '' }}>{{ mb_strtoupper($cliente->nome) }} (CNPJ: {{ $cliente->cnpj }}) (Razão: {{ mb_strtoupper($cliente->razao) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="data_movimento">Data de entrada em estoque:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_movimento" placeholder="Data de entrada em estoque" class="form-control pull-right" id="date_picker" value="{{ $data_movimento }}" disabled>
                </div>
            </div>
            <div class="form-group">
                <a href="{{ route('admin.notas_fiscais_estornos') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection