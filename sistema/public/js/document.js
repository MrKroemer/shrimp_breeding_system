/**
 * @description Lib de funcoes da aplicacao
 * @author Pedro Aguiar <pedro.aguiar@camanor.com.br>
 */

$(document).ready(function () {

/**
* [Captura de parametros]
*
* Coleta os parametros passados pelo src na inclusão do arquivo
*/
    const script_tag = document.getElementById('document_js');
    const parameters = script_tag.src.replace(/^[^\?]+\??/, '');
    const attributes = parameters.split('&');
    
    let args = {};

    for (let i = 0; i < attributes.length; i++) {

        let attribute = attributes[i].split('=');

        args[attribute[0]] = decodeURI(attribute[1]).replace(/\+/g, ' ');

    }

/**
* [Exibição do status da conexão com a internet]
*
* Altera o icone e o tipo do status da conexão para online ou offline
*/
    const fetchTimeout = 1000;
    const verifyStatus = async () => {

        const online = await checkInternetConnection(fetchTimeout);
        
        const status = document.getElementById('status_icon');
        const icon = status.parentNode.childNodes[0];

        let fa_class = icon.getAttribute('class');

        if (online) {

            status.innerHTML = 'Online';

            fa_class = fa_class.replace('warning', 'success');

            icon.setAttribute('class', fa_class.replace('danger', 'success'));

        } else {

            status.innerHTML = 'Offline';

            fa_class = fa_class.replace('warning', 'danger');

            icon.setAttribute('class', fa_class.replace('success', 'danger'));

        }

    };

    setInterval(verifyStatus, fetchTimeout);

/**
* [Exibição do timer de sessão]
*
* Exibe na tela do usuário, o tempo restante da sessão iniciada
*/
    const startTime = Date.now();
    const sessionTime = parseInt(args['session_time']);
    const verifySession = () => {

        const session = document.getElementById("session_time");

        let secondsLeft = ((sessionTime * 60) - (((Date.now() - startTime) / 1000) | 0));
        let hours, minutes, seconds;

        hours   = ((((secondsLeft / 60) / 60) % 24) | 0);
        minutes = (((secondsLeft / 60) % 60) | 0);
        seconds = ((secondsLeft % 60) | 0);

        hours   = (hours < 10)   ? ("0" + hours)   : hours;
        minutes = (minutes < 10) ? ("0" + minutes) : minutes;
        seconds = (seconds < 10) ? ("0" + seconds) : seconds;

        session.innerHTML = hours + ":" + minutes + ":" + seconds;

        if (secondsLeft < 0) {

            clearInterval(updateTime);

            session.innerHTML = "EXPIRADA";

            location.reload();

        }

    };

    const updateTime = setInterval(verifySession, 1000);

/**
 * [Scroll to up floating button]
 * 
 * Responsável por rolar o scroll da janela ao clicar no botão
 */
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.flt_btn_scrollup').fadeIn();
        } else {
            $('.flt_btn_scrollup').fadeOut();
        }
    });

    $('.flt_btn_scrollup').click(function () {
        $('html, body').animate({
            scrollTop: 0
        }, 600);
        return false;
    });

/**
 * [iCheck Initialization]
 * 
 * Codigo para inicializar a lib do iCheck 1.0.x
 */
    var checkbox_icheck = $('input');

    checkbox_icheck.each(function () {

        var input_type = $(this).attr('type');

        if (input_type === 'checkbox' || input_type === 'radio') {
            $(this).iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });
        }

    });

/**
 * [Select2]
 * 
 * Codigo para inicializar a lib do Select2 v4.0.12
 */
    var select2_single = $('select[class="select2_single"]');
    var select2_multiple = $('select[class="select2_multiple"]');

    var select2_config = {
        width: '100%',
        placeholder: '..:: Selecione ::..'
    }

    select2_single.select2(select2_config);

    select2_multiple.select2(select2_config);

    select2_multiple.on("select2:select", function (e) {

        if (event.shiftKey) {

            var current_element = 'select[name="' + this.name + '"]';

            var selected_options = $(current_element).get(0).selectedOptions;

            var element_length = (selected_options.length > 0) ? selected_options.length - 1 : 0;

            var first_option = selected_options[0].index
            var last_option = selected_options[element_length].index

            selected_options = [];

            for (var i = first_option; i <= last_option; i ++) {
                var option_value = $(current_element + ' option').eq(i).val();
                selected_options.push(option_value);
            }

            $(current_element).val(selected_options);
            $(current_element).trigger('change');
            $(current_element).select2('open');

        }

    });

