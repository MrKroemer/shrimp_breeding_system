// Arquivo JavaScript para a funcionalidade de insumos

// Dados de exemplo para insumos
const insumosData = [
    { id: 1, data: '2025-04-01', insumo_id: 55, nome: 'HIPOCLORITO DE CALCIO 65% (CLORO)', quantidade: 0.5, unidade: 'kg' },
    { id: 2, data: '2025-04-02', insumo_id: 21, nome: 'CARBONATO DE SODIO', quantidade: 3.2, unidade: 'kg' },
    { id: 3, data: '2025-04-03', insumo_id: 55, nome: 'HIPOCLORITO DE CALCIO 65% (CLORO)', quantidade: 0.8, unidade: 'kg' },
    { id: 4, data: '2025-04-05', insumo_id: 117, nome: 'LITHONUTRI', quantidade: 2.5, unidade: 'kg' },
    { id: 5, data: '2025-04-07', insumo_id: 116, nome: 'LITHONUTRI FAREL', quantidade: 1.8, unidade: 'kg' },
    { id: 6, data: '2025-04-10', insumo_id: 21, nome: 'CARBONATO DE SODIO', quantidade: 4.1, unidade: 'kg' },
    { id: 7, data: '2025-04-12', insumo_id: 55, nome: 'HIPOCLORITO DE CALCIO 65% (CLORO)', quantidade: 1.2, unidade: 'kg' },
    { id: 8, data: '2025-04-15', insumo_id: 117, nome: 'LITHONUTRI', quantidade: 3.0, unidade: 'kg' },
    { id: 9, data: '2025-04-18', insumo_id: 116, nome: 'LITHONUTRI FAREL', quantidade: 2.9, unidade: 'kg' },
    { id: 10, data: '2025-04-20', insumo_id: 21, nome: 'CARBONATO DE SODIO', quantidade: 7.7, unidade: 'kg' }
];

// Variáveis para armazenar os dados filtrados
let insumosFiltrados = [...insumosData];
let filtroInsumoAtual = '';
let filtroDataInicioInsumos = '';
let filtroDataFimInsumos = '';

// Função para inicializar o gráfico de insumos
function inicializarGraficoInsumos() {
    const ctx = document.getElementById('insumos-chart').getContext('2d');
    
    // Agrupar dados por tipo de insumo
    const dadosAgrupados = {};
    insumosData.forEach(item => {
        if (!dadosAgrupados[item.nome]) {
            dadosAgrupados[item.nome] = 0;
        }
        dadosAgrupados[item.nome] += item.quantidade;
    });
    
    // Preparar dados para o gráfico
    const labels = Object.keys(dadosAgrupados);
    const data = Object.values(dadosAgrupados);
    
    // Cores para o gráfico
    const cores = [
        'rgba(0, 188, 212, 0.7)',  // Azul claro (Cloro)
        'rgba(158, 158, 158, 0.7)', // Cinza (Calcário)
        'rgba(141, 110, 99, 0.7)',  // Marrom (Melaço)
        'rgba(126, 87, 194, 0.7)'   // Roxo (Outros)
    ];
    
    // Criar gráfico
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Quantidade (kg)',
                data: data,
                backgroundColor: cores,
                borderColor: cores.map(cor => cor.replace('0.7', '1')),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Quantidade (kg)'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Consumo de Insumos',
                    font: {
                        size: 16
                    }
                }
            }
        }
    });
}

// Função para aplicar filtros aos insumos
function aplicarFiltrosInsumos() {
    // Obter valores dos filtros
    filtroInsumoAtual = document.getElementById('filtro-insumo-simples').value;
    filtroDataInicioInsumos = document.getElementById('filtro-data-inicio-insumos').value;
    filtroDataFimInsumos = document.getElementById('filtro-data-fim-insumos').value;
    
    // Aplicar filtros
    insumosFiltrados = insumosData.filter(item => {
        // Filtro de insumo
        if (filtroInsumoAtual && item.insumo_id.toString() !== filtroInsumoAtual) {
            return false;
        }
        
        // Filtro de data de início
        if (filtroDataInicioInsumos && item.data < filtroDataInicioInsumos) {
            return false;
        }
        
        // Filtro de data de fim
        if (filtroDataFimInsumos && item.data > filtroDataFimInsumos) {
            return false;
        }
        
        return true;
    });
    
    // Atualizar os cards de resumo
    atualizarCardsInsumos();
    
    // Fechar o modal
    document.getElementById('modal-filtro-insumos').style.display = 'none';
}

// Função para limpar os filtros de insumos
function limparFiltrosInsumos() {
    document.getElementById('filtro-insumo-simples').value = '';
    document.getElementById('filtro-data-inicio-insumos').value = '';
    document.getElementById('filtro-data-fim-insumos').value = '';
    
    filtroInsumoAtual = '';
    filtroDataInicioInsumos = '';
    filtroDataFimInsumos = '';
    
    insumosFiltrados = [...insumosData];
    atualizarCardsInsumos();
}

// Função para atualizar os cards de resumo de insumos
function atualizarCardsInsumos() {
    // Calcular totais por tipo de insumo
    let totalCloro = 0;
    let totalCalcario = 0;
    let totalMelaco = 0;
    let totalOutros = 0;
    
    insumosFiltrados.forEach(item => {
        if (item.insumo_id === 55) { // CLORO
            totalCloro += item.quantidade;
        } else if (item.insumo_id === 21) { // CARBONATO DE SODIO (Calcário)
            totalCalcario += item.quantidade;
        } else if (item.insumo_id === 117 || item.insumo_id === 116) { // LITHONUTRI (Melaço)
            totalMelaco += item.quantidade;
        } else {
            totalOutros += item.quantidade;
        }
    });
    
    // Atualizar valores nos cards
    document.querySelector('.insumos-summary-card.cloro .value').textContent = totalCloro.toFixed(1) + ' kg';
    document.querySelector('.insumos-summary-card.calcario .value').textContent = totalCalcario.toFixed(1) + ' kg';
    document.querySelector('.insumos-summary-card.melaco .value').textContent = totalMelaco.toFixed(1) + ' kg';
    document.querySelector('.insumos-summary-card.outros .value').textContent = totalOutros.toFixed(1) + ' kg';
}

// Inicializar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar o gráfico de insumos
    inicializarGraficoInsumos();
    
    // Configurar evento de submit do formulário de filtro de insumos
    const formFiltroInsumos = document.getElementById('form-filtro-insumos');
    if (formFiltroInsumos) {
        formFiltroInsumos.addEventListener('submit', function(e) {
            e.preventDefault();
            aplicarFiltrosInsumos();
        });
    }
    
    // Configurar evento de clique do botão de limpar filtros de insumos
    const btnLimparFiltroInsumos = document.getElementById('limpar-filtro-insumos');
    if (btnLimparFiltroInsumos) {
        btnLimparFiltroInsumos.addEventListener('click', limparFiltrosInsumos);
    }
    
    // Inicializar os cards de resumo
    atualizarCardsInsumos();
});
