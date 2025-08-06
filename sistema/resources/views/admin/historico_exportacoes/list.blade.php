@extends('adminlte::page')

@section('title', 'Histórico de exportações')

@section('content_header')
<h1>Histórico de exportações</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Histórico de exportações</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.historico_exportacoes.create')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.historico_exportacoes.to_search') }}" method="POST" class="form form-inline">
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
            <div class="form-group">
                <select name="tipo_exportacao" class="form-control" style="width: 190px;">
                    <option value="">..:: Tipo de exportação ::..</option>
                    <option value="1">PRODUTOS QUÍMICOS</option>
                    <option value="2">RAÇÕES</option>
                    {{-- <option value="3">RATEIO DE RESERVATÓRIOS</option> --}}
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a class="btn btn-warning" data-toggle="modal" data-target="#exportacao_saidas_modal">
                <i class="fa fa-upload" aria-hidden="true"></i> Exportação de saídas
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Usuário responsável</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historico_exportacoes as $exportacao)
                    <tr>
                        <td>{{ $exportacao->id }}</td>
                        <td>{{ $exportacao->data_exportacao() }}</td>
                        <td>{{ $exportacao->tipo_exportacao() }}</td>
                        <td>{{ $exportacao->usuario->nome }}</td>
                        <td>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.historico_exportacoes.to_remove', ['id' => $exportacao->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $historico_exportacoes->appends($formData)->links() !!}
        @else
            {!! $historico_exportacoes->links() !!}
        @endif
    </div>
</div>
@endsection