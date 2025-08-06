/**
 * @description Lib de funcoes da aplicacao
 * @author Pedro Aguiar <pedro.aguiar@camanor.com.br>
 * @author Bruno Silva  <bruno.silva@camanor.com.br>
 */

/**
 * [checkInternetConnection]
 *
 * Se a internet estiver acessível, um valor 'verdadeiro' será retornado
 */

async function checkInternetConnection(fetchTimeout)
{
    const url = 'https://avatars3.githubusercontent.com/u/958072?s=1';

    const timeout = (time, promise) => {
        return new Promise(function(resolve, reject) {
            setTimeout(() => {
                reject(new Error('Request timed out.'))
            }, time);
            promise.then(resolve, reject);
        });
    }

    return timeout(fetchTimeout, fetch(url, {cache: 'no-cache'}))
    .then(response => {
        return (response.status >= 200 && response.status < 300);
    })
    .catch(error => {
        return false;
    });
}

/**
 * [onChangePais]
 *
 * Preenche o campo de estados, baseado no país selecionado
 */
function onChangePais(root_url)
{
    var pais_id = $('select[name="pais_id"]');
    var estado_id = $('select[name="estado_id"]');
    var cidade_id = $('select[name="cidade_id"]');

    estado_id.empty();
    cidade_id.empty();
    estado_id.append('<option value>..:: Selecione ::..</option>');
    cidade_id.append('<option value>..:: Selecione ::..</option>');
    $.getJSON(root_url + '/api/collection/estados/' + pais_id.val(), function (json) {
        $.each(json.data, function (i, field) {
            estado_id.append('<option value=' + field.id + '>' + field.nome + '</option>');
        });
    });
}

/**
 * [onChangeEstado]
 *
 * Preenche o campo de cidades, baseado no estado selecionado
 */
function onChangeEstado(root_url)
{
    var estado_id = $('select[name="estado_id"]');
    var cidade_id = $('select[name="cidade_id"]');

    cidade_id.empty();
    cidade_id.append('<option value>..:: Selecione ::..</option>');
    $.getJSON(root_url + '/api/collection/cidades/' + estado_id.val(), function (json) {
        $.each(json.data, function (i, field) {
            cidade_id.append('<option value=' + field.id + '>' + field.nome + '</option>');
        });
    });
}

/**
* [ausentarValoresBacteriologia]
*
* Zera os campos numéricos e limpa os campos de texto no cadastro de amostras bacteriológicas
*/
function ausentarValoresBacteriologia(tanque_id)
{
    $(`input[name="amarela_opaca_mm1_inc_${tanque_id}"]`).iCheck('uncheck');
    $(`input[name="amarela_opaca_mm2_inc_${tanque_id}"]`).iCheck('uncheck');
    $(`input[name="amarela_opaca_mm3_inc_${tanque_id}"]`).iCheck('uncheck');
    $(`input[name="amarela_opaca_mm4_inc_${tanque_id}"]`).iCheck('uncheck');
    $(`input[name="amarela_opaca_mm5_inc_${tanque_id}"]`).iCheck('uncheck');

    $(`input[name="amarela_esverdeada_inc_${tanque_id}"]`).iCheck('uncheck');
    $(`input[name="verde_inc_${tanque_id}"]`).iCheck('uncheck');
    $(`input[name="azul_inc_${tanque_id}"]`).iCheck('uncheck');
    $(`input[name="translucida_inc_${tanque_id}"]`).iCheck('uncheck');
    $(`input[name="preta_inc_${tanque_id}"]`).iCheck('uncheck');

    $(`input[name="amarela_opaca_mm1_${tanque_id}"]`).val(0);
    $(`input[name="amarela_opaca_mm2_${tanque_id}"]`).val(0);
    $(`input[name="amarela_opaca_mm3_${tanque_id}"]`).val(0);
    $(`input[name="amarela_opaca_mm4_${tanque_id}"]`).val(0);
    $(`input[name="amarela_opaca_mm5_${tanque_id}"]`).val(0);

    $(`input[name="amarela_esverdeada_${tanque_id}"]`).val(0);
    $(`input[name="verde_${tanque_id}"]`).val(0);
    $(`input[name="azul_${tanque_id}"]`).val(0);
    $(`input[name="translucida_${tanque_id}"]`).val(0);
    $(`input[name="preta_${tanque_id}"]`).val(0);

    $(`input[name="observacoes_${tanque_id}"]`).val('');

    $('#form_analise_bacteriologica_amostra').trigger('change');
}

/**
* [ausentarValoresCalcicas]
*
* Zera os campos numéricos e limpa os campos de texto no cadastro de amostras bacteriológicas
*/
function ausentarValoresCalcicas(tanque_id)
{
    $(`input[name="dureza_${tanque_id}"]`).val(0);
    $(`input[name="moleculas_${tanque_id}"]`).val(0);

    $('#form_analise_calcica_amostra').trigger('change');
}

/**
* [setLoadSpinner]
*
* Exibe um spinner para bloquear a página durante o carregamento de transições
*/
function setLoadSpinner()
{
    var body_style = $('body').attr('style');

    $('.loading').attr('style', 'visibility: visible;');
    $('body').attr('style', body_style + 'overflow: hidden;');
}

/**
* [onChangeSetores]
*
* Preenche o campo de subsetor, baseado no setor selecionado
*/
function onChangeSetores(root_url)
{
    /* var setor_id = $('select[name="setor_id"]');
    var subsetor_id = $('select[name="subsetor_id"]');

    subsetor_id.empty();
    subsetor_id.append('<option value>..:: Selecione ::..</option>');
    $.getJSON(root_url + '/api/collection/subsetores/' + setor_id.val(), function (json) {
        $.each(json.data, function (i, field) {
            subsetor_id.append('<option value=' + field.id + '>' + field.nome + '</option>');
        });
    }); */
}

/**
 * [onChangeSelectSetores]
 * 
 * Exibe e/ou oculta as boxes dos setores
 */
function onChangeSelectSetores()
{
    var setor_id = $('select[name="setor_id"]');
    var setores = $('div[name="setores"]');
    var setor = $('div[id="setor_' + setor_id.val() + '"]');

    setores.attr('style', 'display:none;');
    setor.attr('style', '');
}

/**
 * [onChangePermissoesModulo]
 * 
 * Preenche o campo de opcões de menu, baseado no módulo selecionado
 */
function onChangePermissoesModulo(root_url)
{
    var permissoes_modulo = $('select[name="permissoes_modulo"]');
    var permissoes_opcao = $('select[name="permissoes_opcao"]');
    var permissoes_item = $('select[name="permissoes_item"]');

    permissoes_opcao.empty();
    permissoes_item.empty();
    permissoes_opcao.append('<option value>..:: Selecione ::..</option>');
    permissoes_item.append('<option value>..:: Selecione ::..</option>');
    $.getJSON(root_url + '/api/collection/menus/opcoes/' + permissoes_modulo.val(), function (json) {
        $.each(json.data, function (i, field) {
            permissoes_opcao.append('<option value=' + field.id + '>' + field.nome + '</option>');
        });
    });
}

/**
 * [onChangePermissoesOpcao]
 * 
 * Preenche o campo de itens de menu, baseado na opção de menu selecionada
 */
function onChangePermissoesOpcao(root_url) 
{
    var permissoes_opcao = $('select[name="permissoes_opcao"]');
    var permissoes_item = $('select[name="permissoes_item"]');

    permissoes_item.empty();
    permissoes_item.append('<option value>..:: Selecione ::..</option>');
    $.getJSON(root_url + '/api/collection/menus/itens/' + permissoes_opcao.val(), function (json) {
        $.each(json.data, function (i, field) {
            permissoes_item.append('<option value=' + field.id + '>' + field.nome + '</option>');
        });
    });
}

/**
 * [onInputPorcentagemAlimentacoes]
 *
 * Exibe a porcentagem de racao para cada alimentacao do intervalo informado
 */
function onInputPorcentagemAlimentacoes()
{
    var quantidades_primeira = $('input[name="quantidades_primeira"]');
    var quantidades_ultima = $('input[name="quantidades_ultima"]');
    var quantidades_porcentagem = $('input[name="quantidades_porcentagem"]');
    var quantidades_divisao = $('span[name="quantidades_divisao"]');

    quantidades_divisao.text(porcentagemAlimentacoes(
        quantidades_primeira.val(),
        quantidades_ultima.val(),
        quantidades_porcentagem.val()
    ));
}

/**
 * [onChangeArracoamentoAplicacaoTipo]
 *
 * Carrega os dados dos itens para aplicações de arracoamentos
 */
function onChangeArracoamentoAplicacaoTipo(root_url, components) 
{
    var arracoamento_aplicacao_tipo = $('select[name="' + components[0] + '"]');
    var arracoamento_aplicacao_item = $('select[name="' + components[1] + '"]');

    arracoamento_aplicacao_item.empty();
    arracoamento_aplicacao_item.append('<option value>..:: Selecione ::..</option>');
    $.getJSON(root_url + '/api/collection/arracoamentos_aplicacoes/itens/' + arracoamento_aplicacao_tipo.val(), function (json) {
        $.each(json.data, function (i, field) {
            arracoamento_aplicacao_item.append('<option value=' + field.id + '>' + field.nome + ' (Und.: ' + field.unidade_medida + ')</option>');
        });
    });
}

/**
 * [addElement]
 *
 * Adiciona novos campos na página
 */
function addElement(elementId, components) 
{
    var element = $('div[id="' + elementId + '"]');
    var rows = [];

    element.contents().each(function (i) {
        if (this.nodeType === 1) {
            rows.unshift(this);
        }
    });

    var old_id = parseInt(rows[0].id.split("_").slice(-1)[0]);
    var new_id = old_id + 1;
    var row = rows[0].outerHTML;

    var func = "addElement('" + elementId + "',[";

    components.forEach((item, key) => {
        row = replaceAll(item + old_id, item + new_id, row);
        func += "'" + item + "'";
        func += (key < (components.length - 1)) ? "," : "";
    });

    func += "])";

    row = replaceAll('btn-success', 'btn-danger', row);
    row = replaceAll('fa-plus', 'fa-minus', row);
    
    if (old_id === 1) {
        row = replaceAll(func, "removeElement('" + components[0] + new_id + "')", row);
    } else {
        row = replaceAll(
            "removeElement('" + components[0] + old_id + "')", 
            "removeElement('" + components[0] + new_id + "')", 
            row
        );
    }

    element.append(row);
}

/**
 * [replaceAll]
 *
 * Realiza a substituição de uma string informada no "find" pela string informada em "replace"
 */
function replaceAll(find, replace, str) 
{
    while (str.indexOf(find) > -1) {
        str = str.replace(find, replace);
    }

    return str;
}

/**
 * [removeElement]
 *
 * Retira da pagina o elemento que possui o id informado como parâmetro
 */
function removeElement(elementId) 
{
    var element = $('div[id="' + elementId + '"]');

    element.remove();
}

/**
 * [onChangePesoTotal]
 * 
 * Calucula o valor do peso médio da amostra biométrica
 */
function onChangePesoTotal()
{
    var total_animais = parseFloat($('input[name="total_animais"]').val());
    var peso_total    = parseFloat($('input[name="peso_total"]').val());
    var peso_medio    = 0;

    if (!isNaN(total_animais) && !isNaN(peso_total)) {
        peso_medio = (total_animais !== 0 && peso_total !== 0) ? (peso_total / total_animais) : '';
    }

    $('input[name="peso_medio"]').attr('value', peso_medio);
}

/**
 * [onChangeAmostraPorMm]
 * 
 * Calucula o valor do tamanho médio da amostra de pós larvas
 */
function onChangeAmostraPorMm()
{
    var tamanho_medio  = 0;
    var total_animais  = 0;
    var total_biomassa = 0;

    for (var i = 5; i <= 20; i++) {

        var campo = $('input[name="mm' + i + '"]');

        var tamanho    = parseInt(campo.attr('name').split('mm')[1]);
        var quantidade = parseInt(campo.val());

        if (!isNaN(quantidade)) {
            total_animais  += quantidade;
            total_biomassa += (tamanho * quantidade);
        }

    }

    tamanho_medio = (total_animais !== 0) ? (total_biomassa / total_animais) : '';

    $('input[name="tamanho_medio"]').attr('value', tamanho_medio);
    $('input[name="total_animais"]').attr('value', total_animais);
}

/**
 * [onChangeAmostraPorClasse]
 * 
 * Calucula a quantidade de unidades da amostra biométrica
 */
function onChangeAmostraPorClasse() 
{
    var total_animais = 0;
    var classificacoes = [
        $('input[name="classe0to10"]').val(),
        $('input[name="classe10to20"]').val(),
        $('input[name="classe20to30"]').val(),
        $('input[name="classe30to40"]').val(),
        $('input[name="classe40to50"]').val(),
        $('input[name="classe50to60"]').val(),
        $('input[name="classe60to70"]').val(),
        $('input[name="classe70to80"]').val(),
        $('input[name="classe80to100"]').val(),
        $('input[name="classe100to120"]').val(),
        $('input[name="classe120to150"]').val(),
        $('input[name="classe150toUP"]').val()
    ];
    
    for (var i = 0; i < classificacoes.length; i++) {

        var quantidade = parseInt(classificacoes[i]);
        
        if (!isNaN(quantidade) && quantidade > 0) {
            total_animais += parseInt(classificacoes[i]);
        }

    }

    $('input[name="total_animais"]').attr('value', total_animais);
}

/**
 * [onActionForSubmit]
 * 
 * Submete um formulário informado como parametro
 */
function onActionForSubmit(formId)
{
    swal("Deseja realmente realizar esta operação?", {
        icon: "warning",
        buttons: {
            yes: { text: "Sim" },
            cancel: "Não"
        },
    }).then((value) => {
        switch (value) {
            case "yes":
                submitForm(formId);
                break;
            default:
                reloadWindow();
                break;
        }
    });
}

/**
 * [onActionForRequest]
 *
 * Requisita uma URL informada como parametro
 */
function onActionForRequest(url)
{
    swal("Deseja realmente realizar esta operação?", {
        icon: "warning",
        buttons: {
            yes: { text: "Sim" },
            cancel: "Não"
        },
    }).then((value) => {
        switch (value) {
            case "yes":
                requestUrl(url);
                break;
            default:
                reloadWindow();
                break;
        }
    });
}

/**
 * [reloadWindow]
 *
 * Atualiza a aba em evidência
 */
function reloadWindow()
{
    location.reload();
}

/**
 * [requestWithoutQuestion]
 *
 * Requisita uma URL informada como parametro sem solicitar confirmação
 */
function requestUrl(url)
{
    location.href = url;
}

/**
 * [submitForm]
 * 
 * Submete um formulário
 */
function submitForm(formId)
{
    var form = $('form[id="' + formId + '"]');

    form.submit();
}

/**
 * [submitForm]
 * 
 * Submete um formulário com alteração de um valor de um campo hidden
 */
function submitFormAlt(campo,valor,formId){

    var form = $('form[id="' + formId + '"]');

    $("input[name="+campo+"]").val(valor);

    form.submit();

}

/**
 * [submitManyForms]
 * 
 * Submete vários formulários
 */
function submitManyForms(forms) 
{
    swal("Deseja realmente realizar esta operação?", {
        icon: "warning",
        buttons: {
            yes: { text: "Sim" },
            cancel: "Não"
        },
    }).then((value) => {

        if (value === "yes") {

            forms.forEach(function (formId) {
                submitByAjax(formId);
            });

        }

        reloadWindow();

    });
}

/**
 * [submitByAjax]
 *
 * Envia um formulário por AJAX
 */
function submitByAjax(formId)
{
    var form = $('form[id="' + formId + '"]');

    var form_data   = form.serialize();
    var form_url    = form.attr("action");
    var form_method = form.attr("method");
    
    $.ajax({
        url:  form_url,
        type: form_method,
        data: form_data,
        cache: false,
        success: function (data) {
            console.log({
                form_id: formId,
                status: "success"
            });
        },
        error: function (data) {
            swal("Ocorreu um erro ao enviar os dados. Verifique sua conexão com a internet.", {
                icon: "warning"
            });
        }
    });
}

/**
 * [onChangeSolventeInsumos]
 *
 * Calcula os valores existentes e restantes, baseado no preenchimento dos campos "Solvente" do formulário
 */
