@extends('adminlte::page')
@php
    use App\Models\Ciclos;
    use App\Models\Tanques;

    $maxAxisY = 0;
@endphp
@section('title', 'coeficiente de variação')

@section('content_header')
<h1>Gráfico de coeficiente de variação</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="{{ route('admin.graficos_coeficiente_variacao') }}">Gráfico de coeficiente de variação</a></li>
</ol>
@endsection

@section('content')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @forelse ($graficos as $ciclo_id => $grafico)
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-7">
                @php
                    $ciclo = Ciclos::where('id',$ciclo_id)->first();

                    $tanque = Tanques::where('id',$ciclo->tanque_id)->first();
                    
                    $sigla = $tanque->sigla;

                    $labels = [];
                    $datasets = [
                        'label'           => "Coeficiente de Variação (%)",
                        'backgroundColor' => '#2EA800',
                        'borderColor'     => '#2EA800',
                        'borderWidth'     => 2,
                        'fill'            => false,
                    ];

                    $data = [];

                    foreach ($grafico as $amostra) {
                        $labels[] = $amostra->data_analise();
                        $data[]   = $amostra->coeficiente_variacao();
                    }

                    $datasets['data'] = $data;
                @endphp
                <script>
                $(document).ready(function() {
                    var ctx = document.getElementById('chart_{{ $ciclo_id }}');
                    var chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [
                                @foreach ($labels as $label)
                                    '{{ $label }}',
                                @endforeach 
                            ],
                            datasets: [
                                {!! json_encode($datasets, JSON_UNESCAPED_UNICODE) !!},
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
                <canvas id="chart_{{ $ciclo_id }}"></canvas>
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