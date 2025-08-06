@extends('adminlte::page')

@section('title', 'Registro de saídas avulsas')

@section('content_header')
<h1>Listagem de produtos de saídas avulsas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Saídas avulsas</a></li>
    <li><a href="">Produtos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

@if ($saida_avulsa->situacao == 'N')
<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $quantidade = !empty(session('quantidade')) ? session('quantidade') : old('quantidade');
            $produto_id = !empty(session('produto_id')) ? session('produto_id') : old('produto_id');
        @endphp
        <form action="{{ route('admin.saidas_avulsas.produtos.to_store', ['saida_avulsa_id' => $saida_avulsa->id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-sm-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#add_produto" data-toggle="tab" aria-expanded="false">Produto avulso</a>
                            </li>
                            <li>
                                <a href="#add_receita" data-toggle="tab" aria-expanded="true">Receita laboratorial</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="add_produto" class="tab-pane active">
                                <div class="form-group">
                                    <label for="produto_id">Produto avulso:</label>
                                    <select name="produto_id" class="form-control">
                                        <option value="0">..:: Selecione ::..</option>
                                        @foreach($produtos as $produto)
                                            <option value="{{ $produto->produto_id }}">{{ $produto->produto_nome }} (Und.: {{ $produto->unidade_saida->sigla }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="qtd_produto">Quantidade:</label>
                                    <input type="number" step="any" name="qtd_produto" placeholder="Quantidade" class="form-control">
                                </div>
                            </div>
                            <div id="add_receita" class="tab-pane">
                                <div class="form-group">
                                    <label for="receita_laboratorial_id">Receita laboratorial:</label>
                                    <select name="receita_laboratorial_id" class="form-control">
                                        <option value="0">..:: Selecione ::..</option>
                                        @foreach($receitas as $receita)
                                            <option value="{{ $receita->id }}">{{ $receita->nome }} (Und.: {{ $receita->unidade_medida->sigla }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="qtd_receita">Quantidade:</label>
                                    <input type="number" step="any" name="qtd_receita" placeholder="Quantidade" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> Salvar
                </button>
                <a href="{{ route('admin.saidas_avulsas.to_search', ['id' => $saida_avulsa->id]) }}" class="btn btn-default">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endif

<div class="box">
    <div class="box-header">
    @if ($saida_avulsa->situacao != 'N')
        <a href="{{ route('admin.saidas_avulsas.to_search', ['id' => $saida_avulsa->id]) }}" class="btn btn-default">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
        </a>
    @endif
    </div>
    <div class="box-body">

        <form class="form form-inline">
            <div class="form-group" style="width:100%;">
                <label for="descricao">Justificativa da saída:</label>
                <textarea rows="2" name="descricao" placeholder="Justificativa da saída" class="form-control" style="width:100%;" disabled>{{ $saida_avulsa->descricao }}</textarea>
            </div>
        </form>

        <h5 class="box-title" style="font-weight: bold;">Saídas para o <span style="color: red;">{{ $saida_avulsa->tanque->sigla }}</span> referentes a <span style="color: red;">{{ $saida_avulsa->data_movimento() }}</span></h5>
        
        @if($saida_avulsa_produtos->isNotEmpty())
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto avulso</th>
                        <th>Quantidade</th>
                        <th>Registrado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($saida_avulsa_produtos as $saida_avulsa_produto)
                        <tr>
                            <td>{{ $saida_avulsa_produto->produto_id }}</td>
                            <td>{{ $saida_avulsa_produto->produto->nome }}</td>
                            <td>{{ (float) $saida_avulsa_produto->quantidade }} {{ $saida_avulsa_produto->produto->unidade_saida->sigla }}</td>
                            <td>{{ $saida_avulsa_produto->alterado_em('d/m/Y H:i') }}</td>
                            <th>
                                @if ($saida_avulsa->situacao == 'N')
                                    <button type="button" onclick="onActionForRequest('{{ route('admin.saidas_avulsas.produtos.to_remove', ['saida_avulsa_id' => $saida_avulsa->id, 'id' => $saida_avulsa_produto->id]) }}');" class="btn btn-danger btn-xs">
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
                {!! $saida_avulsa_produtos->appends($formData)->links() !!}
            @else
                {!! $saida_avulsa_produtos->links() !!}
            @endif
        @endif

        @if($saida_avulsa_receitas->isNotEmpty())
            <hr>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Receita laboratorial</th>
                        <th>Quantidade</th>
                        <th>Registrado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($saida_avulsa_receitas as $saida_avulsa_receita)
                        <tr>
                            <td>{{ $saida_avulsa_receita->receita_laboratorial_id }}</td>
                            <td>{{ $saida_avulsa_receita->receita_laboratorial->nome }}</td>
                            <td>{{ (float) $saida_avulsa_receita->quantidade }} {{ $saida_avulsa_receita->receita_laboratorial->unidade_medida->sigla }}</td>
                            <td>{{ $saida_avulsa_receita->alterado_em('d/m/Y H:i') }}</td>
                            <th>
                                @if ($saida_avulsa->situacao == 'N')
                                    <button type="button" onclick="onActionForRequest('{{ route('admin.saidas_avulsas.receitas.to_remove', ['saida_avulsa_id' => $saida_avulsa->id, 'id' => $saida_avulsa_receita->id]) }}');" class="btn btn-danger btn-xs">
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
                {!! $saida_avulsa_receitas->appends($formData)->links() !!}
            @else
                {!! $saida_avulsa_receitas->links() !!}
            @endif
        @endif

    </div>
</div>
@endsection