@extends('adminlte::page')

@section('title', 'Registro de baixas justificadas')

@section('content_header')
<h1>Validações de produtos de baixas justificadas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Baixas justificadas</a></li>
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
        @endphp
        <div class="row">
            <form id="form_baixas_justificadas_validacoes" action="{{ route('admin.baixas_justificadas_validacoes', ['data_inicial' => $data_inicial, 'data_final' => $data_final]) }}" method="POST">
                {!! csrf_field() !!}
                <div class="col-lg-3">
                    <label for="data_aplicacao">Data inicial do intervalo</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" id="date_picker" name="data_inicial" placeholder="Data inicial do intervalo" class="form-control pull-right" value="{{ $data_inicial }}" onchange="submitForm('form_baixas_justificadas_validacoes');">
                    </div>
                </div>
                <div class="col-lg-3">
                    <label for="data_aplicacao">Data final do intervalo</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" id="date_picker" name="data_final" placeholder="Data final do intervalo" class="form-control pull-right" value="{{ $data_final }}" onchange="submitForm('form_baixas_justificadas_validacoes');">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data do movimento</th>
                    <th>Usuário responsável</th>
                    <th>Realizado em</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($baixas_justificadas as $baixa_justificada)
                    <tr>
                        <td>{{ $baixa_justificada->id }}</td>
                        <td>{{ $baixa_justificada->data_movimento() }}</td>
                        <td>{{ $baixa_justificada->usuario->nome }}</td>
                        <td>{{ $baixa_justificada->alterado_em('d/m/Y H:i') }}</td>
                        <td>
                            <p style="font-weight:bold;color:{{ ($baixa_justificada->situacao == 'N') ? 'red' : 'green' }};">
                                @if ($baixa_justificada->baixas_justificadas_produtos->count() > 0)
                                    {{ mb_strtoupper($baixa_justificada->situacao()) }}
                                @else
                                    PRODUTOS NÃO DEFINIDOS
                                @endif
                            </p>
                        </td>
                        <td>
                            <a href="{{ route('admin.baixas_justificadas_validacoes.to_create', ['baixa_justificada_id' => $baixa_justificada->id, /* 'data_inicial' => $data_inicial, 'data_final' => $data_final */]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-check" aria-hidden="true"></i> Abrir validação
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $baixas_justificadas->appends($formData)->links() !!}
        @else
            {!! $baixas_justificadas->links() !!}
        @endif
    </div>
</div>
@endsection
