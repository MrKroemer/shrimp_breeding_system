@extends('adminlte::page')

@section('title', 'Registro de baixas justificadas')

@section('content_header')
<h1>Cadastro de baixas justificadas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Baixas justificadas</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.baixas_justificadas.to_update', ['id' => $baixa_justificada->id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_movimento">Data do movimento:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_movimento" placeholder="Data do movimento" class="form-control pull-right" id="date_picker" value="{{ $baixa_justificada->data_movimento() }}" {{ $baixa_justificada->situacao == 'V' ? 'disabled' : '' }}>
                </div>
            </div>
            <div class="form-group" style="width:100%;">
                <label for="descricao">Justificativa da saída:</label>
                <textarea rows="2" name="descricao" placeholder="Informe uma justificativa para a saída." class="form-control" style="width:100%;" {{ $baixa_justificada->situacao == 'V' ? 'disabled' : '' }}>{{ $baixa_justificada->descricao }}</textarea>
            </div>
            <div class="form-group">
                @if ($baixa_justificada->situacao != 'V')
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save" aria-hidden="true"></i> Salvar
                    </button>
                @endif
                <a href="{{ route('admin.baixas_justificadas') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
