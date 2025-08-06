/**
 * Script simples para renderizar os gráficos do dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando gráficos simples...');
    
    // Renderizar gráficos dos cards
    renderCardCharts();
    
    // Renderizar gráficos dos tanques
    renderTankCharts();
});

function renderCardCharts() {
    // Gráfico de Análises
    renderDoughnutChart('analises-chart', ['Concluídas', 'Pendentes'], [75, 25], ['#0056b3', '#e9ecef']);
    
    // Gráfico de Ciclos
    renderDoughnutChart('ciclos-chart', ['Ativos', 'Inativos'], [42, 18], ['#28a745', '#e9ecef']);
    
    // Gráfico de Produção
    renderDoughnutChart('producao-chart', ['Realizado', 'Meta'], [65, 35], ['#ffc107', '#e9ecef']);
    
    // Gráfico de Conclusão
    renderDoughnutChart('conclusao-chart', ['Concluído', 'Pendente'], [87, 13], ['#17a2b8', '#e9ecef']);
}

function renderTankCharts() {
    // Gráfico de Tanques com Maior Crescimento
    if (document.getElementById('crescimento-chart')) {
        new Chart(document.getElementById('crescimento-chart'), {
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
    }
    
    // Gráfico de Distribuição por Tanque
    if (document.getElementById('distribuicao-tanques-chart')) {
        new Chart(document.getElementById('distribuicao-tanques-chart'), {
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
    }
    
    // Gráfico de Tanques com Menor Mortalidade
    if (document.getElementById('mortalidade-chart')) {
        new Chart(document.getElementById('mortalidade-chart'), {
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
    }
    
    // Gráfico de Sobrevivência Média
    if (document.getElementById('sobrevivencia-media-chart')) {
        new Chart(document.getElementById('sobrevivencia-media-chart'), {
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
    }
}

function renderDoughnutChart(canvasId, labels, data, colors) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) {
        console.error(`Canvas ${canvasId} não encontrado`);
        return;
    }
    
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
