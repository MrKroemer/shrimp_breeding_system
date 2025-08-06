<div class="modal fade" id="tanques_tipos_modal" tabindex="-1" role="dialog" aria-labelledby="tanques_tipos_modal_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="tanques_tipos_modal_label">Adicionar novo tipo de tanque</h4>
      </div>
      <div class="modal-body">
        <!-- Form  -->
        <form id="tanques_tipos_form" action="{{ route('admin.tanques.tipos.to_store') }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="tipo_nome">Nome:</label>
                <input type="text" name="tipo_nome" placeholder="Nome" class="form-control">
            </div>
        </form>
        <!-- /Form  -->
      </div>
      <div class="modal-footer">
        <!-- Buttons -->
        <button type="submit" class="btn btn-primary" form="tanques_tipos_form"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</button>
        <!-- /Buttons -->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->