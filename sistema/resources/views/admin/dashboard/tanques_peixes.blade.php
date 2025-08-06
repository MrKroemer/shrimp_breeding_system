@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>{{ $setor->nome }} <small><b>{{ $setor->sigla }}</b></small></h1>
<ol class="breadcrumb">
    <li><i class="fa fa-dashboard"></i> Dashboard</li>
    <li class="active"><a href="{{ route('admin.dashboard') }}">Setores</a></li>
    <li class="active">Tanques de peixes</li>
</ol>
@endsection

@section('content')

<!-- Small boxes (Stat box) -->
<div class="row">
    @foreach ($tanques as $tanque)
        @php
            $lotes_peixes = $tanque->lotes_peixes();
            $lote         = null;
            $situacao     = 0; // Vazio

            if ($tanque->situacao == 'OFF') {

                $situacao = 9; // Desativado

            } else {

                $lote = $lotes_peixes->first();

                if (! is_null($lote)) {
                    $situacao = $lote->situacao;
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
                                <i>Vazio</i>
                                @break
                            @case(9)
                                <i>Desativado</i>
                                @break
                            @default
                                @if($lotes_peixes->count() > 1)
                                    <i>Possui varios lotes</i>
                                @else
                                    Lote: {{ $lote->codigo }} (<i>{{ $lote->situacao() }}</i>)
                                @endif
                                @break
                        @endswitch
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                @if ($situacao != 0 && $situacao != 9) 
                    @if($lotes_peixes->count() > 1)
                        <a href="#" data-toggle="modal" data-target="#modal_informacoes_{{ $tanque->id }}" class="small-box-footer">
                            Mais informações <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard.informacoes_peixes', ['setor_id' => $setor->id, 'tanque_id' => $tanque->id, 'lote_peixes_id' => $lote->id]) }}" class="small-box-footer" onclick="setLoadSpinner();">
                            Mais informações <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    @endif
                @else
                    <a class="small-box-footer">&nbsp;</a>
                @endif
            </div>
        </div>
        <!-- ./col -->

        @if($lotes_peixes->count() > 1)

            <div class="modal fade" id="modal_informacoes_{{ $tanque->id }}" tabindex="-1" role="dialog" aria-labelledby="modal_informacoes_{{ $tanque->id }}_label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_informacoes_{{ $tanque->id }}_title">
                            <b>Selecione o lote que deseja visualizar as informações</b>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Código</th>
                                    <th>Tipo</th>
                                    <th>Atividade</th>
                                    <th>Espécie</th>
                                    <th>Situação</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($lotes_peixes as $lote)
                                <tr onclick="location.href='{{ route('admin.dashboard.informacoes_peixes', ['setor_id' => $setor->id, 'tanque_id' => $tanque->id, 'lote_peixes_id' => $lote->id]) }}';setLoadSpinner();" style="cursor:pointer;">
                                    <td>{{ $lote->id }}</td>
                                    <td>{{ $lote->codigo }}</td>
                                    <td>{{ $lote->tipo() }}</td>
                                    <td>{{ $lote->dias_atividade() }} Dias</td>
                                    <td>{{ $lote->especie->nome_cientifico }}</td>
                                    <td>
                                        <p style="font-weight:bold;color: {{ ($lote->situacao == 5 || $lote->situacao == 6) ? '#ff8100' : (($lote->situacao != 7) ? 'green' : 'red') }};">
                                            {{ mb_strtoupper($lote->situacao()) }}
                                        </p>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
                </div>
            </div>

        @endif

    @endforeach
</div>
<!-- /.row -->

@endsection
