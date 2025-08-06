@extends('adminlte::page')

@section('title', 'Registro de coletas de parametros')

@section('content_header')
<h1>Cadastro de coletas de parâmetros</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Listagem de coletas de parâmetros</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $data_coleta              = !empty(session('data_coleta'))              ? session('data_coleta')              : old('data_coleta');
            $tanque_tipo_id           = !empty(session('tanque_tipo_id'))           ? session('tanque_tipo_id')           : old('tanque_tipo_id');
            $coleta_parametro_tipo_id = !empty(session('coleta_parametro_tipo_id')) ? session('coleta_parametro_tipo_id') : old('coleta_parametro_tipo_id');
            $sonda_laboratorial_id    = !empty(session('sonda_laboratorial_id'))    ? session('sonda_laboratorial_id')    : old('sonda_laboratorial_id');
        @endphp
        <form action="{{ route('admin.coletas_parametros_new.to_store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_coleta">Data da coleta:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_coleta" placeholder="Data da coleta" class="form-control pull-right" id="date_picker" value="{{ $data_coleta }}">
                </div>
            </div>
            <div class="form-group">
                <label for="tanque_tipo_id">Tipo de tanque:</label>
                <select name="tanque_tipo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($tanques_tipos as $tanque_tipo)
                        <option value="{{ $tanque_tipo->id }}" {{ ($tanque_tipo->id == $tanque_tipo_id) ? 'selected' : '' }}>{{ $tanque_tipo->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="coleta_parametro_tipo_id">Parâmetro:</label>
                <select name="coleta_parametro_tipo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($coletas_parametros_tipos as $coleta_parametro_tipo)
                        <option value="{{ $coleta_parametro_tipo->id }}" {{ ($coleta_parametro_tipo->id == $coleta_parametro_tipo_id) ? 'selected' : '' }}>{{ $coleta_parametro_tipo->descricao }} ({{ $coleta_parametro_tipo->sigla }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="sonda_laboratorial_id">Sonda Laboratorial:</label>
                <select name="sonda_laboratorial_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($sondas_laboratorias as $sonda_laboratorial)
                        <option value="{{ $sonda_laboratorial->id }}" {{ ($sonda_laboratorial->id == $sonda_laboratorial_id) ? 'selected' : '' }}>{{ $sonda_laboratorial->marca }} ({{ $sonda_laboratorial->nome }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.coletas_parametros_new') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection