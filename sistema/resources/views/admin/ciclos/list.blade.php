@extends('adminlte::page')

@section('title', 'Registro de ciclos de cultivo')

@section('content_header')
<h1>Listagem de ciclos de cultivo</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Ciclos de cultivo</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.ciclos.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <select name="tanque_id" class="form-control" style="width: 185px;">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($tanques as $tanque)
                        <option value="{{ $tanque->id }}">{{ $tanque->sigla }} ( {{ $tanque->tanque_tipo->nome }} )</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicio" placeholder="Data do início" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_fim" placeholder="Data do fim" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.ciclos.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Cultivo</th>
                    <th>Tipo</th>
                    <th>Data de início</th>
                    <th>Data de fim</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ciclos as $ciclo)
                    <tr>
                        <td>{{ $ciclo->tanque->sigla }} ( Ciclo Nº {{ $ciclo->numero }} )</td>
                        <td>{{ $ciclo->tipo() }}</td>
                        <td>{{ $ciclo->data_inicio() }}</td>
                        <td>{{ $ciclo->data_fim() ?: '- - / - - / - - - -' }}</td>
                        <td>
                            @php
                                $dashboard = new \App\Http\Controllers\Admin\DashboardController;
                                $cores = $dashboard->getCores();
                            @endphp
                            <p style="font-weight:bold;color: {{ $cores[$ciclo->situacao] }};">
                                {{ mb_strtoupper($ciclo->situacao($ciclo->situacao)) }}
                            </p>
                        </td>
                        <td>
                      {{--  @if ($ciclo->verificarSituacao([1])) --}}
                            <button type="button" onclick="onActionForRequest('{{ route('admin.ciclos.to_remove', ['id' => $ciclo->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                       {{--  @else
                            <button class="btn btn-danger btn-xs" disabled>
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        @endif --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $ciclos->appends($formData)->links() !!}
        @else
            {!! $ciclos->links() !!}
        @endif
    </div>
</div>
@endsection
