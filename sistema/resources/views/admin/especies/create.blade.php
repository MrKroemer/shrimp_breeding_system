<div class="modal fade" id="especies_modal" tabindex="-1" role="dialog" aria-labelledby="especies_modal_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="especies_modal_label">Adicionar nova espécie</h4>
      </div>
      <div class="modal-body">
        <!-- Form  -->
        <form id="especies_form" action="{{ route('admin.especies.to_store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="nome_cientifico">Nome científico:</label>
                <input type="text" name="nome_cientifico" placeholder="Nome científico" class="form-control" style="text-transform:uppercase">
            </div>
            <div class="form-group">
                <label for="nome_comum">Nome comum:</label>
                <input type="text" name="nome_comum" placeholder="Nome comum" class="form-control" style="text-transform:uppercase">
            </div>
        </form>
        <!-- /Form  -->
      </div>
      <div class="modal-footer">
        <!-- Buttons -->
        <button type="submit" class="btn btn-primary" form="especies_form"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</button>
        <!-- /Buttons -->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->