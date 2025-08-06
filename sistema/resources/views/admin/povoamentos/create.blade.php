@extends('adminlte::page')

@section('title', 'Registro de povoamentos de tanques')

@section('content_header')
<h1>Cadastro de povoamentos de tanques</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Povoamentos de tanques</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $ciclo_id    = !empty(session('ciclo_id'))    ? session('ciclo_id')    : old('ciclo_id');
            $data_inicio = !empty(session('data_inicio')) ? session('data_inicio') : old('data_inicio');
            $data_fim    = !empty(session('data_fim'))    ? session('data_fim')    : old('data_fim');
            // $lote_id     = !empty(session('lote_id'))     ? session('lote_id')     : old('lote_id');
        @endphp
        <form action="{{ route('admin.povoamentos.to_store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="ciclo_id">Ciclo:</label>
                <select name="ciclo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($ciclos as $ciclo)
                        <option value="{{ $ciclo->id }}" {{ ($ciclo->id == $ciclo_id) ? 'selected' : '' }}>{{ $ciclo->tanque->sigla }} ( Ciclo Nº {{ $ciclo->numero }} iniciado em {{ $ciclo->data_inicio() }} )</option>
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
            {{-- <div class="form-group">
                <label for="lote_id">Lote de pós-larvas:</label>
                <div class="input-group">
                    <select name="lote_id" class="form-control">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($lotes as $lote)
                            <option value="{{ $lote->id }}" {{ ($lote->id == $lote_id) ? 'selected' : '' }}>{{ (float) $lote->quantidade }} UND ( Nº {{ $lote->lote_fabricacao }} | ID: {{ $lote->id }} ) {{ is_null($lote->lote_biometria) ? '[ BIOMETRIA NÃO INFORMADA ]' : '' }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <a href="{{ route('admin.povoamentos.redirect_to.lotes.to_create') }}" class="btn btn-success btn-flat">
                            <i class="fa fa-plus" aria-hidden="true"></i> Lotes de PL's
                        </a>
                    </span>
                </div>
            </div> --}}

                   
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.povoamentos') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection