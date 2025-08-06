@extends('adminlte::page')

@section('title', 'Registro de despescas de tanques')

@section('content_header')
<h1>Cadastro de despescas de tanques</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Despescas de tanques</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $ciclo_id       = !empty(session('ciclo_id'))       ? session('ciclo_id')       : old('ciclo_id');
            $data_inicio    = !empty(session('data_inicio'))    ? session('data_inicio')    : old('data_inicio');
            $data_fim       = !empty(session('data_fim'))       ? session('data_fim')       : old('data_fim');
            $qtd_prevista   = !empty(session('qtd_prevista'))   ? session('qtd_prevista')   : old('qtd_prevista');
            $qtd_despescada = !empty(session('qtd_despescada')) ? session('qtd_despescada') : old('qtd_despescada');
            $peso_medio     = !empty(session('peso_medio'))     ? session('peso_medio')     : old('peso_medio');
            $tipo_despesca  = !empty(session('tipo_despesca'))  ? session('tipo_despesca')  : old('tipo_despesca');
            $observacoes    = !empty(session('observacoes'))    ? session('observacoes')    : old('observacoes');
        @endphp
        <form action="{{ route('admin.despescas.to_store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <span style="font-weight:bold;">(<span style="color:red;">Obs:</span> Para realizar uma despesca e encerrar o cultivo, caso não seja um descarte, o ciclo deve possuir no mínimo uma análise biométrica)</span>
            </div>
            <div class="form-group">
                <label for="ciclo_id">Ciclo:</label>
                <select name="ciclo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($ciclos as $ciclo)
                        {{-- @if($ciclo->analises_biometricas->count() > 0) --}}
                            <option value="{{ $ciclo->id }}" {{ ($ciclo->id == $ciclo_id) ? 'selected' : '' }}>{{ $ciclo->tanque->sigla }} ( Ciclo Nº {{ $ciclo->numero }} )</option>
                        {{-- @endif --}}
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
                <label for="data_fim">Data de fim:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_fim" placeholder="Data de fim" class="form-control pull-right" id="datetime_picker" value="{{ $data_fim }}">
                </div>
            </div>
            <div class="form-group">
                <label for="qtd_prevista">Quantidade prevista (Kg):</label>
                <input type="number" step="any" name="qtd_prevista" placeholder="Quantidade prevista" class="form-control" value="{{ $qtd_prevista }}">
            </div>
            <div class="form-group">
                <label for="qtd_despescada">Quantidade despescada (Kg):</label>
                <input type="number" step="any" name="qtd_despescada" placeholder="Quantidade despescada" class="form-control" value="{{ $qtd_despescada }}">
            </div>
            <div class="form-group">
                <label for="peso_medio">Peso médio (g):</label>
                <input type="number" step="any" name="peso_medio" placeholder="Peso médio" class="form-control" value="{{ $peso_medio }}">
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de despesca:</label>
                <input type="hidden" name="tipo_despesca">
                <ul class="list">
                    <li><input type="radio"  name="radio_button01" id="tipo_despesca01"> Despesca completa</li>
                    <li><input type="radio"  name="radio_button01" id="tipo_despesca02"> Despesca parcial</li>
                    <li><input type="radio"  name="radio_button01" id="tipo_despesca03"> Descarte pré povoamento</li>
                    <li><input type="radio"  name="radio_button01" id="tipo_despesca04"> Descarte pós povoamento</li>
                </ul>
            </div>       
            <div class="form-group">
                <label for="observacoes">Observações:</label>
                <textarea rows="3" name="observacoes" placeholder="Observações" class="form-control">{{ $observacoes }}</textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.despescas') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection