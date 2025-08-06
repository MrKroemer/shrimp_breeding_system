@extends('adminlte::page')

@section('title', 'Registro de parâmetros')

@section('content_header')
<h1>Cadastro de parâmetros</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Parâmetros</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.unidades_medidas.create')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $sigla             = !empty(session('sigla'))             ? session('sigla')             : old('sigla');
            $descricao         = !empty(session('descricao'))         ? session('descricao')         : old('descricao');
            $minimo            = !empty(session('minimo'))            ? session('minimo')            : old('minimo');
            $maximo            = !empty(session('maximo'))            ? session('maximo')            : old('maximo');
            $minimov           = !empty(session('minimov'))           ? session('minimov')           : old('minimov');
            $maximov            = !empty(session('maximov'))           ? session('maximov')           : old('maximov');
            $minimoy           = !empty(session('minimoy'))           ? session('minimoy')           : old('minimoy');
            $maximoy           = !empty(session('maximoy'))           ? session('maximoy')           : old('maximoy');
            $intervalo         = !empty(session('intervalo'))         ? session('intervalo')         : old('intervalo');
            $cor               = !empty(session('cor'))               ? session('cor')               : old('cor');
            $alerta            = !empty(session('alerta'))            ? session('alerta')            : old('alerta');
            $unidade_medida_id = !empty(session('unidade_medida_id')) ? session('unidade_medida_id') : old('unidade_medida_id');
        @endphp
        <form action="{{ route('admin.coletas_parametros_tipos.to_store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="descricao">Nome:</label>
                <input type="text" name="descricao" placeholder="Nome" class="form-control" value="{{ $descricao }}">
            </div>
            <div class="form-group">
                <label for="sigla">Sigla:</label>
                <input type="text" name="sigla" placeholder="Sigla" class="form-control" value="{{ $sigla }}">
            </div>
            <div class="form-group">
                <label for="unidade_medida_id">Unidade de Medida:</label>
                <div class="input-group">
                    <select name="unidade_medida_id" class="form-control">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($unidades_medidas as $unidade_medida)
                            <option value="{{ $unidade_medida->id }}" {{ ($unidade_medida->id == $unidade_medida_id) ? 'selected' : '' }}>{{ $unidade_medida->nome }}</option>
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
                <label for="minimo">Valor mínimo padrão para coleta:</label>
                <input type="number" step="any" name="minimo" placeholder="Mínimo para coleta" class="form-control" value="{{ $minimo }}">
            </div>
            <div class="form-group"> 
                <label for="maximo">Valor máximo padrão para coleta:</label>
                <input type="number" step="any" name="maximo" placeholder="Máximo para coleta" class="form-control" value="{{ $maximo }}">
            </div>
            <div class="form-group">    
                <label for="minimov">Valor mínimo para coleta:</label>
                <input type="number" step="any" name="minimov" placeholder="Mínimo para coleta" class="form-control" value="{{ $minimo }}">
            </div>
            <div class="form-group"> 
                <label for="maximov">Valor máximo para coleta:</label>
                <input type="number" step="any" name="maximov" placeholder="Máximo para coleta" class="form-control" value="{{ $maximo }}">
            </div>
            <div class="form-group">
                <input type="checkbox" name="alerta" class="form-control" value="{{ $alerta }}">
                <label for="alerta">Alertar variação excessiva</label>
            </div>
            <hr>
            <h4>Definições do gráfico</h4>
            <div class="form-group">
                <div class="row"> 
                    <div class="col-sm-3">   
                        <label for="minimoy">Valor mínimo para o eixo Y:</label>
                        <input type="number" step="any" name="minimoy" placeholder="Mínimo para o eixo Y" class="form-control" value="{{ $minimoy }}">
                    </div>
                    <div class="col-sm-3"> 
                        <label for="maximoy">Valor máximo para o eixo Y:</label>
                        <input type="number" step="any" name="maximoy" placeholder="Máximo para o eixo Y" class="form-control" value="{{ $maximo }}">
                    </div>
                    <div class="col-sm-3">
                        <label for="intervalo">Intervalo entre linhas (Escala):</label>
                        <input type="number" step="any" name="intervalo" placeholder="Intervalo entre linhas" class="form-control" value="{{ $intervalo }}">
                    </div>
                    <div class="col-sm-3">
                        <label for="cor">Cor do eixo:</label>
                        <input type="text" step="any" name="cor" placeholder="Cor do eixo" class="form-control jscolor" data-jscolor="{hash:true}" value="{{ $cor }}">
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.coletas_parametros_tipos') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection