@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>{{ $setor->nome }} <small><b>{{ $setor->sigla }}</b></small></h1>
<ol class="breadcrumb">
    <li><i class="fa fa-dashboard"></i> Dashboard</li>
    <li class="active"><a href="{{ route('admin.dashboard') }}">Setores</a></li>
    <li class="active">Tanques</li>
</ol>
@endsection

@section('content')

<!-- Small boxes (Stat box) -->
<div class="row">
    @foreach ($tanques as $tanque)
        @php
            $situacao = 0; // Nunca cultivado

            if ($tanque->situacao == 'OFF') {

                $situacao = 9; // Desativado

            } else {

                $ciclo = $tanque->ultimo_ciclo();

                if (! is_null($ciclo)) {
                    $situacao = $ciclo->situacao;
                }

            }
        @endphp
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-blue" style="background-color: {{ $cores[$situacao] }} !important; {{ $situacao == 0 ? 'color: #c0c4c7 !important;' : '' }}">
                <div class="inner">
                    <h3>{{ $tanque->sigla }}</h3>
                    <p>{{ $tanque->tanque_tipo->nome }}</p>
                    <p>
                        @switch($situacao)
                            @case(0)
                                <i>Nunca cultivado</i>
                                @break
                            @case(9)
                                <i>Desativado</i>
                                @break
                            @default
                                Ciclo: Nº{{ $ciclo->numero }} (<i>{{ $ciclo->situacao($situacao) }}</i>)
                                @break
                        @endswitch
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                @if ($situacao != 0 && $situacao != 9) 
                    <a href="{{ route('admin.dashboard.informacoes', ['setor_id' => $setor->id, 'tanque_id' => $tanque->id]) }}" class="small-box-footer" onclick="setLoadSpinner();">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
                @else
                    <a class="small-box-footer">&nbsp;</a>
                @endif
            </div>
        </div>
        <!-- ./col -->
    @endforeach
</div>
<!-- /.row -->

@endsection
