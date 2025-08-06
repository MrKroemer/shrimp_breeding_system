@extends('adminlte::page')
@php

    use App\Models\Tanques;
    $maxAxisY = 0;

@endphp
@section('title', 'análise de balanço ionico')

@section('content_header')
<h1>Gráfico de balanço iônico</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="{{ route('admin.graficos_calcio') }}">Gráfico de análise de cálcio</a></li>
</ol>
@endsection

@section('content')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @forelse ($graficos as $tanque_id => $grafico)
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-7">
                @php

                    $sigla = $tanques->where('id', $tanque_id)->first()->sigla;
                    $maxAxisY = 0;
                    $labels = [];
                    $datasets = [];

                    //Calcio
                    $dataset = [
                        'label'           => "Cálcio (Ca) )",
                        'backgroundColor' => "#FFBDBD",
                        'borderColor'     => "#FFBDBD",
                        'borderWidth'     => 2,
                        'fill'            => false,
                    ];

                    $data = [];

                    foreach ($grafico as $amostraDataCalcio => $amostra) {

                        $labels[$amostraDataCalcio] = $amostra->data_coleta();

                        $data[$amostraDataCalcio] = $amostra->calcio;

                    }

                    foreach ($labels as $key => $label) {
                        $dataset['data'][] = isset($data[$key]) ? $data[$key] : null;
                    }

                    $datasets[] = $dataset;

                    //Dureza Calcica
                    $dataset = [
                        'label'           => "Dureza Cálcica",
                        'backgroundColor' => "#F0D4FF",
                        'borderColor'     => "#F0D4FF",
                        'borderWidth'     => 2,
                        'fill'            => false,
                    ];

                    $data = [];

                    foreach ($grafico as $amostraDataCalcio => $amostra) {

                        $labels[$amostraDataCalcio] = $amostra->data_coleta();

                        $data[$amostraDataCalcio] = $amostra->dureza_calcio;

                        
                    }

                    foreach ($labels as $key => $label) {
                        $dataset['data'][] = isset($data[$key]) ? $data[$key] : null;
                    }

                    $datasets[] = $dataset;


                    //Dureza Magnésio
                    $dataset = [
                        'label'           => "Dureza Mágnésio",
                        'backgroundColor' => "#E5B3FF",
                        'borderColor'     => "#E5B3FF",
                        'borderWidth'     => 2,
                        'fill'            => false,
                    ];

                    $data = [];

                    foreach ($grafico as $amostraDataCalcio => $amostra) {

                        $labels[$amostraDataCalcio] = $amostra->data_coleta();

                        $data[$amostraDataCalcio] = $amostra->dureza_magnesio;

                    }

                    foreach ($labels as $key => $label) {
                        $dataset['data'][] = isset($data[$key]) ? $data[$key] : null;
                    }

                    $datasets[] = $dataset;

                    
                @endphp
                <script>
                $(document).ready(function() {
                    var ctx = document.getElementById('chart_{{ $tanque_id }}');
                    var chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [
                                @foreach ($labels as $label)
                                    '{{ $label }}',
                                @endforeach
                            ],
                            datasets: [
                                @foreach ($datasets as $dataset)
                                    {!! json_encode($dataset, JSON_UNESCAPED_UNICODE) !!},
                                @endforeach
                            ]
                        },
                        options: {
                            title: {
                                display: true,
                                text: 'Tanque {{ $sigla }}'
                            },
                            plugins: {
                                datalabels: {
                                    anchor: 'end',
                                    align: 'top'
                                }
                            },
                            layout: {
                                padding: {
                                    left: 12,
                                    right: 12
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
                <canvas id="chart_{{ $tanque_id }}"></canvas>
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