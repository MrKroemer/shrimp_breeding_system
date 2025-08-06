@extends('adminlte::page')

@section('title', 'Registro de grupos dos usuários')

@section('content_header')
<h1>Listagem de grupos dos usuários</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Usuários</a></li>
    <li><a href="">Grupos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.usuarios.grupos.create')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.usuarios.usuarios_grupos.to_search', ['usuario_id' => $usuario_id]) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="grupo" class="form-control" placeholder="Grupo">
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            <a class="btn btn-success" data-toggle="modal" data-target="#usuarios_grupos_modal">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
            <a href="{{ route('admin.usuarios') }}" class="btn btn-default">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>
        </form>
    </div>
    <div class="box-body">
        @php
            $usuario = \App\Models\Usuarios::find($usuario_id);
        @endphp
        <h5 class="box-title">Usuários / {{ $usuario->nome }} /</h5>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Grupo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios_grupos as $usuario_grupo)
                    <tr>
                        <td>{{ $usuario_grupo->id }}</td>
                        <td>{{ $usuario_grupo->grupo->nome }}</td>
                        <td>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.usuarios.usuarios_grupos.to_remove', ['usuario_id' => $usuario_id, 'id' => $usuario_grupo->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $usuarios_grupos->appends($formData)->links() !!}
        @else
            {!! $usuarios_grupos->links() !!}
        @endif
    </div>
</div>
@endsection