function onChangeSolventeInsumos(formId)
{
    var form = $('form[id="' + formId + '"]');
    var form_data = form.serializeArray();

    var t_receita = $('span[id="t_receita"]');
    var t_solvente = $('span[id="t_solvente"]');
    var t_solucao = $('span[id="t_solucao"]');
    var t_faltam = $('span[id="t_faltam"]');
    
    var qtd_receita = parseFloat(t_receita.text());
    var qtd_solvente = 0.0;

    form_data.forEach(function (currentValue, index) {
        if (currentValue.name.search('solvente_') !== -1 && !isNaN(parseInt(currentValue.value))) {
            qtd_solvente += parseFloat(currentValue.value);
        }
    });

    t_solvente.text(qtd_solvente);

    var qtd_solucao = (qtd_receita + qtd_solvente);

    if (t_faltam.text() !== '') {

        var t_base = $('span[id="t_base"]');
        var qtd_base = parseFloat(t_base.text());

        if (qtd_solucao !== qtd_base) {

            t_solucao.attr('style', 'color: red;');
            t_faltam.attr('style', 'color: red;');

            t_solucao.text(qtd_solucao);
            t_faltam.text(qtd_base - qtd_solucao);

        } else {

            t_solucao.attr('style', 'color: green;');
            t_faltam.attr('style', 'color: green;');

            t_solucao.text(qtd_solucao);
            t_faltam.text(qtd_base - qtd_solucao);

        }

    } else {

        t_solucao.text(qtd_solucao);

    }
}

/**
 * [calcularSolventeInsumos]
 *
 * Calcula a quantidade de solvente por aplicação de receita em cada tanque
 */
function calcularSolventeInsumos(formId)
{
    var form = $('form[id="' + formId + '"]');
    var form_data = form.serializeArray();

    var t_receita = parseInt($('span[id="t_receita"]').text());
    var t_base = parseInt($('span[id="t_base"]').text());
    
    if (!isNaN(t_base)) {
 
        form_data.forEach(function (currentValue, index) {

            if (currentValue.name.search('solvente_') !== -1 && !isNaN(parseInt(currentValue.value))) {
                
                var solvente_field = $('input[name="' + currentValue.name + '"]');

                var aplicacao_id = parseInt(currentValue.name.split("_").slice(-1)[0]);

                var qtd_receita = parseInt($('input[name="quantidade_' + aplicacao_id + '"]').val());

                var qtd_solvente = Math.round((qtd_receita / t_receita) * (t_base - t_receita));

                qtd_solvente = Math.round((qtd_solvente / 5)) * 5;

                solvente_field.val(qtd_solvente);

            }

        });

        onChangeSolventeInsumos(formId);

    } else {

        swal("Não exitem valores para serem ajustados.", {
            icon: "warning",
        });

    }
    
}

/**
 * [porcentagemAlimentacoes]
 *
 * Calcula a porcentagem de racao para cada alimentacao do intervalo informado
 */
function porcentagemAlimentacoes(primeira, ultima, porcentagem) 
{
    var resultado = 0;

    if (!isNaN(primeira) && !isNaN(ultima) && !isNaN(porcentagem)) {
        resultado = (porcentagem / ((ultima - primeira) + 1));
    }

    return isFinite(resultado) ? resultado : 0;
}

/**
 * [onChangeModulos]
 * 
 * Recarrega o layout de acordo com as opcoes de menu do modulo selecionado 
 */
function onChangeModulos(root_url) 
{
    var selected_module = $('select[name="selected_module"]');

    location.href =  root_url + '?module=' + selected_module.val();
}

/**
 * [consultaNfe]
 * 
 * Abre uma nova aba no navegador, para realizar um consulta de nota fiscal
 */
function consultaNfe(chave) 
{
    var url = 'http://www.nfe.fazenda.gov.br/portal/consultaRecaptcha.aspx';

    window.open(url + '?tipoConsulta=completa&tipoConteudo=XbSeqxE8pl8=&nfe=' + chave, '_blank');
}

/**
 * [onChangeIncontaveis]
 *
 * Responsável por habilitar/desabilitar os campos do formulário de analises
 */
function onChangeIncontaveis(field_name, isEnabled)
{
    if (field_name.search('_inc_') !== -1) {

        var tanque_id = field_name.split('_').slice(-1)[0];
        var field = null;

        switch (field_name) {

            case 'amarela_opaca_mm1_inc_' + tanque_id:
                field = $('input[name="amarela_opaca_mm1_' + tanque_id + '"]');
                break;
            case 'amarela_opaca_mm2_inc_' + tanque_id:
                field = $('input[name="amarela_opaca_mm2_' + tanque_id + '"]');
                break;
            case 'amarela_opaca_mm3_inc_' + tanque_id:
                field = $('input[name="amarela_opaca_mm3_' + tanque_id + '"]');
                break;
            case 'amarela_opaca_mm4_inc_' + tanque_id:
                field = $('input[name="amarela_opaca_mm4_' + tanque_id + '"]');
                break;
            case 'amarela_opaca_mm5_inc_' + tanque_id:
                field = $('input[name="amarela_opaca_mm5_' + tanque_id + '"]');
                break;
            case 'amarela_esverdeada_inc_' + tanque_id:
                field = $('input[name="amarela_esverdeada_' + tanque_id + '"]');
                break;
            case 'verde_inc_' + tanque_id:
                field = $('input[name="verde_' + tanque_id + '"]');
                break;
            case 'azul_inc_' + tanque_id:
                field = $('input[name="azul_' + tanque_id + '"]');
                break;
            case 'translucida_inc_' + tanque_id:
                field = $('input[name="translucida_' + tanque_id + '"]');
                break;
            case 'preta_inc_' + tanque_id:
                field = $('input[name="preta_' + tanque_id + '"]');
                break;

        }

        field.attr('disabled', isEnabled);
        field.val('');

    }
}

/**
 * [dump]
 *
 * Equivalente ao Print_r do PHP
 */
function dump(arr, level) 
{
    var dumped_text = "";

    if (! level) {
        level = 0;
    }

    var level_padding = "";

    for (var j = 0; j < level + 1; j ++) {
        level_padding += "    ";
    }

    if(typeof(arr) == 'object') {  

        for (var item in arr) {

            var value = arr[item];
 
            if(typeof(value) == 'object') { 
                dumped_text += level_padding + "'" + item + "' ...\n";
                dumped_text += dump(value, level + 1);
            } else {
                dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
            }
            
        }

    } else { 

        dumped_text = "===>" + arr + "<===(" + typeof(arr) + ")";

    }

    return dumped_text;
}

/**
 * [onKeyupSomaCampos]
 *
 * Responsável por somar campos especificados na função
 * Ao usar a fórmula chamar o evento como no exemplo abaixo
 * onkeyup="onKeyupSomaCampos('cloroficeas,cianoficeas,diatomaceas,euglenoficeas,total_algas')"
 * lembrando que o ultimo campo é o campo que exibirá o somatório, nele não é preciso colocar um evento
 */
function onKeyupSomaCampos(fields)
{
    fields = fields.split(',');

    var qtd_campos = fields.length;
    var j = qtd_campos - 1;
    var total = 0;
    
    for(i = 0; i < j; i ++) {

        if (isNaN($('input[name="' + fields[i] + '"]').val())) {

            alert("Por favor, confira se o valor digitado é um numero!");
            $('input[name="' + fields[i] + '"]').val(0);
            $('input[name="' + fields[i] + '"]').focus();

            return false;

        } else if ($('input[name="' + fields[i] + '"]').val() == "") {

            total = total + 0; 

        } else {

            total = total + parseInt($('input[name="' + fields[i] + '"]').val());

        }
    }

    $('input[name="' + fields[j] + '"]').removeAttr("disabled");
    $('input[name="' + fields[j] + '"]').val(total);
    $('input[name="' + fields[j] + '"]').attr("disabled", true);
}

/**
 * [onCalculaVariacaoParametro]
 *
 * Responsável por somar campos especificados na função
 */
function onCalculaVariacaoParametro(field)
{
    var id = field;

    valor = $("input[name=valor_"+id+"]").val() == "" ? 0 : $("input[name=valor_"+id+"]").val();

    valoranterior = $("input[name=valor_anterior_"+id+"]").val();

    variacao = parseFloat(valor) - parseFloat(valoranterior);

    $("input[name=variacao_"+id+"]").val(variacao);

}

/**
 * [changeFocusOnPressEnter]
 *
 * Muda o foco do cursor para o próximo campo, ao pressionar a tecla Enter
 */
function changeFocusOnPressEnter(e)
{
    if (e.key === "Enter" && e.currentTarget.form) {
        
        var fieldsType = 'input[class="form-control"]:not([disabled])';

        var focusableElements = e.currentTarget.form.querySelectorAll(fieldsType);
        var index = Array.prototype.indexOf.call(focusableElements, document.activeElement);

        if (e.shiftKey) {
          index --;
        } else {
          index ++;
        }

        focusableElements[index].focus();

        e.preventDefault();

    }
}

