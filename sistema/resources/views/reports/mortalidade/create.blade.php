@extends('adminlte::page')

@section('title', 'Relatório de ativação de probióticos e aditivos')

@section('content_header')
<h1>Relatório de Mortalidade</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Relatório de Mortalidade</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')


<div class="box">
    <div class="box-header">
        <h4 class="box-title">Escolha o Intervalo de Datas</h4>  
    </div>
    <div class="box-body">
        
        @php
            $data_inicial = !empty(session('data_inicial')) ? session('data_inicial') : old('data_inicial');
            $data_final   = !empty(session('data_final'))   ? session('data_final')   : old('data_final');
        @endphp
        <form action="{{ route('admin.mortalidade.to_view') }}" method="POST" target="_blank">
            {!! csrf_field() !!}
            
            <div class="form-group">
                <label for="data_inicial">Data Inicial:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicial" placeholder="Data Inicial da Aplicação" class="form-control pull-right" id="date_picker" value="{{ $data_inicial }}">
                </div>
            </div>
            <div class="form-group">
                <label for="data_final">Data Final:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_final" placeholder="Data Final da Aplicação" class="form-control pull-right" id="date_picker" value="{{ $data_final }}">
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Gerar relatório">
            </div>
        </form>
    </div>
</div>
@endsection