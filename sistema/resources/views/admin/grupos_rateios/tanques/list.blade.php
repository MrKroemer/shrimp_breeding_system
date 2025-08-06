@extends('adminlte::page')

@section('title', 'Registro de grupos de rateios')

@section('content_header')
<h1>Registro de tanques para grupos de rateios</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Grupos de rateios</a></li>
    <li><a href="">Tanques</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.grupos_rateios.grupos_tanques.to_store', ['grupo_rateio_id' => $grupo_rateio_id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="grupo">Grupo:</label>
                <input type="text" name="nome" placeholder="Nome" class="form-control" value="{{ $grupo_rateio->nome }}" disabled>
            </div>
            <div class="form-group">
                <label for="tanques[]">Tanques:</label>
                <select name="tanques[]" class="select2_multiple" multiple="multiple">
                    @foreach ($tanques as $tanque)
                        <option value="{{ $tanque->id }}">{{ $tanque->sigla }} ( {{ $tanque->tanque_tipo->nome }} )</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="tipo">Finalidade:</label>
                <select name="tipo" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    <option value="E">ENVIAR CUSTO</option>
                    <option value="R">RECEBER CUSTO</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> Salvar
                </button>
                <a href="{{ route('admin.grupos_rateios') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
        
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-default box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Enviam custos</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Sigla</th>
                                        <th>Tipo</th>
                                        <th>Setor</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enviam as $tanque)
                                        <tr>
                                            <td>{{ $tanque->tanque->sigla }}</td>
                                            <td>{{ $tanque->tanque_tipo->nome }}</td>
                                            <td>{{ $tanque->setor->nome }}</td>
                                            <td>
                                                <button type="button" onclick="onActionForRequest('{{ route('admin.grupos_rateios.grupos_tanques.to_remove', ['grupo_rateio_id' => $tanque->grupo_rateio_id, 'id' => $tanque->id]) }}');" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                                <a href="{{ route('admin.grupos_rateios.grupos_tanques.to_turn', ['grupo_rateio_id' => $tanque->grupo_rateio_id, 'id' => $tanque->id]) }}" class="btn btn-xs btn-success">
                                                    <i class="fa fa-forward" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-default box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Recebem custos</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Ações</th>
                                        <th>Sigla</th>
                                        <th>Tipo</th>
                                        <th>Setor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recebem as $tanque)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.grupos_rateios.grupos_tanques.to_turn', ['grupo_rateio_id' => $tanque->grupo_rateio_id, 'id' => $tanque->id]) }}" class="btn btn-xs btn-success">
                                                    <i class="fa fa-backward" aria-hidden="true"></i>
                                                </a>
                                                <button type="button" onclick="onActionForRequest('{{ route('admin.grupos_rateios.grupos_tanques.to_remove', ['grupo_rateio_id' => $tanque->grupo_rateio_id, 'id' => $tanque->id]) }}');" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                            <td>{{ $tanque->tanque->sigla }}</td>
                                            <td>{{ $tanque->tanque_tipo->nome }}</td>
                                            <td>{{ $tanque->setor->nome }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection