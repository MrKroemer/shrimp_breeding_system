<div class="modal fade" id="exportacao_saidas_modal" tabindex="-1" role="dialog" aria-labelledby="exportacao_saidas_modal_label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exportacao_saidas_modal_label">Exportação de arquivo de saídas</h4>
            </div>
            <div class="modal-body">
            <!-- Form  -->
                <form id="exportacao_saidas_form" action="{{ route('admin.estoque_saidas.to_export') }}" method="POST" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="data_exportacao">Movimentações do dia:</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                            </div>
                            <input type="text" name="data_exportacao" placeholder="Informe a data de exportação" class="form-control pull-right" id="date_picker">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tipo_exportacao">Saídas de:</label>
                        <select name="tipo_exportacao" class="form-control">
                            <option value="">..:: Selecione ::..</option>
                            <option value="1">PRODUTOS QUÍMICOS</option>
                            <option value="2">RAÇÕES</option>
                            {{-- <option value="3">RATEIO DE RESERVATÓRIOS</option> --}}
                        </select>
                    </div>
                </form>
            <!-- /Form  -->
            </div>
            <div class="modal-footer">
            <!-- Buttons -->
                <button type="submit" class="btn btn-primary" form="exportacao_saidas_form">
                    <i class="fa fa-upload" aria-hidden="true"></i> Exportar
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="location.reload();">
                    <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                </button>
            <!-- /Buttons -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
