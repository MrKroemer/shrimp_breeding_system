@extends('adminlte::page')

@section('title', 'Registro de lotes de peixes')

@section('content_header')
<h1>Cadastro de lotes de peixes</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Lotes de peixes</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.especies.create')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $codigo      = !empty(session('codigo'))      ? session('codigo')      : old('codigo');
            $data_inicio = !empty(session('data_inicio')) ? session('data_inicio') : old('data_inicio');
            $data_fim    = !empty(session('data_fim'))    ? session('data_fim')    : old('data_fim');
            $especie_id  = !empty(session('especie_id'))  ? session('especie_id')  : old('especie_id');
        @endphp
        <form action="{{ route('admin.lotes_peixes.to_store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_inicio">Data de início:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicio" placeholder="Data de início" class="form-control pull-right" id="date_picker" value="{{ $data_inicio }}">
                </div>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de lote:</label>
                <select name="tipo" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach ($tipos as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="codigo">Código do lote:</label>
                <input type="text" name="codigo" placeholder="Código do lote" class="form-control" value="{{ $codigo }}">
            </div>
            <div class="form-group">
                <label for="especie_id">Espécie:</label>
                <div class="input-group">
                    <select name="especie_id" class="form-control">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($especies as $especie)
                            <option value="{{ $especie->id }}" {{ ($especie->id == $especie_id) ? 'selected' : '' }}>{{ $especie->nome_cientifico }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <a class="btn btn-success btn-flat" data-toggle="modal" data-target="#especies_modal">
                            <i class="fa fa-plus" aria-hidden="true"></i> Criar novo
                        </a>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.lotes_peixes') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection