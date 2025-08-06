/**
 * @description Lib de funcoes específicas para pagina de análises presuntivas
 * @author Bruno Silva <bruno.silva@camanor.com.br>
 */
function totallipideos() {

    var media = 0;
    var lipideos1 = $("#lipideos1").val();
    var lipideos2 = $("#lipideos2").val();
    var lipideos3 = $("#lipideos3").val();
    var lipideos4 = $("#lipideos4").val();
    var lipideos5 = $("#lipideos5").val();
    var lipideos6 = $("#lipideos6").val();
    var lipideos7 = $("#lipideos7").val();
    var lipideos8 = $("#lipideos8").val();
    var lipideos9 = $("#lipideos9").val();
    var lipideos10 = $("#lipideos10").val();
    if(lipideos1 != ""){
        media = media+1;
    }else{
        lipideos1 = 0;
    }
    if(lipideos2 != ""){
        media = media+1;
    }else{
        lipideos2 = 0;
    }
    if(lipideos3 != ""){
        media = media+1;
    }else{
        lipideos3 = 0;
    }
    if(lipideos4 != ""){
        media = media+1;
    }else{
        lipideos4 = 0;
    }
    if(lipideos5 != ""){
        media = media+1;
    }else{
        lipideos5 = 0;
    }
    if(lipideos6 != ""){
        media = media+1;
    }else{
        lipideos6 = 0;
    }
    if(lipideos7 != ""){
        media = media+1;
    }else{
        lipideos7 = 0;
    }
    if(lipideos8 != ""){
        media = media+1;
    }else{
        lipideos8 = 0;
    }
    if(lipideos9 != ""){
        media = media+1;
    }else{
        lipideos9 = 0;
    }
    if(lipideos10 != ""){
        media = media+1;
    }else{
        lipideos10 = 0;
    }

    total = parseInt(lipideos1)+parseInt(lipideos2)+parseInt(lipideos3)+parseInt(lipideos4)+parseInt(lipideos5)+parseInt(lipideos6)+parseInt(lipideos7)+parseInt(lipideos8)+parseInt(lipideos9)+parseInt(lipideos10);
    if(total == 0){
        total = 0;
    }else{
        total = total/media;
    }
    $("#medialipideos").removeAttr("disabled");
    $("#medialipideos").val(total);
    $("#medialipideos").attr("disabled",true);
}

function checkvalue(campo){
    var	field = '#'+campo;
    var valor = $(field).val();
    if(isNaN(valor)){
        $(field).val("");
        totallipideos();
    }else if(parseInt(valor) > 4){
        alert("O valor máximo pra este campo é 4!");
        $(field).val("");
        totallipideos();
    }
}

function checkvalueDn(campo){
    var	field = '#'+campo;
    var valor = $(field).val();
    if(isNaN(valor)){
        $(field).val("");
        totallipideos();
    }else if(parseInt(valor) > 10){
        alert("O valor máximo pra este campo é 10!");
        $(field).val("");
        totallipideos();
    }
}

