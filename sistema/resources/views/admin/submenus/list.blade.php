@extends('adminlte::page')

@section('title', 'Registro de submenus')

@section('content_header')
<h1>Listagem de submenus</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Módulos</a></li>
    <li><a href="">Menus</a></li>
    <li><a href="">Submenus</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.modulos.submenus.to_search', ['modulo_id' => $modulo_id, 'menu_id' => $menu_id]) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="nome" class="form-control" placeholder="Nome do submenu">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.modulos.submenus.to_create', ['modulo_id' => $modulo_id, 'menu_id' => $menu_id]) }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
            <a href="{{ route('admin.modulos.menus', ['modulo_id' => $modulo_id]) }}" class="btn btn-default">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>
        </form>
    </div>
    <div class="box-body">
        @php
            $modulo = \App\Models\Modulos::find($modulo_id);
            $menu   = \App\Models\Menus::find($menu_id);
        @endphp
        <h5 class="box-title">{{ $modulo->nome }} / {{ $menu->nome }} /</h5>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Icone</th>
                    <th>Rota</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submenus as $submenu)
                    <tr>
                        <td>{{ $submenu->id }}</td>
                        <td>{{ $submenu->nome }}</td>
                        <td>
                            @if($submenu->icone)
                                <i class="fa fa-{{ $submenu->icone }}" aria-hidden="true"></i> [{{ $submenu->icone }}]
                            @else
                                n/a
                            @endif
                        </td>
                        <td>{{ $submenu->rota }}</td>
                        <td>
                            @php
                                if ($submenu->situacao == 'ON') {
                                    $btnColor = 'success';
                                    $btnTitle = 'Ativo';
                                } else {
                                    $btnColor = 'default';
                                    $btnTitle = 'Inativo';
                                }
                            @endphp
                            <a href="{{ route('admin.modulos.submenus.to_turn', ['modulo_id' => $modulo_id, 'menu_id' => $menu_id, 'id' => $submenu->id]) }}" class="btn btn-{{ $btnColor }} btn-xs">
                                <i class="fa fa-power-off" aria-hidden="true"></i> {{ $btnTitle }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.modulos.submenus.to_edit', ['modulo_id' => $modulo_id, 'menu_id' => $menu_id, 'id' => $submenu->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.modulos.submenus.to_remove', ['modulo_id' => $modulo_id, 'menu_id' => $menu_id, 'id' => $submenu->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $submenus->appends($formData)->links() !!}
        @else
            {!! $submenus->links() !!}
        @endif
    </div>
</div>
@endsection