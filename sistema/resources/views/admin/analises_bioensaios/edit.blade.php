@extends('adminlte::page')

@section('title' 'Análise Bioensaios')

@section('conte-header')
<h1>{{ $situacao_ciclo ? 'Edição'  : 'Visualização' }} de análises bioensaios</h1>

<ol class="breadcrumb">

    <li><a href="">Dashboard</a></li>
    <li><a href="">Atividades</a></li>
    <li><a href="">{{ $situacao_ciclo ?  'Edição'  : 'Visualização'  }}de Bioensaios</a></li>

</ol>

@endsection 

@section('content')
@include('admin.includes.alerts')

   <div class="box">
       <div class="box-header"></div>
           <div class="box-body">
               @if($situacao_ciclo)
               <form action="{{ route('admin.anlises_bioensaios.to_update', array_merge(['id' => $id], $redirectParam)) }}" method="POST"></form> {!! csrf_field() !!}
               @else
                <form>
               @endif
                    <div class="form-group">´
                        <label for="data_analise">Data Análise</label>
                            <div class="input-group date">
                                <a onclick="clearDateValue(this);"><i class="fas fa-calendar"></i></a>
                            </div>
                                <input type="text" name="data_analise" placeholder="Data da Análise" class="form-control pull-right" id="date_picker" value="{{ $analise_biometrica->data_analise()'}}" disabled>
                    </div>     
           </div> 
                            <div class="form-group">
                                <label for="ciclo_d">Ciclo:</label>
                                <select name="ciclo_id" onChangelBiotipo id=""></select>
                            </div>

   </div>






