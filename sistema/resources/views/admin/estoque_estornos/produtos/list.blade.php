@extends('adminlte::page')

@section('title', 'Histórico de estorno de produtos')

@section('content_header')
<h1>Histórico de estorno de produtos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Estorno de produtos</a></li>
    <li><a href="">Produtos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.estoque_estornos.produtos.to_search', ['estorno_justificativa_id' => $estorno_justificativa->id]) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <select name="produto_id" class="form-control" style="width: 285px;">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($produtos as $produto)
                        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            <a href="{{ route('admin.estoque_estornos.to_search', ['id' => $estorno_justificativa->id]) }}" class="btn btn-default">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>
        </form>
    </div>
    <div class="box-body">
        
        <form class="form form-inline">
            <div class="form-group" style="width:100%;">
                <label for="descricao">Justificativa de estorno:</label>
                <textarea rows="2" name="descricao" placeholder="Justificativa de estorno." class="form-control" style="width:100%;" disabled>{{ $estorno_justificativa->descricao }}</textarea>
            </div>
        </form>

        <h5 class="box-title" style="font-weight: bold;">Realizado em: <span style="color: red;">{{ $estorno_justificativa->alterado_em('d/m/Y \à\s H:i') }}</span>, Origem: <span style="color: red;">{{ $estorno_justificativa->tipo_origem() }}</span></h5>
        
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID da saída</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor unitário</th>
                    <th>Valor total</th>
                    <th>Data do movimento</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estoque_estornos as $estoque_estorno)
                    <tr>
                        <td>{{ $estoque_estorno->estoque_saida->id }}</td>
                        <td>{{ $estoque_estorno->produto->nome }}</td>
                        <td>{{ (float) $estoque_estorno->quantidade }} {{ $estoque_estorno->produto->unidade_saida->sigla }}</td>
                        <td>R$ {{ (float) $estoque_estorno->valor_unitario }}</td>
                        <td>R$ {{ (float) $estoque_estorno->valor_total }}</td>
                        <td>{{ $estoque_estorno->data_movimento() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $estoque_estornos->appends($formData)->links() !!}
        @else
            {!! $estoque_estornos->links() !!}
        @endif
    </div>
</div>
@endsection