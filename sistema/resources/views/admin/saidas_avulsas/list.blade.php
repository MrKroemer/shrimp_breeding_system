@extends('adminlte::page')

@section('title', 'Registro de saídas avulsas')

@section('content_header')
<h1>Listagem de saídas avulsas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Saídas avulsas</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.saidas_avulsas.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <select name="tanque_id" class="form-control" style="width: 190px;">
                    <option value="">..:: Tanque ::..</option>
                    @foreach($tanques as $tanque)
                        <option value="{{ $tanque->id }}">{{ $tanque->sigla }}</option>
                    @endforeach
                </select>
            </div>
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
                <select name="situacao" class="form-control" style="width: 190px;">
                    <option value="">..:: Situação ::..</option>
                    @foreach($situacoes as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.saidas_avulsas.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanque</th>
                    <th>Data do movimento</th>
                    <th>Usuário responsável</th>
                    <th>Realizado em</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($saidas_avulsas as $saida_avulsa)
                    <tr>
                        <td>{{ $saida_avulsa->id }}</td>
                        <td>{{ $saida_avulsa->tanque->sigla }}</td>
                        <td>{{ $saida_avulsa->data_movimento() }}</td>
                        <td>{{ $saida_avulsa->usuario->nome }}</td>
                        <td>{{ $saida_avulsa->alterado_em('d/m/Y H:i') }}</td>
                        <td>
                            <p style="font-weight:bold;color:{{ ($saida_avulsa->situacao != 'V') ? 'red' : 'green' }};">
                                @if (($saida_avulsa->saidas_avulsas_produtos->count() > 0)||($saida_avulsa->saidas_avulsas_receitas->count() > 0))
                                    {{ mb_strtoupper($saida_avulsa->situacao()) }}
                                @else
                                    ITENS NÃO DEFINIDOS
                                @endif
                            </p>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.saidas_avulsas.produtos', ['saida_avulsa_id' => $saida_avulsa->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Produtos e/ou receitas</a></li>
                                </ul>
                            </div>
                            @if ($saida_avulsa->situacao == 'N')
                                <a href="{{ route('admin.saidas_avulsas.to_edit', ['id' => $saida_avulsa->id]) }}" class="btn btn-primary btn-xs">
                                    <i class="fa fa-edit" aria-hidden="true"></i> Editar
                                </a>
                                @if ($saida_avulsa->saidas_avulsas_produtos->isEmpty())
                                    <button type="button" onclick="onActionForRequest('{{ route('admin.saidas_avulsas.to_remove', ['id' => $saida_avulsa->id]) }}');" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('admin.saidas_avulsas.to_view', ['id' => $saida_avulsa->id]) }}" class="btn btn-primary btn-xs">
                                    <i class="fa fa-eye" aria-hidden="true"></i> Visualizar
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $saidas_avulsas->appends($formData)->links() !!}
        @else
            {!! $saidas_avulsas->links() !!}
        @endif
    </div>
</div>
@endsection
