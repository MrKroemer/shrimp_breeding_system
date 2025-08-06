@extends('adminlte::page')
@php
    use App\Http\Controllers\Util\DataPolisher;

    $custo_preparacoes    = 0;
    $custo_povoamento     = 0;
    $custo_arracoamentos  = 0;
    $custo_manejos        = 0;
    $custo_avulsas        = 0;
    $custo_transferencias = 0;
    $custo_kg             = 0;
@endphp
@section('title', 'Dashboard')

@section('content_header')
<h1>Tanque {{ $tanque->sigla }}
    @if(! is_null($ciclo))
        <small><b>Ciclo: Nº {{ $ciclo->numero }}</b></small>
    @endif
</h1>
<ol class="breadcrumb">
    <li><i class="fa fa-dashboard"></i> Dashboard</li>
    <li class="active"><a href="{{ route('admin.dashboard') }}">Setores</a></li>
    <li class="active"><a href="{{ route('admin.dashboard.tanques', ['setor_id' => $setor_id]) }}">Tanques</a></li>
    <li class="active">Informações</li>
</ol>
@endsection

@section('content')

<script>

$(document).ready(function () {

    var oxigenio = new RadialGauge({
        renderTo: 'oxigenio',
        width: 200,
        height: 200,
        units: 'Oxigênio',
        title: false,
        value: {{ $oxigenio }},
        minValue: 0,
        maxValue: 10,
        majorTicks: [
            '0','1','2','3','4','5','6','7','8','9','10'
        ],
        minorTicks: 4,
        highlights: [
            { from: 0, to: 3, color: '#f66' },
            { from: 3, to: 4, color: '#ff3' },
            { from: 4, to: 10, color: '#6f6' }
        ]
    });

    oxigenio.draw();

    var ph = new RadialGauge({
        renderTo: 'ph',
        width: 200,
        height: 200,
        units: 'pH',
        title: false,
        value: {{ $ph }},
        minValue: 5,
        maxValue: 10,
        majorTicks: [
            '5','6','7','8','9','10'
        ],
        minorTicks: 4,
        highlights: [
            { from: 5, to: 6, color: '#f66' },
            { from: 6, to: 7, color: '#ff3' },
            { from: 7, to: 8, color: '#6f6' },
            { from: 8, to: 9, color: '#ff3' },
            { from: 9, to: 10, color: '#f66' }
        ]
    });

    ph.draw();

    var biofloco = new RadialGauge({
        renderTo: 'biofloco',
        width: 200,
        height: 200,
        units: 'Biofloco',
        title: false,
        value: {{ $biofloco }},
        minValue: 0,
        maxValue: 50,
        majorTicks: [
            '0','5','10','15','20','25','30','35','40','45','50'
        ],
        minorTicks: 4,
        highlights: [
            { from: 0, to: 50, color: '#6f6' }
        ]
    });

    biofloco.draw();

    var temperatura = new LinearGauge({
        renderTo: 'temperatura',
        width: 150,
        height: 250,
        borderRadius: 20,
        units: 'C°',
        title: false,
        value: {{ $temperatura }},
        minValue: 0,
        maxValue: 40,
        majorTicks: [
            '0','5','10','15','20','25','30','35','40'
        ],
        minorTicks: 4
    });

    temperatura.draw();

    var nivel = new LinearGauge({
        renderTo: 'nivel',
        width: 150,
        height: 250,
        borderRadius: 20,
        units: 'Nivel',
        title: false,
        value: {{ $nivel }},
        minValue: 0,
        maxValue: 3,
        majorTicks: [
            '0','0.2','0.4','0.6','0.8','1','1.2','1.4','1.6','1.8','2','2.2','2.4','2.6','2.8','3'
        ],
        minorTicks: 2
    });

    nivel.draw();

    var salinidade = new LinearGauge({
        renderTo: 'salinidade',
        width: 150,
        height: 250,
        borderRadius: 20,
        units: 'Salinidade',
        title: false,
        value: {{ $salinidade }},
        minValue: 0,
        maxValue: 40,
        majorTicks: [
            '0','5','10','15','20','25','30','35','40'
        ],
        minorTicks: 4
    });

    salinidade.draw();

});

