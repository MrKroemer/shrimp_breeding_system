@extends('adminlte::page')

@section('title', 'Validação de arraçoamentos programados')

@section('content_header')
<h1>Validação de arraçoamentos programados</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Programações de arraçoamentos</a></li>
    <li><a href="">Validação de programação de arraçoamentos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        @if ($arracoamento->situacao == 'N')
            <button type="button" onclick="onActionForRequest('{{ route('admin.arracoamentos.arracoamentos_validacoes.to_close', ['arracoamento_id' => $arracoamento->id]) }}');" class="btn btn-success">
                <i class="fa fa-check" aria-hidden="true"></i> Validar
            </button>
        @else
            @include('admin.estoque_estornos.justificativas.create', [
                'reverse_route' => route('admin.arracoamentos.arracoamentos_validacoes.to_reverse', [
                    'arracoamento_id' => $arracoamento->id,
                ]),
                'form_id'   => "form_estorno_arracoamentos",
                'btn_name'  => 'Estornar',
                'btn_icon'  => 'fa fa-repeat',
                'btn_class' => 'btn btn-warning',
            ])
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
                        Arraçoamentos do dia <span style="color: red;">{{ $arracoamento->data_aplicacao() }}</span> para o tanque <span style="color: red;">{{ $arracoamento->tanque->sigla }} ( Ciclo Nº {{ $arracoamento->ciclo->numero }} )</span> 
                        [<span style="font-weight:bold;{{ ($arracoamento->situacao == 'N') ? 'color:red;' : 'color:green;' }}"> {{ mb_strtoupper($arracoamento->situacao($arracoamento->situacao)) }} </span>]
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($arracoamento->horarios as $arracoamento_horario)
                    <tr class="info">
                        <td>
                            <h5><b>{{ $arracoamento_horario->ordinal }}º arraçoamento</b></h5>
                        </td>
                    </tr>

                    @foreach ($arracoamento_horario->aplicacoes->sortBy('id') as $arracoamento_aplicacao)
                        <tr class="warning">
                            <td>
                                @if ($arracoamento->situacao == 'N')
                                    <form class="form form-inline" action="{{ route('admin.arracoamentos.arracoamentos_aplicacoes.to_update', ['arracoamento_id' => $arracoamento->id, 'arracoamento_horario_id' => $arracoamento_horario->id, 'id' => $arracoamento_aplicacao->id]) }}" method="POST">
                                            {!! csrf_field() !!}
                                @else
                                    <form class="form form-inline">
                                @endif
                                    <div class="form-group">
                                        <label for="arracoamento_aplicacao_tipo_id_{{ $arracoamento_aplicacao->id }}">Aplicação de:</label>
                                        <select name="arracoamento_aplicacao_tipo_id_{{ $arracoamento_aplicacao->id }}" class="form-control" style="width:200px;" {{ ($arracoamento->situacao != 'N') ? 'disabled' : '' }} 
                                            onchange="onChangeArracoamentoAplicacaoTipo('{{ url('admin') }}', ['arracoamento_aplicacao_tipo_id_{{ $arracoamento_aplicacao->id }}', 'arracoamento_aplicacao_item_id_{{ $arracoamento_aplicacao->id }}']);">
                                            <option value="">..:: Selecione ::..</option>
                                            @foreach($arracoamentos_aplicacoes_tipos as $arracoamento_aplicacao_tipo)
                                                <option value="{{ $arracoamento_aplicacao_tipo->id }}" {{ ($arracoamento_aplicacao_tipo->id == $arracoamento_aplicacao->arracoamento_aplicacao_tipo_id) ? 'selected' : '' }}>{{ $arracoamento_aplicacao_tipo->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="arracoamento_aplicacao_item_id_{{ $arracoamento_aplicacao->id }}">Item:</label>
                                        <select name="arracoamento_aplicacao_item_id_{{ $arracoamento_aplicacao->id }}" class="form-control" style="width:355px;" {{ ($arracoamento->situacao != 'N') ? 'disabled' : '' }}>
                                            <option value="">..:: Selecione ::..</option>
                                            @foreach($arracoamentos_aplicacoes_itens->getJsonItens($arracoamento_aplicacao->arracoamento_aplicacao_tipo_id) as $arracoamento_aplicacao_item)
                                                <option value="{{ $arracoamento_aplicacao_item->id }}" {{ ($arracoamento_aplicacao_item->id == $arracoamento_aplicacao->arracoamento_aplicacao_item()->item_id) ? 'selected' : '' }}>{{ $arracoamento_aplicacao_item->nome }} (Und.: {{ $arracoamento_aplicacao_item->unidade_medida }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantidade_{{ $arracoamento_aplicacao->id }}">Quantidade:</label>
                                        <input type="text" name="quantidade_{{ $arracoamento_aplicacao->id }}" placeholder="Quantidade" class="form-control" style="width:110px;" value="{{ round($arracoamento_aplicacao->arracoamento_aplicacao_item()->quantidade, 3) }}" {{ ($arracoamento->situacao != 'N') ? 'disabled' : '' }}>
                                    </div>
                                    @if ($arracoamento->situacao == 'N')
                                        <div class="form-group" style="margin-left: 1%;">
                                            <button type="submit" class="btn btn-primary btn-xs"><i class="fa fa-edit" aria-hidden="true"></i> Alterar</button>
                                        </div>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach

                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection