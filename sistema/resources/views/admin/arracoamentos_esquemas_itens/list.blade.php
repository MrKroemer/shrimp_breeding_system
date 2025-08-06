@extends('adminlte::page')

@section('title', 'Registro de quantidades por períodos')

@section('content_header')
<h1>Listagem de quantidades por períodos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Perfis de arraçoamentos</a></li>
    <li><a href="">Esquemas de alimentação</a></li>
    <li><a href="">Quantidades por períodos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.arracoamentos_esquemas_itens.racoes.create')
@include('admin.arracoamentos_esquemas_itens.alimentacoes.create')

{{-- listagem das racoes atribuidas --}}
<div class="box">
    <div class="box-header">
        <div class="box-header">
            <a href="{{ route('admin.arracoamentos_perfis.arracoamentos_esquemas', ['arracoamento_perfil_id' => $arracoamento_perfil_id]) }}" class="btn btn-default">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>
        </div>
    </div>
    <div class="box-body">

        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Rações utilizadas</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ração</th>
                            <th>Porcentagem aplicada</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($arracoamentos_racoes as $arracoamento_racao)
                            <tr>
                                <td>{{ $arracoamento_racao->id }}</td>
                                <td>{{ $arracoamento_racao->racao->nome }}</td>
                                <td>{{ $arracoamento_racao->porcentagem }}%</td>
                                <td>
                                    <button type="button" onclick="onActionForRequest('{{ route('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_racoes.to_remove', ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'arracoamento_esquema_id' => $arracoamento_esquema_id, 'id' => $arracoamento_racao->id]) }}');" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a class="btn btn-xs btn-success" data-toggle="modal" data-target="#arracoamentos_racoes_modal">
                    <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
                </a>
            </div>
        </div>

        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Quantidade por período</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cardinal</th>
                            <th>Porcentagem aplicada</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($arracoamentos_alimentacoes as $arracoamento_alimentacao)
                            <tr>
                                <td>{{ $arracoamento_alimentacao->id }}</td>
                                @if($arracoamento_alimentacao->alimentacao_inicial == $arracoamento_alimentacao->alimentacao_final)
                                    <td>Na <b>{{ $arracoamento_alimentacao->alimentacao_inicial }}ª</b> alimentação</td>
                                    <td>{{ $arracoamento_alimentacao->porcentagem }}% do total de ração</td>
                                @else
                                    <td>Da <b>{{ $arracoamento_alimentacao->alimentacao_inicial }}ª</b> a <b>{{ $arracoamento_alimentacao->alimentacao_final }}ª</b> alimentação</td>
                                    <td>{{ $arracoamento_alimentacao->porcentagem }}% do total de ração, com {{ $arracoamento_alimentacao->porcentagem_alimentacoes() }}% em cada alimentação</td>
                                @endif
                                <td>
                                    <button type="button" onclick="onActionForRequest('{{ route('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_alimentacoes.to_remove', ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'arracoamento_esquema_id' => $arracoamento_esquema_id, 'id' => $arracoamento_alimentacao->id]) }}');" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a class="btn btn-xs btn-success" data-toggle="modal" data-target="#arracoamentos_alimentacoes_modal">
                    <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
