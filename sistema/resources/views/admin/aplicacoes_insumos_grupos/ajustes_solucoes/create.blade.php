@extends('adminlte::page')

@section('title', 'Registro de aplicações de insumos')

@section('content_header')
<h1>Registro de aplicações de insumos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Aplicações de insumos</a></li>
    <li><a href="">Ajuste de soluções</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <div class="row">
            @php
                $data_aplicacao = isset($data_aplicacao) ? $data_aplicacao : date('d/m/Y');
                $receita_laboratorial_id = isset($receita_laboratorial_id) ? $receita_laboratorial_id : 0;
            @endphp
            <form id="form_aplicacoes_por_receitas" action="{{ route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.ajustes.to_create', ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo_id]) }}" method="POST">
                {!! csrf_field() !!}
                <div class="col-lg-3">
                    <label for="data_aplicacao">Data das aplicações</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" id="date_picker" name="data_aplicacao" placeholder="Data das aplicações" class="form-control pull-right" value="{{ $data_aplicacao }}" onchange="submitForm('form_aplicacoes_por_receitas');">
                    </div>
                </div>
                <div class="col-lg-3">
                    <label for="receita_laboratorial_id">Receita para ajuste:</label>
                    <select name="receita_laboratorial_id" class="form-control" onchange="submitForm('form_aplicacoes_por_receitas');">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($receitas_laboratoriais as $receita_laboratorial)
                            <option value="{{ $receita_laboratorial->id }}" {{ ($receita_laboratorial->id == $receita_laboratorial_id) ? 'selected' : '' }}>{{ $receita_laboratorial->nome }} (Und.: {{ $receita_laboratorial->unidade_medida->sigla }})</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>
    <div class="box-body">
        <form id="form_quantidades_solvente" action="{{ route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.ajustes.to_store', 
            ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo_id, 'receita_laboratorial_id' => $receita_laboratorial_id, 'data_aplicacao' => $data_aplicacao,]
        ) }}" method="POST">
        {!! csrf_field() !!}
            <table class="table table-striped table-hover">
                <thead></thead>
                <tbody>
                @foreach ($aplicacoes_insumos as $aplicacao_insumo)
                <tr>
                    <td>
                        <span class="label label-default" style="font-size: 14px;">{{ $aplicacao_insumo->tanque->sigla }}</span>
                    </td>
                    <td>
                        @forelse ($aplicacao_insumo->aplicacoes_insumos_receitas as $aplicacao_receita)

                            @if ($aplicacao_receita->receita_laboratorial->id == $receita_laboratorial_id || $receita_laboratorial_id == 0)
                                @php
                                    $naoPossuiSolvente = is_null($aplicacao_receita->solvente);

                                    if ($naoPossuiSolvente && $total_receita > 0) {
                                        
                                        $aplicacao_receita->solvente = round(($aplicacao_receita->quantidade / $total_receita) * ($qtd_base - $total_receita));

                                        $aplicacao_receita->solvente = round($aplicacao_receita->solvente / 5) * 5;

                                        $total_solvente += $aplicacao_receita->solvente;
                                        $total_solucao += $aplicacao_receita->solvente;
                                        $total_ajuste -= $aplicacao_receita->solvente;

                                    }
                                @endphp
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="receita_nome_{{ $aplicacao_receita->id }}">Receita</label>
                                        <input name="receita_nome_{{ $aplicacao_receita->id }}" value="{{ $aplicacao_receita->receita_laboratorial->nome }}" type="text" class="form-control" placeholder="Receita" disabled>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="quantidade_{{ $aplicacao_receita->id }}">Quantidade</label>
                                        <input name="quantidade_{{ $aplicacao_receita->id }}" value="{{ (float) $aplicacao_receita->quantidade }}" type="number" step="any" class="form-control" placeholder="Quantidade" disabled>
                                    </div>
                                @if ($receita_laboratorial_id > 0 && $possuiEstaReceita)
                                    <div class="col-lg-2">
                                        <label for="solvente_{{ $aplicacao_receita->id }}" style="color: {{ $naoPossuiSolvente ? 'red' : 'green' }};">Solvente</label>
                                        <input name="solvente_{{ $aplicacao_receita->id }}" value="{{ (float) $aplicacao_receita->solvente }}" type="number" step="any" class="form-control" placeholder="Solvente" oninput="onChangeSolventeInsumos('form_quantidades_solvente');">
                                    </div>
                                @endif
                                </div>
                                <hr style="margin: 5px 0 5px 0;">
                            @endif

                        @empty
                            <span style="font-weight: bold; color: red;">RECEITAS NÃO DEFINIDOS</span>
                        @endforelse
                    </td>
                </tr>
                @endforeach

                @if ($receita_laboratorial_id > 0 && $possuiEstaReceita)
                    <tr>
                        <td colspan="2">
                            <label>Total da receita: <span id="t_receita" style="color: blue;">{{ (float) $total_receita }}</span> {{ $unidade_receita }}</label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <label>Total de solvente: <span id="t_solvente" style="color: blue;">{{ (float) $total_solvente }}</span> {{ $unidade_receita }}</label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <label>Total de solução: <span id="t_solucao" style="color: {{ ($total_solucao < $qtd_base) ? 'red' : 'green' }};">{{ (float) $total_solucao }}</span> {{ $unidade_receita }}</label>
                            @if ($total_solucao < $qtd_base)
                                <label>(Faltaram <span id="t_faltam" style="color: red;">{{ $total_ajuste }}</span> para <span id="t_base" style="color: blue;">{{ $qtd_base }}</span> {{ $unidade_receita }})</label>
                            @elseif($total_solucao > $qtd_base)
                                <label>(Sobraram <span id="t_faltam" style="color: green;">{{ ($total_ajuste * -1) }}</span> para <span id="t_base" style="color: blue;">{{ $qtd_base }}</span> {{ $unidade_receita }})</label>
                            @endif
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
            <div style="margin-top: 10px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> Salvar
                </button>
                @if ($aplicacao_insumo_grupo_id > 0)
                    <a href="{{ route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_view', 
                        ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo_id, 'data_aplicacao' => $data_aplicacao]
                    ) }}" class="btn btn-success">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                    </a>
                @endif
                @if ($receita_laboratorial_id > 0 && $possuiEstaReceita)
                    <button type="button" class="btn btn-warning" onclick="calcularSolventeInsumos('form_quantidades_solvente');">
                        <i class="fa fa-calculator" aria-hidden="true"></i> Calcular solvente
                    </button>
                @endif
                @if (! empty($data_aplicacao) && ! empty($aplicacoes_insumos))
                    <button type="sudmit" form="gerar_fichas_aplicacoes" class="btn btn-info">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Gerar fichas
                    </button>
                @endif
            </div>
        </form>
        @if (! empty($data_aplicacao) && ! empty($aplicacoes_insumos))
            <form id="gerar_fichas_aplicacoes" action="{{ route('admin.aplicacoes_insumos_grupos.fichas.to_view') }}" method="POST" target="_blank">
                {!! csrf_field() !!}
                <input type="hidden" name="aplicacao_insumo_grupo_id" value="{{ $aplicacao_insumo_grupo_id }}">
                <input type="hidden" name="data_aplicacao" value="{{ $data_aplicacao }}">
                <input type="hidden" name="receita_laboratorial_id" value="{{ $receita_laboratorial_id }}">
            </form>
        @endif
    </div>
</div>
@endsection
