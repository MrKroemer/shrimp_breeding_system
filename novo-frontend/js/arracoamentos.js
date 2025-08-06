/**
 * Gerenciamento de Arraçoamentos
 * Este arquivo contém as funções para exibir e gerenciar os dados de arraçoamentos
 */

// Dados de exemplo para arraçoamentos (serão substituídos por dados reais da API)
const arracoamentosData = [
    {
        id: 1,
        data_aplicacao: '2025-05-05',
        situacao: 'V',
        mortalidade: 25,
        ciclo_id: 1,
        tanque_id: 42,
        aplicacoes: [
            { quantidade: 3.50, tipo_id: 1, tipo_nome: 'RAÇÃO' },
            { quantidade: 0.25, tipo_id: 2, tipo_nome: 'PROBIÓTICO' },
            { quantidade: 0.15, tipo_id: 5, tipo_nome: 'OUTROS INSUMOS' }
        ]
    },
    {
        id: 2,
        data_aplicacao: '2025-05-04',
        situacao: 'V',
        mortalidade: 18,
        ciclo_id: 1,
        tanque_id: 42,
        aplicacoes: [
            { quantidade: 3.20, tipo_id: 1, tipo_nome: 'RAÇÃO' },
            { quantidade: 0.22, tipo_id: 2, tipo_nome: 'PROBIÓTICO' },
            { quantidade: 0.12, tipo_id: 5, tipo_nome: 'OUTROS INSUMOS' }
        ]
    },
    {
        id: 3,
        data_aplicacao: '2025-05-03',
        situacao: 'V',
        mortalidade: 20,
        ciclo_id: 1,
        tanque_id: 42,
        aplicacoes: [
            { quantidade: 3.00, tipo_id: 1, tipo_nome: 'RAÇÃO' },
            { quantidade: 0.20, tipo_id: 2, tipo_nome: 'PROBIÓTICO' },
            { quantidade: 0.18, tipo_id: 3, tipo_nome: 'ADITIVO' },
            { quantidade: 0.15, tipo_id: 4, tipo_nome: 'VITAMINA' },
            { quantidade: 0.10, tipo_id: 5, tipo_nome: 'OUTROS INSUMOS' }
        ]
    }
];

// Função para formatar a data
function formatarData(dataString) {
    const data = new Date(dataString);
    return data.toLocaleDateString('pt-BR');
}

// Função para obter o ícone com base no tipo de aplicação
function getIconForTipo(tipoId) {
    const icons = {
        1: 'fa-shrimp', // RAÇÃO
        2: 'fa-pills', // PROBIÓTICO
        3: 'fa-vial', // ADITIVO
        4: 'fa-capsules', // VITAMINA
        5: 'fa-box' // OUTROS INSUMOS
    };
    
    return icons[tipoId] || 'fa-box';
}

// Função para obter a cor com base no tipo de aplicação
function getColorForTipo(tipoId) {
    const colors = {
        1: '#3498db', // RAÇÃO - Azul
        2: '#2ecc71', // PROBIÓTICO - Verde
        3: '#f39c12', // ADITIVO - Laranja
        4: '#9b59b6', // VITAMINA - Roxo
        5: '#95a5a6'  // OUTROS INSUMOS - Cinza
    };
    
    return colors[tipoId] || '#95a5a6';
}

// Função para carregar os dados de arraçoamentos
function carregarArracoamentos() {
    // Aqui seria feita uma chamada AJAX para obter os dados da API
    // Por enquanto, usamos os dados de exemplo
    return arracoamentosData;
}

// Função para renderizar o gráfico de arraçoamentos
function renderizarGraficoArracoamentos(dados = null) {
    // Usar os dados filtrados ou os dados originais
    const dadosParaRenderizar = dados || arracoamentosData;
    
    const ctx = document.getElementById('arracoamentos-chart').getContext('2d');
    
    // Preparar dados para o gráfico
    const labels = dadosParaRenderizar.map(item => formatarData(item.data_aplicacao)).reverse();
    
    // Agrupar aplicações por tipo
    const tiposUnicos = [...new Set(dadosParaRenderizar.flatMap(item => item.aplicacoes.map(ap => ap.tipo_id)))];
    
    // Criar datasets para cada tipo
    const datasets = tiposUnicos.map(tipoId => {
        const tipoNome = dadosParaRenderizar[0].aplicacoes.find(ap => ap.tipo_id === tipoId)?.tipo_nome || `Tipo ${tipoId}`;
        
        const quantidades = dadosParaRenderizar.map(item => {
            const aplicacao = item.aplicacoes.find(ap => ap.tipo_id === tipoId);
            return aplicacao ? aplicacao.quantidade : 0;
        }).reverse();
        
        return {
            label: tipoNome,
            data: quantidades,
            backgroundColor: getColorForTipo(tipoId),
            borderColor: getColorForTipo(tipoId),
            borderWidth: 1
        };
    });
    
    // Criar o gráfico
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    // Definir um limite máximo mais realista para o eixo Y
                    suggestedMax: 4.0,
                    title: {
                        display: true,
                        text: 'Quantidade (kg)'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw.toFixed(2)} kg`;
                        }
                    }
                }
            }
        }
    });
}

// Função para renderizar a tabela de arraçoamentos
function renderizarTabelaArracoamentos(dados = null) {
    // Usar os dados filtrados ou os dados originais
    const dadosParaRenderizar = dados || arracoamentosData;
    
    const tbody = document.querySelector('#arracoamentos-table tbody');
    
    // Limpar a tabela
    tbody.innerHTML = '';
    
    // Adicionar linhas
    dadosParaRenderizar.forEach((item, index) => {
        const tr = document.createElement('tr');
        
        // Calcular o total de aplicações
        const totalAplicacoes = item.aplicacoes.reduce((sum, ap) => sum + ap.quantidade, 0);
        
        // Criar células
        tr.innerHTML = `
            <td>${formatarData(item.data_aplicacao)}</td>
            <td>${totalAplicacoes.toFixed(2)} kg</td>
            <td>${item.mortalidade} unid.</td>
            <td>
                ${item.aplicacoes.map(ap => {
                    // Formatar a quantidade com base no tipo
                    let quantidadeFormatada = ap.quantidade.toFixed(2);
                    // Para valores muito pequenos, mostrar com mais casas decimais
                    if (ap.quantidade < 0.1) {
                        quantidadeFormatada = ap.quantidade.toFixed(3);
                    }
                    return `
                        <span class="badge" style="background-color: ${getColorForTipo(ap.tipo_id)}">
                            <i class="fas ${getIconForTipo(ap.tipo_id)}"></i> ${quantidadeFormatada} kg
                        </span>
                    `;
                }).join(' ')}
            </td>
            <td>
                <button class="btn btn-sm btn-icon btn-outline-primary btn-view-detail" data-index="${index}" title="Ver detalhes">
                    <i class="fas fa-eye"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(tr);
    });
    
    // Adicionar event listeners para os botões de detalhes
    document.querySelectorAll('.btn-view-detail').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            mostrarDetalhesArracoamento(index);
        });
    });
}

// Função para mostrar o modal de detalhes do arraçoamento
function mostrarDetalhesArracoamento(index) {
    const dados = carregarArracoamentos();
    const item = dados[index];
    
    if (!item) return;
    
    // Calcular o total de aplicações
    const totalAplicacoes = item.aplicacoes.reduce((sum, ap) => sum + ap.quantidade, 0);
    
    // Atualizar os elementos do modal com os dados do arraçoamento
    document.getElementById('arracoamento-detail-date').textContent = formatarData(item.data_aplicacao);
    document.getElementById('arracoamento-detail-total').textContent = `${totalAplicacoes.toFixed(2)} kg`;
    document.getElementById('arracoamento-detail-mortality').textContent = `${item.mortalidade} unid.`;
    
    // Definir a situação
    let situacao = 'Não Validado';
    if (item.situacao === 'V') situacao = 'Validado';
    else if (item.situacao === 'P') situacao = 'Parcialmente Validado';
    document.getElementById('arracoamento-detail-status').textContent = situacao;
    
    // Preencher a tabela de aplicações
    const tbody = document.querySelector('#arracoamento-detail-applications tbody');
    tbody.innerHTML = '';
    
    item.aplicacoes.forEach(ap => {
        const tr = document.createElement('tr');
        
        // Formatar a quantidade
        let quantidadeFormatada = ap.quantidade.toFixed(2);
        if (ap.quantidade < 0.1) {
            quantidadeFormatada = ap.quantidade.toFixed(3);
        }
        
        tr.innerHTML = `
            <td>
                <span class="badge" style="background-color: ${getColorForTipo(ap.tipo_id)}">
                    <i class="fas ${getIconForTipo(ap.tipo_id)}"></i> ${ap.tipo_nome}
                </span>
            </td>
            <td>${quantidadeFormatada} kg</td>
        `;
        
        tbody.appendChild(tr);
    });
    
    // Exibir o modal
    const modal = document.getElementById('arracoamento-detail-modal');
    modal.style.display = 'block';
    
    // Adicionar event listener para fechar o modal
    const closeButtons = modal.querySelectorAll('.modal-close, .modal-close-btn');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    });
    
    // Fechar o modal quando clicar fora dele
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}

