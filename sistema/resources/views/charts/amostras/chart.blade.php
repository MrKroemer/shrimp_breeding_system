@extends('adminlte::page');
@php

    use App\Models\Ciclos;
    use App\Model\AnalisesBiometricas;
    use App\Models\AnalisesBiometricasAmostras;

    @maxAxisY;
@endphp

    @section('title', '############# TESTE DE SAÍDA ##############')
    @section('content_header')
       <h1>############# TESTE DE SAÍDA ##############</h1>

       <ol class="breadcrumb">
       <li><a href="">Dashboard</a></li>
       <li><a href="{{ route(admin.graficos_amostras) }}"></a></li>
       </ol>

       @endsection
       
       @section('content')

        <divc class ="box">
          <div class="box-header"></div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-7">
                    @php 

                    $labels = [];
                    $datasets = [];

                    $labels = [];
                    $datasets = [];

                    foreach ($ciclos as $ciclo) {

                        $letras = '0123456789ABCDEF';
                        $cor = '#';
                        for($i = 0; $i < 6; $i++) {
                            $indice = rand(0,15);
                            $cor .= $letras[$indice];
                        }

                        $biometrias = $ciclo->biometrias()->reverse();

                        
                        $dataset = [
                            'label'           => "{$ciclo->tanque->sigla} (Lote - {$ciclo->numero})",
                            'backgroundColor' => $cor,
                            'borderColor'     => $cor,
                            'borderWidth'     => 2,
                            'fill'            => false,
                        ];

                        $data = [];

                        $i=1;

                        foreach ($biometrias as $biometria) {

                            $labels[$i] = $i.'ª';

                            $data[$i] = round($biometria->peso_medio, 2);

                            $maxAxisY = ($biometria->peso_medio > $maxAxisY) ? ($biometria->peso_medio * 1.2) : $maxAxisY;

                            $i++;
                        }

                        foreach ($labels as $key => $label) {
                            $dataset['data'][] = isset($data[$key]) ? $data[$key] : null;
                        }

                        $datasets[] = $dataset;

                    }
                @endphp
                



                <script>
                $(document).ready(function() {
                    var ctx = document.getElementById('chart1');
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
                                text: 'Comparação Crescimento de Amostras',
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
                                        autoSkip: false
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        max: {{ $maxAxisY }}
                                    }
                                }]
                            },
                            aspectRatio: 1.8
                        }
                    });
                });
                </script>
                <canvas id="chart1"></canvas>
                </div>
                <div class="col-m2-2"></div>
            </div>

        </div>
       </div>

