@extends('adminlte::pages')
@section('title' 'Registro de Análises dos Bioensaios')

@section('content_header')
<h1>Registro de Análises dos Bioensaios</h1>




<ol class="breadcrump">

    <li><a href="">Dashboard</a></li>
    <li><a href="">Análises Bioensaios</a></li>
    <li><a href="">Cadastro</a></li>

</ol>


@endsection

@section(content)
@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
       
        @php

            $ciclo_id      =   !empty(session('ciclo_id'))      ?  session('ciclo_id')       :   old('ciclo_id')
            $data_analise  =   !empty(session('data_analise'))  ?  session('data_analise')   :   old('data_analise') 
            $usuario_id    =   !empty(session('usuario_id'))    ?  session('usuario_id')     :   old('usuario_id')

        @endphp

        <form action="{{ route('admin.analises_bioensaios.to_store) }}" method="POST">
            {{ !! csrf_field() !!}}

            <div class="form-group">
                <label for="data_analise">Data análise:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_analise" placeholder="Data Análise" class="form-control pull-right" id="data_picker" value="{{ $data_analise }}">
                </div>
            </div>

            <div class="form-group">
                <label for="ciclo_id">Ciclo:</label>
                <select name="ciclo_id" onchange="onChangeBioTipoCultivo();" class="form-control">
                <option value="">..::Selecione::..</option>
                @foreach($ciclos as $ciclo)

                <option value="{{ $ciclo->ciclo_id }}" {{ ($ciclo->ciclo_id == $ciclo_id)  ?  'selected'  :  '' }}>
                        {{ $ciclo->tanque_sigla }} ( Ciclo Nº {{ $ciclo->ciclo_numero }} )</option>
                
                
                @endforeach
                </select>
            </div>
            <hr>
            <h1>Cadastro de Análises Bioensaios</h1>

            <table class="table table-striped table-hover"> 

                <tr>
             
                   <td>
                       <div class="row">

                           <div class="col-md-2">
                               <label for="resultado-24">24h</label>
                               <input type="text" placeholder="Resultado após 24h" class="form-control">
                           </div>

                           <div class="col-md-2">
                                <label for="resultado-48">48h</label>
                                <input type="text" placeholder="Resultadob após 48h" class="form-control">
                           </div>

                           <div class="col-md-2">
                               <label for="resultado-72">72h</label>
                               <input type="text" placeholder="Resultado após 72h">
                           </div>

                           <div class="col-md-10"> 

                                <label for="observacoes"></label>
                                <textarea name="observacoes" cols="30" rows="5" class="form-control" placeholder="Observações"></textarea>

                           </div>

                           </div>


                       </div>
                   </td>

                </tr> 




            </table>

        </form>
    </div>
</div>