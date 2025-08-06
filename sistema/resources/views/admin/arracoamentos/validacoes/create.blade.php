@extends('adminlte::page')

@section('title', 'Registro de programações de arraçoamentos')

@section('content_header')
<h1>Validações de programações de arraçoamentos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Programações de arraçoamentos</a></li>
    <li><a href="">Validações</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        @if ($arracoamento->situacao == 'B')
            <button type="button" class="btn btn-danger" disabled>
                <i class="fa fa-ban" aria-hidden="true"></i> Bloqueado
            </button>
        @elseif ($arracoamento->situacao == 'N')
            <button type="button" onclick="onActionForRequest('{{ route('admin.arracoamentos_validacoes.to_close', ['arracoamento_id' => $arracoamento->id]) }}');" class="btn btn-success locked">
                <i class="fa fa-check" aria-hidden="true"></i> Validar
            </button>
        @else
            @include('admin.estoque_estornos.justificativas.create', [
                'reverse_route' => route('admin.arracoamentos_validacoes.to_reverse', [
                    'arracoamento_id' => $arracoamento->id,
                ]),
                'form_id'   => "form_estorno_arracoamentos",
                'btn_name'  => 'Estornar',
                'btn_icon'  => 'fa fa-repeat',
                'btn_class' => 'btn btn-warning',
            ])
        @endif
        <a href="{{ route('admin.arracoamentos_validacoes', ['setor_id' => $arracoamento->tanque->setor_id, 'data_aplicacao' => $arracoamento->data_aplicacao()]) }}" class="btn btn-default">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
        </a>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover table-condensed">
            <thead>
                <tr>
                    <th>
                        Arraçoamentos do dia <span style="color: red;">{{ $arracoamento->data_aplicacao() }}</span> para o tanque <span style="color: red;">{{ $arracoamento->tanque->sigla }} ( Ciclo Nº {{ $arracoamento->ciclo->numero }} )</span> 
                        [<span style="font-weight:bold;{{ ($arracoamento->situacao != 'V') ? 'color:red;' : 'color:green;' }}"> {{ mb_strtoupper($arracoamento->situacao($arracoamento->situacao)) }} </span>]
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="warning">
                    <td>
                    @php
                        $forms = [];
                        $form_id = 'form_arracoamento_mortalidade_' . $arracoamento->id;
                        $forms[] = $form_id;
                    @endphp
                    @if ($arracoamento->situacao == 'N')
                        <form id="{{ $form_id }}" class="form form-inline" action="{{ route('admin.arracoamentos.to_update', ['id' => $arracoamento->id]) }}" method="POST">
                            {!! csrf_field() !!}
                    @else
                        <form class="form form-inline">
                    @endif
                            <div class="form-group">
                                <label for="mortalidade">Mortalidade:</label>
                                <input type="number" step="any" name="mortalidade" class="form-control" style="width:200px;" value="{{ $arracoamento->mortalidade }}" {{ ($arracoamento->situacao != 'N') ? 'disabled' : '' }}>
                            </div>
                        </form>
                    </td>
                </tr>
                @foreach($arracoamentos_horarios as $arracoamento_horario)
                    <tr class="info">
                        <td>
                            <h5><b>{{ $arracoamento_horario->ordinal }}º arraçoamento</b></h5>
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
                                        <input type="hidden" name="_type" value="V">
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
                                        <label for="edit_quantidade_{{ $arracoamento_aplicacao->id }}">Quantidade:</label>
                                        <input type="number" step="any" name="edit_quantidade_{{ $arracoamento_aplicacao->id }}" placeholder="Quantidade" class="form-control" style="width:110px;" value="{{ round($arracoamento_aplicacao->arracoamento_aplicacao_item()->qtd_aplicada, 3) }}" {{ ($arracoamento->situacao != 'N') ? 'disabled' : '' }}>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach

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
    </div>
</div>
@endsection