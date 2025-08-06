@extends('adminlte::page')

@section('title', 'Registro de produtos para receitas laboratoriais')

@section('content_header')
<h1>Produtos para receitas laboratoriais</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Receitas laboratoriais</a></li>
    <li><a href="">Registro de produtos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $quantidade = !empty(session('quantidade')) ? session('quantidade') : old('quantidade');
            $produto_id = !empty(session('produto_id')) ? session('produto_id') : old('produto_id');
        @endphp
        <form action="{{ route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos.to_store', ['receita_laboratorial_id' => $receita_laboratorial->id, 'receita_laboratorial_periodo_id' => $receita_laboratorial_periodo->id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="produto_id">Produto:</label>
                <select name="produto_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($produtos as $produto)
                        <option value="{{ $produto->produto_id }}" {{ ($produto->produto_id == $produto_id) ? 'selected' : '' }}>{{ $produto->produto_nome }} (Und.: {{ $produto->unidade_saida->sigla }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade por {{ $receita_laboratorial->unidade_medida->sigla }} da receita:</label>
                <input type="number" step="any" name="quantidade" placeholder="Quantidade" class="form-control" value="{{ $quantidade }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos', ['receita_laboratorial_id' => $receita_laboratorial->id]) }}" class="btn btn-default">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <h5 class="box-title">{{ $receita_laboratorial->nome }} / {{ $receita_laboratorial_periodo->periodo($receita_laboratorial_periodo->periodo) }} {{ $receita_laboratorial_periodo->dia_base }}º DIA</h5>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receitas_laboratoriais_produtos as $receita_laboratorial_produto)
                    <tr>
                        <td>{{ $receita_laboratorial_produto->produto->nome }}</td>
                        <td>Adicionar {{ $receita_laboratorial_produto->quantidade() }} {{ $receita_laboratorial_produto->produto->unidade_saida->sigla }} para cada {{ $receita_laboratorial_produto->receita_laboratorial->unidade_medida->sigla }} da receita</td>
                        <td>
                            <a href="{{ route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos.to_edit', ['receita_laboratorial_id' => $receita_laboratorial->id, 'receita_laboratorial_periodo_id' => $receita_laboratorial_periodo->id, 'id' => $receita_laboratorial_produto->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos.to_remove', ['receita_laboratorial_id' => $receita_laboratorial->id, 'receita_laboratorial_periodo_id' => $receita_laboratorial_periodo->id, 'id' => $receita_laboratorial_produto->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $receitas_laboratoriais_produtos->appends($formData)->links() !!}
        @else
            {!! $receitas_laboratoriais_produtos->links() !!}
        @endif
    </div>
</div>
@endsection
