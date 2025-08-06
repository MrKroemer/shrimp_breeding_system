@extends('adminlte::page')

@section('title', 'Registro de análises bacteriológicas')

@section('content_header')
<h1>Listagem de análises bacteriológicas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Análises bacteriológicas</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.analises_bacteriologicas.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_analise" placeholder="Data da aplicação" class="form-control pull-right" id="date_picker" value="">
                </div>
            </div>
            <div class="form-group">
                <select name="tanque_tipo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($tanques_tipos as $tanque_tipo)
                        <option value="{{ $tanque_tipo->id }}" >{{ $tanque_tipo->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select name="analise_laboratorial_tipo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($analises_laboratoriais_tipos as $analise_laboratorial_tipo)
                        <option value="{{ $analise_laboratorial_tipo->id }}" >{{ $analise_laboratorial_tipo->nome }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.analises_bacteriologicas.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data da análise</th>
                    <th>Tipo de tanque</th>
                    <th>Tipo de análise</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($analises_laboratoriais as $analise_laboratorial)
                    <tr>
                        <td>{{ $analise_laboratorial->id }}</td>
                        <td>{{ $analise_laboratorial->data_analise() }}</td>
                        <td>{{ $analise_laboratorial->tanque_tipo->nome }}</td>
                        <td>{{ $analise_laboratorial->analise_laboratorial_tipo->nome }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.analises_bacteriologicas.amostras.to_create', ['analise_laboratorial_id' => $analise_laboratorial->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Registro de amostras</a></li>
                                </ul>
                            </div>
                            @if ($analise_laboratorial->bacteriologicas->count() == 0)
                                <a href="{{ route('admin.analises_bacteriologicas.to_edit', ['id' => $analise_laboratorial->id]) }}" class="btn btn-primary btn-xs">
                                    <i class="fa fa-edit" aria-hidden="true"></i> Editar
                                </a>
                            @endif
                            <button type="button" onclick="onActionForRequest('{{ route('admin.analises_bacteriologicas.to_remove', ['id' => $analise_laboratorial->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $analises_laboratoriais->appends($formData)->links() !!}
        @else
            {!! $analises_laboratoriais->links() !!}
        @endif
    </div>
</div>
@endsection