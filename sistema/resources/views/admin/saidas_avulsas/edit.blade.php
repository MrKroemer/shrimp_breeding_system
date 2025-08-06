@extends('adminlte::page')

@section('title', 'Registro de saídas avulsas')

@section('content_header')
<h1>Cadastro de saídas avulsas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Saídas avulsas</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.saidas_avulsas.to_update', ['id' => $saida_avulsa->id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="tanque_id">Tanque:</label>
                <select name="tanque_id" class="form-control" disabled>
                    <option value="{{ $saida_avulsa->tanque_id }}" selected>{{ $saida_avulsa->tanque->sigla }} ( {{ $saida_avulsa->tanque->tanque_tipo->nome }} )</option>
                </select>
            </div>
            <div class="form-group">
                <label for="data_movimento">Data do movimento:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_movimento" placeholder="Data do movimento" class="form-control pull-right" id="date_picker" value="{{ $saida_avulsa->data_movimento() }}" {{ $saida_avulsa->situacao == 'V' ? 'disabled' : '' }}>
                </div>
            </div>
            <div class="form-group" style="width:100%;">
                <label for="descricao">Justificativa da saída:</label>
                <textarea rows="2" name="descricao" placeholder="Informe uma justificativa para a saída." class="form-control" style="width:100%;" {{ $saida_avulsa->situacao == 'V' ? 'disabled' : '' }}>{{ $saida_avulsa->descricao }}</textarea>
            </div>
            <div class="form-group">
                @if ($saida_avulsa->situacao != 'V')
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save" aria-hidden="true"></i> Salvar
                    </button>
                @endif
                <a href="{{ route('admin.saidas_avulsas') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