// Dados das rações ativas (tipo 2 - RAÇÃO, situação ON)
const racoesAtivas = [
    { id: 32, nome: 'DENSITY 38', codigo: null },
    { id: 33, nome: 'DENSITY 2.0 J TRAT 2 T2.0J', codigo: null },
    { id: 35, nome: 'DENSITY 38 - BIOTRONIC', codigo: null },
    { id: 36, nome: 'DENSITY 38 CAM 38C', codigo: null },
    { id: 37, nome: 'DENSITY 38 CAM PRO 38CP', codigo: null },
    { id: 38, nome: 'DENSITY 38J', codigo: null },
    { id: 39, nome: 'DENSITY 38J - BIOTRONIC', codigo: null },
    { id: 40, nome: 'DENSITY 38J CAM 38JC', codigo: null },
    { id: 41, nome: 'DENSITY 38J CAM PRO 38JCP', codigo: null },
    { id: 42, nome: 'DENSITY 4.0 TRAT CRUMBLE 2 T4.0', codigo: null },
    { id: 18, nome: 'CAMANUTRI 35', codigo: null },
    { id: 20, nome: 'CAMANUTRI CR1', codigo: null },
    { id: 104, nome: 'Flash Shrimp #1.3 mm 20kg F1.3', codigo: null },
    { id: 105, nome: 'NUTRIPISCIS TR 32 6 a 8MM', codigo: null },
    { id: 106, nome: 'Poli-Camarão 380 CP 30kg P380', codigo: null }
];

// Filtros atuais
let filtroAtual = {
    racaoId: '',
    dataInicio: '',
    dataFim: ''
};

// Função para carregar as opções de rações no select
function carregarOpcoesRacoes() {
    const selectRacao = document.getElementById('filtro-racao');
    
    // Limpar opções existentes, mantendo a primeira opção (Todas as rações)
    while (selectRacao.options.length > 1) {
        selectRacao.remove(1);
    }
    
    // Adicionar opções de rações ativas
    racoesAtivas.forEach(racao => {
        const option = document.createElement('option');
        option.value = racao.id;
        option.textContent = racao.nome;
        selectRacao.appendChild(option);
    });
}

// Função para aplicar os filtros aos dados de arraçoamentos
// Exposta globalmente para ser acessível via onclick no HTML
window.aplicarFiltros = function() {
    // Filtrar os dados com base nos filtros atuais
    const dadosFiltrados = arracoamentosData.filter(item => {
        // Verificar filtro de ração
        if (filtroAtual.racaoId && !item.aplicacoes.some(aplicacao => 
            aplicacao.tipo_id === 1 && aplicacao.produto_id == filtroAtual.racaoId)) {
            return false;
        }
        
        // Verificar filtro de data de início
        if (filtroAtual.dataInicio && item.data_aplicacao < filtroAtual.dataInicio) {
            return false;
        }
        
        // Verificar filtro de data de fim
        if (filtroAtual.dataFim && item.data_aplicacao > filtroAtual.dataFim) {
            return false;
        }
        
        return true;
    });
    
    // Atualizar a visualização com os dados filtrados
    renderizarGraficoArracoamentos(dadosFiltrados);
    renderizarTabelaArracoamentos(dadosFiltrados);
    
    // Atualizar os resumos
    atualizarResumoArracoamentos(dadosFiltrados);
}

