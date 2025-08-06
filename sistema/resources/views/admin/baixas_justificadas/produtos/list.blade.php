@extends('adminlte::page')

@section('title', 'Registro de baixas justificadas')

@section('content_header')
<h1>Listagem de produtos de baixas justificadas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Baixas justificadas</a></li>
    <li><a href="">Produtos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

@if ($baixa_justificada->situacao == 'N')
<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $quantidade = !empty(session('quantidade')) ? session('quantidade') : old('quantidade');
            $produto_id = !empty(session('produto_id')) ? session('produto_id') : old('produto_id');
        @endphp
        <form action="{{ route('admin.baixas_justificadas.produtos.to_store', ['baixa_justificada_id' => $baixa_justificada->id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="produto_id">Produto:</label>
                <select name="produto_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($produtos as $produto)
                        <option value="{{ $produto->produto_id }}" {{ ($produto->produto_id == $produto_id) ? 'selected' : '' }}>{{ $produto->produto_nome }} (Em estoque: {{ (float) $produto->em_estoque }} {{ $produto->unidade_saida->sigla }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" step="any" name="quantidade" placeholder="Quantidade" class="form-control" value="{{ $quantidade }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> Salvar
                </button>
                <a href="{{ route('admin.baixas_justificadas.to_search', ['id' => $baixa_justificada->id]) }}" class="btn btn-default">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endif

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">

        <form class="form form-inline">
            <div class="form-group" style="width:100%;">
                <label for="descricao">Justificativa da saída:</label>
                <textarea rows="2" name="descricao" placeholder="Justificativa da saída" class="form-control" style="width:100%;" disabled>{{ $baixa_justificada->descricao }}</textarea>
            </div>
        </form>

        <h5 class="box-title" style="font-weight: bold;">Saídas para a baixa ID Nº<span style="color: red;">{{ $baixa_justificada->id }}</span> referentes a <span style="color: red;">{{ $baixa_justificada->data_movimento() }}</span></h5>
        
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Registrado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($baixa_justificada_produtos as $baixa_justificada_produto)
                    <tr>
                        <td>{{ $baixa_justificada_produto->produto_id }}</td>
                        <td>{{ $baixa_justificada_produto->produto->nome }}</td>
                        <td>{{ (float) $baixa_justificada_produto->quantidade }} {{ $baixa_justificada_produto->produto->unidade_saida->sigla }}</td>
                        <td>{{ $baixa_justificada_produto->alterado_em('d/m/Y H:i') }}</td>
                        <th>
                            @if ($baixa_justificada->situacao == 'N')
                                <button type="button" onclick="onActionForRequest('{{ route('admin.baixas_justificadas.produtos.to_remove', ['baixa_justificada_id' => $baixa_justificada->id, 'id' => $baixa_justificada_produto->id]) }}');" class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                </button>
                            @else
                                <button class="btn btn-danger btn-xs" disabled>
                                    <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                </button>
                            @endif
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $baixa_justificada_produtos->appends($formData)->links() !!}
        @else
            {!! $baixa_justificada_produtos->links() !!}
        @endif
    </div>
</div>
@endsection