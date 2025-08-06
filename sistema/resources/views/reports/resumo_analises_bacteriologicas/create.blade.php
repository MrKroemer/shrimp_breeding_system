@extends('adminlte::page')

@section('title', 'Relatório de Bacteriologia')

@section('content_header')
<h1>Relatório</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Relatório de análises Bacteriologicas</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $data_analise                  = !empty(session('data_analise'))                   ? session('data_analise')                    : old('data_analise');
            $setor_id                      = !empty(session('setor_id'))                       ? session('setor_id')                        : old('setor_id');
            $analise_laboratorial_tipo_id  = !empty(session('analise_laboratorial_tipo_id '))  ? session('analise_laboratorial_tipo_id ')   : old('analise_laboratorial_tipo_id ');
        @endphp
        <form action="{{ route('admin.resumo_analises_bacteriologicas.to_view') }}" method="POST" target="_blank">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_analise">Data da Análise:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_analise" placeholder="Data das análises" class="form-control pull-right" id="date_picker" value="{{ $data_analise }}">
                </div>
            </div>
            <div class="form-group">
                <label for="analise_laboratorial">Tipo de Analise Bacteriologica:</label>
                <select name="analise_laboratorial" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($analises as $analise)
                        <option value="{{ $analise->id }},{{ $analise->nome }}" {{ ($analise->id == $analise_laboratorial_tipo_id ) ? 'selected' : '' }}>{{ $analise->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="setor_id">Setor:</label>
                <select name="setor_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($setores as $setor)
                        <option value="{{ $setor->id }}" {{ ($setor->id == $setor_id) ? 'selected' : '' }}>{{ $setor->nome }}</option>
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
