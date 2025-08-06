@extends('adminlte::page')

@section('title', 'Registro de parâmetros')

@section('content_header')
<h1>Listagem de parâmetros</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Parametros</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.coletas_parametros_tipos.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="sigla" class="form-control" placeholder="Sigla">
            <input type="text" name="descricao" class="form-control" placeholder="Nome">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.coletas_parametros_tipos.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sigla</th>
                    <th>Nome</th>
                    <th>Valor mínimo (Padrão)</th>
                    <th>Valor máximo (Padrão)</th>
                    <th>Valor mínimo</th>
                    <th>Valor máximo</th>
                    <th>Cor (gráficos)</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ColetasParametrosTipos as $parametro)
                    <tr>
                        <td>{{ $parametro->id }}</td>
                        <td>{{ $parametro->sigla }}</td>
                        <td>{{ $parametro->descricao }}</td> 
                        <td>{{ $parametro->minimo }}</td>
                        <td>{{ $parametro->maximo }}</td>
                        <td>{{ $parametro->minimov }}</td>
                        <td>{{ $parametro->maximov }}</td>
                        <td><input type="color" value="{{ $parametro->cor }}" disabled></td>
                        <td>
                            @php
                                if ($parametro->ativo == 'ON') {
                                    $btnColor = 'success';
                                    $btnTitle = 'Ativo';
                                } else {
                                    $btnColor = 'default';
                                    $btnTitle = 'Inativo';
                                }
                            @endphp
                            <a href="{{ route('admin.coletas_parametros_tipos.to_turn', ['id' => $parametro->id]) }}" class="btn btn-{{ $btnColor }} btn-xs">
                                <i class="fa fa-power-off" aria-hidden="true"></i> {{ $btnTitle }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.coletas_parametros_tipos.to_edit', ['id' => $parametro->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.coletas_parametros_tipos.to_remove', ['id' => $parametro->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
        @if(isset($formData))
            {!! $ColetasParametrosTipos->appends($formData)->links() !!}
        @else
            {!! $ColetasParametrosTipos->links() !!}
        @endif
    </div>
</div>
@endsection