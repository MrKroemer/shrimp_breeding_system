<div class="modal fade" id="arracoamentos_alimentacoes_modal" tabindex="-1" role="dialog" aria-labelledby="arracoamentos_alimentacoes_modal_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="arracoamentos_alimentacoes_modal_label">Cadastro de período de alimentação</h4>
      </div>
      <div class="modal-body">
        <!-- Form  -->
        <form id="arracoamentos_alimentacoes_form" action="{{ 
            route(
                'admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_alimentacoes.to_store', 
                ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'arracoamento_esquema_id' => $arracoamento_esquema_id]
            ) 
        }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-4">
                        <label for="quantidades_primeira">Alimentação inicial:</label>
                        <div class="input-group">
                            <input type="text" name="quantidades_primeira" placeholder="Ex.: 1" class="form-control" oninput="onInputPorcentagemAlimentacoes();">
                            <span class="input-group-addon" style="font-size:20px;">ª</span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label for="quantidades_ultima">Alimentação final:</label>
                        <div class="input-group">
                            <input type="text" name="quantidades_ultima" placeholder="Ex.: 5" class="form-control" oninput="onInputPorcentagemAlimentacoes();">
                            <span class="input-group-addon" style="font-size:20px;">ª</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="quantidades_porcentagem">Porcentagem total de ração:</label>
                <div class="slidecontainer">
                    <input type="range" name="quantidades_porcentagem" class="slider" oninput="onInputPorcentagemAlimentacoes();" min="0" max="{{ $porcentagem_alimentacoes }}" value="{{ $porcentagem_alimentacoes }}">
                    <span name="value_quantidades_porcentagem" style="font-weight:bold;font-size:18px;">{{ $porcentagem_alimentacoes }}</span>%
                </div>
            </div>
            <div class="form-group">
                <label for="quantidades_divisao">Porcentagem por alimentação:</label><br>
                <span name="quantidades_divisao" style="font-weight:bold;font-size:18px;">0</span>%
            </div>
        </form>
        <!-- /Form  -->
      </div>
      <div class="modal-footer">
        <!-- Buttons -->
        <button type="submit" class="btn btn-primary" form="arracoamentos_alimentacoes_form"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</button>
        <!-- /Buttons -->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