/**
 * [Date Range Picker]
 * 
 * Habilitar a exibição de um calendario para selecao de datas e/ou horas
 */
    var date_picker = $('input[id="date_picker"]');
    var datetime_picker = $('input[id="datetime_picker"]');

    var pickers = [];

    date_picker.each(function () {
        if ($(this).val() === '') {
            pickers.push($(this).attr('name'));
        }
    });

    datetime_picker.each(function () {
        if ($(this).val() === '') {
            pickers.push($(this).attr('name'));
        }
    });

    var days = [
        'Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'
    ];
    var months = [
        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 
        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
    ];
    var single_picker = true;
    var drop_downs = true;
    var auto_apply = true;
    var min_year = 1901;
    var max_year = parseInt(moment().format('YYYY'), 10);
    var open_layout = 'right'; // left|center|right
    var drop_layout = 'down'; // up|down
    var btn_class = 'btn btn-xs';
    var btn_apply = 'Ok';
    var btn_cancel = 'Cancelar';
    var locale = {
        applyLabel: btn_apply,
        cancelLabel: btn_cancel,
        format: 'DD/MM/YYYY',
        daysOfWeek: days,
        monthNames: months
    };

    date_picker.daterangepicker({
        singleDatePicker: single_picker,
        showDropdowns: drop_downs,
        autoApply: auto_apply,
        minYear: min_year,
        maxYear: max_year,
        opens: open_layout,
        drops: drop_layout,
        buttonClasses: btn_class,
        locale: locale
    }).attr('autocomplete', 'off');

    locale['format'] = 'DD/MM/YYYY HH:mm';

    datetime_picker.daterangepicker({
        singleDatePicker: single_picker,
        showDropdowns: drop_downs,
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 5,
        autoApply: auto_apply,
        minYear: min_year,
        maxYear: max_year,
        opens: open_layout,
        drops: drop_layout,
        buttonClasses: btn_class,
        locale: locale
    }).attr('autocomplete', 'off');

    pickers.forEach(function (element) {
        $('input[name="' + element + '"]').val('');
    });

/**
 * [lockButtonOnClick]
 * 
 * Bloqueia o botão de envio após o formulário ser enviado
 */

    // Devido a grande quantidade de botões de submit
    var form_submited = $('form');
    var button_submit = $('button[type="submit"]');

    form_submited.on('submit', function () {
        button_submit.attr('disabled', true);
    });

    // Para solucionar outras situações de bloqueios de componentes
    var locked = $('[class*="locked"]');
    
    locked.on('click', function () {
        locked.attr('disabled', true);
    });

/**
 * [slidecontainer js]
 * 
 * Js para alterar o value do slide-bar (type="range") html
 */
    var slide_bar = $('input[type="range"]');

    slide_bar.on('input', function () {
        $('span[name="value_' + this.name + '"]').text(this.value);
    });

/**
 * [bootstrap tooltip]
 * 
 * Cria um hint bootstrap no componente definido com data-toggle igual a tooltip
 */
    var bs_tooltip = $('[data-toggle="tooltip"]');

    bs_tooltip.tooltip();

/**
 * [onChangeArracoamentoPerfil]
 *
 * Exibe ou oculta a div quando a personalização de perfil de arraçoamento é selecionada
 */
    var perfil_personalizado = $('div[id="perfil_personalizado"]');

    $('input[id="perfil_0"]').on('ifChecked', function (event) {
        perfil_personalizado.attr('style', '');
    }).on('ifUnchecked', function (event) {
        perfil_personalizado.attr('style', 'display:none;');
    });

/**
 * [onChangeRacaoBorda]
 *
 * Exibe ou oculta a div ao selecionar o opção de ração nas bordas
 */
    var racao_bordas = $('div[id="racao_bordas"]');

    $('input[name="add_racao_borda"]').on('ifChecked', function (event) {
        racao_bordas.attr('style', '');
    }).on('ifUnchecked', function (event) {
        racao_bordas.attr('style', 'display:none;');
    });

/**
 * [onChangeProbiotico]
 *
 * Exibe ou oculta a div ao selecionar o opção de probiótico
 */
    var probioticos = $('div[id="probioticos"]');

    $('input[name="add_probiotico"]').on('ifChecked', function (event) {
        probioticos.attr('style', '');
    }).on('ifUnchecked', function (event) {
        probioticos.attr('style', 'display:none;');
    });

/**
 * [onChangeAditivo]
 *
 * Exibe ou oculta a div ao selecionar o opção de aditivo
 */
    var aditivos = $('div[id="aditivos"]');

    $('input[name="add_aditivo"]').on('ifChecked', function (event) {
        aditivos.attr('style', '');
    }).on('ifUnchecked', function (event) {
        aditivos.attr('style', 'display:none;');
    });

/**
 * [onChangeVitamina]
 *
 * Exibe ou oculta a div ao selecionar o opção de vitamina
 */
    var vitaminas = $('div[id="vitaminas"]');

    $('input[name="add_vitamina"]').on('ifChecked', function (event) {
        vitaminas.attr('style', '');
    }).on('ifUnchecked', function (event) {
        vitaminas.attr('style', 'display:none;');
    });

/**
 * [onChangeTipoDespesca]
 * 
 * Defini o valor para o input tipo_despesca baseado na opção selecionada
 */
    var tipo_despesca = $('input[name="tipo_despesca"]');
    var tipo_despesca01 = $('input[id="tipo_despesca01"]');
    var tipo_despesca02 = $('input[id="tipo_despesca02"]');
    var tipo_despesca03 = $('input[id="tipo_despesca03"]');
    var tipo_despesca04 = $('input[id="tipo_despesca04"]');

    tipo_despesca01.on('ifChecked', function (event) {
        tipo_despesca.attr('value', 1); // Completa
    });
    tipo_despesca02.on('ifChecked', function (event) {
        tipo_despesca.attr('value', 2); // Parcial
    });
    tipo_despesca03.on('ifChecked', function (event) {
        tipo_despesca.attr('value', 3); // Descarte pré
    });
    tipo_despesca04.on('ifChecked', function (event) {
        tipo_despesca.attr('value', 4); // Descarte pós
    });

/**
 * [onChangeIncontaveis]
 *
 * Executa a função responsável por habilitar/desabilitar os campos do formulário de analises
 */
    $('input[type="checkbox"]').on('ifChecked', function (event) {
        onChangeIncontaveis(this.name, true);
    }).on('ifUnchecked', function (event) {
        onChangeIncontaveis(this.name, false);
    });

/**
 * [Popover Preview Image]
 *
 * Código de inicialização do componente
 */
    // Create the close button
    var closebtn = $('<button/>', {
        type:"button",
        text: 'x',
        id: 'close-preview',
        style: 'font-size: initial;',
    });
    closebtn.attr("class","close pull-right");
    // Set the popover default content
    $('.image-preview').popover({
        trigger:'manual',
        html:true,
        title: "<strong>Pré-visualização</strong>"+$(closebtn)[0].outerHTML,
        content: "Ainda não possui uma imagem.",
        placement:'top'
    });
    // Clear event
    $('.image-preview-clear').click(function () {
        $('.image-preview').attr("data-content","").popover('hide');
        $('.image-preview-filename').val("");
        $('.image-preview-clear').hide();
        $('.image-preview-input input:file').val("");
        $(".image-preview-input-title").text("Adicionar"); 
    }); 
    // Create the preview image
    $(".image-preview-input input:file").change(function () { 
        var img = $('<img/>', {
            id: 'dynamic',
            width:250,
            height:200
        });      
        var file = this.files[0];
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            $(".image-preview-input-title").text("Mudar");
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);            
            img.attr('src', e.target.result);
            $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
        }        
        reader.readAsDataURL(file);
    });

    $(this).on('click', '#close-preview', function () { 
        $('.image-preview').popover('hide');
        // Hover befor close the preview
        $('.image-preview').hover(
            function () {
               $('.image-preview').popover('show');
            }, 
             function () {
               $('.image-preview').popover('hide');
            }
        );
    });

/**
 * Totalizador de analises macroscópicas
 */

    $("input[type='checkbox']").on('ifChanged', function() {
        
        var splited_name = this.name.split('_');
        var check_name = splited_name.slice(1, -1);
        name = "chk_"+check_name.join('_');
        total = 0;
        for (var i=1; i <= 10; i ++) {
            if($('#'+name+'_'+i).is(':checked')){
               total = total + 1;
            }
        }
        $("#tt_"+check_name.join('_')).removeAttr("disabled");
        $("#tt_"+check_name.join('_')).val(total*10);
        $("#tt_"+check_name.join('_')).attr("disabled",true);
        $("#total_"+check_name.join('_')).val(total*10);
    });

});

