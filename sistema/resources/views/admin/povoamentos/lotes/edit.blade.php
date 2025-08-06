@extends('adminlte::page')

@section('title', 'Registro de povoamentos de tanques')

@section('content_header')
<h1>Edição de povoamentos de tanques</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Povoamentos de tanques</a></li>
    <li><a href="">Edição</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.povoamentos.lotes.to_update', ['povoamento_id' => $povoamento->id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="ciclo_id">Ciclo:</label>
                <select name="ciclo_id" class="form-control" disabled>
                    <option value="{{ $povoamento->ciclo_id }}">{{ $povoamento->ciclo->tanque->sigla }} ( Ciclo Nº {{ $povoamento->ciclo->numero }} iniciado em {{ $povoamento->ciclo->data_inicio() }} )</option>
                </select>
            </div>
            <div class="form-group">
                <label for="lote_id">Lote de pós-larvas:</label>
                <div class="input-group">
                    <select name="lote_id" class="form-control">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($lotes as $lote)
                            <option value="{{ $lote->id }}">{{ (float) $lote->quantidade }} UND ( NFe: {{ $lote->estoque_entrada->estoque_entrada_nota->nota_fiscal->numero }} ) ( Lote de fabri.: {{ $lote->lote_fabricacao }} ) ( ID: {{ $lote->id }} ) {{ is_null($lote->lote_biometria) ? '[ BIOMETRIA NÃO INFORMADA ]' : '' }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <a href="{{ route('admin.povoamentos.redirect_to.lotes.to_create', ['povoamento_id' => $povoamento->id]) }}" class="btn btn-success btn-flat">
                            <i class="fa fa-plus" aria-hidden="true"></i> Lotes de PL's
                        </a>
                    </span>
                </div>
            </div>
            <div class="form-group">
                @if ($lotes->isNotEmpty())
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save" aria-hidden="true"></i> Salvar
                    </button>
                @endif
                <a href="{{ route('admin.povoamentos') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>

@if ($povoamento->lotes->isNotEmpty())
    <div class="box">
        <div class="box-header">
            <form id="encerrar_povoamento_form" action="{{ route('admin.povoamentos.to_close', ['id' => $povoamento->id]) }}" method="POST" class="form form-inline">
                {!! csrf_field() !!}
                <div class="form-group">
                    <div class="input-group date">
                        <div class="input-group-addon"><b>Início</b></div>
                        <input type="text" name="data_inicio" placeholder="Data de início" class="form-control pull-right" id="datetime_picker" value="{{ $povoamento->data_inicio() }}" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            @if($povoamento->ciclo->situacao == 5)
                                <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                            @else
                                <b>Fim</b>
                            @endif
                        </div>
                        <input type="text" name="data_fim" placeholder="Data de encerramento" class="form-control pull-right" id="datetime_picker" value="{{ $povoamento->data_fim() }}" {{ ($povoamento->ciclo->situacao == 5) ? '' : 'disabled' }}>
                    </div>
                </div>
                <div class="form-group">
                @if ($povoamento->lotes->where('situacao', 'N')->isNotEmpty())
                    <button type="button" class="btn btn-warning" onclick="onActionForSubmit('encerrar_povoamento_form');">
                        <i class="fa fa-check" aria-hidden="true"></i> 
                        {{ ($povoamento->ciclo->situacao == 5) ? 'Encerrar' : 'Retificar povoamento' }}
                    </button>
                @elseif ($povoamento->lotes->where('situacao', 'P')->isNotEmpty())
                    <button type="button" class="btn btn-danger" disabled>
                        <i class="fa fa-ban" aria-hidden="true"></i> Bloqueado
                    </button>
                @endif
                </div>
            </form>
        </div>
        <div class="box-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID do lote</th>
                        <th>Nº de fabricação</th>
                        <th>Classe / Idade</th>
                        <th>Quantidade</th>
                        <th>Data de vinculação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($povoamento->lotes as $lote)
                        <tr>
                            <td>{{ $lote->lote->id }}</td>
                            <td>Nº 
                                @if ($lote->lote->lote_fabricacao)
                                    {{ $lote->lote->lote_fabricacao }}
                                @else
                                    @foreach($lote->lote->lote_larvicultura()->get() as $lote_larvicultura)
                                        {{ $lote_larvicultura->lote }}/
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ $lote->lote->classe_idade }}</td>
                            <td>{{ $lote->lote->quantidade }} UND</td>
                            <td>{{ $lote->data_vinculo() }}</td>
                            <td>
                                @if ($lote->situacao == 'N')
                                    <button type="button" class="btn btn-danger btn-xs" onclick="onActionForRequest('{{ route('admin.povoamentos.lotes.to_remove', ['povoamento_id' => $povoamento->id, 'id' => $lote->id]) }}');">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                @else
                                    <button type="button" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@endsection
