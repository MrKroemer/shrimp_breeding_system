@extends('adminlte::page')

@section('title', 'Teste de componentes')

@section('content_header')
<h1>Teste de componentes</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Teste de componentes</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<script>
$(document).ready(function(){

    var quantitiy = 0;

    $('.quantity-right-plus').click(function(e){
        e.preventDefault();
        var quantity = parseInt($('#quantity').val());
        $('#quantity').val(quantity + 1);
    });

    $('.quantity-left-minus').click(function(e){
        e.preventDefault();
        var quantity = parseInt($('#quantity').val());
        if(quantity > 0){
            $('#quantity').val(quantity - 1);
        }
    });
    
});
</script>

{{--<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form>
             <div class="form-group">
                <label for="bandejas">Gramatura:</label>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button type="button" class="quantity-left-minus btn btn-danger btn-number"  data-type="minus" data-field="">
                                    <span class="glyphicon glyphicon-minus"></span>
                                </button>
                            </span>
                            <input type="text" id="quantity" name="quantity" class="form-control input-number" min="0" max="200" value="0">
                            <span class="input-group-btn">
                                <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field="">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </span>
                        </div>
                    </div>  
                </div>
            </div> 
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
                <a href="#" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>--}}

<div class="box">
    <div class="box-body">
        <button type="button" onclick="onActionForRequest('{{ route('admin.execute') }}')" class="btn btn-warning">
            <i class="fa fa-check" aria-hidden="true"></i> Executar
        </button>
    </div>
</div>
@endsection