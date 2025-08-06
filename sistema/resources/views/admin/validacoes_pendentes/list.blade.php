@extends('adminlte::page')
@php
    use App\Http\Controllers\Util\DataPolisher;
@endphp
@section('title', 'Validações pendentes')

@section('content_header')
<h1>Validações pendentes</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Validações pendentes</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.validacoes_pendentes.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_aplicacao" placeholder="Data da aplicação" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <div class="form-group">
                <select name="tipo_aplicacao" class="form-control">
                    <option value="">..:: Tipos de aplicações ::..</option>
                    @foreach ($tipos_aplicacoes as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select name="tanque_id" class="form-control">
                    <option value="">..:: Tanques ::..</option>
                    @foreach($tanques as $tanque)
                        <option value="{{ $tanque->id }}">{{ $tanque->sigla }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select name="situacao" class="form-control">
                    <option value="">..:: Situações ::..</option>
                    @foreach ($situacoes as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Data da aplicação</th>
                    <th>Tipo da aplicação</th>
                    <th>Tanque</th>
                    <th>Situação</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach($validacoes_pendentes as $validacao_pendente)
                    <tr>
                        <td>{{ $validacao_pendente->data_aplicacao() }}</td>
                        <td>{{ $validacao_pendente->tipo_aplicacao() }}</td>
                        <td>{{ $validacao_pendente->tanque_sigla }}</td>
                        <td>
                            <p style="font-weight:bold;{{ ($validacao_pendente->situacao != 'V') ? 'color:red;' : 'color:green;' }}">
                                {{ mb_strtoupper($validacao_pendente->situacao($validacao_pendente->situacao)) }}
                            </p>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $validacoes_pendentes->appends($formData)->links() !!}
        @else
            {!! $validacoes_pendentes->links() !!}
        @endif
    </div>
</div>
@endsection