function porcentagemdano(){


    var media = 0;

    var dano0 = 0;
    var dano1 = 0;
    var dano2 = 0;
    var dano3 = 0;
    var dano4 = 0;


    var tudano1 = $("#danos_tubulos1").val();
    var tudano2 = $("#danos_tubulos2").val();
    var tudano3 = $("#danos_tubulos3").val();
    var tudano4 = $("#danos_tubulos4").val();
    var tudano5 = $("#danos_tubulos5").val();
    var tudano6 = $("#danos_tubulos6").val();
    var tudano7 = $("#danos_tubulos7").val();
    var tudano8 = $("#danos_tubulos8").val();
    var tudano9 = $("#danos_tubulos9").val();
    var tudano10 = $("#danos_tubulos10").val();
    if(tudano1 != ""){
        media = media + 1;
        if(tudano1 == 0){
            dano0 = dano0+1;
        }else if(tudano1 == 1){
            dano1 = dano1+1;
        }else if(tudano1 == 2){
            dano2 = dano2+1;
        }else if(tudano1 == 3){
            dano3 = dano3+1;
        }else if(tudano1 == 4){
            dano4 = dano4+1;
        }else if(tudano1 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#danos_tubulos1").focus();
            return false;
        }
    }
    if(tudano2 != ""){
        media = media + 1;
        if(tudano2 == 0){
            dano0 = dano0+1;
        }else if(tudano2 == 1){
            dano1 = dano1+1;
        }else if(tudano2 == 2){
            dano2 = dano2+1;
        }else if(tudano2 == 3){
            dano3 = dano3+1;
        }else if(tudano2 == 4){
            dano4 = dano4+1;
        }else if(tudano2 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#danos_tubulos2").focus();
            return false;
        }
    }
    if(tudano3 != ""){
        media = media + 1;
        if(tudano3 == 0){
            dano0 = dano0+1;
        }else if(tudano3 == 1){
            dano1 = dano1+1;
        }else if(tudano3 == 2){
            dano2 = dano2+1;
        }else if(tudano3 == 3){
            dano3 = dano3+1;
        }else if(tudano3 == 4){
            dano4 = dano4+1;
        }else if(tudano3 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#danos_tubulos3").focus();
            return false;
        }
    }
    if(tudano4 != ""){
        media = media + 1;
        if(tudano4 == 0){
            dano0 = dano0+1;
        }else if(tudano4 == 1){
            dano1 = dano1+1;
        }else if(tudano4 == 2){
            dano2 = dano2+1;
        }else if(tudano4 == 3){
            dano3 = dano3+1;
        }else if(tudano4 == 4){
            dano4 = dano4+1;
        }else if(tudano4 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#danos_tubulos4").focus();
            return false;
        }
    }
    if(tudano5 != ""){
        media = media + 1;
        if(tudano5 == 0){
            dano0 = dano0+1;
        }else if(tudano5 == 1){
            dano1 = dano1+1;
        }else if(tudano5 == 2){
            dano2 = dano2+1;
        }else if(tudano5 == 3){
            dano3 = dano3+1;
        }else if(tudano5 == 4){
            dano4 = dano4+1;
        }else if(tudano5 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#danos_tubulos5").focus();
            return false;
        }
    }
    if(tudano6 != ""){
        media = media + 1;
        if(tudano6 == 0){
            dano0 = dano0+1;
        }else if(tudano6 == 1){
            dano1 = dano1+1;
        }else if(tudano6 == 2){
            dano2 = dano2+1;
        }else if(tudano6 == 3){
            dano3 = dano3+1;
        }else if(tudano6 == 4){
            dano4 = dano4+1;
        }else if(tudano6 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#danos_tubulos6").focus();
            return false;
        }
    }
    if(tudano7 != ""){
        media = media + 1;
        if(tudano7 == 0){
            dano0 = dano0+1;
        }else if(tudano7 == 1){
            dano1 = dano1+1;
        }else if(tudano7 == 2){
            dano2 = dano2+1;
        }else if(tudano7 == 3){
            dano3 = dano3+1;
        }else if(tudano7 == 4){
            dano4 = dano4+1;
        }else if(tudano7 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#danos_tubulos7").focus();
            return false;
        }
    }
    if(tudano8 != ""){
        media = media + 1;
        if(tudano8 == 0){
            dano0 = dano0+1;
        }else if(tudano8 == 1){
            dano1 = dano1+1;
        }else if(tudano8 == 2){
            dano2 = dano2+1;
        }else if(tudano8 == 3){
            dano3 = dano3+1;
        }else if(tudano8 == 4){
            dano4 = dano4+1;
        }else if(tudano8 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#danos_tubulos8").focus();
            return false;
        }
    }
    if(tudano9 != ""){
        media = media + 1;
        if(tudano9 == 0){
            dano0 = dano0+1;
        }else if(tudano9 == 1){
            dano1 = dano1+1;
        }else if(tudano9 == 2){
            dano2 = dano2+1;
        }else if(tudano9 == 3){
            dano3 = dano3+1;
        }else if(tudano9 == 4){
            dano4 = dano4+1;
        }else if(tudano9 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#danos_tubulos9").focus();
            return false;
        }
    }
    if(tudano10 != ""){
        media = media + 1;
        if(tudano10 == 0){
            dano0 = dano0+1;
        }else if(tudano10 == 1){
            dano1 = dano1+1;
        }else if(tudano10 == 2){
            dano2 = dano2+1;
        }else if(tudano10 == 3){
            dano3 = dano3+1;
        }else if(tudano10 == 4){
            dano4 = dano4+1;
        }else if(tudano10 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#danos_tubulos10").focus();
            return false;
        }
    }


    if(dano0 > 0){
        dano0 = (dano0*100)/media;
    }

    if(dano1 > 0){
        dano1 = (dano1*100)/media;
    }

    if(dano2 > 0){
        dano2 = (dano2*100)/media;
    }

    if(dano3 > 0){
        dano3 = (dano3*100)/media;
    }

    if(dano4 > 0){
        dano4 = (dano4*100)/media;
    }

    //Coloca o valor do dano 0
    $("#lipideos_dano_normal").removeAttr("disabled");
    $("#lipideos_dano_normal").val(dano0.toFixed(2));
    $("#lipideos_dano_normal").attr("disabled",true);

    //Coloca o valor do dano 1
    $("#lipideos_dano_grau1").removeAttr("disabled");
    $("#lipideos_dano_grau1").val(dano1.toFixed(2));
    $("#lipideos_dano_grau1").attr("disabled",true);

    //Coloca o valor do dano 2
    $("#lipideos_dano_grau2").removeAttr("disabled");
    $("#lipideos_dano_grau2").val(dano2.toFixed(2));
    $("#lipideos_dano_grau2").attr("disabled",true);

    //Coloca o valor do dano 3
    $("#lipideos_dano_grau3").removeAttr("disabled");
    $("#lipideos_dano_grau3").val(dano3.toFixed(2));
    $("#lipideos_dano_grau3").attr("disabled",true);

    //Coloca o valor do dano 4
    $("#lipideos_dano_grau4").removeAttr("disabled");
    $("#lipideos_dano_grau4").val(dano4.toFixed(2));
    $("#lipideos_dano_grau4").attr("disabled",true);
}

function porcentagemdanoO(){


    var media = 0;

    var dano0 = 0;
    var dano1 = 0;
    var dano2 = 0;
    var dano3 = 0;
    var dano4 = 0;


    var tudano1 = $("#cristais1").val();
    var tudano2 = $("#cristais2").val();
    var tudano3 = $("#cristais3").val();
    var tudano4 = $("#cristais4").val();
    var tudano5 = $("#cristais5").val();
    var tudano6 = $("#cristais6").val();
    var tudano7 = $("#cristais7").val();
    var tudano8 = $("#cristais8").val();
    var tudano9 = $("#cristais9").val();
    var tudano10 = $("#cristais10").val();

    if(tudano1 != ""){
        media = media + 1;
        if(tudano1 == 0){
            dano0 = dano0+1;
        }else if(tudano1 == 1){
            dano1 = dano1+1;
        }else if(tudano1 == 2){
            dano2 = dano2+1;
        }else if(tudano1 == 3){
            dano3 = dano3+1;
        }else if(tudano1 == 4){
            dano4 = dano4+1;
        }else if(tudano1 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#cristais1").focus();
            return false;
        }
    }
    if(tudano2 != ""){
        media = media + 1;
        if(tudano2 == 0){
            dano0 = dano0+1;
        }else if(tudano2 == 1){
            dano1 = dano1+1;
        }else if(tudano2 == 2){
            dano2 = dano2+1;
        }else if(tudano2 == 3){
            dano3 = dano3+1;
        }else if(tudano2 == 4){
            dano4 = dano4+1;
        }else if(tudano2 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#cristais2").focus();
            return false;
        }
    }
    if(tudano3 != ""){
        media = media + 1;
        if(tudano3 == 0){
            dano0 = dano0+1;
        }else if(tudano3 == 1){
            dano1 = dano1+1;
        }else if(tudano3 == 2){
            dano2 = dano2+1;
        }else if(tudano3 == 3){
            dano3 = dano3+1;
        }else if(tudano3 == 4){
            dano4 = dano4+1;
        }else if(tudano3 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#cristais3").focus();
            return false;
        }
    }
    if(tudano4 != ""){
        media = media + 1;
        if(tudano4 == 0){
            dano0 = dano0+1;
        }else if(tudano4 == 1){
            dano1 = dano1+1;
        }else if(tudano4 == 2){
            dano2 = dano2+1;
        }else if(tudano4 == 3){
            dano3 = dano3+1;
        }else if(tudano4 == 4){
            dano4 = dano4+1;
        }else if(tudano4 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#cristais4").focus();
            return false;
        }
    }
    if(tudano5 != ""){
        media = media + 1;
        if(tudano5 == 0){
            dano0 = dano0+1;
        }else if(tudano5 == 1){
            dano1 = dano1+1;
        }else if(tudano5 == 2){
            dano2 = dano2+1;
        }else if(tudano5 == 3){
            dano3 = dano3+1;
        }else if(tudano5 == 4){
            dano4 = dano4+1;
        }else if(tudano5 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#cristais5").focus();
            return false;
        }
    }
    if(tudano6 != ""){
        media = media + 1;
        if(tudano6 == 0){
            dano0 = dano0+1;
        }else if(tudano6 == 1){
            dano1 = dano1+1;
        }else if(tudano6 == 2){
            dano2 = dano2+1;
        }else if(tudano6 == 3){
            dano3 = dano3+1;
        }else if(tudano6 == 4){
            dano4 = dano4+1;
        }else if(tudano6 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#cristais6").focus();
            return false;
        }
    }
    if(tudano7 != ""){
        media = media + 1;
        if(tudano7 == 0){
            dano0 = dano0+1;
        }else if(tudano7 == 1){
            dano1 = dano1+1;
        }else if(tudano7 == 2){
            dano2 = dano2+1;
        }else if(tudano7 == 3){
            dano3 = dano3+1;
        }else if(tudano7 == 4){
            dano4 = dano4+1;
        }else if(tudano7 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#cristais7").focus();
            return false;
        }
    }
    if(tudano8 != ""){
        media = media + 1;
        if(tudano8 == 0){
            dano0 = dano0+1;
        }else if(tudano8 == 1){
            dano1 = dano1+1;
        }else if(tudano8 == 2){
            dano2 = dano2+1;
        }else if(tudano8 == 3){
            dano3 = dano3+1;
        }else if(tudano8 == 4){
            dano4 = dano4+1;
        }else if(tudano8 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#cristais8").focus();
            return false;
        }
    }
    if(tudano9 != ""){
        media = media + 1;
        if(tudano9 == 0){
            dano0 = dano0+1;
        }else if(tudano9 == 1){
            dano1 = dano1+1;
        }else if(tudano9 == 2){
            dano2 = dano2+1;
        }else if(tudano9 == 3){
            dano3 = dano3+1;
        }else if(tudano9 == 4){
            dano4 = dano4+1;
        }else if(tudano9 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#cristais9").focus();
            return false;
        }
    }
    if(tudano10 != ""){
        media = media + 1;
        if(tudano10 == 0){
            dano0 = dano0+1;
        }else if(tudano10 == 1){
            dano1 = dano1+1;
        }else if(tudano10 == 2){
            dano2 = dano2+1;
        }else if(tudano10 == 3){
            dano3 = dano3+1;
        }else if(tudano10 == 4){
            dano4 = dano4+1;
        }else if(tudano10 > 4){
            alert("Por favor, insira um valor entre 0 e 4!");
            $("#cristais10").focus();
            return false;
        }
    }


    if(dano0 > 0){
        dano0 = (dano0*100)/media;
    }

    if(dano1 > 0){
        dano1 = (dano1*100)/media;
    }

    if(dano2 > 0){
        dano2 = (dano2*100)/media;
    }

    if(dano3 > 0){
        dano3 = (dano3*100)/media;
    }

    if(dano4 > 0){
        dano4 = (dano4*100)/media;
    }

    //Coloca o valor do dano 0
    $("#cristais_dano_normal").removeAttr("disabled");
    $("#cristais_dano_normal").val(dano0.toFixed(2));
    $("#cristais_dano_normal").attr("disabled",true);

    //Coloca o valor do dano 1
    $("#cristais_dano_grau1").removeAttr("disabled");
    $("#cristais_dano_grau1").val(dano1.toFixed(2));
    $("#cristais_dano_grau1").attr("disabled",true);

    //Coloca o valor do dano 2
    $("#cristais_dano_grau2").removeAttr("disabled");
    $("#cristais_dano_grau2").val(dano2.toFixed(2));
    $("#cristais_dano_grau2").attr("disabled",true);

    //Coloca o valor do dano 3
    $("#cristais_dano_grau3").removeAttr("disabled");
    $("#cristais_dano_grau3").val(dano3.toFixed(2));
    $("#cristais_dano_grau3").attr("disabled",true);

    //Coloca o valor do dano 4
    $("#cristais_dano_grau4").removeAttr("disabled");
    $("#cristais_dano_grau4").val(dano4.toFixed(2));
    $("#cristais_dano_grau4").attr("disabled",true);
}

