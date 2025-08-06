@extends('adminlte::page')

@section('title', 'Registro de lotes de peixes')

@section('content_header')
<h1>Listagem de lotes de peixes</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Lotes de peixes</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.lotes_peixes.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="codigo" class="form-control" placeholder="Código do lote">
            <select name="tipo" class="form-control" style="width: 185px;">
                <option value="">..:: Tipo de lote ::..</option>
                @foreach($tipos as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.lotes_peixes.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Data de início</th>
                    <th>Data de fim</th>
                    <th>Tipo de lote</th>
                    <th>Espécie</th>
                    <th>Total de ovos</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lotes_peixes as $lote)
                    <tr>
                        <td>{{ $lote->id }}</td>
                        <td>{{ $lote->codigo }}</td>
                        <td>{{ $lote->data_inicio() }}</td>
                        <td>{{ $lote->data_fim() ?: '- - / - - / - - - -' }}</td>
                        <td>{{ $lote->tipo() }}</td>
                        <td>{{ $lote->especie->nome_cientifico }}</td>
                        <td>{{ ($lote->tipo == 1) ? ($lote->total_ovos() . ' mL') : 'n/a' }}</td>
                        <td>
                            <p style="font-weight:bold;color: {{ ($lote->situacao == 5 || $lote->situacao == 6) ? '#ff8100' : (($lote->situacao != 7) ? 'green' : 'red') }};">
                                {{ mb_strtoupper($lote->situacao()) }}
                            </p>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" {{ $lote->tipo == 2 ? 'disabled' : ''}}>
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    @if($lote->tipo == 1)
                                        <li><a href="{{ route('admin.lotes_peixes.ovos.to_create', ['lote_peixes_id' => $lote->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Ovos coletados</a></li>
                                    @endif
                                </ul>
                            </div>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.lotes_peixes.to_remove', ['id' => $lote->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- @if(isset($formData))
            {!! $lotes_peixes->appends($formData)->links() !!}
        @else
            {!! $lotes_peixes->links() !!}
        @endif --}}
    </div>
</div>
@endsection