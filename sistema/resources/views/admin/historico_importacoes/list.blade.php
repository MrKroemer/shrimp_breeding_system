@extends('adminlte::page')

@section('title', 'Histórico de importações')

@section('content_header')
<h1>Histórico de importações</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Histórico de importações</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.historico_importacoes.create')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.historico_importacoes.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group" style="width: 175px;">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicial" placeholder="Á partir do dia" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <div class="form-group" style="width: 175px;">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_final" placeholder="Até o dia" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a class="btn btn-warning" data-toggle="modal" data-target="#importacao_entradas_modal">
                <i class="fa fa-download" aria-hidden="true"></i> Importação de entradas
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Usuário responsável</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historico_importacoes as $importacao)
                    <tr>
                        <td>{{ $importacao->id }}</td>
                        <td>{{ $importacao->data_importacao() }}</td>
                        <td>{{ $importacao->usuario->nome }}</td>
                        <td>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.historico_importacoes.to_remove', ['id' => $importacao->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $historico_importacoes->appends($formData)->links() !!}
        @else
            {!! $historico_importacoes->links() !!}
        @endif
    </div>
</div>
@endsection