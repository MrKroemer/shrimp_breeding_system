@extends('adminlte::page')

@section('title', 'Registro de analises biométricas')

@section('content_header')
<h1>Cadastro de analises biométricas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Analises biométricas</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        
            
            <div class="row">
                <div class="col-xs-6">
                    <label for="ciclo_id">Ciclo:</label>
                    <input type="text" name="ciclo_id" disabled value="{{ $analise_biometrica->ciclo->tanque->sigla }} ( Ciclo Nº {{ $analise_biometrica->ciclo->numero }} )" class="form-control">
                </div>
                <div class="col-xs-6">
                    <label for="data_analise">Data da análise:</label>
                    <input type="text" name="data_analise" disabled placeholder="Data da análise" class="form-control"  value="{{ $analise_biometrica->data_analise() }}">   
                </div>
            </div>
            <br>
            <h3>Amostras</h3>
            <div class="row">
                <div class="col-xs-12">
                    <label for="moles">Ultimas Amostras Cadastradas:</label>
                    <br>
                    @foreach ($amostras as $amostra)
                    Amostra {{$cont}} <input type="text" value="{{$amostra->gramatura}} ({{$siglas[$amostra->classificacao]}})" disabled/>
                    <button type="button" onclick="onActionForRequest('{{ route('admin.analises_biometricas_v2.amostras.to_remove', ['id' => $amostra->id]) }}');" class="btn btn-danger btn-xs">
                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                    </button>
                    <br>
                    @php
                        $cont++;
                    @endphp
                    @endforeach
                </div>
            </div>
            <br><br>
            <form action="{{ route('admin.analises_biometricas_v2.amostras.to_store', ['analise_biometrica_id' => $analise_biometrica->id]) }}" method="POST" >
                {!! csrf_field() !!}
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <label for="moles">Gramatura:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <input type="number"step="any"  onkeyup="repeat(this.value,'repeat');" name="gramatura" placeholder="Gramatura" class="form-control" value="" autofocus>
                        </div>
                        <div class="col-xs-2" id="repeat" style="font-family: Verdana, Geneva, Tahoma, sans-serif;font-size: 24px;font-weight: bold;">
                        </div>
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary" ><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-xs-3">
                            <input type="radio" name="classificacao" value="1" class="form-control">
                            <label for="Opaco">Opaco</label>
                        </div>
                        <div class="col-xs-3">
                            <input type="radio" name="classificacao" value="2" class="form-control">
                            <label for="Deformado">Deformado</label>
                        </div>
                        <div class="col-xs-3">
                            <input type="radio" name="classificacao" value="3" class="form-control">
                            <label for="caudas_vermelhas">Cauda verm.</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3">
                            <input type="radio" name="classificacao" value="4" class="form-control">
                            <label for="caudas_vermelhas">Flácido</label>
                        </div>
                        <div class="col-xs-3">
                            <input type="radio" name="classificacao" value="5" class="form-control">
                            <label for="mole">Mole</label>
                        </div>
                        <div class="col-xs-3">
                            <input type="radio" name="classificacao" value="6" class="form-control">
                            <label for="semimole">Semi-Mole</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3">
                            <input type="radio" name="classificacao" value="7" class="form-control">
                            <label for="necrose_grau1">Necrose Grau 1</label>
                        </div>
                        <div class="col-xs-3">
                            <input type="radio" name="classificacao" value="8" class="form-control">
                            <label for="necrose_grau2">Necrose Grau 2</label>
                        </div>
                        <div class="col-xs-3">
                            <input type="radio" name="classificacao" value="9" class="form-control">
                            <label for="necrose_grau3">Necrose Grau 3</label>
                        </div>
                        <div class="col-xs-3">
                            <input type="radio" name="classificacao" value="10" class="form-control">
                            <label for="necrose_grau4">Necrose Grau 4</label>
                        </div>
                    </div>
                </div>
            </form>
            <br><br>
            <h3>Resumo</h3>
            <br>
            <h4>Classificação</h4>
            <br>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-2">
                        <label for="classe0to10">0/10:</label>
                        <input type="number" disabled step="any" name="classe0to10" class="form-control" value="{{ $classificacoes['classe0to10'] }}">
                    </div>
                    <div class="col-xs-2">
                        <label for="classe10to20">10/20:</label>
                        <input type="number" disabled step="any" name="classe10to20" class="form-control" value="{{ $classificacoes['classe10to20'] }}">
                    </div>
                    <div class="col-xs-2">
                        <label for="classe20to30">20/30:</label>
                        <input type="number" disabled step="any" name="classe20to30" class="form-control" value="{{ $classificacoes['classe20to30'] }}">
                    </div>
                    <div class="col-xs-2">
                        <label for="classe30to40">30/40:</label>
                        <input type="number" disabled step="any" name="classe30to40" class="form-control" value="{{ $classificacoes['classe30to40'] }}">
                    </div>
                    <div class="col-xs-2">
                        <label for="classe40to50">40/50:</label>
                        <input type="number" disabled step="any" name="classe40to50" class="form-control" value="{{ $classificacoes['classe40to50'] }}">
                    </div>
                    <div class="col-xs-2">
                        <label for="classe50to60">50/60:</label>
                        <input type="number" disabled step="any" name="classe50to60" class="form-control" value="{{ $classificacoes['classe50to60'] }}">
                    </div>
                    <div class="col-xs-2">
                        <label for="classe60to70">60/70:</label>
                        <input type="number" disabled step="any" name="classe60to70" class="form-control" value="{{ $classificacoes['classe60to70'] }}">
                    </div>
                    <div class="col-xs-2">
                        <label for="classe70to80">70/80:</label>
                        <input type="number" disabled step="any" name="classe70to80" class="form-control" value="{{ $classificacoes['classe70to80'] }}">
                    </div>
                    <div class="col-xs-2">
                        <label for="classe80to100">80/100:</label>
                        <input type="number" disabled step="any" name="classe80to100"  class="form-control" value="{{ $classificacoes['classe80to100'] }}">
                    </div>
                    <div class="col-xs-2">
                        <label for="classe100to120">100/120:</label>
                        <input type="number" disabled step="any" name="classe100to120" class="form-control" value="{{ $classificacoes['classe100to120'] }}">
                    </div>
                    <div class="col-xs-2">
                        <label for="classe120to150">120/150:</label>
                        <input type="number" disabled step="any" name="classe120to150" class="form-control" value="{{ $classificacoes['classe120to150'] }}">
                    </div>
                    <div class="col-xs-2">
                        <label for="classe150to200">150/200:</label>
                        <input type="number" disabled step="any" name="classe150toUP" class="form-control" value="{{ $classificacoes['classe150to200'] }}">
                    </div>
                    <div class="col-xs-6">
                        <label for="classe200to330">Juvenil M:</label>
                        <input type="number" disabled step="any" name="classe150toUP" class="form-control" value="{{ $classificacoes['classe200to330'] }}">
                    </div>
                    <div class="col-xs-6">
                        <label for="classe330to1000">Juvenil P:</label>
                        <input type="number" disabled step="any" name="classe150toUP" class="form-control" value="{{ $classificacoes['classe330to1000'] }}">
                    </div>
                </div>
            </div>
            <h4>Qualidade</h4>
            <br>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-4">
                        @php
                            $flacido     = classifica($classificacao['flacido'], $classificacao['total']);
                            $mole          = classifica($classificacao['mole'], $classificacao['total']);
                            $semimole      = classifica($classificacao['semimole'], $classificacao['total']);
                            $opaco         = classifica($classificacao['opaco'], $classificacao['total']);
                            $deformado     = classifica($classificacao['deformado'], $classificacao['total']);
                            $caudavermelha = classifica($classificacao['caudavermelha'], $classificacao['total']);
                            $necrose1      = classifica($classificacao['necrose1'], $classificacao['total']);
                            $necrose2      = classifica($classificacao['necrose2'], $classificacao['total']);
                            $necrose3      = classifica($classificacao['necrose3'], $classificacao['total']);
                            $necrose4      = classifica($classificacao['necrose4'], $classificacao['total']);
                            
                        @endphp
                        <label for="flacido">Flácido:</label>
                        <input type="text" disabled step="any" name="flacido" placeholder="Total de animais" class="form-control" value="{{ $flacido['valor'].' - ('.$flacido['percentual'].'%)' }}">
                    </div>
                    <div class="col-xs-4">
                        <label for="mole">Mole:</label>
                        <input type="text" disabled step="any" name="mole" placeholder="Total de animais" class="form-control" value="{{ $mole['valor'].' - ('.$mole['percentual'].'%)' }}">
                    </div>
                    <div class="col-xs-4">
                        <label for="semimole">Semi-Mole:</label>
                        <input type="text" disabled step="any" name="semimole" placeholder="Total de animais" class="form-control" value="{{ $semimole['valor'].' - ('.$semimole['percentual'].'%)' }}">
                    </div>
                    <div class="col-xs-4">
                        <label for="opaco">Opaco:</label>
                        <input type="text" disabled step="any" name="opaco" placeholder="Total de animais" class="form-control" value="{{ $opaco['valor'].' - ('.$opaco['percentual'].'%)' }}">
                    </div>
                    <div class="col-xs-4">
                        <label for="deformado">Deformado:</label>
                        <input type="text" disabled step="any" name="deformado" placeholder="Total de animais" class="form-control" value="{{ $deformado['valor'].' - ('.$deformado['percentual'].'%)' }}">
                    </div>
                    <div class="col-xs-4">
                        <label for="cauda_vermelha">Cauda-Vermelha:</label>
                        <input type="text" disabled step="any" name="cauda_vermelha" placeholder="Total de animais" class="form-control" value="{{ $caudavermelha['valor'].' - ('.$caudavermelha['percentual'].'%)' }}">
                    </div>
                    <div class="col-xs-3">
                        <label for="Necrose1">Necrose 1:</label>
                        <input type="text" disabled  name="Necrose1" placeholder="Total de animais" class="form-control" value="{{ $necrose1['valor'].' - ('.$necrose1['percentual'].'%)' }}">
                    </div>
                    <div class="col-xs-3">
                        <label for="Necrose2">Necrose 2:</label>
                        <input type="text" disabled  name="Necrose2" placeholder="Total de animais" class="form-control" value="{{ $necrose2['valor'].' - ('.$necrose2['percentual'].'%)' }}">
                    </div>
                    <div class="col-xs-3">
                        <label for="Necrose3">Necrose 3:</label>
                        <input type="text" disabled  name="Necrose3" placeholder="Total de animais" class="form-control" value="{{ $necrose3['valor'].' - ('.$necrose3['percentual'].'%)' }}">
                    </div>
                    <div class="col-xs-3">
                        <label for="Necrose4">Necrose 4:</label>
                        <input type="text" disabled  name="Necrose4" placeholder="Total de animais" class="form-control" value="{{ $necrose4['valor'].' - ('.$necrose4['percentual'].'%)' }}">
                    </div>
                </div>
            </div>
            <h4>Conclusão</h4>
            <br>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">
                        <label for="total_animais">Total de animais:</label>
                        <input type="number" disabled  name="total_animais" placeholder="Total de animais" class="form-control" value="{{ $analise_biometrica->gramaturas->count() }}">
                    </div>
                    <div class="col-xs-3">
                        <label for="peso_total">Peso total (g):</label>
                        <input type="number" disabled  name="peso_total" placeholder="Peso total" class="form-control" value="{{ $analise_biometrica->pesototal() }}">
                    </div>
                    <div class="col-xs-3">
                        <label for="peso_medio">Peso médio (g):</label>
                        <input type="number" disabled  name="peso_medio" placeholder="Peso médio" class="form-control" value="{{ $analise_biometrica->pesomedio() }}">
                    </div>
                    <div class="col-xs-3">
                        <label for="sobrevivencia">Percentual de sobrevivência:</label>
                        <input type="text" disabled  name="sobrevivencia" placeholder="Percentual de sobrevivência" class="form-control" value="{{ $analise_biometrica->sobrevivencia }} %">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="button" onclick="location.assign('{{ route('admin.analises_biometricas_v2.amostras.to_turn', ['analise_biometrica_id' => $analise_biometrica->id]) }}');" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Encerrar Coletas</button>
                <a href="{{ route('admin.analises_biometricas_v2') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
    </div>
</div>
@php
    function classifica($cf,$total){

        $resultado = [
            'valor'  => 0,
            'percentual'   => 0
    ];

        if(($cf != 0) || ($total != 0)){
            $resultado['valor'] = $cf;
            $resultado['percentual'] = round($cf/$total, 1);
        }

        return $resultado;
    }
@endphp
@endsection