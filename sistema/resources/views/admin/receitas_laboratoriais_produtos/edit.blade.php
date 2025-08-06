@extends('adminlte::page')

@section('title', 'Registro de produtos de receitas laboratoriais')

@section('content_header')
<h1>Edição de produtos de receitas laboratoriais</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Receitas laboratoriais</a></li>
    <li><a href="">Produtos de receitas laboratoriais</a></li>
    <li><a href="">Edição</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos.to_update', ['receita_laboratorial_id' => $receita_laboratorial->id, 'receita_laboratorial_periodo_id' => $receita_laboratorial_periodo->id, 'id' => $id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label>Receita:</label>
                <h5>{{ $receita_laboratorial->nome }} / {{ $receita_laboratorial_periodo->periodo($receita_laboratorial_periodo->periodo) }} {{ $receita_laboratorial_periodo->dia_base }}º DIA</h5>
            </div>
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
                <input type="text" name="quantidade" placeholder="Quantidade" class="form-control" value="{{ $quantidade }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos', ['receita_laboratorial_id' => $receita_laboratorial->id, 'receita_laboratorial_periodo_id' => $receita_laboratorial_periodo->id]) }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection