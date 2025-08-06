@extends('adminlte::page')

@section('title', 'Registro de transferência de animais')

@section('content_header')
<h1>Cadastro de transferência de animais</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Transferência de animais</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $ciclo_origem_id   = ! empty(session('ciclo_origem_id'))  ? session('ciclo_origem_id')  : old('ciclo_origem_id');
            $ciclo_destino_id  = ! empty(session('ciclo_destino_id')) ? session('ciclo_destino_id') : old('ciclo_destino_id');
            $qtd_disponivel    = ! empty(session('qtd_disponivel'))   ? session('qtd_disponivel')   : old('qtd_disponivel');
            $quantidade        = ! empty(session('quantidade'))       ? session('quantidade')       : old('quantidade');
            $data_movimento    = ! empty(session('data_movimento'))   ? session('data_movimento')   : old('data_movimento'); 
            $observacoes       = ! empty(session('observacoes'))      ? session('observacoes')      : old('observacoes');
        @endphp
        <form action="{{ route('admin.transferencias_animais.to_store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">   
                <div class="form-group col-xs-12">
                    <label for="data_movimento">Data da transferência:</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" name="data_movimento" placeholder="Data da transferência" class="form-control pull-right" id="date_picker" value="{{ $data_movimento }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <label for="ciclo_origem_id">Ciclo de origem:</label>
                    <select name="ciclo_origem_id" class="form-control" onchange="onChangeCicloOrigem('{{ url('admin') }}', ['ciclo_origem_id', 'ciclo_destino_id', 'qtd_disponivel', 'quantidade']);">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($ciclos as $ciclo)
                            <option value="{{ $ciclo->ciclo_id }}" {{ ($ciclo->ciclo_id == $ciclo_origem_id) ? 'selected' : '' }}>{{ $ciclo->tanque_sigla }} ( Ciclo Nº {{ $ciclo->ciclo_numero }} iniciado em {{ $ciclo->ciclo_inicio() }} ) {{ $ciclo->ciclo_tipo == 3 ? '[Reprodução]' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xs-6">
                    <label for="ciclo_destino_id">Ciclo de destino:</label>
                    <select name="ciclo_destino_id" class="form-control" onchange="enableQuantidadeCicloDestino(['ciclo_origem_id', 'ciclo_destino_id', 'quantidade']);">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($ciclos as $ciclo)
                            @if($ciclo->ciclo_tipo != 3 && $ciclo->ciclo_id != $ciclo_origem_id)
                                <option value="{{ $ciclo->ciclo_id }}" {{ ($ciclo->ciclo_id == $ciclo_destino_id) ? 'selected' : '' }}>{{ $ciclo->tanque_sigla }} ( Ciclo Nº {{ $ciclo->ciclo_numero }} iniciado em {{ $ciclo->ciclo_inicio() }} )</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <label for="qtd_disponivel">Quantidade disponível:</label>
                    <input type="numeric" step="any" name="qtd_disponivel" placeholder="Quantidade disponível" class="form-control" value="{{ $qtd_disponivel }}">
                </div>
                <div class="col-xs-6">
                    <label for="quantidade">Quantidade à transferir:</label>
                    <input type="numeric" step="any" name="quantidade" placeholder="Quantidade à transferir" class="form-control" value="{{ $quantidade }}" {{ is_null($ciclo_destino_id) ? 'disabled' : '' }}>
                </div>
            </div>
            <div class="form-group">
                <label for="observacoes">Observações:</label>
                <textarea rows="3" name="observacoes" placeholder="Observações" class="form-control">{{ $observacoes }}</textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.transferencias_animais') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection