@extends('adminlte::page')

@section('title', 'Registro de tipos de preparações')

@section('content_header')
<h1>Listagem de tipos de preparações</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Tipos de preparações</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.preparacoes_tipos.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="nome" class="form-control" placeholder="Nome">
            <input type="text" name="descricao" class="form-control" placeholder="Descrição">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.preparacoes_tipos.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Método de cultivo</th>
                    <th>Situação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($preparacoes_tipos as $tipo_preparacao)
                    <tr>
                        <td>{{ $tipo_preparacao->id }}</td>
                        <td>{{ $tipo_preparacao->nome }}</td>
                        <td>{{ $tipo_preparacao->descricao }}</td>
                        <td>{{ $tipo_preparacao->metodo($tipo_preparacao->metodo) }}</td>
                        <td>
                            @php
                                if ($tipo_preparacao->situacao == 'ON') {
                                    $btnColor = 'success';
                                    $btnTitle = 'Ativo';
                                } else {
                                    $btnColor = 'default';
                                    $btnTitle = 'Inativo';
                                }
                            @endphp
                            <a href="{{ route('admin.preparacoes_tipos.to_turn', ['id' => $tipo_preparacao->id]) }}" class="btn btn-{{ $btnColor }} btn-xs">
                                <i class="fa fa-power-off" aria-hidden="true"></i> {{ $btnTitle }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.preparacoes_tipos.to_edit', ['id' => $tipo_preparacao->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.preparacoes_tipos.to_remove', ['id' => $tipo_preparacao->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $preparacoes_tipos->appends($formData)->links() !!}
        @else
            {!! $preparacoes_tipos->links() !!}
        @endif
    </div>
</div>
@endsection