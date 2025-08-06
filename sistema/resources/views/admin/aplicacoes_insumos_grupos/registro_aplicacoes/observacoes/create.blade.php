<div class="modal fade" id="aplicacoes_insumos_observacoes_modal" tabindex="-1" role="dialog" aria-labelledby="aplicacoes_insumos_observacoes_modal_label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="aplicacoes_insumos_observacoes_modal_label">Observações para aplicações de insumos</h4>
            </div>
            <div class="modal-body">
            <!-- Form  -->
                <form id="aplicacoes_insumos_observacoes_form" action="{{ route('admin.aplicacoes_insumos_grupos.observacoes.to_store', ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo->id]) }}" method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="data_aplicacao" value="{{ $data_aplicacao }}">
                    <div class="form-group" style="width:100%;">
                        <label for="observacoes">Observações:</label>
                        <textarea rows="5" name="observacoes" placeholder="Observações sobre as aplicações em geral" class="form-control" style="width:100%;">{{ $observacoes }}</textarea>
                    </div>
                </form>
            <!-- /Form  -->
            </div>
            <div class="modal-footer">
            <!-- Buttons -->
                <button type="submit" class="btn btn-primary" form="aplicacoes_insumos_observacoes_form">
                    <i class="fa fa-save" aria-hidden="true"></i> Salvar
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                </button>
            <!-- /Buttons -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
