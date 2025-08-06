@extends('adminlte::page')

@section('title', 'Registro de referenciais de alimentações')

@section('content_header')
<h1>Cadastro de referenciais de alimentações</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Períodos climáticos</a></li>
    <li><a href="">Referenciais de alimentações</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $dias_cultivo = !empty(session('dias_cultivo')) ? session('dias_cultivo') : old('dias_cultivo');
            $peso_medio   = !empty(session('peso_medio'))   ? session('peso_medio')   : old('peso_medio');
            $porcentagem  = !empty(session('porcentagem'))  ? session('porcentagem')  : old('porcentagem');
            $crescimento  = !empty(session('crescimento'))  ? session('crescimento')  : old('crescimento');
            $observacoes  = !empty(session('observacoes'))  ? session('observacoes')  : old('observacoes');
        @endphp
        <form action="{{ route('admin.arracoamentos_climas.arracoamentos_referenciais.to_store', ['arracoamento_clima_id' => $arracoamento_clima_id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label>Período climático:</label>
                <h5 class="box-title">{{ $arracoamento_clima->nome }} ( {{ $arracoamento_clima->descricao }} )</h5>
            </div>
            <div class="form-group">
                <label for="dias_cultivo">Dias de cultivo:</label>
                <input type="number" step="any" name="dias_cultivo" placeholder="Dias de cultivo (referência)" class="form-control" value="{{ $dias_cultivo }}">
            </div>
            <div class="form-group">
                <label for="peso_medio">Peso médio do animal (g):</label>
                <input type="number" step="any" name="peso_medio" placeholder="Peso médio (referência)" class="form-control" value="{{ $peso_medio }}">
            </div>
            <div class="form-group">
                <label for="porcentagem">Porcentagem de ração por biomassa:</label>
                <input type="number" step="any" name="porcentagem" placeholder="Porcentagem de ração por biomassa" class="form-control" value="{{ $porcentagem }}">
            </div>
            <div class="form-group">
                <label for="crescimento">Crescimento diário estimado (g):</label>
                <input type="number" step="any" name="crescimento" placeholder="Crescimento diário estimado" class="form-control" value="{{ $crescimento }}">
            </div>
            <div class="form-group">
                <label for="observacoes">Observações:</label>
                <textarea rows="3" name="observacoes" placeholder="Observações" class="form-control">{{ $observacoes }}</textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.arracoamentos_climas.arracoamentos_referenciais', ['arracoamento_clima_id' => $arracoamento_clima_id]) }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection