@extends('adminlte::page')

@section('title', 'Registro de menus')

@section('content_header')
<h1>Listagem de menus</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Módulos</a></li>
    <li><a href="">Menus</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.modulos.menus.to_search', ['modulo_id' => $modulo_id]) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="nome" class="form-control" placeholder="Nome do menu">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.modulos.menus.to_create', ['modulo_id' => $modulo_id]) }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
            <a href="{{ route('admin.modulos') }}" class="btn btn-default">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>
        </form>
    </div>
    <div class="box-body">
        @php
            $modulo = \App\Models\Modulos::find($modulo_id);
        @endphp
        <h5 class="box-title">{{ $modulo->nome }} /</h5>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Icone</th>
                    <th>Tipo</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menus as $menu)
                    <tr>
                        <td>{{ $menu->id }}</td>
                        <td>{{ $menu->nome }}</td>
                        <td>
                            @if($menu->icone)
                                <i class="fa fa-{{ $menu->icone }}" aria-hidden="true"></i> [{{ $menu->icone }}]
                            @else
                                n/a
                            @endif
                        </td>
                        <td>{{ $menu->tipo($menu->tipo) }}</td>
                        <td>
                            @php
                                if ($menu->situacao == 'ON') {
                                    $btnColor = 'success';
                                    $btnTitle = 'Ativo';
                                } else {
                                    $btnColor = 'default';
                                    $btnTitle = 'Inativo';
                                }
                            @endphp
                            <a href="{{ route('admin.modulos.menus.to_turn', ['modulo_id' => $modulo_id, 'id' => $menu->id]) }}" class="btn btn-{{ $btnColor }} btn-xs">
                                <i class="fa fa-power-off" aria-hidden="true"></i> {{ $btnTitle }}
                            </a>
                        </td>
                        <td>
                            @if($menu->tipo == 'menu')
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Mais opções <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ route('admin.modulos.submenus', ['modulo_id' => $modulo_id, 'menu_id' => $menu->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Itens de menu</a></li>
                                    </ul>
                                </div>
                            @endif
                            <a href="{{ route('admin.modulos.menus.to_edit', ['modulo_id' => $modulo_id, 'id' => $menu->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.modulos.menus.to_remove', ['modulo_id' => $modulo_id, 'id' => $menu->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $menus->appends($formData)->links() !!}
        @else
            {!! $menus->links() !!}
        @endif
    </div>
</div>
@endsection