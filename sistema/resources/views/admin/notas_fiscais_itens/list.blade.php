@extends('adminlte::page')
@php
    use App\Http\Controllers\Util\DataPolisher;
@endphp
@section('title', 'Registro de itens de notas fiscais')

@section('content_header')
<h1>Listagem de itens de notas fiscais</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Notas fiscais</a></li>
    <li><a href="">Itens de notas fiscais</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

@php
    $params['nota_fiscal_id'] = $nota_fiscal_id;
    
    if ($redirectBack == 'yes') {
        $params['redirectBack'] = $redirectBack;
    }
@endphp

<div class="box">
    <div class="box-header">
        <form action="{{ route("admin.{$route}.notas_fiscais_itens.to_search", $params) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>

            @if ($nota_fiscal->situacao == 'A')
                <a href="{{ route("admin.{$route}.notas_fiscais_itens.to_create", $params) }}" class="btn btn-success">
                    <i class="fa fa-plus" aria-hidden="true"></i> Adicionar item
                </a>
            @endif

            @php
                unset($params['nota_fiscal_id']);
                $params['id'] = $nota_fiscal_id; // Adição do ID da nota fiscal
            @endphp

            @if ($nota_fiscal->situacao == 'A' && $notas_fiscais_itens->count() > 0)
                <button type="button" onclick="onActionForRequest('{{ route("admin.{$route}.to_close", $params) }}');" class="btn btn-warning">
                    <i class="fa fa-check" aria-hidden="true"></i> Fechar nota
                </button>
            @endif

            @if ($redirectBack == 'yes')
                <a href="{{ route('admin.notas_fiscais_entradas.redirect_to.lotes.to_create') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Lotes de pós-larvas
                </a>
            @else
                <a href="{{ route("admin.{$route}") }}" class="btn btn-default">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            @endif
        </form>
    </div>
    <div class="box-body">
        <h5 class="box-title">
            Nota fiscal Nº {{ $nota_fiscal->numero }} / Chave de acesso: <a class="btn btn-default btn-xs" onclick="consultaNfe('{{ $nota_fiscal->chave }}');">{{ $nota_fiscal->chave }}</a>
            @if ($redirectBack == 'yes')
                <a href="{{ route('admin.notas_fiscais_entradas.to_edit', $params) }}" class="btn btn-warning btn-xs">
                    <i class="fa fa-edit" aria-hidden="true"></i> Editar
                </a>
            @endif
        </h5>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor unitário</th>
                    <th>Valor total</th>
                    <th>Valor do frete</th>
                    <th>Valor do desconto</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>

                @php
                    unset($params['id']); // Remoção do ID da nota fiscal
                    $params['nota_fiscal_id'] = $nota_fiscal_id;
                @endphp

                @foreach($notas_fiscais_itens as $nota_fiscal_item)
                    <tr>
                        <td>{{ $nota_fiscal_item->id }}</td>
                        <td>{{ $nota_fiscal_item->produto->nome }}</td>
                        <td>
                            {{ DataPolisher::numberFormat($nota_fiscal_item->quantidade, 0) }}
                            {{ $nota_fiscal_item->produto->unidade_entrada->sigla }}
                           ({{ DataPolisher::numberFormat($nota_fiscal_item->quantidade * $nota_fiscal_item->produto->unidade_razao, 0) }}
                            {{ $nota_fiscal_item->produto->unidade_saida->sigla }})
                        </td>
                        <td>R$ {{ DataPolisher::numberFormat($nota_fiscal_item->valor_unitario) }}</td>
                        <td>R$ {{ DataPolisher::numberFormat($nota_fiscal_item->valor_unitario * $nota_fiscal_item->quantidade) }}</td>
                        <td>R$ {{ DataPolisher::numberFormat($nota_fiscal_item->valor_frete) }}</td>
                        <td>R$ {{ DataPolisher::numberFormat($nota_fiscal_item->valor_desconto) }}</td>
                        <td>
                        @php
                            $params['id'] = $nota_fiscal_item->id; // Adição do ID do item da nota fiscal
                        @endphp

                        @if ($nota_fiscal->situacao == 'A')
                            <a href="{{ route("admin.{$route}.notas_fiscais_itens.to_edit", $params) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route("admin.{$route}.notas_fiscais_itens.to_remove", $params) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        @else
                            <a href="{{ route("admin.{$route}.notas_fiscais_itens.to_view", $params) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-eye" aria-hidden="true"></i> Visualizar 
                            </a>
                        @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $notas_fiscais_itens->appends($formData)->links() !!}
        @else
            {!! $notas_fiscais_itens->links() !!}
        @endif
    </div>
</div>
@endsection
