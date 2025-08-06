<div class="modal fade" id="{{ $form_id }}_modal" tabindex="-1" role="dialog" aria-labelledby="{{ $form_id }}_modal_label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="{{ $form_id }}_modal_label">Justificativa para estorno de produtos</h4>
            </div>
            <div class="modal-body">
                <!-- Form  -->
                <form id="{{ $form_id }}" action="{{ $reverse_route }}" method="POST">
                    {!! csrf_field() !!}
                    <div class="form-group" style="width:100%;">
                        <label for="descricao">Descrição:</label>
                        <textarea rows="2" name="descricao" placeholder="Informe um justificativa para o estorno." class="form-control" style="width:100%;"></textarea>
                    </div>
                </form>
                <!-- /Form  -->
            </div>
            <div class="modal-footer">
                <!-- Buttons -->
                <button type="button" onclick="onActionForSubmit('{{ $form_id }}');" class="btn btn-primary locked">
                    <i class="fa fa-check" aria-hidden="true"></i> Salvar
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                </button>
                <!-- /Buttons -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<a class="{{ $btn_class }}" data-toggle="modal" data-target="#{{ $form_id }}_modal">
    <i class="{{ $btn_icon }}" aria-hidden="true"></i> {{ $btn_name }}
</a><!-- /.show-modal-button -->