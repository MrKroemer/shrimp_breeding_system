@extends('adminlte::page')

@section('title', 'Registro de aplicações de insumos')

@section('content_header')
<h1>Aplicações de insumos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Aplicações de insumos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        @php
            $data_aplicacao = isset($formData['data_aplicacao']) ? $formData['data_aplicacao'] : '';
            $tanque_id      = isset($formData['tanque_id'])      ? $formData['tanque_id']      : '';
        @endphp
        <form action="{{ route('admin.aplicacoes_insumos.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="data_aplicacao" placeholder="Data da aplicação" class="form-control pull-right" id="date_picker" value="{{ $data_aplicacao }}">
                </div>
            </div>
            <div class="form-group">
                <select name="tanque_id" class="form-control" style="width: 185px;">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($tanques as $tanque)
                        @if ($tanque->tanque_tipo_id != 2) {{-- Exceto berçarios --}}
                            <option value="{{ $tanque->id }}" {{ ($tanque->id == $tanque_id) ? 'selected' : '' }}>{{ $tanque->sigla }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.aplicacoes_insumos.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        @if (! empty($data_aplicacao) && $aplicacoes_insumos->isNotEmpty())
            <a href="{{ route('admin.aplicacoes_insumos.aplicacoes_insumos_ajustes.to_create', ['data_aplicacao' => $data_aplicacao]) }}" class="btn btn-warning">
                <i class="fa fa-check" aria-hidden="true"></i> Ajuste de soluções
            </a>
            <button type="submit" class="btn btn-info" form="form_aplicacoes_insumos_fichas">
                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Gerar fichas
            </button>
        </form>
        <form id="form_aplicacoes_insumos_fichas" action="{{ route('admin.aplicacoes_insumos.fichas.to_generate') }}" method="POST" target="_blank">
            {!! csrf_field() !!}
            <input type="hidden" name="data_aplicacao" value="{{ $data_aplicacao }}">
        @endif
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data da aplicação</th>
                    <th>Tanque</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aplicacoes_insumos as $aplicacao_insumo)
                    <tr>
                        <td>{{ $aplicacao_insumo->id }}</td>
                        <td>{{ $aplicacao_insumo->data_aplicacao() }}</td>
                        <td>{{ $aplicacao_insumo->tanque->sigla }}</td>
                        <td>
                            <p style="font-weight:bold;{{ ($aplicacao_insumo->situacao == 'N') ? 'color:red;' : 'color:green;' }}">
                                @if (
                                    $aplicacao_insumo->aplicacoes_insumos_receitas->isNotEmpty() ||
                                    $aplicacao_insumo->aplicacoes_insumos_produtos->isNotEmpty()
                                )
                                    {{ mb_strtoupper($aplicacao_insumo->situacao($aplicacao_insumo->situacao)) }}
                                @else
                                    INSUMOS NÃO DEFINIDOS
                                @endif
                            </p>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.aplicacoes_insumos.aplicacoes_insumos_itens', ['aplicacao_insumo_id' => $aplicacao_insumo->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Receitas e produtos</a></li>
                                    @if (
                                        $aplicacao_insumo->aplicacoes_insumos_receitas->isNotEmpty() ||
                                        $aplicacao_insumo->aplicacoes_insumos_produtos->isNotEmpty()
                                    )
                                        <li><a href="{{ route('admin.aplicacoes_insumos.aplicacoes_insumos_validacoes', ['aplicacao_insumo_id' => $aplicacao_insumo->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Validações e estornos</a></li>
                                    @endif
                                </ul>
                            </div>
                            <a href="{{ route('admin.aplicacoes_insumos.to_remove', ['id' => $aplicacao_insumo->id]) }}" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- @if(isset($formData))
            {!! $aplicacoes_insumos->appends($formData)->links() !!}
        @else
            {!! $aplicacoes_insumos->links() !!}
        @endif --}}
    </div>
</div>
@endsection