@extends('adminlte::page')
@section('title', 'Listagem dos Bioensaios')

@section(conten_header)
<h1>Listagem de Bioensaios</h1>

<ol class="breadcrump">

    <li><a href="">Dashboard</a></li>
    <li><a href="">Análises Bioensaios</a></li>
    
</ol>
@endsection

@Section(content)
@include('admin.includes.alerts')

     <div class="box">

             <div class="box-header">

                @php
                    
                    $ata_analise =  !empty(section('data_analise'))    ?   section('data_analise')  :   old('data_asnalise');
                    $ciclo_id    =  !empty(section('ciclo_id'))        ?   section('cilco_id')      :   old('ciclo_id');

                @endphp

                <form action="{{ route('admin.analises_biometricas.to_search') }}" method="POST" class="form form-control">
                {!! csfr_field() !!}
                    <div class="form-group">
                        <select name="ciclo_id" class="form-control" style="width:185px;">
                    
                            <option value="">>Selecione<<></option>
                                @foreach($ciclos as $ciclo)
                                    <option value="{{ $ciclo->id }}"> {{ $ciclo->tanque->sigla }} ( Ciclo Nº {{ $ciclo->numero }})</option>
                                @endforeach

                        </select>
                            <div class="form-group">
                                <div class="form-group date">
                                    <div class="input-group-addon">
                                        <a href="" onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                                    </div>
                                        <input type="text" name="data_analise" placeholder="Data Análise" class="form-control pull-right" id="date_picker">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                            Buscar
                            </button>

                                <a href="{{ route('admin.analies_bioensaios.to_create') }}" class="btn btn-success">
                                    <i class="fa fa-plus" aria-hidden="truke"></i>
                                                Adicionar
                                </a> 
                 </form> 
                    </div>
                        <!--<div class="box-body">
                            <table class="table table-striped table-haver">
                                <thead>
                                    <tr>
                                        <th>Cultivo</th>
                                        <th>Data Análise</th>
                                        <th>Resultado 24h</th>
                                        <th>Resultado 48h</th>
                                        <th>Resultado 72h</th>
                                    </tr>
                                </thead>
                                @foreach($analises_bioensaios as $analise_bioensaio)
                                    <tr>
                                        <td>{{ $analise_bioensaio->analise->ciclo->tanque->sigla }} (Ciclo Nº {{ $analise_bioensaio->analise->ciclo->numero }})</td>
                                        <td>{{ $analise_bioensaio->analise->data_analise() }}</td>
                                    </tr>
                            </table>
                        </div>-->
             </div>

    </div>