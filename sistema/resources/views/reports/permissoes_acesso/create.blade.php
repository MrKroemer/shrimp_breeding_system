@extends('adminlte::page')

@section('title', 'Relatório de permissões de acesso')

@section('content_header')
<h1>Relatório de permissões de acesso</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Relatório de permissões de acesso</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.permissoes_acesso.to_view') }}" method="POST" target="_blank">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="filiais[]">Filiais:</label>
                <select name="filiais[]" class="select2_multiple" multiple="multiple">
                    @foreach ($filiais as $filial)
                        <option value="{{ $filial->id }}">{{ $filial->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="usuarios[]">Usuários:</label>
                <select name="usuarios[]" class="select2_multiple" multiple="multiple">
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->nome }} ({{ $usuario->username }}), {{ $usuario->situacao == 'ON' ? 'Ativo' : 'Inativo' }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Gerar relatório">
            </div>
        </form>
    </div>
</div>
@endsection