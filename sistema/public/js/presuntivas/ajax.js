/**
 * @description Lib de funcoes específicas para pagina de análises presuntivas
 * @author Bruno Silva <bruno.silva@camanor.com.br>
 */
$(document).ready(function() {

  $('#salvar').click(function(e){
    $('#total_cor_animal').removeAttr("disabled"); 
    $('#total_aparencia_carapaca').removeAttr("disabled");
    $('#total_aparencia_musculatura').removeAttr("disabled");
    $('#total_coloracao_associada').removeAttr("disabled");
    $('#total_antenas').removeAttr("disabled");
    $('#form_presuntiva').submit();
  });

  $('#cor_animal').change(function(e){
    if($('#ciclo_id').val() == ""){
      $('#cor_animal').val(0);
      $('#ciclo_id').focus();
      alert('Antes de continuar, por favor, preencha o ciclo da análise');
    }
  });

  $('#cor_animal').change(function(e){
    if($('#datepicker').val() == ""){
      $('#cor_animal').val(0);
      $('#datepicker').focus();
      alert('Antes de continuar, por favor, preencha a Data da análise');
    }
  });

  $('#antenas').change(function(e){
    if($('#ciclo_id').val() == ""){
      $('#antenas').val(0);
      $('#ciclo_id').focus();
      alert('Antes de continuar, por favor, preencha o ciclo da análise');
    }
  });

  $('#antenas').change(function(e){
    if($('#datepicker').val() == ""){
      $('#antenas').val(0);
      $('#datepicker').focus();
      alert('Antes de continuar, por favor, preencha a Data da análise');
    }
  });

  $('#aparencia_musculatura').change(function(e){
    if($('#ciclo_id').val() == ""){
      $('#aparencia_musculatura').val(0);
      $('#ciclo_id').focus();
      alert('Antes de continuar, por favor, preencha o ciclo da análise');
    }
  });

  $('#aparencia_musculatura').change(function(e){
    if($('#datepicker').val() == ""){
      $('#aparencia_musculatura').val(0);
      $('#datepicker').focus();
      alert('Antes de continuar, por favor, preencha a Data da análise');
    }
  });

  $('#lipideos1').change(function(e){
    if($('#ciclo_id').val() == ""){
      $('#lipideos1').val("");
      $('#ciclo_id').focus();
      alert('Antes de continuar, por favor, preencha o ciclo da análise');
    }
  });

  $('#lipideos1').change(function(e){
    if($('#datepicker').val() == ""){
      $('#lipideos1').val("");
      $('#datepicker').focus();
      alert('Antes de continuar, por favor, preencha a Data da análise');
    }
  });

  $('#lipideos2').change(function(e){
    if($('#ciclo_id').val() == ""){
      $('#lipideos2').val("");
      $('#ciclo_id').focus();
      alert('Antes de continuar, por favor, preencha o ciclo da análise');
    }
  });

  $('#lipideos2').change(function(e){
    if($('#datepicker').val() == ""){
      $('#lipideos2').val("");
      $('#datepicker').focus();
      alert('Antes de continuar, por favor, preencha a Data da análise');
    }
  });

  $('#lipideos3').change(function(e){
    if($('#ciclo_id').val() == ""){
      $('#lipideos3').val("");
      $('#ciclo_id').focus();
      alert('Antes de continuar, por favor, preencha o ciclo da análise');
    }
  });

  $('#lipideos3').change(function(e){
    if($('#datepicker').val() == ""){
      $('#lipideos3').val("");
      $('#datepicker').focus();
      alert('Antes de continuar, por favor, preencha a Data da análise');
    }
  });

  $('#lipideos4').change(function(e){
    if($('#ciclo_id').val() == ""){
      $('#lipideos4').val("");
      $('#ciclo_id').focus();
      alert('Antes de continuar, por favor, preencha o ciclo da análise');
    }
  });

  $('#lipideos4').change(function(e){
    if($('#datepicker').val() == ""){
      $('#lipideos4').val("");
      $('#datepicker').focus();
      alert('Antes de continuar, por favor, preencha a Data da análise');
    }
  });

  $('#lipideos5').change(function(e){
    if($('#ciclo_id').val() == ""){
      $('#lipideos5').val("");
      $('#ciclo_id').focus();
      alert('Antes de continuar, por favor, preencha o ciclo da análise');
    }
  });

  $('#lipideos5').change(function(e){
    if($('#datepicker').val() == ""){
      $('#lipideos5').val("");
      $('#datepicker').focus();
      alert('Antes de continuar, por favor, preencha a Data da análise');
    }
  });

  $('#lipideos6').change(function(e){
    if($('#ciclo_id').val() == ""){
      $('#lipideos6').val("");
      $('#ciclo_id').focus();
      alert('Antes de continuar, por favor, preencha o ciclo da análise');
    }
  });

  $('#lipideos6').change(function(e){
    if($('#datepicker').val() == ""){
      $('#lipideos6').val("");
      $('#datepicker').focus();
      alert('Antes de continuar, por favor, preencha a Data da análise');
    }
  });

  $('#lipideos7').change(function(e){
    if($('#ciclo_id').val() == ""){
      $('#lipideos7').val("");
      $('#ciclo_id').focus();
      alert('Antes de continuar, por favor, preencha o ciclo da análise');
    }
  });

  $('#lipideos7').change(function(e){
    if($('#datepicker').val() == ""){
      $('#lipideos7').val("");
      $('#datepicker').focus();
      alert('Antes de continuar, por favor, preencha a Data da análise');
    }
  });

  $('#lipideos8').change(function(e){
    if($('#ciclo_id').val() == ""){
      $('#lipideos8').val("");
      $('#ciclo_id').focus();
      alert('Antes de continuar, por favor, preencha o ciclo da análise');
    }
  });

  $('#lipideos8').change(function(e){
    if($('#datepicker').val() == ""){
      $('#lipideos8').val("");
      $('#datepicker').focus();
      alert('Antes de continuar, por favor, preencha a Data da análise');
    }
  });

  $('#lipideos9').change(function(e){
    if($('#ciclo_id').val() == ""){
      $('#lipideos9').val("");
      $('#ciclo_id').focus();
      alert('Antes de continuar, por favor, preencha o ciclo da análise');
    }
  });

  $('#lipideos9').change(function(e){
    if($('#datepicker').val() == ""){
      $('#lipideos9').val("");
      $('#datepicker').focus();
      alert('Antes de continuar, por favor, preencha a Data da análise');
    }
  });

  $('#lipideos10').change(function(e){
    if($('#ciclo_id').val() == ""){
      $('#lipideos10').val("");
      $('#ciclo_id').focus();
      alert('Antes de continuar, por favor, preencha o ciclo da análise');
    }
  });

  $('#lipideos10').change(function(e){
    if($('#datepicker').val() == ""){
      $('#lipideos10').val("");
      $('#datepicker').focus();
      alert('Antes de continuar, por favor, preencha a Data da análise');
    }
  });

  $('#form_presuntiva').change(function(e){
    if(($('input[name="ciclo_id"]').val() != "") || ($('input[name="dataanalise"]').val() != "")){
      e.preventDefault();
      $.ajaxSetup({});
      $.ajax({
        url: "http://localhost/sistema/public/admin/presuntivas/temp",
        method: 'post',
        data: {
          _token:                      $('input[name="_token"]').val(),
          ciclo_id:                    $('input[name="ciclo_id"]').val(),
          data_analise:                $('input[name="dataanalise"]').val(),
          cor_animal:                  $('#cor_animal').val(),
          antenas:                     $('#antenas').val(),
          aparencia_musculatura:       $('#aparencia_musculatura').val(),
          aparencia_carapaca:          $('#aparencia_carapaca').val(),
          edema_uropodos:              $('#edema_uropodos').val(),
          aditivo:                     $('#aditivo').val(),
          total_cor_animal:            $('#total_cor_animal').val(),
          total_antenas:               $('#total_antenas').val(),
          total_aparencia_carapaca:    $('#total_aparencia_carapaca').val(),
          total_aparencia_musculatura: $('#total_aparencia_musculatura').val(),
          total_coloracao_associada:   $('#total_coloracao_associada').val(),
          lipideos1:                   $('#lipideos1').val(),
          lipideos2:                   $('#lipideos2').val(),
          lipideos3:                   $('#lipideos3').val(),
          lipideos4:                   $('#lipideos4').val(),
          lipideos5:                   $('#lipideos5').val(),
          lipideos6:                   $('#lipideos6').val(),
          lipideos7:                   $('#lipideos7').val(),
          lipideos8:                   $('#lipideos8').val(),
          lipideos9:                   $('#lipideos9').val(),
          lipideos10:                  $('#lipideos10').val(),
          qualidade_lipideos:          $('#qualidade_lipideos').val(),
          danos_tubulos1:              $('#danos_tubulos1').val(),
          danos_tubulos2:              $('#danos_tubulos2').val(),
          danos_tubulos3:              $('#danos_tubulos3').val(),
          danos_tubulos4:              $('#danos_tubulos4').val(),
          danos_tubulos5:              $('#danos_tubulos5').val(),
          danos_tubulos6:              $('#danos_tubulos6').val(),
          danos_tubulos7:              $('#danos_tubulos7').val(),
          danos_tubulos8:              $('#danos_tubulos8').val(),
          danos_tubulos9:              $('#danos_tubulos9').val(),
          danos_tubulos10:             $('#danos_tubulos10').val(),
          epistilis:                   $('#epistilis').val(),
          zoothamium:                  $('#zoothamium').val(),
          acinetos:                    $('#acinetos').val(),
          necroses:                    $('#necroses').val(),
          melanoses:                   $('#melanoses').val(),
          deformidade:                 $('#deformidade').val(),
          leucotrix:                   $('#leucotrix').val(),
          biofloco:                    $('#biofloco').val(),
          cristais1:                   $('#cristais1').val(),
          cristais2:                   $('#cristais2').val(),
          cristais3:                   $('#cristais3').val(),
          cristais4:                   $('#cristais4').val(),
          cristais5:                   $('#cristais5').val(),
          cristais6:                   $('#cristais6').val(),
          cristais7:                   $('#cristais7').val(),
          cristais8:                   $('#cristais8').val(),
          cristais9:                   $('#cristais9').val(),
          cristais10:                  $('#cristais10').val(),
          intestino_racao:             $('#intestino_racao').val(),
          intestino_biofloco:          $('#intestino_biofloco').val(),
          intestino_algas:             $('#intestino_algas').val(),
          intestino_canibalismo:       $('#intestino_canibalismo').val(),
          hemolinfa:                   $('#hemolinfa').val(),
          observacao:                  $('#observacao').val(), 
        },
        success: function(result){
            console.log(result.success);
        }
      });
    }
  });

});