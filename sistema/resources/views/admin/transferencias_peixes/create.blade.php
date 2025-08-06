@extends('adminlte::page')

@section('title', 'Registro de transferência de peixes')

@section('content_header')
<h1>Cadastro de transferência de peixes</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Transferência de peixes</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $data_movimento = ! empty(session('data_movimento')) ? session('data_movimento') : old('data_movimento'); 
            $lote_peixes_id = ! empty(session('lote_peixes_id')) ? session('lote_peixes_id') : old('lote_peixes_id');
            $tanque_id      = ! empty(session('tanque_id'))      ? session('tanque_id')      : old('tanque_id');
            $quantidade     = ! empty(session('quantidade'))     ? session('quantidade')     : old('quantidade');
            $observacoes    = ! empty(session('observacoes'))    ? session('observacoes')    : old('observacoes');
        @endphp
        <form action="{{ route('admin.transferencias_peixes.to_store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_movimento">Data da transferência:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_movimento" placeholder="Data da transferência" class="form-control pull-right" id="date_picker" value="{{ $data_movimento }}">
                </div>
            </div>
            <div class="form-group">
                <label for="lote_peixes_id">Lote:</label>
                <select name="lote_peixes_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($lotes as $lote)
                        <option value="{{ $lote->id }}" {{ $lote->id == $lote_peixes_id ? 'selected' : '' }}>{{ $lote->codigo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="tanque_id">Tanque:</label>
                <select name="tanque_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($tanques as $tanque)
                        <option value="{{ $tanque->id }}" {{ $tanque->id == $tanque_id ? 'selected' : '' }}>{{ $tanque->sigla }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade à transferir:</label>
                <input type="numeric" step="any" name="quantidade" placeholder="Quantidade à transferir" class="form-control" value="{{ $quantidade }}">
            </div>
            <div class="form-group">
                <label for="observacoes">Observações:</label>
                <textarea rows="3" name="observacoes" placeholder="Observações" class="form-control">{{ $observacoes }}</textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.transferencias_peixes') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection