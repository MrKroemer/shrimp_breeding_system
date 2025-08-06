@extends('adminlte::page')

@section('title', 'Registro de tipos de preparações')

@section('content_header')
<h1>Cadastro de tipos de preparações</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Tipos de preparações</a></li>
    <li><a href="">Edição</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.preparacoes_tipos.to_update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}  
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" placeholder="Nome" class="form-control" value="{{ $nome }}">
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <input type="text" name="descricao" placeholder="Descrição" class="form-control" value="{{ $descricao }}">
            </div>
            <div class="form-group">
                <label for="metodo">Método de cultivo:</label>
                <select name="metodo" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach ($metodos as $key => $value)
                        <option value="{{ $key }}" {{ ($key == $metodo) ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.preparacoes_tipos') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection