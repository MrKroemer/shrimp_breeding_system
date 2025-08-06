@extends('adminlte::page')

@section('title', 'Registro de filiais')

@section('content_header')
<h1>Edição de filiais</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Filiais</a></li>
    <li><a href="">Edição</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.filiais.to_update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" placeholder="Nome" class="form-control" value="{{ $nome }}">
            </div>
            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <input type="text" name="cidade" placeholder="Cidade" class="form-control" value="{{ $cidade }}">
            </div>
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" name="endereco" placeholder="Endereço" class="form-control" value="{{ $endereco }}">
            </div>
            <div class="form-group">
                <label for="cnpj">CNPJ:</label>
                <input type="text" name="cnpj" placeholder="CNPJ" class="form-control" value="{{ $cnpj }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.filiais') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection