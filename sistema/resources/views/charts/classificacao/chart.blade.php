@extends('adminlte::page')

@section('title', 'Gráficos de classificação')

@section('content_header')
<h1>Gráficos de classificação</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="{{ route('admin.graficos_classificacao') }}">Gráficos de classificação</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @forelse ($graficos as $grafico)
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-7">
                <canvas id="chart_{{ $grafico[0]->id }}"></canvas>
                <script>
                $(document).ready(function(){

                    var context = document.getElementById('chart_{{ $grafico[0]->id }}');

                    var title_text = 'Tanque {{ $grafico[0]->tanque->sigla }} (Ciclo Nº {{ $grafico[0]->numero }}) - [ Peso médio: {{ round($grafico[0]->peso_medio($grafico[2]), 3) }}g ] - [ Última biometria: {{ $grafico[3]->data_analise() }} ]';

                    var values = [
                        @foreach($grafico[1] as $coluna => $valor)
                            '{{ round($valor, 2) }}',
                        @endforeach
                    ];

                    var labels = [
                        @foreach($grafico[1] as $coluna => $valor)
                            '{{ $coluna }}',
                        @endforeach
                    ];

                    var colors = [
                        @foreach($grafico[1] as $coluna => $valor)
                            stringToColour("{{ $coluna }}"),
                        @endforeach
                    ];

                    var data = {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: colors,
                            borderColor: colors,
                            borderWidth: 2,
                            fill: false
                        }]
                    };

                    var options = {
                        legend: false,
                        title: {
                            display: true,
                            text: title_text
                        },
                        plugins: {
                            datalabels: {
                                anchor: 'end',
                                align: 'top'
                            }
                        },
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
                                    stepSize: 10
                                }
                            }]
                        },
                        aspectRatio: 1.8
                    };

                    var chart = new Chart(context, {
                        type: 'bar',
                        data: data,
                        options: options
                    });

                });
                </script>
            </div>
            <div class="col-md-2"></div>
        </div>
        @empty
        <div class="bg-gray color-palette alert text-center">
            <i>Não existem dados para gerar os gráficos solicitados.</i>
        </div>
        @endforelse
    </div>
</div>
@endsection