// Função para atualizar os resumos de arraçoamentos com base nos dados filtrados
function atualizarResumoArracoamentos(dados) {
    // Calcular totais por tipo
    const totais = {
        racao: 0,
        probiotico: 0,
        aditivo: 0,
        vitamina: 0,
        outros: 0
    };
    
    dados.forEach(item => {
        item.aplicacoes.forEach(aplicacao => {
            switch(aplicacao.tipo_id) {
                case 1: // RAÇÃO
                    totais.racao += aplicacao.quantidade;
                    break;
                case 2: // PROBIÓTICO
                    totais.probiotico += aplicacao.quantidade;
                    break;
                case 3: // ADITIVO
                    totais.aditivo += aplicacao.quantidade;
                    break;
                case 4: // VITAMINA
                    totais.vitamina += aplicacao.quantidade;
                    break;
                case 5: // OUTROS INSUMOS
                    totais.outros += aplicacao.quantidade;
                    break;
            }
        });
    });
    
    // Atualizar os cards de resumo
    document.querySelector('.arracoamentos-summary-card.racao .value').textContent = totais.racao.toFixed(1) + ' kg';
    document.querySelector('.arracoamentos-summary-card.probiotico .value').textContent = totais.probiotico.toFixed(2) + ' kg';
    document.querySelector('.arracoamentos-summary-card.aditivo .value').textContent = totais.aditivo.toFixed(2) + ' kg';
    document.querySelector('.arracoamentos-summary-card.vitamina .value').textContent = totais.vitamina.toFixed(2) + ' kg';
    document.querySelector('.arracoamentos-summary-card.outros .value').textContent = totais.outros.toFixed(2) + ' kg';
}

// Função para abrir o modal de filtro
// Exposta globalmente para ser acessível via onclick no HTML
window.abrirModalFiltro = function() {
    console.log('Abrindo modal de filtro');
    const modal = document.getElementById('filtro-arracoamentos-modal');
    if (!modal) {
        console.error('Modal de filtro não encontrado');
        return;
    }
    
    // Adicionar classe active para exibir o modal com flexbox
    modal.classList.add('active');
    
    // Definir valores atuais nos campos
    const selectRacao = document.getElementById('filtro-racao');
    const inputDataInicio = document.getElementById('filtro-data-inicio');
    const inputDataFim = document.getElementById('filtro-data-fim');
    
    if (selectRacao) selectRacao.value = filtroAtual.racaoId || '';
    if (inputDataInicio) inputDataInicio.value = filtroAtual.dataInicio || '';
    if (inputDataFim) inputDataFim.value = filtroAtual.dataFim || '';
}

// Função para fechar o modal de filtro
// Exposta globalmente para ser acessível via onclick no HTML
window.fecharModalFiltro = function() {
    console.log('Fechando modal de filtro');
    const modal = document.getElementById('filtro-arracoamentos-modal');
    if (!modal) {
        console.error('Modal de filtro não encontrado');
        return;
    }
    
    // Remover a classe active para esconder o modal
    modal.classList.remove('active');
}

// Função para limpar os filtros
// Exposta globalmente para ser acessível via onclick no HTML
window.limparFiltros = function() {
    // Limpar os campos do formulário
    document.getElementById('filtro-racao').value = '';
    document.getElementById('filtro-data-inicio').value = '';
    document.getElementById('filtro-data-fim').value = '';
    
    // Resetar o objeto de filtros
    filtroAtual = {
        racaoId: '',
        dataInicio: '',
        dataFim: ''
    };
    
    // Aplicar os filtros (que agora estão vazios)
    aplicarFiltros();
}

// Inicializar quando o documento estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    renderizarGraficoArracoamentos();
    renderizarTabelaArracoamentos();
    
    // Carregar opções de rações
    carregarOpcoesRacoes();
    
    // Configurar evento de clique no botão de filtro
    const btnFiltro = document.getElementById('filtrar-arracoamentos');
    if (btnFiltro) {
        btnFiltro.addEventListener('click', abrirModalFiltro);
    }
    
    // Configurar evento de clique no botão de fechar modal
    const closeButtons = document.querySelectorAll('#filtro-arracoamentos-modal .modal-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', fecharModalFiltro);
    });
    
    // Configurar evento de clique no botão de limpar filtros
    const btnLimpar = document.getElementById('limpar-filtro');
    if (btnLimpar) {
        btnLimpar.addEventListener('click', limparFiltros);
    }
    
    // Configurar evento de submit do formulário de filtro
    const formFiltro = document.getElementById('form-filtro-arracoamentos');
    if (formFiltro) {
        formFiltro.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Atualizar o objeto de filtros com os valores do formulário
            filtroAtual.racaoId = document.getElementById('filtro-racao').value;
            filtroAtual.dataInicio = document.getElementById('filtro-data-inicio').value;
            filtroAtual.dataFim = document.getElementById('filtro-data-fim').value;
            
            // Aplicar os filtros
            aplicarFiltros();
            
            // Fechar o modal
            fecharModalFiltro();
        });
    }
});
