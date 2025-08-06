@extends('adminlte::page')

@section('title', 'Registro de recriação de peixes')

@section('content_header')
<h1>Listagem de recriação de peixes</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Recriação de peixes</a></li>
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
            $codigo      = !empty(session('codigo'))      ? session('codigo')      : old('codigo');
        @endphp
        <form action="{{ route('admin.recriacao_peixes.to_store') }}" method="POST">
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
                <label for="tanque_id">Tanque:</label>
                <select name="tanque_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($tanques as $tanque)
                        <option value="{{ $tanque->id }}" {{ $tanque->id == $tanque_id ? 'selected' : '' }}>{{ $tanque->sigla }} ( {{ $tanque->tanque_tipo->nome }} )</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="codigo">Código de recriação:</label>
                <input type="text" name="codigo" placeholder="Código" class="form-control" value="{{ $codigo }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.recriacao_peixes') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection