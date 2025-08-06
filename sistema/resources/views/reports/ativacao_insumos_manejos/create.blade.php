@extends('adminlte::page')

@section('title', 'Relatório de ativação de insumos para manejo')

@section('content_header')
<h1>Relatório de ativação de insumos para manejo</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Relatório de ativação de insumos para manejo</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $data_aplicacao = !empty(session('data_aplicacao')) ? session('data_aplicacao') : old('data_aplicacao');
        @endphp
        <form action="{{ route('admin.ativacao_insumos_manejos.to_view') }}" method="POST" target="_blank">
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
                <label for="receita_laboratorial_id">Receita:</label>
                <select name="receita_laboratorial_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($receitas_laboratoriais as $receita_laboratorial)
                        <option value="{{ $receita_laboratorial->id }}">{{ $receita_laboratorial->nome }} (Und.: {{ $receita_laboratorial->unidade_medida->sigla }})</option>
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
