@extends('adminlte::page')

@section('title', 'Registro de ciclos de cultivo')

@section('content_header')
<h1>Cadastro de ciclos de cultivo</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Ciclos de cultivo</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $data_inicio = !empty(session('data_inicio')) ? session('data_inicio') : old('data_inicio');
            $tanque_id   = !empty(session('tanque_id'))   ? session('tanque_id')   : old('tanque_id');
        @endphp
        <form action="{{ route('admin.ciclos.to_store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_inicio">Data de Início:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicio" placeholder="Data de início" class="form-control pull-right" id="date_picker" value="{{ $data_inicio }}">
                </div>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de Cultivo:</label>
                <select name="tipo" class="form-control" onchange="onChangeTipoCultivo('{{ url('admin') }}', ['tipo', 'tanque_id', 'numero'])">
                    <option value="">..:: Selecione ::..</option>
                    @foreach ($tipos as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="tanque_id">Tanque:</label>
                <select name="tanque_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                </select>
            </div>
            <div class="form-group">
                <label for="numero">Nº do Ciclo:</label>
                <input type="number" step="any" name="numero" placeholder="Nº do ciclo" class="form-control" {{-- value="{{ $numero }}" --}}>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.ciclos') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection