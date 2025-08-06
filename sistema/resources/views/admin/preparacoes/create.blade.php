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
            $ciclo_id    = !empty(session('ciclo_id'))    ? session('ciclo_id')    : old('ciclo_id');
            $data_inicio = !empty(session('data_inicio')) ? session('data_inicio') : old('data_inicio');
            $data_fim    = !empty(session('data_fim'))    ? session('data_fim')    : old('data_fim'); 
            $abas_inicio = !empty(session('abas_inicio')) ? session('abas_inicio') : old('abas_inicio');
            $abas_fim    = !empty(session('abas_fim'))    ? session('abas_fim')    : old('abas_fim');
            $bandejas    = !empty(session('bandejas'))    ? session('bandejas')    : old('bandejas');
            $ph_solo     = !empty(session('ph_solo'))     ? session('ph_solo')     : old('ph_solo');
            $observacoes = !empty(session('observacoes')) ? session('observacoes') : old('observacoes');
        @endphp
        <form action="{{ route('admin.preparacoes.to_store') }}" method="post" enctype="multipart/form-data">
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
            <div class="row">
                <div class="form-group col-sm-3">
                    <label for="data_inicio">Início da preparação:</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" name="data_inicio" placeholder="Início da preparação" class="form-control pull-right" id="date_picker" value="{{ $data_inicio }}">
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label for="abas_inicio">Início do abastecimento:</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" name="abas_inicio" placeholder="Início do abastecimento" class="form-control pull-right" id="date_picker" value="{{ $abas_inicio }}">
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label for="abas_fim">Fim do abastecimento:</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" name="abas_fim" placeholder="Fim do abastecimento" class="form-control pull-right" id="date_picker" value="{{ $abas_fim }}">
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label for="data_fim">Fim da preparação:</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" name="data_fim" onfocus="alertaFinalizacao(this);" placeholder="Fim da preparação" class="form-control pull-right" id="date_picker" value="{{ $data_fim }}">
                    </div>
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
                <a href="{{ route('admin.preparacoes') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection