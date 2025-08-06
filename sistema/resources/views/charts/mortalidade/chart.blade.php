@extends('adminlte::page')
@php
    $maxAxisY = 0;
    $stepAxisY = 200;
@endphp
@section('title', 'Gráficos de mortalidade')

@section('content_header')
<h1>Gráficos de mortalidade</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="{{ route('admin.graficos_mortalidade') }}">Gráficos de mortalidade</a></li>
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
                <script>
                $(document).ready(function(){
                    var ctx = document.getElementById('chart_{{ $grafico[0]->id }}');
                    var chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [
                                @foreach($grafico[1] as $arracoamento)
                                    '{{ $arracoamento->data_aplicacao() }}',
                                @endforeach
                            ],
                            datasets: [
                                @php
                                    $colunas = [
                                        'mortalidade' => 'Índice de mortalidade', 
                                    ];
                                @endphp
                                @foreach($colunas as $coluna => $titulo)
                                {
                                    label: '{{ $titulo }}',
                                    data: [
                                        @foreach ($grafico[1] as $arracoamento)
                                            @php
                                                $valor = $arracoamento->{$coluna};
                                                $maxAxisY = $maxAxisY < $valor ? $valor : $maxAxisY;
                                            @endphp
                                            '{{ $valor }}',
                                        @endforeach
                                    ],
                                    backgroundColor: stringToColour("{{ $titulo }}"),
                                    borderColor: stringToColour("{{ $titulo }}"),
                                    borderWidth: 2,
                                    fill: false
                                },
                                @endforeach
                            ]
                        },
                        options: {
                            title: {
                                display: true,
                                text: 'Tanque {{ $grafico[0]->tanque->sigla }} (Ciclo Nº {{ $grafico[0]->numero }})'
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
                                        beginAtZero: true
                                    }
                                }]
                            },
                            aspectRatio: 1.8
                        }
                    });
                });
                </script>
                <canvas id="chart_{{ $grafico[0]->id }}"></canvas>
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