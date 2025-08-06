@extends('adminlte::page')

@section('title', 'Relatório de consumo de produtos')

@section('content_header')
<h1>Relatório de consumo de produtos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Relatório de consumo de produtos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $data_inicial = !empty(session('data_inicial')) ? session('data_inicial') : old('data_inicial');
            $data_final   = !empty(session('data_final'))   ? session('data_final')   : old('data_final');
        @endphp
        <form action="{{ route('admin.consumo_produtos.to_view') }}" method="POST" target="_blank">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_inicial">Data inicial:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicial" placeholder="Data inicial" class="form-control pull-right" id="date_picker" value="{{ $data_inicial }}">
                </div>
            </div>
            <div class="form-group">
                <label for="data_final">Data final:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_final" placeholder="Data final" class="form-control pull-right" id="date_picker" value="{{ $data_final }}">
                </div>
            </div>
            <div class="form-group">
                <label for="ciclos[]">Tanques:</label>
                <select name="ciclos[]" class="select2_multiple" multiple="multiple">
                    @foreach ($ciclos as $ciclo)
                        <option value="{{ $ciclo->id }}">{{ $ciclo->tanque->sigla }} (Ciclo Nº {{ $ciclo->numero }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="produtos[]">Produtos:</label>
                <select name="produtos[]" class="select2_multiple" multiple="multiple">
                    @foreach ($produtos as $produto)
                        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Gerar relatório">
            </div>
        </form>
    </div>
</div>
@endsection