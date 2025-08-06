@extends('adminlte::page')

@section('title', 'Registro de programações de arraçoamentos')

@section('content_header')
<h1>Validações de programações de arraçoamentos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Programações de arraçoamentos</a></li>
    <li><a href="">Validações</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        @php
            $data_aplicacao = isset($data_aplicacao) ? $data_aplicacao : date('d/m/Y');
            $setor_id       = isset($setor_id)       ? $setor_id       : 0;
        @endphp
        <div class="row">
            <form id="form_arracoamentos_validacoes" action="{{ route('admin.arracoamentos_validacoes', ['data_aplicacao' => $data_aplicacao]) }}" method="POST">
                {!! csrf_field() !!}
                <div class="col-lg-3">
                    <label for="data_aplicacao">Data das aplicações</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" id="date_picker" name="data_aplicacao" placeholder="Data das aplicações" class="form-control pull-right" value="{{ $data_aplicacao }}" onchange="submitForm('form_arracoamentos_validacoes');">
                    </div>
                </div>
                <div class="col-lg-3">
                    <label for="setor_id">Setor</label>
                    <select name="setor_id" class="form-control" onchange="submitForm('form_arracoamentos_validacoes');">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($setores as $setor)
                            <option value="{{ $setor->id }}" {{ ($setor->id == $setor_id) ? 'selected' : '' }}>{{ $setor->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>
    <div class="box-body">
        <form id="form_arracoamentos_fichas" action="{{ route('admin.arracoamentos.fichas.to_view') }}" method="POST" target="_blank" class="form form-inline">
        {!! csrf_field() !!}
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data da aplicação</th>
                        <th>Cultivo</th>
                        <th>Situação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arracoamentos as $arracoamento)
                        <tr>
                            <td>{{ $arracoamento->id }}</td>
                            <td>{{ $arracoamento->data_aplicacao() }}</td>
                            <td>{{ $arracoamento->tanque->sigla }} ( Ciclo Nº {{ $arracoamento->ciclo->numero }} )</td>
                            <td>
                                <p style="font-weight:bold;{{ ($arracoamento->situacao != 'V') ? 'color:red;' : 'color:green;' }}">
                                    @if ($arracoamento->horarios->count() > 0)
                                        {{ mb_strtoupper($arracoamento->situacao($arracoamento->situacao)) }}
                                    @else
                                        HORÁRIOS NÃO PROGRAMADOS
                                    @endif
                                </p>
                            </td>
                            <td>
                                <a href="{{ route('admin.arracoamentos_validacoes.to_create', ['arracoamento_id' => $arracoamento->id]) }}" class="btn btn-primary btn-xs">
                                    <i class="fa fa-check" aria-hidden="true"></i> Abrir validação
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
        @if(isset($formData))
            {!! $arracoamentos->appends($formData)->links() !!}
        @else
            {!! $arracoamentos->links() !!}
        @endif
    </div>
</div>
@endsection