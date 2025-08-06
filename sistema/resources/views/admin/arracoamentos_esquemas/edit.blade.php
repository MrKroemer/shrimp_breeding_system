@extends('adminlte::page')

@section('title', 'Registro de esquemas de alimentação')

@section('content_header')
<h1>Cadastro de esquemas de alimentação</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Esquemas de alimentação</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.arracoamentos_perfis.arracoamentos_esquemas.to_update', ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'id' => $id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label>Perfil de arraçoamento:</label>
                <h5 class="box-title">{{ $arracoamento_perfil->nome }} ( {{ $arracoamento_perfil->descricao }} )</h5>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="input-group">
                            <label for="dia_inicial">Primeiro dia:</label>
                            <input type="text" name="dia_inicial" placeholder="Primeiro dia" class="form-control" value="{{ $dia_inicial }}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="input-group">
                            <label for="dia_final">Último dia:</label>
                            <input type="text" name="dia_final" placeholder="Último dia" class="form-control" value="{{ $dia_final }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="periodo">Período:</label>
                <select name="periodo" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($periodos as $key => $value)
                        <option value="{{ $key }}" {{ ($key == $periodo) ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição/Observação:</label>
                <input type="text" name="descricao" placeholder="Descrição/Observação" class="form-control" value="{{ $descricao }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.arracoamentos_perfis.arracoamentos_esquemas', ['arracoamento_perfil_id' => $arracoamento_perfil_id]) }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection