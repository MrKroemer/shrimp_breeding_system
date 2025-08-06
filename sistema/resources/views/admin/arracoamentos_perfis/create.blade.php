@extends('adminlte::page')

@section('title', 'Registro de perfis de arraçoamentos')

@section('content_header')
<h1>Cadastro de perfis de arraçoamentos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Perfis de arraçoamentos</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $nome      = !empty(session('nome'))      ? session('nome')      : old('nome');
            $descricao = !empty(session('descricao')) ? session('descricao') : old('descricao');
        @endphp
        <form action="{{ route('admin.arracoamentos_perfis.to_store') }}" method="post" enctype="multipart/form-data">
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
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.arracoamentos_perfis') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection