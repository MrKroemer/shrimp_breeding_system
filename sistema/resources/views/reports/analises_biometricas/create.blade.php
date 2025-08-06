@extends('adminlte::page')

@section('title', 'Relatório de análises biométricas')

@section('content_header')
<h1>Relatório de análises biométricas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Relatório de análises biométricas</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $data_solicitacao = !empty(session('data_solicitacao')) ? session('data_solicitacao') : old('data_solicitacao');
        @endphp
        <form action="{{ route('admin.resumo_analises_biometricas.to_view') }}" method="POST" target="_blank">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_solicitacao">Data da solicitação:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_solicitacao" placeholder="Data da solicitação" class="form-control pull-right" id="date_picker" value="{{ $data_solicitacao }}">
                </div>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Gerar relatório">
            </div>
        </form>
    </div>
</div>
@endsection
