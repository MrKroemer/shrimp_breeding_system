@extends('adminlte::page')

@section('title', 'Registro de períodos climáticos')

@section('content_header')
<h1>Edição de períodos climáticos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Períodos climáticos</a></li>
    <li><a href="">Edição</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.arracoamentos_climas.to_update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
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
                <a href="{{ route('admin.arracoamentos_climas') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection