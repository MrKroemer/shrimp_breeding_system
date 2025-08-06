<div class="modal fade" id="opcoes_arracoamento_modal" tabindex="-1" role="dialog" aria-labelledby="opcoes_arracoamento_modal_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="opcoes_arracoamento_modal_label">Opções de programação automática</h4>
      </div>
      <div class="modal-body">
        <!-- Form  -->
        <form id="opcoes_arracoamento_form" action="{{ route('admin.arracoamentos.arracoamentos_horarios.to_generate', ['arracoamento_id' => $arracoamento->id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="list-group-item" style="text-align:center;font-weight:bold;">Perfis de arraçoamento</div>
                @foreach ($arracoamentos_perfis as $item)
                    <div class="list-group-item">
                        <input id="perfil_{{ $item->id }}" type="radio" name="perfil" class="form-control" value="{{ $item->id }}" {{ $arracoamentos_perfis->first()->id == $item->id ? 'checked' : '' }}>
                        <label>{{ $item->nome }}</label>
                    </div>
                @endforeach
                <div class="list-group-item">
                    <input id="perfil_0" type="radio" name="perfil" class="form-control" value="0">
                    <label>Alimentação personalizada</label>
                </div>
            </div>
            <div id="perfil_personalizado" class="form-group" style="display:none;">
                <div id="racoes_utilizadas">
                    <div id="racao_1" class="row">
                        <div class="col-sm-6">
                            <label for="racao_utilizada_1">Ração utilizada:</label>
                            <select name="racao_utilizada_1" class="form-control" autocomplete="off">
                                <option value="">..:: Selecione ::..</option>
                                @foreach($produtos as $produto)
                                    @if($produto->produto_tipo_id == 2)
                                        <option value="{{ $produto->produto_id }}">{{ $produto->produto_nome }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="racao_porcentagem_1">% utilizada:</label>
                            <div class="input-group">
                                <input type="number" name="racao_porcentagem_1" placeholder="% utilizada" class="form-control" min="1" max="100" value="" autocomplete="off">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-success btn-flat" onclick="addElement('racoes_utilizadas',['racao_','racao_utilizada_','racao_porcentagem_']);">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="qtds_alimentacoes">
                    <div id="alime_1" class="row">
                        <div class="col-sm-6">
                            <label for="alime_quantidade_1">Qtd. de alimentações:</label>
                            <input type="number" name="alime_quantidade_1" placeholder="Qtd. de alimentações" class="form-control" value="" autocomplete="off">
                        </div>                       
                        <div class="col-sm-6">
                            <label for="alime_porcentagem_1">% por alimentações:</label>
                            <div class="input-group">
                                <input type="number" name="alime_porcentagem_1" placeholder="% por alimentaçõe" class="form-control" min="1" max="100" value="" autocomplete="off">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-success btn-flat" onclick="addElement('qtds_alimentacoes',['alime_','alime_quantidade_','alime_porcentagem_']);">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="list-group-item" style="text-align:center;font-weight:bold;">Acréscimos</div>
                <div class="list-group-item">
                    <input type="checkbox" name="add_racao_borda" class="form-control">
                    <label for="add_racao_borda">Aplicar ração nas bordas</label>
                </div>
                <div id="racao_bordas" class="list-group-item" style="display:none;">
                    <select name="racao_borda_id" class="form-control" autocomplete="off">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($produtos as $item)
                            @if ($item->produto_tipo_id == 2) {{-- RAÇÃO --}}
                                <option value="{{ $item->produto_id }}">{{ $item->produto_nome }} (Und.: {{ $item->produto->unidade_saida->sigla }})</option>
                            @endif
                        @endforeach
                    </select>
                    <br>
                    <input type="number" step="any" name="racao_borda_qtd" placeholder="Quantidade (Kg) à ser aplicada" class="form-control" autocomplete="off">
                    <br>
                    <input type="number" name="racao_borda_num" placeholder="Número de aplicações" class="form-control" autocomplete="off">
                    <br>
                    <input type="text" name="racao_borda_hor" placeholder="Horários de aplicação" class="form-control" autocomplete="off">
                </div>
                <div class="list-group-item">
                    <input type="checkbox" name="add_probiotico" class="form-control">
                    <label for="add_probiotico">Adicionar probiótico</label>
                </div>
                <div id="probioticos" class="list-group-item" style="display:none;">
                    <select name="probiotico_id" class="form-control" autocomplete="off">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($receitas_laboratoriais as $receita_laboratorial)
                            @if ($receita_laboratorial->receita_laboratorial_tipo_id == 1) {{-- PROBIÓTICOS PARA RAÇÃO --}}
                                <option value="{{ $receita_laboratorial->id }}">{{ $receita_laboratorial->nome }}</option>
                            @endif
                        @endforeach
                    </select>
                    <br>
                    <input type="number" name="probiotico_num" placeholder="Número de aplicações" class="form-control" autocomplete="off">
                    <br>
                    <input type="text" name="probiotico_hor" placeholder="Horários de aplicação" class="form-control" autocomplete="off">
                </div>
                <div class="list-group-item">
                    <input type="checkbox" name="add_aditivo" class="form-control" {{ $add_aditivo ? 'checked' : '' }}>
                    <label for="add_aditivo">Adicionar aditivo</label>
                </div>
                <div id="aditivos" class="list-group-item" style="{{ $add_aditivo ? '' : 'display:none;' }}">
                    <select name="aditivo_id" class="form-control" autocomplete="off">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($receitas_laboratoriais as $receita_laboratorial)
                            @if ($receita_laboratorial->receita_laboratorial_tipo_id == 2) {{-- ADITIVOS PARA RAÇÃO --}}
                                <option value="{{ $receita_laboratorial->id }}">{{ $receita_laboratorial->nome }}</option>
                            @endif
                        @endforeach
                    </select>
                    <br>
                    <input type="number" name="aditivo_num" placeholder="Número de aplicações" class="form-control" autocomplete="off">
                    <br>
                    <input type="text" name="aditivo_hor" placeholder="Horários de aplicação" class="form-control" autocomplete="off">
                </div>
                <div class="list-group-item">
                    <input type="checkbox" name="add_vitamina" class="form-control">
                    <label for="add_vitamina">Adicionar vitamina</label>
                </div>
                <div id="vitaminas" class="list-group-item" style="display:none;">
                    <select name="vitamina_id" class="form-control" autocomplete="off">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($receitas_laboratoriais as $receita_laboratorial)
                            @if ($receita_laboratorial->receita_laboratorial_tipo_id == 3) {{-- VITAMINAS PARA RAÇÃO --}}
                                <option value="{{ $receita_laboratorial->id }}">{{ $receita_laboratorial->nome }}</option>
                            @endif
                        @endforeach
                    </select>
                    <br>
                    <input type="number" name="vitamina_num" placeholder="Número de aplicações" class="form-control" autocomplete="off">
                    <br>
                    <input type="text" name="vitamina_hor" placeholder="Horários de aplicação" class="form-control" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="racao_estimada_qtd">Ração estimada (Kg):</label>
                        <input type="number" step="any" name="racao_estimada_qtd" placeholder="Ração estimada (Kg)" class="form-control" value="{{ $racao_estimada_qtd ?: '' }}" autocomplete="off">
                    </div>
                    <div class="col-sm-6" style="padding-top: 10px;">
                        <br>
                        <input type="checkbox" name="fracionar" class="form-control">
                        <label for="fracionar">Fracionar aplicações</label>
                    </div>
                </div>
            </div>
            <div class="form-group" style="width:100%;">
                <label for="observacoes">Observações:</label>
                <textarea rows="2" name="observacoes" placeholder="Observações sobre a aplicação do arraçoamento." class="form-control" style="width:100%;">{{ $arracoamento->observacoes }}</textarea>
            </div>
        </form>
        <!-- /Form  -->
      </div>
      <div class="modal-footer">
        <!-- Buttons -->
        <button type="submit" class="btn btn-primary" form="opcoes_arracoamento_form"><i class="fa fa-check" aria-hidden="true"></i> Gerar programação</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</button>
        <!-- /Buttons -->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->