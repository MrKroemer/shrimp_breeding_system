@extends('adminlte::page')

@section('title', 'Gráficos de parâmetros por viveiro')

@section('content_header')
<h1>Gráficos de Parâmetros</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Gráficos de parâmetros por viveiro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <div class="row">
        @foreach ($graficos as $grafico)
            <div class="col-md-6">
                <script>
                $(document).ready(function(){
                    var ctx = document.getElementById('chart_coletas_{{ $grafico[0]->id }}');
                    var chart_coletas = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [
                                @foreach($grafico[1] as $medicao)
                                    '{{ $medicao->coletas_parametros->data_coleta() }}',
                                @endforeach
                            ],
                            datasets: [{
                                label: '{{ $parametro->descricao }} do tanque {{ $grafico[0]->sigla }}',
                                data: [
                                    @foreach ($grafico[1] as $medicao)
                                        '{{ $medicao->valor }}',
                                    @endforeach
                                ],
                                borderColor: ['rgba(88, 152, 207, 0.7)'],
                                borderWidth: 2,
                                fill: false
                            }]
                        },
                        options: {
                            scales: {
                                xAxes: [{
                                    ticks: {
                                        autoSkip: false,
                                        maxRotation: 45,
                                        minRotation: 45
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        max: {{ $parametro->maximoy }},
                                        min: {{ $parametro->minimoy }},
                                        stepSize: {{ $parametro->intervalo }}
                                    }
                                }]
                            },
                            aspectRatio: 1.8
                        }
                    });
                });
                </script>
                <canvas id="chart_coletas_{{ $grafico[0]->id }}"></canvas>
            </div>
        @endforeach
        </div>
    </div>
</div>
@endsection