@extends('adminlte::page')

@section('title', 'Registro de preparações de cultivos')

@section('content_header')
<h1>Cadastro de preparações de cultivos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Preparações de cultivos</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $bandejas    = !empty(session('bandejas'))    ? session('bandejas')    : old('bandejas');
            $ph_solo     = !empty(session('ph_solo'))     ? session('ph_solo')     : old('ph_solo');
            $data_inicio = !empty(session('data_inicio')) ? session('data_inicio') : old('data_inicio');
            $observacoes = !empty(session('observacoes')) ? session('observacoes') : old('observacoes');
            $ciclo_id    = !empty(session('ciclo_id'))    ? session('ciclo_id')    : old('ciclo_id');
        @endphp
        <form action="{{ route('admin.preparacoes_v2.to_store') }}" method="POST">
            {!! csrf_field() !!}   
            <div class="form-group">
                <label for="ciclo_id">Ciclo:</label>
                <select name="ciclo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($ciclos as $ciclo)
                        <option value="{{ $ciclo->id }}" {{ ($ciclo->id == $ciclo_id) ? 'selected' : '' }}>{{ $ciclo->tanque->sigla }} ( Ciclo Nº {{ $ciclo->numero }} iniciado em {{ $ciclo->data_inicio() }} )</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="data_inicio">Data de início:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicio" placeholder="Data de início" class="form-control pull-right" id="datetime_picker" value="{{ $data_inicio }}">
                </div>
            </div>
            <div class="form-group">
                <label for="bandejas">Bandejas:</label>
                <input type="number" step="any" name="bandejas" placeholder="Bandejas" class="form-control" value="{{ $bandejas }}">
            </div>
            <div class="form-group">
                <label for="ph_solo">pH do solo:</label>
                <input type="number" step="any" name="ph_solo" placeholder="pH do solo" class="form-control" value="{{ $ph_solo }}">
            </div>
            <div class="form-group">
                <label for="observacoes">Observações:</label>
                <textarea rows="3" name="observacoes" placeholder="Observações" class="form-control">{{ $observacoes }}</textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.preparacoes_v2') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection