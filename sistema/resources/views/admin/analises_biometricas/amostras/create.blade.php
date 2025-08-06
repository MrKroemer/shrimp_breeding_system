@extends('adminlte::page')
@php
    $flacido       = qualifica($qualificacoes['flacido'],       $qualificacoes['total']);
    $mole          = qualifica($qualificacoes['mole'],          $qualificacoes['total']);
    $semimole      = qualifica($qualificacoes['semimole'],      $qualificacoes['total']);
    $opaco         = qualifica($qualificacoes['opaco'],         $qualificacoes['total']);
    $deformado     = qualifica($qualificacoes['deformado'],     $qualificacoes['total']);
    $caudavermelha = qualifica($qualificacoes['caudavermelha'], $qualificacoes['total']);
    $necrose1      = qualifica($qualificacoes['necrose1'],      $qualificacoes['total']);
    $necrose2      = qualifica($qualificacoes['necrose2'],      $qualificacoes['total']);
    $necrose3      = qualifica($qualificacoes['necrose3'],      $qualificacoes['total']);
    $necrose4      = qualifica($qualificacoes['necrose4'],      $qualificacoes['total']);

    function qualifica ($qua, $total)
    {
        $resultado = [
            'valor'      => 0,
            'percentual' => 0,
        ];

        if (($qua != 0) || ($total != 0)) {
            $resultado['valor']      = $qua;
            $resultado['percentual'] = round($qua / $total, 2);
        }

        return $resultado;
    }
@endphp
@section('title', 'Registro de análises biométricas')

