@extends('adminlte::page')

@section('title', 'Registro de permissões do usuários')

@section('content_header')
<h1>Listagem de permissões do usuários</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Usuários</a></li>
    <li><a href="">Permissões</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.usuarios.permissoes.create')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.usuarios.filiais.permissoes.to_search', ['usuario_id' => $usuario->id, 'usuario_filial_id' => $usuario_filial->id]) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="menu" class="form-control" placeholder="Opção">
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            <a class="btn btn-success" data-toggle="modal" data-target="#usuarios_permissoes_modal">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
            <a href="{{ route('admin.usuarios.filiais', ['usuario_id' => $usuario->id]) }}" class="btn btn-default">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>
        </form>
    </div>
    <div class="box-body">
        
        <h5 class="box-title">Usuários / {{ $usuario->nome }} / {{ $usuario_filial->filial->nome }}</h5>
        
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Modulo</th>
                    <th>Opção</th>
                    <th>Item de menu</th>
                    <th>Permissões</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios_permissoes as $usuario_permissao)
                    <tr>
                        <td>{{ $usuario_permissao->id }}</td>
                        <td>{{ $usuario_permissao->modulo->nome }}</td>
                        <td>{{ $usuario_permissao->menu->menu->nome }}</td>
                        <td>{{ $usuario_permissao->menu->nome }}</td>
                        <td>
                            <form id="permissoes_form_{{ $usuario_permissao->id }}" action="{{ route('admin.usuarios.filiais.permissoes.to_update', ['usuario_id' => $usuario->id, 'usuario_filial_id' => $usuario_filial->id, 'id' => $usuario_permissao->id]) }}" method="POST" class="form form-inline">
                                @php
                                $permissoes = explode('|', $usuario_permissao->permissoes);
                                
                                $create = false;
                                $read   = false;
                                $update = false;
                                $delete = false;

                                foreach ($permissoes as $permissao) {
                                    switch ($permissao) {
                                        case 'C': $create = true; break;
                                        case 'R': $read   = true; break;
                                        case 'U': $update = true; break;
                                        case 'D': $delete = true; break;
                                    }
                                }
                                @endphp
                                {!! csrf_field() !!}
                                <input type="checkbox" name="read"   {{ $read   ? 'checked' : '' }}> Visualizar
                                <input type="checkbox" name="create" {{ $create ? 'checked' : '' }} {{ ($usuario_permissao->menu->tipo != 'submenu') ? 'disabled' : '' }}> Criar
                                <input type="checkbox" name="update" {{ $update ? 'checked' : '' }} {{ ($usuario_permissao->menu->tipo != 'submenu') ? 'disabled' : '' }}> Alterar
                                <input type="checkbox" name="delete" {{ $delete ? 'checked' : '' }} {{ ($usuario_permissao->menu->tipo != 'submenu') ? 'disabled' : '' }}> Remover
                            </form>
                        </td> 
                        <td>                 
                            <button type="submit" class="btn btn-success btn-xs" form="permissoes_form_{{ $usuario_permissao->id }}">
                                <i class="fa fa-check" aria-hidden="true"></i> Aplicar
                            </button>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.usuarios.filiais.permissoes.to_remove', ['usuario_id' => $usuario->id, 'usuario_filial_id' => $usuario_filial->id, 'id' => $usuario_permissao->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $usuarios_permissoes->appends($formData)->links() !!}
        @else
            {!! $usuarios_permissoes->links() !!}
        @endif
    </div>
</div>
@endsection