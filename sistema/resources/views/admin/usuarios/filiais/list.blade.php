@extends('adminlte::page')

@section('title', 'Registro de permissões de acesso')

@section('content_header')
<h1>Listagem de permissões de acesso</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Usuários</a></li>
    <li><a href="">Filiais</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.usuarios.filiais.create')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.usuarios.filiais.to_search', ['usuario_id' => $usuario->id]) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <select name="filial_id" class="form-control" style="width: 285px;">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($filiais as $filial)
                        <option value="{{ $filial->id }}">{{ $filial->nome }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            <a class="btn btn-success" data-toggle="modal" data-target="#usuarios_filiais_modal">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
            <a href="{{ route('admin.usuarios') }}" class="btn btn-default">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>
        </form>
    </div>
    <div class="box-body">
        
        <h5 class="box-title">Usuários / {{ $usuario->nome }} /</h5>
        
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID da Filial</th>
                    <th>Nome</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios_filiais as $usuario_filial)
                    <tr>
                        <td>{{ $usuario_filial->filial->id }}</td>
                        <td>
                            <a href="{{ route('admin.usuarios.filiais.permissoes', ['usuario_id' => $usuario->id, 'usuario_filial_id' => $usuario_filial->id]) }}" class="btn btn-default btn-xs">
                                {{ $usuario_filial->filial->nome }}</td>
                            </a>
                        <td>
                            @php
                                if ($usuario_filial->situacao == 'ON') {
                                    $btnColor = 'success';
                                    $btnTitle = 'Ativo';
                                } else {
                                    $btnColor = 'default';
                                    $btnTitle = 'Inativo';
                                }
                            @endphp
                            <a href="{{ route('admin.usuarios.filiais.to_turn', ['usuario_id' => $usuario->id, 'id' => $usuario_filial->id]) }}" class="btn btn-{{ $btnColor }} btn-xs">
                                <i class="fa fa-power-off" aria-hidden="true"></i> {{ $btnTitle }}
                            </a>
                        </td>
                        <td>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.usuarios.filiais.to_remove', ['usuario_id' => $usuario->id, 'id' => $usuario_filial->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $usuarios_filiais->appends($formData)->links() !!}
        @else
            {!! $usuarios_filiais->links() !!}
        @endif
    </div>
</div>
@endsection