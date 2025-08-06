@extends('adminlte::page')
@php
    use App\Http\Controllers\Util\DataPolisher;
@endphp
@section('title', 'Registro de horários da programação')

@section('content_header')
<h1>Horários da programação</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Programações de arraçoamentos</a></li>
    <li><a href="">Horários da programação</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

@if ($arracoamento->situacao == 'N')
    @include('admin.arracoamentos_horarios.opcoes.create')
@endif

<div class="box box-warning collapsed-box">
    <div class="box-header with-border">
        <i class="ion ion-clipboard"></i>
        <h3 class="box-title">Informações do cultivo</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body" style="display:none;">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sobre o cultivo</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <h5><b>Cultivo: </b>{{ $arracoamento->tanque->sigla }} ( Ciclo Nº {{ $arracoamento->ciclo->numero }} )</h5>
                        <h5><b>Abertura do ciclo: </b>{{ $arracoamento->ciclo->data_inicio() }}</h5>
                        <h5><b>Dias de atividade: </b>{{ $arracoamento->ciclo->dias_atividade($arracoamento->data_aplicacao) }}</h5>
                        <h5><b>Data do povoamento: </b>{{ $arracoamento->ciclo->povoamento->data_fim('d/m/Y') }}</h5>
                        <h5><b>Quantidade de pós-larvas: </b>{{ $arracoamento->ciclo->povoamento->qtd_poslarvas() }}</h5>
                        <h5><b>Dias de cultivo: </b>{{ $arracoamento->ciclo->dias_cultivo($arracoamento->data_aplicacao) }}</h5>
                        <h5><b>Sobrevivência (%): </b> {{ DataPolisher::numberFormat($arracoamento->ciclo->sobrevivencia($arracoamento->data_aplicacao)) ?: 'Nenhuma biometria encontrada' }}</h5>
                        <h5><b>Peso médio (g): </b> {{ DataPolisher::numberFormat($arracoamento->ciclo->peso_medio($arracoamento->data_aplicacao), 3) ?: 'Nenhuma biometria encontrada' }}</h5>
                        <h5><b>Biomassa (Kg): </b>{{ DataPolisher::numberFormat($arracoamento->ciclo->biomassa($arracoamento->data_aplicacao)) ?: 'Nenhuma biometria encontrada' }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Últimos arraçoamentos</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <ul class="todo-list ui-sortable">
                            @forelse ($ultimos_arracoamentos as $ultimo_arracoamento)
                                <li>
                                    <span class="handle ui-sortable-handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <span class="text">Dia {{ $ultimo_arracoamento->data_aplicacao() }}:
                                        @if ($ultimo_arracoamento->qtdTotalRacaoProgramada() > 0)

                                            <i style="color:blue;">{{ $ultimo_arracoamento->qtdTotalRacaoProgramada() }}Kg de ração</i>

                                            @foreach ($ultimo_arracoamento->qtdQuilosPorRacao() as $racao)
                                                ( <i>{{ round($racao['quantidade'], 3) }}Kg de </i><b>{{ substr($racao['nome'], 0, 20) }}</b> )
                                            @endforeach

                                        @else
                                            <i style="color:red;">Nenhuma aplicação registrada</i>
                                        @endif
                                    </span>
                                </li>
                            @empty
                                <li><span><i>Não há registros</i></span></li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box">
    <div class="box-header">
        @if ($arracoamento->situacao == 'N')
            <a class="btn btn-primary" data-toggle="modal" data-target="#opcoes_arracoamento_modal">
                <i class="fa fa-check-square-o" aria-hidden="true"></i> Opções de arraçoamento
            </a>
            <a href="{{ route('admin.arracoamentos.arracoamentos_horarios.to_store', ['arracoamento_id' => $arracoamento->id]) }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar horário
            </a>
        @endif
        <a href="{{ route('admin.arracoamentos.to_search', ['setor_id' => $arracoamento->tanque->setor->id, 'data_aplicacao' => $arracoamento->data_aplicacao()]) }}" class="btn btn-default">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
        </a>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover table-condensed">
            <thead>
                <tr>
                    <th>
                        <span>Arraçoamentos do dia <span style="color: red;">{{ $arracoamento->data_aplicacao() }}</span> para o tanque <span style="color: red;">{{ $arracoamento->tanque->sigla }} ( Ciclo Nº {{ $arracoamento->ciclo->numero }} )</span></span>
                        <span>[ Total programada: <span style="color: green;">{{ $arracoamento->qtdTotalRacaoProgramada() }}Kg</span> ]</span>
                    @if ($arracoamento->situacao != 'N')
                        <span>[ Total aplicada: <span style="color: green;">{{ $arracoamento->qtdTotalRacaoAplicada() }}Kg</span> ]</span>
                    @endif
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $forms = [];
                @endphp
                @foreach($arracoamentos_horarios as $arracoamento_horario)
                    <tr class="info">
                        <td>
                            <h5><b>{{ $arracoamento_horario->ordinal }}º arraçoamento</b></h5>
                            @if ($arracoamento->situacao == 'N')
                                <a data-toggle="modal" data-target="#arracoamento_horario_modal_{{ $arracoamento_horario->id }}" class="btn btn-success btn-xs" >
                                    <i class="fa fa-plus" aria-hidden="true"></i> Adicionar item
                                </a>
                                @if ($arracoamento_horario->aplicacoes->count() == 0)
                                    <button type="button" onclick="onActionForRequest('{{ route('admin.arracoamentos.arracoamentos_horarios.to_remove', ['arracoamento_id' => $arracoamento->id, 'id' => $arracoamento_horario->id]) }}');" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                @endif
                            @endif
                        </td>
                    </tr>

                    @foreach ($arracoamento_horario->aplicacoes->sortBy('id') as $arracoamento_aplicacao)
                        <tr class="warning">
                            <td>
                                @php
                                    $form_id = 'form_arracoamento_aplicacao_' . $arracoamento_aplicacao->id;
                                    $forms[] = $form_id;
                                @endphp
                                @if ($arracoamento->situacao == 'N')
                                    <form id="{{ $form_id }}" class="form form-inline" action="{{ route('admin.arracoamentos.arracoamentos_aplicacoes.to_update', ['arracoamento_id' => $arracoamento->id, 'arracoamento_horario_id' => $arracoamento_horario->id, 'id' => $arracoamento_aplicacao->id]) }}" method="POST">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="_type" value="P">
                                @else
                                    <form class="form form-inline">
                                @endif
                                    <div class="form-group">
                                        <label for="edit_arracoamento_aplicacao_tipo_id_{{ $arracoamento_aplicacao->id }}">Aplicação de:</label>
                                        <select name="edit_arracoamento_aplicacao_tipo_id_{{ $arracoamento_aplicacao->id }}" class="form-control" style="width:200px;" {{ ($arracoamento->situacao != 'N') ? 'disabled' : '' }} 
                                            onchange="onChangeArracoamentoAplicacaoTipo('{{ url('admin') }}', ['edit_arracoamento_aplicacao_tipo_id_{{ $arracoamento_aplicacao->id }}', 'edit_arracoamento_aplicacao_item_id_{{ $arracoamento_aplicacao->id }}']);">
                                            <option value="">..:: Selecione ::..</option>
                                            @foreach($arracoamentos_aplicacoes_tipos as $arracoamento_aplicacao_tipo)
                                                <option value="{{ $arracoamento_aplicacao_tipo->id }}" {{ ($arracoamento_aplicacao_tipo->id == $arracoamento_aplicacao->arracoamento_aplicacao_tipo_id) ? 'selected' : '' }}>{{ $arracoamento_aplicacao_tipo->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_arracoamento_aplicacao_item_id_{{ $arracoamento_aplicacao->id }}">Item:</label>
                                        <select name="edit_arracoamento_aplicacao_item_id_{{ $arracoamento_aplicacao->id }}" class="form-control" style="width:355px;" {{ ($arracoamento->situacao != 'N') ? 'disabled' : '' }}>
                                            <option value="">..:: Selecione ::..</option>
                                            @foreach($arracoamentos_aplicacoes_itens->getJsonItens($arracoamento_aplicacao->arracoamento_aplicacao_tipo_id) as $arracoamento_aplicacao_item)
                                                <option value="{{ $arracoamento_aplicacao_item->id }}" {{ ($arracoamento_aplicacao_item->id == $arracoamento_aplicacao->arracoamento_aplicacao_item()->item_id) ? 'selected' : '' }}>{{ $arracoamento_aplicacao_item->nome }} (Und.: {{ $arracoamento_aplicacao_item->unidade_medida }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_quantidade_{{ $arracoamento_aplicacao->id }}">{{ ($arracoamento->situacao != 'N') ? 'Programada:' : 'Quantidade:' }}</label>
                                        <input type="number" step="any" name="edit_quantidade_{{ $arracoamento_aplicacao->id }}" placeholder="Quantidade" class="form-control" style="width:110px;" value="{{ round($arracoamento_aplicacao->arracoamento_aplicacao_item()->quantidade, 3) }}" {{ ($arracoamento->situacao != 'N') ? 'disabled' : '' }}>
                                    </div>
                                    @if ($arracoamento->situacao != 'N')
                                        <div class="form-group">
                                            <label for="qtd_aplicada_{{ $arracoamento_aplicacao->id }}">Aplicada:</label>
                                            <input type="number" step="any" name="qtd_aplicada_{{ $arracoamento_aplicacao->id }}" placeholder="Aplicada" class="form-control" style="width:110px;" value="{{ round($arracoamento_aplicacao->arracoamento_aplicacao_item()->qtd_aplicada, 3) }}" {{ ($arracoamento->situacao != 'N') ? 'disabled' : '' }}>
                                        </div>
                                    @endif
                                    @if ($arracoamento->situacao == 'N')
                                        <div class="form-group" style="margin-left: 1%;">
                                            <button type="button" onclick="onActionForRequest('{{ route('admin.arracoamentos.arracoamentos_aplicacoes.to_remove', ['arracoamento_id' => $arracoamento->id, 'arracoamento_horario_id' => $arracoamento_horario->id, 'id' => $arracoamento_aplicacao->id]) }}');" class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                            </button>
                                        </div>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if ($arracoamento->situacao == 'N')
                        @include('admin.arracoamentos_aplicacoes.create')
                    @endif

                @endforeach

                @if ($arracoamento->situacao == 'N')
                    <tr>
                        <td style="padding: 10px;">
                            <button type="button" onclick="submitManyForms({{ json_encode($forms) }});" class="btn btn-primary btn-sm">
                                <i class="fa fa-edit" aria-hidden="true"></i> Salvar alterações
                            </button>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        @if(isset($formData))
            {!! $arracoamentos_horarios->appends($formData)->links() !!}
        @else
            {!! $arracoamentos_horarios->links() !!}
        @endif
    </div>
</div>
@endsection