function calculaVariacaoMedicoes(tanque_id, amostra_id1, amostra_id2)
{
    var posfix1 = (amostra_id1 > 0) ? `${tanque_id}_${amostra_id1}` : `${tanque_id}`;
    var posfix2 = (amostra_id2 > 0) ? `${tanque_id}_${amostra_id2}` : `${tanque_id}`;

    var valor1 = parseFloat($(`input[name="medicao1_ant_${tanque_id}"]`).val());
    var valor2 = parseFloat($(`input[name="medicao2_ant_${tanque_id}"]`).val());
    var valor3 = parseFloat($(`input[name="medicao1_${posfix1}"]`).val());
    var valor4 = parseFloat($(`input[name="medicao2_${posfix2}"]`).val());

    var variacao = 0;

    if (!isNaN(valor1) && !isNaN(valor2)) {
        variacao = (valor1 - valor2);
    }

    if (!isNaN(valor2) && !isNaN(valor3)) {
        variacao = (valor2 - valor3);
    }

    if (!isNaN(valor3) && !isNaN(valor4)) {
        variacao = (valor3 - valor4);
    }

    if (variacao < 0) {
        variacao = (variacao * -1);
    }

    $(`input[name="variacao_${tanque_id}"]`).attr('value', variacao.toFixed(2));
}

/**
 * [repeat]
 *
 * replica o que foi digitado na DIV
 */
function repeat(valor, replicador)
{
    $('#'+replicador+'').html(valor);
}

/**
 * [clearDateValue]
 *
 * Remove o valor do input datepicker informado
 */
function clearDateValue(element)
{
    element = $(element).parent().parent();

    input = element.children('input');

    if (
        input.attr('id') === 'date_picker' || 
        input.attr('id') === 'datetime_picker'
    ) {
        input.val('');
    }
}

/**
 * [alertaFinalizacao]
 *
 * Informa que a inserção da data pode causar o encerramento da atividade.
 */
function alertaFinalizacao(element)
{
    if (element.value === '') {

        swal("Ao inserir essa informação, a atividade será encerrada imediatamente.", {
            icon: "warning",
            buttons: {
                yes: { text: "Ok" },
                cancel: "Cancelar"
            },
        }).then((value) => {
            if (value !== "yes") {
                element.value = "";
            }
        });

    }
}

/**
 * [alertaExclusaoArracoamento]
 *
 * Aviso de exclusão de arraçoamento com itens programados.
 */
function alertaExclusaoArracoamento(url, del)
{
    if (del === 1) {

        swal("O arracoamento já possui itens programados! Realmente deseja excluir o registro?", {
            icon: "warning",
            buttons: {
                yes: { text: "Ok" },
                cancel: "Cancelar"
            },
        }).then((value) => {
            switch (value) {
                case "yes":
                    requestUrl(url);
                    break;
                default:
                    reloadWindow();
                    break;
            }
        });

    }
}

/**
 * [calculaVariacao]
 *
 * calcula variacao de parametros.
 */
function calVariacao(id,valor)
{
    valoranterior = valor;
    valoratual = $("input[name=valor_"+id+"]").val();

    variacao = valoratual - valoranterior;

    $("input[name=variacao_"+id+"]").removeAttr("disabled");
    $("input[name=variacao_"+id+"]").val(variacao.toFixed(1));
    $("input[name=variacao_"+id+"]").attr("disabled", true);

}

/**
 * [onChangeTipoCultivo]
 *
 * Popula o selectbox de tanques, de acordo com o tipo de cultivo selecionado
 */
function onChangeTipoCultivo(root_url, components) 
{
    var tipo = $('select[name="' + components[0] + '"]');
    var tanque_id = $('select[name="' + components[1] + '"]');
    var numero = $('input[name="' + components[2] + '"]');

    tanque_id.empty();
    tanque_id.append('<option value>..:: Selecione ::..</option>');

    $.getJSON(root_url + '/api/collection/tanques/' + tipo.val(), function (json) {
        $.each(json.data, function (i, field) {
            tanque_id.append('<option value=' + field.id + '>' + field.sigla + ' ( ' + field.tipo + ', ' + field.setor + ' )</option>');
        });
    });

    $.get(root_url + '/api/collection/ciclos/numero/' + tipo.val(), function(data, status){
        numero.val(data);
    });
}

