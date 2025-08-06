@extends('adminlte::page')

@section('title', 'Coleta de Parâmetros')

@section('content_header')
<h1>Cadastro de Coleta de Parâmetros</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Coleta de Parâmetros</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $data_coleta                  = !empty(session('data_coleta'))                  ? session('data_coleta')                  : old('data_coleta');
            $setor_id                     = !empty(session('setor_id'))                     ? session('setor_id')                     : old('setor_id');
            $parametro_id                 = !empty(session('parametro_id'))                 ? session('parametro_id')                 : old('parametro_id');
        @endphp
        <form action="{{ route('admin.coletas_parametros.to_store') }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_coleta">Data da coleta:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_coleta" placeholder="Data da Coleta" class="form-control pull-right" id="date_picker" value="{{ $data_coleta }}">
                </div>
            </div>
            <div class="form-group">
                <label for="setor_id">Setor:</label>
                <select name="setor_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($setores as $setor)
                        <option value="{{ $setor->id }}" {{ ($setor->id == $setor_id) ? 'selected' : '' }}>{{ $setor->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="coletas_parametros_tipos_id">Parâmetro:</label>
                <select name="coletas_parametros_tipos_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($parametros as $parametro)
                        <option value="{{ $parametro->id }}" {{ ($parametro->id == $parametro_id) ? 'selected' : '' }}>{{ $parametro->sigla }} - ({{ $parametro->descricao }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.coletas_parametros') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection