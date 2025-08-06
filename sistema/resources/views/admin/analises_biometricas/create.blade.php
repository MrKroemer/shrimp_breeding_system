@extends('adminlte::page')

@section('title', 'Registro de analises biométricas')

@section('content_header')
<h1>Cadastro de analises biométricas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Analises biométricas</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $data_analise   = ! empty(session('data_analise'))   ? session('data_analise')   : old('data_analise');
            $ciclo_id       = ! empty(session('ciclo_id'))       ? session('ciclo_id')       : old('ciclo_id');
            $total_animais  = ! empty(session('total_animais'))  ? session('total_animais')  : old('total_animais');
            $peso_total     = ! empty(session('peso_total'))     ? session('peso_total')     : old('peso_total');
            $peso_medio     = ! empty(session('peso_medio'))     ? session('peso_medio')     : old('peso_medio');
            $sobrevivencia  = ! empty(session('sobrevivencia'))  ? session('sobrevivencia')  : old('sobrevivencia');
        @endphp
        <form action="{{ route('admin.analises_biometricas.to_store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_analise">Data da análise:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_analise" placeholder="Data da análise" class="form-control pull-right" id="date_picker" value="{{ $data_analise }}">
                </div>
            </div>
            <div class="form-group">
                <label for="ciclo_id">Ciclo:</label>
                <select name="ciclo_id" onchange="onChangeBioTipoCultivo();" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($ciclos as $ciclo)
                        <option value="{{ $ciclo->ciclo_id }}" {{ ($ciclo->ciclo_id == $ciclo_id) ? 'selected' : '' }}>{{ $ciclo->tanque_sigla }} ( Ciclo Nº {{ $ciclo->ciclo_numero }} )</option>
                    @endforeach
                </select>
            </div>
            <hr>
            <h4>Informações iniciais</h4>
            <div class="form-group" id="total_animais">
                <label for="total_animais">Total de animais:</label>
                <input type="number" step="any" name="total_animais" placeholder="Total de animais" class="form-control" value="{{ $total_animais }}" oninput="onChangePesoTotal();">
            </div>
            <div class="form-group">
                <label for="peso_total">Peso total (g):</label>
                <input type="number" step="any" name="peso_total" placeholder="Peso total" class="form-control" value="{{ $peso_total }}" oninput="onChangePesoTotal();">
            </div>
            <div class="form-group">
                <label for="peso_medio">Peso médio (g):</label>
                <input type="number" step="any" name="peso_medio" placeholder="Peso médio" class="form-control" value="{{ $peso_medio }}">
            </div>
            <div class="form-group" id="sobrevivencia">
                <label for="sobrevivencia">Percentual de sobrevivência:</label>
                <input type="number" step="any" name="sobrevivencia" placeholder="Percentual de sobrevivência" class="form-control" value="{{ $sobrevivencia }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.analises_biometricas') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>

@foreach($ciclos as $ciclo)
    <input type="hidden" name="{{ $ciclo->ciclo_id }}" id="{{ $ciclo->ciclo_id }}" value="{{ $ciclo->ciclo_tipo }}">
@endforeach

@endsection