/**
 * [onChangeCicloOrigem]
 *
 * Popula o select-box dos tanques de destino das transferências de animais
 */
function onChangeCicloOrigem(root_url, components)
{
    var ciclo_origem   = $('select[name="' + components[0] + '"]');
    var ciclo_destino  = $('select[name="' + components[1] + '"]');
    var qtd_disponivel = $('input[name="'  + components[2] + '"]');
    var quantidade     = $('input[name="'  + components[3] + '"]');

    ciclo_destino.empty();
    ciclo_destino.append('<option value>..:: Selecione ::..</option>');
    
    quantidade.attr('disabled', true);

    $.getJSON(root_url + '/api/collection/transferencias_animais/' + ciclo_origem.val(), function (json) {

        $.each(json.data, function (i, field) {
            
            if (field.ciclo_id == ciclo_origem.val()) {

                var disponivel = field.disponivel >= 0 ? field.disponivel : 'Indeterminada';

                qtd_disponivel.val(disponivel);

            } else if (field.ciclo_tipo != 3) {

                ciclo_destino.append('<option value=' + field.ciclo_id + '>' + field.tanque_sigla + ' ( Ciclo Nº ' + field.ciclo_numero +' iniciado em '+ field.ciclo_inicio +' )</option>');

            }

        });

    });
}

/**
 * [enableQuantidadeCicloDestino]
 *
 * Habilita o campo de inserção da quantidade de animais a serem transferidos
 */
function enableQuantidadeCicloDestino(components)
{
    var ciclo_origem  = $('select[name="' + components[0] + '"]');
    var ciclo_destino = $('select[name="' + components[1] + '"]');
    var quantidade    = $('input[name="'  + components[2] + '"]');

    var origem = ciclo_origem.find(':selected').val();
    var destino = ciclo_destino.find(':selected').val();

    quantidade.attr('disabled', true);

    if (origem !== "" && destino !== "") {
        quantidade.removeAttr('disabled');
    }
}

function onChangeBioTipoCultivo() 
{
   var id = $("#ciclo_id").val();
   var tipo = $("#" + id).val();

   if (tipo > 1){
        $("#sobrevivencia").attr('style', 'display:none;');
   }else{
        $("#sobrevivencia").attr('style', '');
   }
}

/**
 * [stringToColour]
 *
 * Retorna um padrão de cor em hexadecimal, baseado em uma string enviada como parâmetro
 */
function stringToColour(str) 
{
    var hash = 0;

    for (var i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }

    var colour = '#';

    for (var i = 0; i < 3; i++) {
        var value = (hash >> (i * 8)) & 0xFF;
        colour += ('00' + value.toString(16)).substr(-2);
    }

    return colour;
}

/**
 * @description Funções específicas do cadastro de análises presuntivas.
 *
 */
function totallipideos() 
{
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

function checkvalue(campo) 
{
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

function checkvalueDn(campo) 
{
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

function porcentagemdano() 
{
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

function porcentagemdanoO() 
{
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

function confereaditivo() 
{
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

function onClickFormModal(id)
{
    //alert('http://localhost/sistema/public/admin/certificacao_reprodutores/'+id+'/import');
    $('#imagem_analise_form').attr('action', 'http://192.168.0.150/admin/certificacao_reprodutores/'+id+'/import');
}

function onClickImageModal(image)
{
    //alert('http://localhost/sistema/public/admin/certificacao_reprodutores/'+id+'/import');
    $('#imagem_analise').attr('src', image);
}

function onChangeMudaStatus(id,value)
{
   
    if( value == "TRUE" ){
        $('#'+id+'_item_tubo').css("color", "red");
        $('#'+id+'_sexo').css("color", "red");
        $('#'+id+'_numero_anel').css("color", "red");
        $('#'+id+'_cor_anel').css("color", "red");
    }else{
        $('#'+id+'_item_tubo').css("color", "#333333");
        $('#'+id+'_sexo').css("color", "#333333");
        $('#'+id+'_numero_anel').css("color", "#333333");
        $('#'+id+'_cor_anel').css("color", "#333333");
    }
}
