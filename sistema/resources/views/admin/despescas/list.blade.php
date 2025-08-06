@extends('adminlte::page')
@php
    use App\Http\Controllers\Util\DataPolisher;
@endphp
@section('title', 'Registro de despescas de tanques')

@section('content_header')
<h1>Listagem de despescas de tanques</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Despescas de tanques</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.despescas.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <select name="ciclo_id" class="form-control" style="width: 185px;">
                    <option value="">..:: Ciclo ::..</option>
                    @foreach($ciclos as $ciclo)
                        <option value="{{ $ciclo->id }}">Ciclo Nº {{ $ciclo->numero }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select name="tanque_id" class="form-control" style="width: 185px;">
                    <option value="">..:: Tanque ::..</option>
                    @foreach($tanques as $tanque)
                        <option value="{{ $tanque->id }}">{{ $tanque->sigla }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicio" placeholder="Data de início" class="form-control pull-right" id="datetime_picker">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_fim" placeholder="Data de fim" class="form-control pull-right" id="datetime_picker">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.despescas.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cultivo</th>
                    <th>Data de início</th>
                    <th>Data de fim</th>
                    <th>Prev.</th>
                    <th>Desp.</th>
                    <th>Apro.</th>
                    <th>Tipo</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($despescas as $despesca)
                    <tr>
                        <td>{{ $despesca->id }}</td>
                        <td>{{ $despesca->tanque_sigla }} ( Ciclo Nº {{ $despesca->ciclo_numero }} )</td>
                        <td>{{ $despesca->data_inicio() }}</td>
                        <td>{{ $despesca->data_fim() ?: '- - / - - / - - - -' }}</td>
                        <td>{{ DataPolisher::numberFormat($despesca->qtd_prevista, 0) }} Kg</td>
                        <td>{{ DataPolisher::numberFormat($despesca->qtd_despescada, 0) }} Kg</td>
                        <td>{{ DataPolisher::numberFormat($despesca->aproveitamento()) }} %</td>
                        <td>{{ $despesca->tipo == 2 ? $despesca->ordinal . 'ª' : '' }} {{ $despesca->tipo() }}</td>
                        <td>
                            @if ($despesca->ciclo->verificarSituacao([7]) && empty($despesca->data_fim))
                                <button type="button" onclick="onActionForRequest('{{ route('admin.despescas.to_close', ['id' => $despesca->id]) }}');" class="btn btn-warning btn-xs">
                                    <i class="fa fa-check" aria-hidden="true"></i> Encerrar despesca
                                </button>
                            @else
                                <p style="font-weight:bold;color:red;">DESPESCA ENCERRADA</p>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.despescas.to_view', ['id' => $despesca->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-eye" aria-hidden="true"></i> Visualizar
                            </a>
                          {{-- @if ($despesca->ciclo->verificarSituacao([7]) && empty($despesca->data_fim)) --}}
                                <button type="button" onclick="onActionForRequest('{{ route('admin.despescas.to_remove', ['id' => $despesca->id]) }}');" class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                </button>
                           {{-- @endif --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $despescas->appends($formData)->links() !!}
        @else
            {!! $despescas->links() !!}
        @endif
    </div>
</div>
@endsection