</script>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Indicadores de parâmetros</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-4" style="text-align: center;">
                <canvas id="oxigenio"></canvas>
            </div>
            <div class="col-xs-4" style="text-align: center;">
                <canvas id="ph"></canvas>
            </div>
            <div class="col-xs-4" style="text-align: center;">
                <canvas id="biofloco"></canvas>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4" style="text-align: center;">
                <canvas id="temperatura"></canvas>
            </div>
            <div class="col-xs-4" style="text-align: center;">
                <canvas id="nivel"></canvas>
            </div>
            <div class="col-xs-4" style="text-align: center;">
                <canvas id="salinidade"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Sobre o cultivo</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-2"><b>Tanque:</b> {{ $tanque->sigla }}</div>
            <div class="col-xs-2"><b>Altura:</b> {{ DataPolisher::numberFormat($tanque->altura ?: 0) }} m</div>
            <div class="col-xs-2"><b>Area:</b> {{ DataPolisher::numberFormat($tanque->area ?: 0, 0) }} m²</div>
            <div class="col-xs-2"><b>Volume:</b> {{ DataPolisher::numberFormat($tanque->volume ?: 0, 0) }} m³</div>
            <div class="col-xs-3"><b>Setor:</b> {{ $tanque->setor->nome }}</div>
        </div>
        <hr>
        @if (! is_null($ciclo))
            <div class="row">
                <div class="col-xs-2"><b>Ciclo:</b> Nº {{ $ciclo->numero }}</div>
                <div class="col-xs-2"><b>Início:</b> {{ $ciclo->data_inicio() }}</div>
                <div class="col-xs-2"><b>Preparação:</b> {{ ! is_null($ciclo->povoamento) ? ($ciclo->dias_atividade() - $ciclo->dias_cultivo()) . ' Dias' : 'n/a' }}</div>
                <div class="col-xs-2"><b>Cultivo:</b> {{ ! is_null($ciclo->povoamento) ? $ciclo->dias_cultivo() . ' Dias' : 'n/a' }}</div>
                <div class="col-xs-2"><b>Atividade:</b> {{ $ciclo->dias_atividade() . ' Dias' }}</div>
            </div>
            <hr>
        @endif

    @if (! is_null($ciclo))

        @if (! is_null($ciclo->preparacao))
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">Sobre a preparação</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p>
                                    [<label>Início da prep.: <span style="color:red;">{{ $ciclo->preparacao->data_inicio() ?: 'n/a' }}</span></label>]
                                    [<label>Início do abas.: <span style="color:red;">{{ $ciclo->preparacao->abas_inicio() ?: 'n/a' }}</span></label>]
                                    [<label>Fim do abas.: <span style="color:red;">{{ $ciclo->preparacao->abas_fim() ?: 'n/a' }}</span></label>]
                                    [<label>Fim da prep.: <span style="color:red;">{{ $ciclo->preparacao->data_fim() ?: 'n/a' }}</span></label>]
                                </p>
                            @if (! empty($ciclo->preparacao->observacoes))
                                <label for="observacoes">Observações:</label>
                                <textarea rows="2" name="observacoes" style="width:100%;" disabled>{{ $ciclo->preparacao->observacoes }}</textarea>
                            @endif
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID da aplicação</th>
                                            <th>Tipo de preparação</th>
                                            <th>Produto</th>
                                            <th>Quantidade</th>
                                            <th>Valor (R$)</th>
                                            <th>Aplicação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ciclo->preparacao->estoque_saidas_preparacoes->sortBy('id') as $preparacoes)
                                        <tr style="{{ $preparacoes->estoque_saida->tipo_destino == 7 ? 'background-color:#ff000036; color:#ffffff;' : '' }}">
                                            <td>{{ $preparacoes->preparacao_aplicacao->id }}</td>
                                            <td>{{ $preparacoes->preparacao_aplicacao->preparacao_tipo->nome }}</td>
                                            <td>{{ $preparacoes->estoque_saida->produto->nome }}</td>
                                            <td>{{ (float) $preparacoes->estoque_saida->quantidade }} {{ $preparacoes->estoque_saida->produto->unidade_saida->sigla }}</td>
                                            <td>R$ {{ DataPolisher::numberFormat($preparacoes->estoque_saida->valor_total) }}</td>
                                            <td>{{ $preparacoes->estoque_saida->data_movimento() }}</td>
                                        </tr>
                                        @php
                                        if ($preparacoes->estoque_saida->tipo_destino != 7) {
                                            $custo_preparacoes += $preparacoes->estoque_saida->valor_total;
                                        }
                                        @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <label>Custo total estimado: <span style="color:blue;"> R$ {{ DataPolisher::numberFormat($custo_preparacoes) }}</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if (! is_null($ciclo->povoamento))
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">Sobre o povoamento</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            @php
                                $saidas_povoamento = $ciclo->povoamento->estoque_saida_povoamento->sortBy('id');
                            @endphp
                            <div class="col-md-12">
                                [<label>Início: <span style="color:red;">{{ $ciclo->povoamento->data_inicio() ?: 'n/a' }}</span></label>]
                                [<label>Fim: <span style="color:red;">{{ $ciclo->povoamento->data_fim() ?: 'n/a' }}</span></label>]
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="8" style="align-content: center">Recepção de Lotes</th>  
                                        </tr>
                                        <tr>
                                            <th>ID do lote</th>
                                            <th>Quantidade</th>
                                            <th>Classe/Idade</th>
                                            <th>Espécie</th>
                                            <th>Valor (R$)</th>
                                            <th>Saída (Larvicultura)</th>
                                            <th>Entrada (Fazenda)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($saidas_povoamento as $saida_povoamento)
                                            <tr style="{{ $saida_povoamento->estoque_saida->tipo_destino == 7 ? 'background-color:#ff000036; color:#ffffff;' : '' }}">
                                                <td>{{ $saida_povoamento->lote->id }}</td>
                                                <td>{{ DataPolisher::numberFormat($saida_povoamento->estoque_saida->quantidade, 0) }}</td>
                                                <td>{{ $saida_povoamento->lote->classe_idade }}</td>
                                                <td>{{ $saida_povoamento->lote->especie->nome_cientifico }}</td>
                                                <td>R$ {{ DataPolisher::numberFormat($saida_povoamento->estoque_saida->valor_total) }}</td>
                                                <td>{{ $saida_povoamento->lote->data_saida() }}</td>
                                                <td>{{ $saida_povoamento->lote->data_entrada() }}</td>
                                            </tr>
                                            @php
                                            if ($saida_povoamento->estoque_saida->tipo_destino != 7) {
                                                $custo_povoamento += $saida_povoamento->estoque_saida->valor_total;
                                            }
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="5" style="align-content: center">Lotes da Larvicultura</th>  
                                        </tr>
                                        <tr>
                                            <th>ID do lote</th>
                                            <th>Lote de fabricação</th>
                                            <th>Quantidade</th>
                                            <th>Sobrevivência</th>
                                            <th>Coeficiente de Variação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($saidas_povoamento as $saida_povoamento)
                                        @php
                                          //dd($saida_povoamento->lote->lote_larvicultura());
                                        @endphp
                                        
                                           @foreach ($saida_povoamento->lote->lote_larvicultura as $lote)
                                                <tr >
                                                    <td>{{ $lote->id }}</td>
                                                    <td>{{ $lote->lote }}</td>
                                                    <td>{{ $lote->quantidade }}</td>
                                                    <td>{{ $lote->sobrevivencia }}%</td>
                                                    <td>{{ round($lote->cv, 2) }}%</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <label>Custo total estimado: <span style="color:blue;"> R$ {{ DataPolisher::numberFormat($custo_povoamento) }}<span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if (! is_null($ciclo->ciclo_peixes))
        @php
            $custo_transferencias = $ciclo->ciclo_peixes->custo_transferencias();
        @endphp
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">Sobre transferências de animais</h3>
                    </div>
                    <div class="box-body">
                        @if($ciclo->ciclo_peixes->transferencias_recebidas->isNotEmpty())
                        <div class="row">
                            <div class="col-xs-12">
                                <h4 class="box-primary"><span style="color:#202794">Animais recebidos</span></h4>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Data do movimento</th>
                                            <th>Ciclo de origem</th>
                                            <th>Quantidade</th>
                                            <th>Proporção</th>
                                            <th>Custo atribuído</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ciclo->ciclo_peixes->transferencias_recebidas as $recebida)
                                        <tr>
                                            <td>{{ $recebida->data_movimento() }}</td>
                                            <td>{{ $recebida->ciclo_origem->tanque->sigla }} ( Ciclo Nº {{ $recebida->ciclo_origem->numero }} )</td>
                                            <td>{{ $recebida->quantidade }}</td>
                                            <td>{{ $recebida->proporcao() }}%</td>
                                            <td>R$ {{ DataPolisher::numberFormat($recebida->custo_atribuido($ciclo->id)) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        @if($ciclo->ciclo_peixes->transferencias_enviadas->isNotEmpty())
                        <div class="row">
                            <div class="col-xs-12">
                                <h4 class="box-primary"><span style="color:#d33535">Animais enviados</span></h4>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Data do movimento</th>
                                            <th>Ciclo de destino</th>
                                            <th>Quantidade</th>
                                            <th>Proporção</th>
                                            <th>Custo atribuído</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ciclo->ciclo_peixes->transferencias_enviadas as $enviada)
                                        <tr>
                                            <td>{{ $enviada->data_movimento() }}</td>
                                            <td>{{ $enviada->ciclo_destino->tanque->sigla }} ( Ciclo Nº {{ $enviada->ciclo_destino->numero }} )</td>
                                            <td>{{ $enviada->quantidade }}</td>
                                            <td>{{ $enviada->proporcao() }}%</td>
                                            <td>R$ {{ DataPolisher::numberFormat($enviada->custo_atribuido($ciclo->id)) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <hr>
                            <div class="col-md-12">
                                <label>Total de animais em cultivo: <span style="color:blue;">{{ $ciclo->tipo == 2 ? $ciclo->ciclo_peixes->qtd_peixes() : 'Indeterminada' }}</span></label>
                            </div>
                            <div class="col-md-12">
                                <label>Total de custo atribuído: <span style="color:{{ ($custo_transferencias < 0 ? 'red' : 'blue') }};">R$ {{ DataPolisher::numberFormat($custo_transferencias) }}</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if ($ciclo->arracoamentos->isNotEmpty())
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">Sobre os arraçoamentos</h3>
                    </div>
                    <div class="box-body">
                        @php
                            $arracoamentos = $ciclo->arracoamentos->sortBy('data_aplicacao');
                        @endphp
                        @foreach($arracoamentos as $arracoamento)
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Arraçoamento do dia <span style="color: red;">{{ $arracoamento->data_aplicacao() }}</span> para o ciclo <span style="color: red;">Nº {{ $arracoamento->ciclo->numero }}</span></label>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID da saída</th>
                                            <th>Tipo de aplicação</th>
                                            <th>Produto</th>
                                            <th>Quantidade</th>
                                            <th>Valor (R$)</th>
                                            <th>Movimentação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($arracoamento->estoque_saidas_arracoamentos as $arracoamento)
                                            <tr style="{{ $arracoamento->estoque_saida->tipo_destino == 7 ? 'background-color:#ff000036; color:#ffffff;' : '' }}">
                                                <td>{{ $arracoamento->estoque_saida->id }}</td>
                                                <td>{{ $arracoamento->estoque_saida->produto->produto_tipo->nome }}</td>
                                                <td>{{ $arracoamento->estoque_saida->produto->nome }}</td>
                                                <td>{{ (float) $arracoamento->estoque_saida->quantidade }} {{ $arracoamento->estoque_saida->produto->unidade_saida->sigla }}</td>
                                                <td>R$ {{ DataPolisher::numberFormat($arracoamento->estoque_saida->valor_total) }}</td>
                                                <td>{{ $arracoamento->estoque_saida->data_movimento() }}</td>
                                            </tr>
                                        @php
                                        if ($arracoamento->estoque_saida->tipo_destino != 7) {
                                            $custo_arracoamentos += $arracoamento->estoque_saida->valor_total;
                                        }
                                        @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                        <div class="row">
                            <hr>
                            <div class="col-md-12">
                                <label>Ração consumida.....: <span style="color:blue;"> {{ DataPolisher::numberFormat($ciclo->racao_consumida()) }} Kg</span></label>
                            </div>
                            <div class="col-md-12">
                                <label>Custo total estimado: <span style="color:blue;"> R$ {{ DataPolisher::numberFormat($custo_arracoamentos) }}</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    @endif

        @if ($tanque->aplicacoes_insumos->isNotEmpty())
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">Sobre os manejos</h3>
                    </div>
                    <div class="box-body">
                        @php
                            $data_referencia = date('Y-m-d');

                            $aplicacoes_insumos = $tanque->aplicacoes_insumos->sortBy('data_aplicacao');

                            if (! is_null($ciclo)) {

                                $data_referencia = $ciclo->data_inicio;

                                if (! is_null($ciclo->data_fim)) {
                                    $data_referencia = $ciclo->data_fim;
                                }

                                $aplicacoes_insumos = $aplicacoes_insumos->where('data_aplicacao', '>=', $data_referencia);

                            }
                        @endphp
                        @foreach($aplicacoes_insumos as $aplicacao_insumo)
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Manejo do dia <span style="color: red;">{{ $aplicacao_insumo->data_aplicacao() }}</label>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID da saída</th>
                                            <th>Tipo</th>
                                            <th>Produto</th>
                                            <th>Quantidade</th>
                                            <th>Valor (R$)</th>
                                            <th>Movimentação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($aplicacao_insumo->estoque_saidas_manejos as $manejo)
                                            <tr style="{{ $manejo->estoque_saida->tipo_destino == 7 ? 'background-color:#ff000036; color:#ffffff;' : '' }}">
                                                <td>{{ $manejo->estoque_saida->id }}</td>
                                                <td>{{ $manejo->estoque_saida->produto->produto_tipo->nome }}</td>
                                                <td>{{ $manejo->estoque_saida->produto->nome }}</td>
                                                <td>{{ (float) $manejo->estoque_saida->quantidade }} {{ $manejo->estoque_saida->produto->unidade_saida->sigla }}</td>
                                                <td>R$ {{ DataPolisher::numberFormat($manejo->estoque_saida->valor_total) }}</td>
                                                <td>{{ $manejo->estoque_saida->data_movimento() }}</td>
                                            </tr>
                                        @php
                                        if ($manejo->estoque_saida->tipo_destino != 7) {
                                            $custo_manejos += $manejo->estoque_saida->valor_total;
                                        }
                                        @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                        <div class="row">
                            <hr>
                            <div class="col-md-12">
                                <label>Custo total estimado: <span style="color:blue;"> R$ {{ DataPolisher::numberFormat($custo_manejos) }}</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if ($tanque->saidas_avulsas->isNotEmpty())
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">Sobre as saídas avulsas</h3>
                    </div>
                    <div class="box-body">
                        @php
                            $data_referencia = date('Y-m-d');

                            $saidas_avulsas = $tanque->saidas_avulsas->sortBy('data_movimento');

                            if (! is_null($ciclo)) {

                                $data_referencia = $ciclo->data_inicio;

                                if (! is_null($ciclo->data_fim)) {
                                    $data_referencia = $ciclo->data_fim;
                                }

                                $saidas_avulsas = $saidas_avulsas->where('data_movimento', '>=', $data_referencia);

                            }
                        @endphp
                        @foreach($saidas_avulsas as $saida_avulsa)
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Saídas avulsas do dia <span style="color: red;">{{ $saida_avulsa->data_movimento() }}</label>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID da saída</th>
                                            <th>Tipo</th>
                                            <th>Produto</th>
                                            <th>Quantidade</th>
                                            <th>Valor (R$)</th>
                                            <th>Movimentação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($saida_avulsa->estoque_saidas_avulsas as $avulsa)
                                            <tr style="{{ $avulsa->estoque_saida->tipo_destino == 7 ? 'background-color:#ff000036; color:#ffffff;' : '' }}">
                                                <td>{{ $avulsa->estoque_saida->id }}</td>
                                                <td>{{ $avulsa->estoque_saida->produto->produto_tipo->nome }}</td>
                                                <td>{{ $avulsa->estoque_saida->produto->nome }}</td>
                                                <td>{{ (float) $avulsa->estoque_saida->quantidade }} {{ $avulsa->estoque_saida->produto->unidade_saida->sigla }}</td>
                                                <td>R$ {{ DataPolisher::numberFormat($avulsa->estoque_saida->valor_total) }}</td>
                                                <td>{{ $avulsa->estoque_saida->data_movimento() }}</td>
                                            </tr>
                                        @php
                                        if ($avulsa->estoque_saida->tipo_destino != 7) {
                                            $custo_avulsas += $avulsa->estoque_saida->valor_total;
                                        }
                                        @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                        <div class="row">
                            <hr>
                            <div class="col-md-12">
                                <label>Custo total estimado: <span style="color:blue;"> R$ {{ DataPolisher::numberFormat($custo_avulsas) }}</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title">Custos estimados</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4><b>Variáveis</b></h4>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Preparação ........ : <span style="color:blue;"> R$ {{ DataPolisher::numberFormat($custo_preparacoes) }}</span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Povoamento ...... : <span style="color:blue;"> R$ {{ DataPolisher::numberFormat($custo_povoamento) }}</span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Arracoamento .... : <span style="color:blue;"> R$ {{ DataPolisher::numberFormat($custo_arracoamentos) }}</span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Manejos ............. : <span style="color:blue;"> R$ {{ DataPolisher::numberFormat($custo_manejos) }}</span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Saídas avulsas .... : <span style="color:blue;"> R$ {{ DataPolisher::numberFormat($custo_avulsas) }}</span></label>
                                    </div>
                                    @if($ciclo->tipo == 2 || $ciclo->tipo == 3) {{-- Cultivo de peixes || Reprodução de peixes --}}
                                        <div class="col-md-12">
                                            <label>Transf. de animais: <span style="color:blue;"> R$ {{ DataPolisher::numberFormat($custo_transferencias) }}</span></label>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if ($ciclo->tipo == 1 && $ciclo->tanque->setor_id != 8) {{-- Tanques de experimentos --}}

                                @php
                                    $custo_fixado = $ciclo->custo_fixado();
                                    $custo_rateio = $ciclo->custo_rateio();
                                    $custo_kg     = $ciclo->custo_kg();
                                @endphp

                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4><b>Fixos</b></h4>
                                            <hr>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Custo fixo ........... : <span style="color:red;"> R$ {{ DataPolisher::numberFormat($custo_fixado->fixo) }}</span></label>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Custo energia ...... : <span style="color:red;"> R$ {{ DataPolisher::numberFormat($custo_fixado->energia) }}</span></label>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Custo combustível: <span style="color:red;"> R$ {{ DataPolisher::numberFormat($custo_fixado->combustivel) }}</span></label>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Custo depreciação: <span style="color:red;"> R$ {{ DataPolisher::numberFormat($custo_fixado->depreciacao) }}</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4><b>Grupos de rateios</b></h4>
                                            <hr>
                                        </div>
                                        @foreach ($tanque->grupos_rateios as $grupo_rateio)
                                        <div class="col-md-12">
                                            <label>[{{ $grupo_rateio->tipo == 'R' ? 'Recebe de' : 'Envia para' }}] {{ mb_strtoupper($grupo_rateio->grupo_rateio_nome) }}</label>
                                        </div>
                                        @endforeach
                                        <div class="col-md-12">
                                            <hr>
                                            <label>
                                                Custo recebido ...... :<span style="color:red;"> R$ {{ DataPolisher::numberFormat($custo_rateio) }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            @endif

                            @php
                                if ($ciclo->tipo == 2 || $ciclo->tipo == 3) { // Cultivo de peixes || Reprodução de peixes
                                    $custo_total = $ciclo->ciclo_peixes->custo_total();
                                } else {
                                    $custo_total = $ciclo->custo_total();
                                }
                            @endphp

			    @php
			     if($ciclo->tanque->setor_id == 8){
                     		$custo_saidas = $ciclo->custo_saidas();
				$custo_total = $custo_saidas;
                             }
		            @endphp		

                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4><b>Totais</b></h4>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Custo total .... : <span style="color:red;"> R$ {{ DataPolisher::numberFormat($custo_total) }}</span></label>
                                    </div>
                                @if($ciclo->tipo == 1 && $ciclo->tanque->setor_id != 8)
                                    <div class="col-md-12">
                                        <label>Biomassa ...... : <span style="color:blue;">{{ DataPolisher::numberFormat($biomassa, 3) }} Kg</span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <label>R$ / Kg .......... : <span style="color:red;"> R$ {{ DataPolisher::numberFormat($custo_kg) }}</span></label>
                                    </div>
                                @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" class="flt_btn_scrollup" title="Ir para o topo">
            <i class="fa fa-angle-up" aria-hidden="true"></i>
        </a>

    </div>
</div>

@endsection
