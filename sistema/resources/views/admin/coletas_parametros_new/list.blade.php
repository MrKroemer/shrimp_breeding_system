@extends('adminlte::page')

@section('title', 'Registro de coletas de parametros')

@section('content_header')
<h1>Listagem de coletas de parâmetros</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Listagem de coletas de parâmetros</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.coletas_parametros_new.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_coleta" placeholder="Data da coleta" class="form-control pull-right" id="date_picker" value="">
                </div>
            </div>
            <div class="form-group">
                <select name="tanque_tipo_id" class="form-control">
                    <option value="">..:: Tipos de tanques ::..</option>
                    @foreach($tanques_tipos as $tanque_tipo)
                        <option value="{{ $tanque_tipo->id }}" >{{ $tanque_tipo->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select name="coleta_parametro_tipo_id" class="form-control">
                    <option value="">..:: Parâmetros ::..</option>
                    @foreach($coletas_parametros_tipos as $coleta_parametro_tipo)
                        <option value="{{ $coleta_parametro_tipo->id }}">{{ $coleta_parametro_tipo->descricao }} ({{ $coleta_parametro_tipo->sigla }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.coletas_parametros_new.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data da coleta</th>
                    <th>Tipo de tanque</th>
                    <th>Parâmetro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coletas_parametros as $coleta_parametro)
                    <tr>
                        <td>{{ $coleta_parametro->id }}</td>
                        <td>{{ $coleta_parametro->data_coleta()}}</td>
                        <td>{{ $coleta_parametro->tanque_tipo->nome}}</td>
                        <td>{{ $coleta_parametro->coleta_parametro_tipo->descricao}}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('admin.coletas_parametros_new.amostras.to_create', ['coleta_parametro_id' => $coleta_parametro->id]) }}">
                                            <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Registro de amostras
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @if ($coletas_parametros->count() == 0)
                                <a href="{{ route('admin.coletas_parametros_new.to_edit', ['id' => $coleta_parametro->id]) }}" class="btn btn-primary btn-xs">
                                    <i class="fa fa-edit" aria-hidden="true"></i> Editar
                                </a>
                            @endif
                            <button type="button" onclick="onActionForRequest('{{ route('admin.coletas_parametros_new.to_remove', ['id' => $coleta_parametro->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $coletas_parametros->appends($formData)->links() !!}
        @else
            {!! $coletas_parametros->links() !!}
        @endif
    </div>
</div>
@endsection