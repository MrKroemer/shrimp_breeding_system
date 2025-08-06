@extends('adminlte::page')

@section('title', 'Registro de preparações de cultivos')

@section('content_header')
<h1>Transferencias de Cultivo</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Transferencias de cultivos</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $ciclo_origem_id        = !empty(session('ciclo_origem_id '))       ? session('ciclo_origem_id ')       : old('ciclo_origem_id ');
            $ciclo_destino_id       = !empty(session('ciclo_destino_id '))      ? session('ciclo_destino_id ')      : old('ciclo_destino_id ');
            $quantidade_origem      = !empty(session('quantidade_origem'))      ? session('quantidade_origem')      : old('quantidade_origem');
            $quantidade_transferida = !empty(session('quantidade_transferida')) ? session('quantidade_transferida') : old('quantidade_transferida');
            $data_movimento         = !empty(session('data_movimento'))         ? session('data_movimento')         : old('data_movimento'); 
            $observacoes            = !empty(session('observacoes'))            ? session('observacoes')            : old('observacoes');
        @endphp
        <form action="{{ route('admin.transferencias.to_store') }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}   
            <div class="row">
                <div class="col-xs-6">
                    <label for="ciclo_origem_id">Ciclo Origem:</label>
                    <select name="ciclo_origem_id" class="form-control" onchange="habilitaQuantidade();onChangeTipoCiclosTransferencia('{{ url('admin') }}', ['ciclo_origem_id', 'ciclo_destino_id', 'quantidade_origem'])">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($tanques_origem as $ciclo)
                            <option value="{{ $ciclo->ciclo_id }}" >{{ $ciclo->sigla }} ( Ciclo Nº {{ $ciclo->numero }} iniciado em {{ $ciclo->data_inicio }} )</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xs-6">
                    <label for="ciclo_destino_id">Ciclo Destino:</label>
                    <select name="ciclo_destino_id" class="form-control" onchange="habilitaQuantidade()">
                        <option value="">..:: Selecione ::..</option>
                    </select>
                </div>
            </div>
            <div class="row">

                <div class="col-xs-6">
                    <label for="quantidade_origem">Quantidade Tanque de Origem:</label>
                    <input type="text" step="any" name="quantidade_origem" placeholder="Quantidade Origem" class="form-control" value="{{ $quantidade_origem }}" disabled>
                </div>

                <div class="col-xs-6">
                    <label for="quantidade">Quantidade Transferida:</label>
                    <input type="text" onkeyup="validaTransferencia('quantidade_origem','quantidade');" step="any" name="quantidade" placeholder="Quantidade Transferida" class="form-control" value="{{ $quantidade_transferida }}" disabled>
                    <input type="hidden" name="proporcao" id="proporcao" />
                </div>

            </div>
            <div class="row">   
                <div class="form-group col-xs-12">
                    <label for="data_movimento">Data Transferência:</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" name="data_movimento" placeholder="Data Transferência" class="form-control pull-right" id="date_picker" value="{{ $data_movimento }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="observacoes">Observações:</label>
                <textarea rows="4" name="observacoes" placeholder="Observações" class="form-control">{{ $observacoes }}</textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.preparacoes') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection