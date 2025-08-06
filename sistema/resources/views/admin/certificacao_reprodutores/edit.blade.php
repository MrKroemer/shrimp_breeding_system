@extends('adminlte::page')

@section('title', 'Registro de Certificação de análises')

@section('content_header')
<h1>Cadastro de certificação de reprodutores</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Certificação de Reprodutores</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
        <div class="box-header"></div>
        <div class="box-body">
            <form action="{{ route('admin.certificacao_reprodutores.to_update',['id' => $id]) }}" method="POST">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label for="data_analise">Data do Estresse:</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" name="data_estresse" placeholder="Data do Estresse" class="form-control pull-right" id="date_picker" value="{{ $data_estresse }}">
                    </div>
                </div> 
                <div class="form-group">
                    <label for="data_analise">Data da Coleta:</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                            </div>
                            <input type="text" name="data_coleta" placeholder="Data da Coleta" class="form-control pull-right" id="date_picker" value="{{ $data_coleta }}">
                        </div>
                </div>
                <div class="form-group">
                    <label for="data_analise">Data da Certificação:</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" name="data_certificacao" placeholder="Data da Certificação" class="form-control pull-right" id="date_picker" value="{{ $data_certificacao }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="plantel">Familia:</label>
                    <input type="text" name="familia" placeholder="Familia" class="form-control" value="{{ $familia }}">
                </div>
                <div class="form-group">
                    <label for="plantel">Plantel:</label>
                    <input type="text" name="plantel" placeholder="Plantel" class="form-control" value="{{ $plantel }}">
                </div>
                <div class="form-group">
                    <label for="numero_certificacao">Número Certificação:</label>
                    <input type="text" name="numero_certificacao" placeholder="Número Certificação" class="form-control" value="{{ $numero_certificacao }}">
                </div>
                <div class="form-group">
                    <label for="tanque_origem_id">Tanque de Origem:</label>
                    <select name="tanque_origem_id"  class="form-control">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($tanques as $tanque)
                            <option value="{{ $tanque->id }}" {{ ($tanque->id == $tanque_origem_id) ? 'selected' : '' }}>{{ $tanque->sigla }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="tanque_maturacao_id">Tanque de Maturação:</label>
                    <select name="tanque_maturacao_id"  class="form-control">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($tanques as $tanque)
                            <option value="{{ $tanque->id }}" {{ ($tanque->id == $tanque_maturacao_id) ? 'selected' : '' }}>{{ $tanque->sigla }} </option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 10px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save" aria-hidden="true"></i> Salvar
                    </button>
                    <a href="{{ route('admin.certificacao_reprodutores') }}" class="btn btn-success">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
