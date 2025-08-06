@extends('adminlte::page')

@section('title', 'Registro de saídas avulsas')

@section('content_header')
<h1>Validações de produtos de saídas avulsas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Saídas avulsas</a></li>
    <li><a href="">Validações</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        @php
            $data_inicial = isset($data_inicial) ? $data_inicial : date('d/m/Y');
            $data_final   = isset($data_final)   ? $data_final   : date('d/m/Y');
            $tanque_id    = isset($tanque_id)    ? $tanque_id    : 0;
        @endphp
        <div class="row">
            <form id="form_saidas_avulsas_validacoes" action="{{ route('admin.saidas_avulsas_validacoes', ['data_inicial' => $data_inicial, 'data_final' => $data_final]) }}" method="POST">
                {!! csrf_field() !!}
                <div class="col-lg-3">
                    <label for="data_aplicacao">Data inicial do intervalo</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" id="date_picker" name="data_inicial" placeholder="Data inicial do intervalo" class="form-control pull-right" value="{{ $data_inicial }}" onchange="submitForm('form_saidas_avulsas_validacoes');">
                    </div>
                </div>
                <div class="col-lg-3">
                    <label for="data_aplicacao">Data final do intervalo</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" id="date_picker" name="data_final" placeholder="Data final do intervalo" class="form-control pull-right" value="{{ $data_final }}" onchange="submitForm('form_saidas_avulsas_validacoes');">
                    </div>
                </div>
                <div class="col-lg-3">
                    <label for="data_aplicacao">Tanque</label>
                    <select name="tanque_id" class="form-control" onchange="submitForm('form_saidas_avulsas_validacoes');">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($tanques as $tanque)
                            <option value="{{ $tanque->id }}" {{ ($tanque->id == $tanque_id) ? 'selected' : '' }}>{{ $tanque->sigla }}</option>
                        @endforeach
                    </select>
                </div> 
            </form>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanque</th>
                    <th>Data do movimento</th>
                    <th>Usuário responsável</th>
                    <th>Realizado em</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($saidas_avulsas as $saida_avulsa)
                    <tr>
                        <td>{{ $saida_avulsa->id }}</td>
                        <td>{{ $saida_avulsa->tanque->sigla }}</td>
                        <td>{{ $saida_avulsa->data_movimento() }}</td>
                        <td>{{ $saida_avulsa->usuario->nome }}</td>
                        <td>{{ $saida_avulsa->alterado_em('d/m/Y H:i') }}</td>
                        <td>
                            <p style="font-weight:bold;color:{{ ($saida_avulsa->situacao != 'V') ? 'red' : 'green' }};">
                                @if (($saida_avulsa->saidas_avulsas_produtos->count() > 0)||($saida_avulsa->saidas_avulsas_receitas->count() > 0))
                                    {{ mb_strtoupper($saida_avulsa->situacao()) }}
                                @else
                                    ITENS NÃO DEFINIDOS
                                @endif
                            </p>
                        </td>
                        <td>
                            <a href="{{ route('admin.saidas_avulsas_validacoes.to_create', ['saida_avulsa_id' => $saida_avulsa->id, /* 'tanque_id' => $tanque_id, 'data_inicial' => $data_inicial, 'data_final' => $data_final */]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-check" aria-hidden="true"></i> Abrir validação
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $saidas_avulsas->appends($formData)->links() !!}
        @else
            {!! $saidas_avulsas->links() !!}
        @endif
    </div>
</div>
@endsection
