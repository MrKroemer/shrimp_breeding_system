@extends('adminlte::page')

@section('title', 'Registro de fatores de conversão para Sondas Laboratorias')

@section('content_header')
<h1>Listagem de Fatores de Conversão para Sondas Laboratorias</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Fatores de Conversão para Sondas Laboratorias</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.sondas_fatores.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <select name="sondas_laboratoriais_id" class="form-control" style="width: 185px;">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($sondas_laboratoriais as $sonda_laboratorial)
                        <option value="{{ $sonda_laboratorial->id }}">{{ $sonda_laboratorial->nome }} </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select name="coleta_parametro_tipo_id" class="form-control" style="width: 185px;">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($coletas_parametros_tipos as $coleta_parametro_tipo)
                        <option value="{{ $coleta_parametro_tipo->id }}">{{ $coleta_parametro_tipo->sigla }} </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            @if ($sonda_laboratorial_id)
                <a href="{{ route('admin.sondas_laboratoriais.sondas_fatores.to_create', ['sonda_id' => $sonda_laboratorial_id]) }}" class="btn btn-success">
                    <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
                </a>
            @else 
                <a href="{{ route('admin.sondas_fatores.to_create') }}" class="btn btn-success">
                    <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
                </a>
            @endif
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marca da Sonda</th>
                    <th>Sonda Laboratorial</th>
                    <th>Parâmetro</th>
                    <th>Fator</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sondas_fatores as $sonda_fator)
                    <tr>
                        <td>{{ $sonda_fator->id }}</td>
                    <td>{{ $sonda_fator->sondas_laboratoriais->marca }}</td>
                        <td>{{ $sonda_fator->sondas_laboratoriais->nome }}</td>
                        <td>{{ $sonda_fator->coletas_parametros_tipos->sigla }}</td>
                        <td>
                            @php
                                if ($sonda_fator->situacao == 'ON') {
                                    $btnColor = 'success';
                                    $btnTitle = 'Ativo';
                                } else {
                                    $btnColor = 'default';
                                    $btnTitle = 'Inativo';
                                }
                            @endphp
                            <a href="{{ route('admin.sondas_fatores.to_turn', ['id' => $sonda_fator->id]) }}" class="btn btn-{{ $btnColor }} btn-xs">
                                <i class="fa fa-power-off" aria-hidden="true"></i> {{ $btnTitle }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.sondas_fatores.to_edit', ['id' => $sonda_fator->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.sondas_fatores.to_remove', ['id' => $sonda_fator->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $sondas_fatores->appends($formData)->links() !!}
        @else
            {!! $sondas_fatores->links() !!}
        @endif
    </div>
</div>
@endsection
