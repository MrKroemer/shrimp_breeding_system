@extends('adminlte::page')
@php
    use App\Http\Controllers\Util\DataPolisher;
@endphp
@section('title', 'Lançamentos Pendentes')

@section('content_header')
<h1>Lançamentos Pendentes</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Lançamentos Pendentes</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.lancamentos_pendentes.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_movimento" placeholder="Data Lançamento" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <div class="form-group">
                <select name="movimento" class="form-control">
                    <option value="">..:: Tipo do Lançamento ::..</option>
                    <option value="Aplicacao de Insumos"> Aplicacao de Insumos </option>
                    <option value="Arracoamento"> Arracoamento </option>
                    <option value="Saidas Avulsas"> Saidas Avulsas </option>
                </select>
            </div>
            <div class="form-group">
                <select name="situacao" class="form-control">
                    <option value="">..:: Situação ::..</option>
                    <option value="B"> Bloqueado </option>
                    <option value="N"> Não Validado </option>
                    <option value="V"> Validado </option>
                </select>
            </div>
            <div class="form-group">
                <select name="tanque" class="form-control">
                    <option value="">..:: Tanque ::..</option>
                    @foreach($tanques as $tanque)
                        <option value="{{ $tanque->sigla }}">{{ $tanque->sigla }}</option>
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
                    
                    <th>Data da Movimentação</th>
                    <th>Tipo de Movimentação</th>
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
                @foreach($lancamentos_pendentes as $lancamento_pendente)
                    <tr>
                        <td>{{ $lancamento_pendente->data_movimento() }}</td>
                        <td>{{ $lancamento_pendente->movimento }}</td>
                        <td>{{ $lancamento_pendente->tanque }}</td>
                        <td>{{ $lancamento_pendente->situacao }}</td>
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
            {!! $lancamentos_pendentes->appends($formData)->links() !!}
        @else
            {!! $lancamentos_pendentes->links() !!}
        @endif
    </div>
</div>
@endsection
