@extends('adminlte::page')

@section('title', 'Registro de coletas de parametros')

@section('content_header')
<h1>Registro de amostras</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Listagem de coletas de parâmetros</a></li>
    <li><a href="">Registro de amostras</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <div class="row">
            <div class="col-md-3">
                <label for="data_coleta">Data da coleta</label>
                <input type="text" name="data_coleta" class="form-control" value="{{ $coleta_parametro->data_coleta() }}" disabled>
            </div>
            <div class="col-md-3">
                <label for="tanque_tipo">Tipo de tanque</label>
                <input type="text" name="tanque_tipo" class="form-control" value="{{ $coleta_parametro->tanque_tipo->nome }}" disabled>
            </div>
            <div class="col-md-3">
                <label for="coleta_parametro_tipo">Parâmetro</label>
                <input type="text" name="coleta_parametro_tipo" class="form-control" value="{{ $coleta_parametro->coleta_parametro_tipo->descricao }} ({{ $coleta_parametro->coleta_parametro_tipo->sigla }})" disabled>
            </div>
            <div class="col-md-3">
                <label for="sonda_laboratorial">Sonda Laboratorial</label>
                <input type="text" name="sonda_laboratorial" class="form-control" value="{{ $coleta_parametro->sonda_laboratorial->marca }} ({{ $coleta_parametro->sonda_laboratorial->nome }})" disabled>
            </div>
        </div>
    </div>
    <div class="box-body">
        <form id="form_coleta_parametro_amostras" action="{{ route('admin.coletas_parametros_new.amostras.to_store', ['coleta_parametro_id' => $coleta_parametro->id]) }}" method="POST" onchange="submitByAjax(this.id);">
            {!! csrf_field() !!}
            <div style="margin-bottom: 20px;">
                <button type="button" onclick="submitFormAlt('fator',1,'form_coleta_parametro_amostras');" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> Salvar
                </button>
                <a href="{{ route('admin.coletas_parametros_new') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
            <table class="table table-striped table-hover">
                @foreach ($tanques as $tanque)
                @php
                    $medicao1     = null;
                    $medicao2     = null;
                    $medicao1_ant = null;
                    $medicao2_ant = null;

                    $coleta_atual = $coleta_parametro->amostras->where('tanque_id', $tanque->id)->sortByDesc('id');

                    $medicao1 = $coleta_atual->where('medicao', 1)->first();
                    $medicao2 = $coleta_atual->where('medicao', 2)->first();

                    if (! is_null($coleta_parametro_ant)) {

                        $coleta_anterior = $coleta_parametro_ant->amostras->where('tanque_id', $tanque->id)->sortByDesc('id');

                        $medicao1_ant = $coleta_anterior->where('medicao', 1)->first();
                        $medicao2_ant = $coleta_anterior->where('medicao', 2)->first();

                    }

                    $calculaVariacaoMedicoes = $tanque->id . ',' . (isset($medicao1->id) ? $medicao1->id : 0) . ',' . (isset($medicao2->id) ? $medicao2->id : 0);
                @endphp
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-md-1">
                                <span class="label label-default" style="font-size: 14px;">{{ $tanque->sigla }}</span>
                            </div>

                            @if (! is_null($coleta_parametro_ant))
                            <div class="col-md-2">
                                <label for="medicao1_ant_{{ $tanque->id }}">Medição nº1 anterior:</label>
                                <input 
                                name="medicao1_ant_{{ $tanque->id }}" 
                                value="{{ isset($medicao1_ant->valor) ? $medicao1_ant->valor : '' }}" 
                                type="number" 
                                step="any" 
                                class="form-control" 
                                disabled>
                            </div>

                            <div class="col-md-2">
                                <label for="medicao2_ant_{{ $tanque->id }}">Medição nº2 anterior:</label>
                                <input 
                                name="medicao2_ant_{{ $tanque->id }}" 
                                value="{{ isset($medicao2_ant->valor) ? $medicao2_ant->valor : '' }}" 
                                type="number" 
                                step="any" 
                                class="form-control" 
                                disabled>
                            </div>
                            @endif

                            <div class="col-md-2">
                                <label for="medicao1_{{ $tanque->id }}{{ isset($medicao1->id) ? "_{$medicao1->id}" : '' }}">Medição nº1:</label>
                                <div class="input-group">
                                    <input 
                                    name="medicao1_{{ $tanque->id }}{{ isset($medicao1->id) ? "_{$medicao1->id}" : '' }}"  
                                    value="{{ isset($medicao1->valor) ? $medicao1->valor : '' }}" 
                                    type="number" 
                                    step="any" 
                                    class="form-control" 
                                    onkeypress="changeFocusOnPressEnter(event);"
                                    oninput="calculaVariacaoMedicoes({{ $calculaVariacaoMedicoes }});">
                                    <span class="input-group-btn">
                                    @if (isset($medicao1->id))
                                        <button 
                                        type="button" 
                                        onclick="onActionForRequest('{{ route('admin.coletas_parametros_new.amostras.to_remove', ['coleta_parametro_id' => $coleta_parametro->id, 'id' => $medicao1->id]) }}')" 
                                        class="btn btn-danger btn-flat">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    @endif
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="medicao2_{{ $tanque->id }}{{ isset($medicao2->id) ? "_{$medicao2->id}" : '' }}">Medição nº2:</label>
                                <div class="input-group">
                                    <input 
                                    name="medicao2_{{ $tanque->id }}{{ isset($medicao2->id) ? "_{$medicao2->id}" : '' }}"
                                    value="{{ isset($medicao2->valor) ? $medicao2->valor : '' }}" 
                                    type="number" 
                                    step="any" 
                                    class="form-control" 
                                    onkeypress="changeFocusOnPressEnter(event);"
                                    oninput="calculaVariacaoMedicoes({{ $calculaVariacaoMedicoes }});">
                                    <span class="input-group-btn">
                                    @if (isset($medicao2->id))
                                        <button 
                                        type="button" 
                                        onclick="onActionForRequest('{{ route('admin.coletas_parametros_new.amostras.to_remove', ['coleta_parametro_id' => $coleta_parametro->id, 'id' => $medicao2->id]) }}')" 
                                        class="btn btn-danger btn-flat">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    @endif
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <script>
                                    $(document).ready(function () {
                                        calculaVariacaoMedicoes({{ $calculaVariacaoMedicoes }});
                                    });
                                </script>
                                <label for="variacao_{{ $tanque->id }}">Variação de valores:</label>
                                <input name="variacao_{{ $tanque->id }}" type="number" step="any" class="form-control" disabled>
                            </div>

                        </div>
                    </td>
                </tr>
                @endforeach
            </table>
            <div style="margin-bottom: 10px;">
                <input type="hidden" name="fator" id="fator" value="0">
                <button type="button" onclick="submitFormAlt('fator',1,'form_coleta_parametro_amostras');" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> Salvar
                </button>
                <a href="{{ route('admin.coletas_parametros_new') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