function confereaditivo(){

    //pega o Dano 0
    $("#lipideos_dano_normal").removeAttr("disabled");
    f_dano = parseFloat($("#lipideos_dano_normal").val());
    $("#lipideos_dano_normal").attr("disabled",true);
    //confere se o aditivo foi selecionado
    aditivo = $("#aditivos").val()
    msg = $("#aditivomsg").val()
    //Se for menor do que 60 sugere entrar com aditivo
    if((msg == 0)){
        if(f_dano < 60){
            alert("Com essa porcentagem de dano, é recomendável entrar com o tratamento de aditivo!");
            $("#aditivomsg").val(1);
            $("#aditivo").val("Sim");
            $("#aditivo").focus();
        }
    }
}

$(document).ready(function(){

    $("input[type='checkbox']").on('ifChanged', function() {
        
        var splited_name = this.name.split('_');
        var check_name = splited_name.slice(1, -1);
        name = "chk_"+check_name.join('_');
        total = 0;
        for (var i=1; i <= 10; i ++) {
            if($('#'+name+'_'+i).is(':checked')){
               total = total + 1;
            }
        }
        $("#total_"+check_name.join('_')).removeAttr("disabled");
        $("#total_"+check_name.join('_')).val(total*10);
        $("#total_"+check_name.join('_')).attr("disabled",true);
    })

});



