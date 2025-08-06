@extends('adminlte::page')

@section('title', 'Registro de períodos de utilização')

@section('content_header')
<h1>Listagem de períodos de utilização</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Receitas laboratoriais</a></li>
    <li><a href="">Períodos de utilização</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.to_search', ['receita_laboratorial_id' => $receita_laboratorial->id]) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.to_create', ['receita_laboratorial_id' => $receita_laboratorial->id]) }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>

            <a href="{{ route('admin.receitas_laboratoriais') }}" class="btn btn-default">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>
        </form>
    </div>
    <div class="box-body">
        <h5 class="box-title">{{ $receita_laboratorial->nome }}</h5>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Período</th>
                    <th>Dia base</th>
                    <th>Qtd. solução / Kg de ração</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receitas_laboratoriais_periodos as $receita_laboratorial_periodo)
                    <tr>
                        <td>{{ $receita_laboratorial_periodo->id }}</td>
                        <td>{{ $receita_laboratorial_periodo->periodo($receita_laboratorial_periodo->periodo) }}</td>
                        <td>{{ $receita_laboratorial_periodo->dia_base }}º DIA</td>
                        <td>{{ (float) $receita_laboratorial_periodo->quantidade }} {{ $receita_laboratorial_periodo->receita_laboratorial->unidade_medida->sigla }} / Kg</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos', ['receita_laboratorial_id' => $receita_laboratorial->id, 'receita_laboratorial_periodo_id' => $receita_laboratorial_periodo->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Produtos da solução</a></li>
                                </ul>
                            </div>
                            <a href="{{ route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.to_edit', ['receita_laboratorial_id' => $receita_laboratorial->id, 'id' => $receita_laboratorial_periodo->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.to_remove', ['receita_laboratorial_id' => $receita_laboratorial->id, 'id' => $receita_laboratorial_periodo->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $receitas_laboratoriais_periodos->appends($formData)->links() !!}
        @else
            {!! $receitas_laboratoriais_periodos->links() !!}
        @endif
    </div>
</div>
@endsection