@section('content_header')
<h1>Cadastro de análises biométricas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Analises biométricas</a></li>
    <li><a href="">Registro de amostras</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <h4><b>Análise do dia <span style="color: red;">{{ $analise_biometrica->data_analise() }}</span>, para o tanque <span style="color: red;">{{ $analise_biometrica->ciclo->tanque->sigla }} ( Ciclo Nº {{ $analise_biometrica->ciclo->numero }} )</span></b></h4>
    </div>
    <div class="box-body">

    @if ($analise_biometrica->situacao == '1')
        <hr>
        <h4>Coleta de amostras:</h4>

        <form action="{{ route('admin.analises_biometricas.amostras.to_store', ['analise_biometrica_id' => $analise_biometrica->id]) }}" method="POST" >
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-4 col-xs-6">
                        <label for="gramatura">Gramatura:</label>
                        <div class="input-group">
                            <input type="number" step="any"  autocomplete="off" name="gramatura" class="form-control" onkeyup="repeat(this.value ? this.value : '&nbsp;', 'conferencia_valor');" autofocus>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary" >
                                    <i class="fa fa-save" aria-hidden="true"></i> Salvar
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-1 col-xs-2">
                        <div class="direct-chat-text" style="margin-left:0px;">
                            <span id="conferencia_valor" style="font-size: xx-large;">&nbsp;</span>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-xs-3">
                        <input type="radio" name="qualificacao" value="7" class="form-control">
                        <label for="necrose_grau1">Necrose Grau 1</label>
                    </div>
                    <div class="col-xs-3">
                        <input type="radio" name="qualificacao" value="8" class="form-control">
                        <label for="necrose_grau2">Necrose Grau 2</label>
                    </div>
                    <div class="col-xs-3">
                        <input type="radio" name="qualificacao" value="9" class="form-control">
                        <label for="necrose_grau3">Necrose Grau 3</label>
                    </div>
                    <div class="col-xs-3">
                        <input type="radio" name="qualificacao" value="10" class="form-control">
                        <label for="necrose_grau4">Necrose Grau 4</label>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-xs-3">
                        <input type="radio" name="qualificacao" value="1" class="form-control">
                        <label for="Opaco">Opaco</label>
                    </div>
                    <div class="col-xs-3">
                        <input type="radio" name="qualificacao" value="2" class="form-control">
                        <label for="Deformado">Deformado</label>
                    </div>
                    <div class="col-xs-3">
                        <input type="radio" name="qualificacao" value="3" class="form-control">
                        <label for="caudas_vermelhas">Cauda verm.</label>
                    </div>
                    <div class="col-xs-3">
                        <input type="radio" name="qualificacao" value="4" class="form-control">
                        <label for="caudas_vermelhas">Flácido</label>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-xs-3">
                        <input type="radio" name="qualificacao" value="5" class="form-control">
                        <label for="mole">Mole</label>
                    </div>
                    <div class="col-xs-3">
                        <input type="radio" name="qualificacao" value="6" class="form-control">
                        <label for="semimole">Semi-Mole</label>
                    </div>
                    <div class="col-xs-2">
                        <input type="radio" name="qualificacao" value="0" class="form-control">
                        <label for="necrose_grau4">Sem Classificação</label>
                    </div>
                </div>
            </div>
        </form>
    @endif

        <hr>
        <h4>Classificação dos animais:</h4>

        <div class="row">
            <div class="col-xs-2">
                <label for="classe0to10">0/10:</label>
                <input type="number" step="any" name="classe0to10" class="form-control" value="{{ $classificacoes['classe0to10'] }}" disabled>
            </div>
            <div class="col-xs-2">
                <label for="classe10to20">10/20:</label>
                <input type="number" step="any" name="classe10to20" class="form-control" value="{{ $classificacoes['classe10to20'] }}" disabled>
            </div>
            <div class="col-xs-2">
                <label for="classe20to30">20/30:</label>
                <input type="number" step="any" name="classe20to30" class="form-control" value="{{ $classificacoes['classe20to30'] }}" disabled>
            </div>
            <div class="col-xs-2">
                <label for="classe30to40">30/40:</label>
                <input type="number" step="any" name="classe30to40" class="form-control" value="{{ $classificacoes['classe30to40'] }}" disabled>
            </div>
            <div class="col-xs-2">
                <label for="classe40to50">40/50:</label>
                <input type="number" step="any" name="classe40to50" class="form-control" value="{{ $classificacoes['classe40to50'] }}" disabled>
            </div>
            <div class="col-xs-2">
                <label for="classe50to60">50/60:</label>
                <input type="number" step="any" name="classe50to60" class="form-control" value="{{ $classificacoes['classe50to60'] }}" disabled>
            </div>
            <div class="col-xs-2">
                <label for="classe60to70">60/70:</label>
                <input type="number" step="any" name="classe60to70" class="form-control" value="{{ $classificacoes['classe60to70'] }}" disabled>
            </div>
            <div class="col-xs-2">
                <label for="classe70to80">70/80:</label>
                <input type="number" step="any" name="classe70to80" class="form-control" value="{{ $classificacoes['classe70to80'] }}" disabled>
            </div>
            <div class="col-xs-2">
                <label for="classe80to100">80/100:</label>
                <input type="number" step="any" name="classe80to100"  class="form-control" value="{{ $classificacoes['classe80to100'] }}" disabled>
            </div>
            <div class="col-xs-2">
                <label for="classe100to120">100/120:</label>
                <input type="number" step="any" name="classe100to120" class="form-control" value="{{ $classificacoes['classe100to120'] }}" disabled>
            </div>
            <div class="col-xs-2">
                <label for="classe120to150">120/150:</label>
                <input type="number" step="any" name="classe120to150" class="form-control" value="{{ $classificacoes['classe120to150'] }}" disabled>
            </div>
            <div class="col-xs-2">
                <label for="classe150to200">150/200:</label>
                <input type="number" step="any" name="classe150toUP" class="form-control" value="{{ $classificacoes['classe150to200'] }}" disabled>
            </div>
            <div class="col-xs-6">
                <label for="classe200to330">Juvenil M:</label>
                <input type="number" step="any" name="classe150toUP" class="form-control" value="{{ $classificacoes['classe200to330'] }}" disabled>
            </div>
            <div class="col-xs-6">
                <label for="classe330to1000">Juvenil P:</label>
                <input type="number" step="any" name="classe150toUP" class="form-control" value="{{ $classificacoes['classe330to1000'] }}" disabled>
            </div>
        </div>

        <hr>
        <h4>Qualidade do animais:</h4>

        <div class="form-group">
            <div class="row">
                <div class="col-xs-3">
                    <label for="necrose1">Necrose 1:</label>
                    <input type="text" name="necrose1" class="form-control" value="{{ $necrose1['valor'].' - ('.($necrose1['percentual']*100).'%)' }}" disabled>
                </div>
                <div class="col-xs-3">
                    <label for="necrose2">Necrose 2:</label>
                    <input type="text" name="necrose2" class="form-control" value="{{ $necrose2['valor'].' - ('.($necrose2['percentual']*100).'%)' }}" disabled>
                </div>
                <div class="col-xs-3">
                    <label for="necrose3">Necrose 3:</label>
                    <input type="text" name="necrose3" class="form-control" value="{{ $necrose3['valor'].' - ('.($necrose3['percentual']*100).'%)' }}" disabled>
                </div>
                <div class="col-xs-3">
                    <label for="necrose4">Necrose 4:</label>
                    <input type="text" name="necrose4" class="form-control" value="{{ $necrose4['valor'].' - ('.($necrose4['percentual']*100).'%)' }}" disabled>
                </div>
                <div class="col-xs-3">
                    <label for="opaco">Opaco:</label>
                    <input type="text" name="opaco" class="form-control" value="{{ $opaco['valor'].' - ('.($opaco['percentual']*100).'%)' }}" disabled>
                </div>
                <div class="col-xs-3">
                    <label for="deformado">Deformado:</label>
                    <input type="text" name="deformado" class="form-control" value="{{ $deformado['valor'].' - ('.($deformado['percentual']*100).'%)' }}" disabled>
                </div>
                <div class="col-xs-3">
                    <label for="cauda_vermelha">Cauda-Vermelha:</label>
                    <input type="text" name="caudavermelha" class="form-control" value="{{ $caudavermelha['valor'].' - ('.($caudavermelha['percentual']*100).'%)' }}" disabled>
                </div>
                <div class="col-xs-3">
                    <label for="flacido">Flácido:</label>
                    <input type="text" name="flacido" class="form-control" value="{{ $flacido['valor'].' - ('.($flacido['percentual']*100).'%)' }}" disabled>
                </div>
                <div class="col-xs-6">
                    <label for="mole">Mole:</label>
                    <input type="text" name="mole" class="form-control" value="{{ $mole['valor'].' - ('.($mole['percentual']*100).'%)' }}" disabled>
                </div>
                <div class="col-xs-6">
                    <label for="semimole">Semi-Mole:</label>
                    <input type="text" name="semimole" class="form-control" value="{{ $semimole['valor'].' - ('.($semimole['percentual']*100).'%)' }}" disabled>
                </div>
            </div>
        </div>

        <hr>
        <h4>Resumo:</h4>

        <div class="row">
            <div class="col-xs-3">
                <label for="total_animais">Total de animais:</label>
                <input type="number" name="total_animais" class="form-control" placeholder="n/a" value="{{ $analise_biometrica->total_animais() }}" disabled>
            </div>
            <div class="col-xs-3">
                <label for="peso_total">Peso total (g):</label>
                <input type="number" name="peso_total" class="form-control" placeholder="n/a" value="{{ $analise_biometrica->peso_total() }}" disabled>
            </div>
            <div class="col-xs-3">
                <label for="peso_medio">Peso médio (g):</label>
                <input type="number" name="peso_medio" class="form-control" placeholder="n/a" value="{{ $analise_biometrica->peso_medio() }}" disabled>
            </div>
            <div class="col-xs-3">
                <label for="sobrevivencia">Sobrevivência (%):</label>
                <input type="number" name="sobrevivencia" class="form-control" placeholder="n/a" value="{{ $analise_biometrica->sobrevivencia }}" disabled>
            </div>
        </div>

    @if ($analise_biometrica->situacao == '1')
        <hr>
        <h4>Últimas amostras registradas:</h4>
        
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gramatura</th>
                    <th>Qualificação</th>
                    <th>Registrada em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($ultimas_amostras as $amostra)
                <tr>
                    <td>{{ $amostra->id }}</td>
                    <td>{{ $amostra->gramatura }}</td>
                    <td>{{ $amostra->qualificacao ?: 'n/a' }}</td>
                    <td>{{ $amostra->criado_em('d/m/Y H:i:s') }}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-xs" onclick="onActionForRequest('{{ route('admin.analises_biometricas.amostras.to_remove', ['analise_biometrica_id' => $amostra->id]) }}');">
                            <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    </div>
    <div class="box-footer">
    @if ($analise_biometrica->situacao == '1')
        <button type="button" onclick="onActionForRequest('{{ route('admin.analises_biometricas.to_close', ['id' => $analise_biometrica->id]) }}');" class="btn btn-warning"><i class="fa fa-check" aria-hidden="true"></i> Finalizar coleta</button>
    @endif
        <a href="{{ route('admin.analises_biometricas') }}" class="btn btn-success">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
        </a>
    </div>
</div>
@endsection
