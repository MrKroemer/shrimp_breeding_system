/**
 * JavaScript para inicializar e gerenciar os gráficos do dashboard
 */

// Garantir que o código só execute quando o DOM estiver completamente carregado
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado, inicializando gráficos...');
    
    // Verificar se Chart.js está disponível
    if (typeof Chart === 'undefined') {
        console.error('Chart.js não foi carregado!');
        return;
    }
    
    console.log('Chart.js está disponível, versão:', Chart.version);
    
    // Verificar se os elementos canvas existem
    console.log('Canvas analises-chart:', document.getElementById('analises-chart'));
    console.log('Canvas ciclos-chart:', document.getElementById('ciclos-chart'));
    console.log('Canvas producao-chart:', document.getElementById('producao-chart'));
    console.log('Canvas conclusao-chart:', document.getElementById('conclusao-chart'));
    
    try {
        // Inicializar gráficos dos cards
        initCardCharts();
        console.log('Gráficos dos cards inicializados com sucesso');
        
        // Inicializar gráfico principal
        initMainChart();
        console.log('Gráfico principal inicializado com sucesso');
        
        // Inicializar gráfico de pizza
        initPieChart();
        console.log('Gráfico de pizza inicializado com sucesso');
        
        // Inicializar gráficos de tanques
        initTanquesCrescimentoChart();
        initDistribuicaoTanquesChart();
        initTanquesMortalidadeChart();
        initSobrevivenciaMediaChart();
        console.log('Gráficos de tanques inicializados com sucesso');
    } catch (error) {
        console.error('Erro ao inicializar gráficos:', error);
    }
});

// Inicializar gráficos circulares nos cards
function initCardCharts() {
    // Configuração para o gráfico de Análises
    const analisesCanvas = document.getElementById('analises-chart');
    if (!analisesCanvas) {
        console.error('Elemento canvas analises-chart não encontrado');
        return;
    }
    
    new Chart(analisesCanvas, {
        type: 'doughnut',
        data: {
            labels: ['Concluídas', 'Pendentes'],
            datasets: [{
                data: [75, 25],
                backgroundColor: ['#0056b3', '#e9ecef'],
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
    
    // Configuração para o gráfico de Ciclos
    const ciclosCanvas = document.getElementById('ciclos-chart');
    if (!ciclosCanvas) {
        console.error('Elemento canvas ciclos-chart não encontrado');
        return;
    }
    
    new Chart(ciclosCanvas, {
        type: 'doughnut',
        data: {
            labels: ['Ativos', 'Inativos'],
            datasets: [{
                data: [42, 18],
                backgroundColor: ['#28a745', '#e9ecef'],
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
    
    // Configuração para o gráfico de Produção
    const producaoCanvas = document.getElementById('producao-chart');
    if (!producaoCanvas) {
        console.error('Elemento canvas producao-chart não encontrado');
        return;
    }
    
    new Chart(producaoCanvas, {
        type: 'doughnut',
        data: {
            labels: ['Realizado', 'Meta'],
            datasets: [{
                data: [65, 35],
                backgroundColor: ['#ffc107', '#e9ecef'],
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
    
    // Configuração para o gráfico de Conclusão
    const conclusaoCanvas = document.getElementById('conclusao-chart');
    if (!conclusaoCanvas) {
        console.error('Elemento canvas conclusao-chart não encontrado');
        return;
    }
    
    new Chart(conclusaoCanvas, {
        type: 'doughnut',
        data: {
            labels: ['Concluído', 'Pendente'],
            datasets: [{
                data: [87, 13],
                backgroundColor: ['#17a2b8', '#e9ecef'],
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
}

// Inicializar gráfico principal
function initMainChart() {
    const ctx = document.getElementById('main-chart');
    if (!ctx) {
        console.error('Elemento canvas main-chart não encontrado');
        return;
    }
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Produção (kg)',
                data: [1200, 1900, 1500, 2000, 1800, 2200],
                backgroundColor: '#0056b3',
                borderColor: '#0056b3',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Inicializar gráfico de pizza
function initPieChart() {
    const ctx = document.getElementById('pie-chart');
    if (!ctx) {
        console.error('Elemento canvas pie-chart não encontrado');
        return;
    }
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Categoria A', 'Categoria B', 'Categoria C', 'Categoria D'],
            datasets: [{
                data: [35, 25, 22, 18],
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
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

// Inicializar gráfico de tanques com maior crescimento
function initTanquesCrescimentoChart() {
    const ctx = document.getElementById('crescimento-chart');
    if (!ctx) {
        console.error('Elemento canvas crescimento-chart não encontrado');
        return;
    }
    
    new Chart(ctx, {
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
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw} g/semana`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Taxa de Crescimento (g/semana)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Inicializar gráfico de distribuição por tanque
function initDistribuicaoTanquesChart() {
    const ctx = document.getElementById('distribuicao-tanques-chart');
    if (!ctx) {
        console.error('Elemento canvas distribuicao-tanques-chart não encontrado');
        return;
    }
    
    new Chart(ctx, {
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

// Inicializar gráfico de tanques com menor mortalidade
function initTanquesMortalidadeChart() {
    const ctx = document.getElementById('mortalidade-chart');
    if (!ctx) {
        console.error('Elemento canvas mortalidade-chart não encontrado');
        return;
    }
    
    new Chart(ctx, {
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
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw}%`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    min: 90,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Taxa de Sobrevivência (%)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Inicializar gráfico de sobrevivência média
function initSobrevivenciaMediaChart() {
    const ctx = document.getElementById('sobrevivencia-media-chart');
    if (!ctx) {
        console.error('Elemento canvas sobrevivencia-media-chart não encontrado');
        return;
    }
    
    new Chart(ctx, {
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
