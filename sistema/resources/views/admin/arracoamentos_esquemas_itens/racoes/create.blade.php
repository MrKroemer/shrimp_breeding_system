<div class="modal fade" id="arracoamentos_racoes_modal" tabindex="-1" role="dialog" aria-labelledby="arracoamentos_racoes_modal_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="arracoamentos_racoes_modal_label">Cadastro de ração para utilização</h4>
      </div>
      <div class="modal-body">
        <!-- Form  -->
        <form id="arracoamentos_racoes_form" action="{{ 
            route(
                'admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_racoes.to_store', 
                ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'arracoamento_esquema_id' => $arracoamento_esquema_id]
            ) 
        }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="racoes_produto">Ração:</label>
                <select name="racoes_produto" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($produtos as $produto)
                        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="racoes_porcentagem">Porcentagem:</label>
                <div class="slidecontainer">
                    <span data-toggle="tooltip" data-placement="bottom" title="Defina aqui, o percentual de ração que deseja adicionar para o arraçoamento do dia.">
                        <input type="range" name="racoes_porcentagem" class="slider" min="0" max="{{ $porcentagem_racoes }}" value="{{ $porcentagem_racoes }}">
                    </span>
                    <span name="value_racoes_porcentagem" style="font-weight:bold;font-size:18px;">{{ $porcentagem_racoes }}</span>%
                </div>
            </div>
        </form>
        <!-- /Form  -->
      </div>
      <div class="modal-footer">
        <!-- Buttons -->
        <button type="submit" class="btn btn-primary" form="arracoamentos_racoes_form"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</button>
        <!-- /Buttons -->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
