@extends('adminlte::page')

@section('title', 'Fichas de aplicações de insumos')

@section('content_header')
<h1>Relatório</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Relatório de análises de Água</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $data_aplicacao = !empty(session('data_aplicacao')) ? session('data_aplicacao') : old('data_aplicacao');
            $setor_id       = !empty(session('setor_id'))       ? session('setor_id')       : old('setor_id');
        @endphp
        <form action="{{ route('admin.resumo_analises_agua.to_view') }}" method="POST" target="_blank">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_aplicacao">Data da aplicação:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_aplicacao" placeholder="Data da aplicação" class="form-control pull-right" id="date_picker" value="{{ $data_aplicacao }}">
                </div>
            </div>
            <div class="form-group">
                <label for="tanque_tipo_id">Tipo de tanque:</label>
                <select name="tanque_tipo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($tanques_tipos as $tanque_tipo)
                        <option value="{{ $tanque_tipo->id }}">{{ $tanque_tipo->nome }}</option>
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
