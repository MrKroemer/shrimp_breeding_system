@extends('adminlte::page')

@section('title', 'Registro de Fatores de conversão para Sondas Laboratoriais')

@section('content_header')
<h1>Cadastro de Fatores de Conversão de Sondas Laboratorias</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Fatores de Conversão de Sondas Laboratorias</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $fator                        = session('fator')                        ?: old('fator');
            $sonda_laboratorial_id        = session('sonda_laboratorial_id')        ?: old('sonda_laboratorial_id');
            $coleta_parametro_tipo_id     = session('coleta_parametro_tipo_id')     ?: old('coleta_parametro_tipo_id');
        @endphp
        <form action="{{ route('admin.sondas_fatores.to_store') }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="sonda_laboratorial_id">Sondas:</label>
                <select name="sonda_laboratorial_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($sondas_laboratoriais as $sonda_laboratorial)
                        <option value="{{ $sonda_laboratorial->id }}" {{ ($sonda_laboratorial->id == $sonda_laboratorial_id) ? 'selected' : '' }}>{{ $sonda_laboratorial->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="coleta_parametro_tipo_id">Paramêtro:</label>
                <select name="coleta_parametro_tipo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($coletas_parametros_tipos as $coleta_parametro_tipo)
                        <option value="{{ $coleta_parametro_tipo->id }}" {{ ($coleta_parametro_tipo->id == $coleta_parametro_tipo_id) ? 'selected' : '' }}>{{ $coleta_parametro_tipo->sigla }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="fator">Fator:</label>
                <input type="text" name="fator" placeholder="fator" class="form-control" value="{{ $fator }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.sondas_fatores') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection