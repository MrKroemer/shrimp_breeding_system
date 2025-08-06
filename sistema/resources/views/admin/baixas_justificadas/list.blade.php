@extends('adminlte::page')

@section('title', 'Registro de baixas justificadas')

@section('content_header')
<h1>Listagem de baixas justificadas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Baixas justificadas</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.baixas_justificadas.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicial" placeholder="Data inicial do intervalo" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_final" placeholder="Data final do intervalo" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.baixas_justificadas.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Justificativa</th>
                    <th>Data do movimento</th>
                    <th>Usuário responsável</th>
                    <th>Realizado em</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($baixas_justificadas as $baixa_justificada)
                    <tr>
                        <td>{{ $baixa_justificada->id }}</td>
                        <td>{{ substr($baixa_justificada->descricao, 0, 30) }} {{ (strlen($baixa_justificada->descricao) > 30) ? '[ ... ]' : '' }}</td>
                        <td>{{ $baixa_justificada->data_movimento() }}</td>
                        <td>{{ $baixa_justificada->usuario->nome }}</td>
                        <td>{{ $baixa_justificada->alterado_em('d/m/Y H:i') }}</td>
                        <td>
                            <p style="font-weight:bold;color:{{ ($baixa_justificada->situacao == 'N') ? 'red' : 'green' }};">
                                @if ($baixa_justificada->baixas_justificadas_produtos->count() > 0)
                                    {{ mb_strtoupper($baixa_justificada->situacao()) }}
                                @else
                                    PRODUTOS NÃO DEFINIDOS
                                @endif
                            </p>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.baixas_justificadas.produtos', ['baixa_justificada_id' => $baixa_justificada->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Produtos registrados</a></li>
                                </ul>
                            </div>
                            @if ($baixa_justificada->situacao == 'N')
                                <a href="{{ route('admin.baixas_justificadas.to_edit', ['id' => $baixa_justificada->id]) }}" class="btn btn-primary btn-xs">
                                    <i class="fa fa-edit" aria-hidden="true"></i> Editar
                                </a>
                                @if ($baixa_justificada->baixas_justificadas_produtos->isEmpty())
                                    <button type="button" onclick="onActionForRequest('{{ route('admin.baixas_justificadas.to_remove', ['id' => $baixa_justificada->id]) }}');" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('admin.baixas_justificadas.to_view', ['id' => $baixa_justificada->id]) }}" class="btn btn-primary btn-xs">
                                    <i class="fa fa-eye" aria-hidden="true"></i> Visualizar
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $baixas_justificadas->appends($formData)->links() !!}
        @else
            {!! $baixas_justificadas->links() !!}
        @endif
    </div>
</div>
@endsection
