@extends('adminlte::page')

@section('title', 'Registro de análises cálcicas')

@section('content_header')
<h1>Edição de análises cálcicas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Análises cálcicas</a></li>
    <li><a href="">Edição</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.analises_calcicas.to_update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
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
                <label for="tanque_tipo_id">Tipo de tanque:</label>
                <select name="tanque_tipo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($tanques_tipos as $tanque_tipo)
                        <option value="{{ $tanque_tipo->id }}" {{ ($tanque_tipo->id == $tanque_tipo_id) ? 'selected' : '' }}>{{ $tanque_tipo->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="analise_laboratorial_tipo_id">Tipo de análise:</label>
                <select name="analise_laboratorial_tipo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($analises_laboratoriais_tipos as $analise_laboratorial_tipo)
                        <option value="{{ $analise_laboratorial_tipo->id }}" {{ ($analise_laboratorial_tipo->id == $analise_laboratorial_tipo_id) ? 'selected' : '' }}>{{ $analise_laboratorial_tipo->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.analises_calcicas') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection