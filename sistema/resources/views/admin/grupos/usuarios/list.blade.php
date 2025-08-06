@extends('adminlte::page')

@section('title', 'Registro de usuários do grupos')

@section('content_header')
<h1>Listagem de usuários do grupos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Grupos</a></li>
    <li><a href="">Usuários</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.grupos.usuarios.create')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.grupos.grupos_usuarios.to_search', ['grupo_id' => $grupo_id]) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="usuario" class="form-control" placeholder="Usuário">
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            <a class="btn btn-success" data-toggle="modal" data-target="#grupos_usuarios_modal">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
            <a href="{{ route('admin.grupos') }}" class="btn btn-default">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>
        </form>
    </div>
    <div class="box-body">
        @php
            $grupo = \App\Models\Grupos::find($grupo_id);
        @endphp
        <h5 class="box-title">Grupos / {{ $grupo->nome }} /</h5>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grupos_usuarios as $grupo_usuario)
                    <tr>
                        <td>{{ $grupo_usuario->id }}</td>
                        <td>{{ $grupo_usuario->usuario->nome }}</td>
                        <td>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.grupos.grupos_usuarios.to_remove', ['grupo_id' => $grupo_id, 'id' => $grupo_usuario->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $grupos_usuarios->appends($formData)->links() !!}
        @else
            {!! $grupos_usuarios->links() !!}
        @endif
    </div>
</div>
@endsection