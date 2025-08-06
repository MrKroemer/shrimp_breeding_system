/**
 * Script para renderizar diretamente os gráficos do dashboard
 */

// Executar assim que o script for carregado
(function() {
    console.log('Inicializando gráficos diretamente...');
    
    // Verificar se o Chart.js está disponível
    if (typeof Chart === 'undefined') {
        console.error('Chart.js não está disponível. Carregando...');
        loadChartJS();
    } else {
        console.log('Chart.js já está disponível. Renderizando gráficos...');
        renderAllCharts();
    }
})();

// Função para carregar o Chart.js dinamicamente
function loadChartJS() {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
    script.onload = function() {
        console.log('Chart.js carregado com sucesso!');
        renderAllCharts();
    };
    script.onerror = function() {
        console.error('Falha ao carregar Chart.js');
    };
    document.head.appendChild(script);
}

// Função para renderizar todos os gráficos
function renderAllCharts() {
    // Aguardar um momento para garantir que os elementos estejam disponíveis
    setTimeout(function() {
        try {
            // Renderizar gráficos dos cards
            renderCardCharts();
            
            // Renderizar gráficos dos tanques
            renderTankCharts();
            
            console.log('Todos os gráficos foram renderizados com sucesso!');
        } catch (error) {
            console.error('Erro ao renderizar gráficos:', error);
        }
    }, 100);
}

// Renderizar os gráficos dos cards
function renderCardCharts() {
    console.log('Renderizando gráficos dos cards...');
    
    // Gráfico de Análises
    renderDoughnutChart('analises-chart', ['Concluídas', 'Pendentes'], [75, 25], ['#0056b3', '#e9ecef']);
    
    // Gráfico de Ciclos
    renderDoughnutChart('ciclos-chart', ['Ativos', 'Inativos'], [42, 18], ['#28a745', '#e9ecef']);
    
    // Gráfico de Produção
    renderDoughnutChart('producao-chart', ['Realizado', 'Meta'], [65, 35], ['#ffc107', '#e9ecef']);
    
    // Gráfico de Conclusão
    renderDoughnutChart('conclusao-chart', ['Concluído', 'Pendente'], [87, 13], ['#17a2b8', '#e9ecef']);
}

// Renderizar os gráficos dos tanques
function renderTankCharts() {
    console.log('Renderizando gráficos dos tanques...');
    
    // Gráfico de Tanques com Maior Crescimento
    renderTanquesCrescimentoChart();
    
    // Gráfico de Distribuição por Tanque
    renderDistribuicaoTanquesChart();
    
    // Gráfico de Tanques com Menor Mortalidade
    renderTanquesMortalidadeChart();
    
    // Gráfico de Sobrevivência Média
    renderSobrevivenciaMediaChart();
}

// Função auxiliar para renderizar gráficos de rosca
function renderDoughnutChart(canvasId, labels, data, colors) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) {
        console.error(`Canvas ${canvasId} não encontrado`);
        return;
    }
    
    console.log(`Renderizando gráfico ${canvasId}...`);
    
    try {
        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                }
            }
        });
        console.log(`Gráfico ${canvasId} renderizado com sucesso`);
    } catch (error) {
        console.error(`Erro ao renderizar gráfico ${canvasId}:`, error);
    }
}

// Gráfico de Tanques com Maior Crescimento
function renderTanquesCrescimentoChart() {
    const canvas = document.getElementById('crescimento-chart');
    if (!canvas) {
        console.error('Canvas crescimento-chart não encontrado');
        return;
    }
    
    try {
        new Chart(canvas, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai'],
                datasets: [
                    {
                        label: 'Tanque V01',
                        data: [0.5, 0.7, 0.9, 1.1, 1.2],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Tanque V05',
                        data: [0.4, 0.6, 0.8, 0.9, 1.1],
                        borderColor: '#0056b3',
                        backgroundColor: 'rgba(0, 86, 179, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Tanque V08',
                        data: [0.3, 0.5, 0.7, 0.8, 0.9],
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Crescimento (g/semana)'
                        }
                    }
                }
            }
        });
        console.log('Gráfico crescimento-chart renderizado com sucesso');
    } catch (error) {
        console.error('Erro ao renderizar gráfico crescimento-chart:', error);
    }
}

// Gráfico de Distribuição por Tanque
function renderDistribuicaoTanquesChart() {
    const canvas = document.getElementById('distribuicao-tanques-chart');
    if (!canvas) {
        console.error('Canvas distribuicao-tanques-chart não encontrado');
        return;
    }
    
    try {
        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['Berçário', 'Crescimento', 'Engorda', 'Maturação'],
                datasets: [{
                    data: [15, 25, 40, 20],
                    backgroundColor: [
                        '#0056b3',
                        '#28a745',
                        '#ffc107',
                        '#17a2b8'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw} tanques`;
                            }
                        }
                    }
                }
            }
        });
        console.log('Gráfico distribuicao-tanques-chart renderizado com sucesso');
    } catch (error) {
        console.error('Erro ao renderizar gráfico distribuicao-tanques-chart:', error);
    }
}

// Gráfico de Tanques com Menor Mortalidade
function renderTanquesMortalidadeChart() {
    const canvas = document.getElementById('mortalidade-chart');
    if (!canvas) {
        console.error('Canvas mortalidade-chart não encontrado');
        return;
    }
    
    try {
        new Chart(canvas, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai'],
                datasets: [
                    {
                        label: 'Tanque V03',
                        data: [99, 98.5, 98.2, 98, 98],
                        borderColor: '#17a2b8',
                        backgroundColor: 'rgba(23, 162, 184, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Tanque V07',
                        data: [99, 98, 97.5, 97.2, 97],
                        borderColor: '#6f42c1',
                        backgroundColor: 'rgba(111, 66, 193, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Tanque V09',
                        data: [98.5, 97.5, 97, 96.5, 96],
                        borderColor: '#fd7e14',
                        backgroundColor: 'rgba(253, 126, 20, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        min: 95,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Taxa de Sobrevivência (%)'
                        }
                    }
                }
            }
        });
        console.log('Gráfico mortalidade-chart renderizado com sucesso');
    } catch (error) {
        console.error('Erro ao renderizar gráfico mortalidade-chart:', error);
    }
}

// Gráfico de Sobrevivência Média
function renderSobrevivenciaMediaChart() {
    const canvas = document.getElementById('sobrevivencia-media-chart');
    if (!canvas) {
        console.error('Canvas sobrevivencia-media-chart não encontrado');
        return;
    }
    
    try {
        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['Sobrevivência', 'Mortalidade'],
                datasets: [{
                    data: [95, 5],
                    backgroundColor: [
                        '#17a2b8',
                        '#e9ecef'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw}%`;
                            }
                        }
                    }
                }
            }
        });
        console.log('Gráfico sobrevivencia-media-chart renderizado com sucesso');
    } catch (error) {
        console.error('Erro ao renderizar gráfico sobrevivencia-media-chart:', error);
    }
}
