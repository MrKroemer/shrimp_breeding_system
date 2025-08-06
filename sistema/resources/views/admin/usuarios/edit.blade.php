@extends('adminlte::page')

@section('title', 'Registro de usuários')

@section('content_header')
<h1>Edição de usuários</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Usuários</a></li>
    <li><a href="">Edição</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.usuarios.to_update', ['id' => $id]) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" placeholder="Nome" class="form-control" value="{{ $nome }}">
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" name="email" placeholder="E-mail" class="form-control" value="{{ $email }}">
            </div>
            <div class="form-group">
                <label for="username">Login:</label>
                <input type="text" name="username" placeholder="Login" class="form-control" value="{{ $username }}">
            </div>
            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" name="password" placeholder="Senha" class="form-control">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirmar senha:</label>
                <input type="password" name="password_confirmation" placeholder="Confirmar senha" class="form-control">
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="imagem">Foto do usuário:</label>
                        <!-- image-preview-filename input [CUT FROM HERE]-->
                        <div class="input-group image-preview">
                            <input type="text" class="form-control image-preview-filename" disabled="disabled"> <!-- don't give a name === doesn't send on POST/GET -->
                            <span class="input-group-btn">
                                <!-- image-preview-clear button -->
                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                    <span class="glyphicon glyphicon-remove"></span> Remover
                                </button>
                                <!-- image-preview-input -->
                                <div class="btn btn-default image-preview-input">
                                    <span class="glyphicon glyphicon-folder-open"></span>
                                    <span class="image-preview-input-title">Adicionar</span>
                                    <input type="file" name="imagem" accept="image/png, image/jpeg"/> <!-- rename it -->
                                </div>
                            </span>
                        </div>
                        <!-- /input-group image-preview [TO HERE]--> 
                    </div>
                    <div class="col-md-6">
                        <div class="pull-left image" style="border: 1px solid #000">
                            <img src="{{ $imagem ? asset('storage/'.$imagem) : asset('img/user.png') }}" class="img-circle" alt="User image" width="80" height="80">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i>Salvar</button>
                <a href="{{ route('admin.usuarios') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection