@extends('adminlte::page')

@section('title', 'Registro de aplicações')

@section('content_header')
<h1>Listagem de aplicações</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Preparações de cultivos</a></li>
    <li><a href="">Aplicações</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.preparacoes.aplicacoes.to_search', ['preparacao_id' => $preparacao_id]) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <select name="preparacao_tipo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($preparacoes_tipos as $preparacao_tipo)
                        <option value="{{ $preparacao_tipo->id }}">{{ $preparacao_tipo->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_aplicacao" placeholder="Data de aplicação" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            @if ($cicloSituacao)
                <a href="{{ route('admin.preparacoes.aplicacoes.to_create', ['preparacao_id' => $preparacao_id]) }}" class="btn btn-success">
                    <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
                </a>
            @endif
            <a href="{{ route('admin.preparacoes') }}" class="btn btn-default">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>
        </form>
    </div>
    <div class="box-body">

        <h5 class="box-title" style="font-weight: bold;">Cultivo: {{ $preparacao->ciclo->tanque->sigla }} ( Ciclo Nº <span style="color: red;">{{ $preparacao->ciclo->numero }}</span> iniciado em <span style="color: red;">{{ $preparacao->ciclo->data_inicio() }}</span> )</h5>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo de preparação</th>
                    <th>Data de aplicação</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($preparacoes_aplicacoes as $preparacao_aplicacao)
                    <tr style="{{ $preparacao_aplicacao->situacao == 'E' ? 'background-color:#ff000036; color:#ffffff;' : '' }}">
                        <td>{{ $preparacao_aplicacao->id }}</td>
                        <td>{{ $preparacao_aplicacao->preparacao_tipo->nome }}</td>
                        <td>{{ $preparacao_aplicacao->data_aplicacao() }}</td>
                        <td>{{ $preparacao_aplicacao->produto->nome }}</td>
                        <td>{{ (float) $preparacao_aplicacao->quantidade }} {{ $preparacao_aplicacao->produto->unidade_saida->sigla }}</td>
                        <td>
                            @if ($cicloSituacao && $preparacao_aplicacao->situacao == 'A')
                                @include('admin.estoque_estornos.justificativas.create', [
                                    'reverse_route' => route('admin.preparacoes.aplicacoes.to_reverse', [
                                        'preparacao_id' => $preparacao_id, 
                                        'id' => $preparacao_aplicacao->id,
                                    ]),
                                    'form_id'   => "form_estorno_preparacoes_{$preparacao_aplicacao->id}",
                                    'btn_name'  => 'Excluir',
                                    'btn_icon'  => 'fa fa-trash',
                                    'btn_class' => 'btn btn-danger btn-xs',
                                ])
                            @else
                                @if ($preparacao_aplicacao->situacao == 'E')
                                <button class="btn btn-default btn-xs" disabled>
                                    <i class="fa fa-undo" aria-hidden="true"></i> Estornado
                                @else
                                <button class="btn btn-danger btn-xs" disabled>
                                    <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                @endif
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $preparacoes_aplicacoes->appends($formData)->links() !!}
        @else
            {!! $preparacoes_aplicacoes->links() !!}
        @endif
    </div>
</div>
@endsection