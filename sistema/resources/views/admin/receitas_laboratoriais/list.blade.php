@extends('adminlte::page')

@section('title', 'Registro de receitas laboratoriais')

@section('content_header')
<h1>Listagem de receitas laboratoriais</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Receitas laboratoriais</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.receitas_laboratoriais.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="nome" class="form-control" placeholder="Nome da receita">
            <input type="text" name="descricao" class="form-control" placeholder="Descrição sobre a receita">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.receitas_laboratoriais.to_create') }}" class="btn btn-success">
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
                    {{-- <th>Descrição</th> --}}
                    <th>Unidade de medida</th>
                    <th>Tipo de receita</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receitas_laboratoriais as $receita_laboratorial)
                    <tr>
                        <td>{{ $receita_laboratorial->id }}</td>
                        <td>{{ 
                            strlen($receita_laboratorial->nome) > 25 ? 
                            mb_substr($receita_laboratorial->nome, 0, 25) . ' [...]' : 
                            $receita_laboratorial->nome 
                        }}</td>
                        {{-- <td>{{ 
                            strlen($receita_laboratorial->descricao) > 25 ? 
                            mb_substr($receita_laboratorial->descricao, 0, 25) . ' [...]' : 
                            $receita_laboratorial->descricao 
                        }}</td> --}}
                        <td>{{ $receita_laboratorial->unidade_medida->sigla }}</td>
                        <td>{{ $receita_laboratorial->receita_laboratorial_tipo->nome }}</td>
                        <td>
                            @php
                                if ($receita_laboratorial->situacao == 'ON') {
                                    $btnColor = 'success';
                                    $btnTitle = 'Ativo';
                                } else {
                                    $btnColor = 'default';
                                    $btnTitle = 'Inativo';
                                }
                            @endphp
                            <a href="{{ route('admin.receitas_laboratoriais.to_turn', ['id' => $receita_laboratorial->id]) }}" class="btn btn-{{ $btnColor }} btn-xs">
                                <i class="fa fa-power-off" aria-hidden="true"></i> {{ $btnTitle }}
                            </a>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos', ['receita_laboratorial_id' => $receita_laboratorial->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Períodos de utilização</a></li>
                                </ul>
                            </div>
                            <a href="{{ route('admin.receitas_laboratoriais.to_edit', ['id' => $receita_laboratorial->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.receitas_laboratoriais.to_remove', ['id' => $receita_laboratorial->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $receitas_laboratoriais->appends($formData)->links() !!}
        @else
            {!! $receitas_laboratoriais->links() !!}
        @endif
    </div>
</div>
@endsection
