/**
 * JavaScript para o Dashboard de Ciclos da Camanor
 * Gerencia a exibição de dados, gráficos e interatividade
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar componentes do dashboard
    initParametrosChart();
    initCustoChart();
    initBiometriaChart();
    initEventListeners();
    
    // Carregar dados do ciclo selecionado
    loadCicloData(getCurrentCicloId());
});

// Obter ID do ciclo atual
function getCurrentCicloId() {
    const cicloSelect = document.getElementById('ciclo-select');
    return cicloSelect ? cicloSelect.value : '1';
}

// Inicializar listeners de eventos
function initEventListeners() {
    // Alternar entre ciclos
    const cicloSelect = document.getElementById('ciclo-select');
    if (cicloSelect) {
        cicloSelect.addEventListener('change', function() {
            loadCicloData(this.value);
        });
    }
    
    // Alternar visualização de parâmetros (gráfico/tabela)
    const toggleParamView = document.getElementById('toggle-parametros-view');
    if (toggleParamView) {
        toggleParamView.addEventListener('click', function() {
            const chartContainer = document.querySelector('.parametros-chart-container');
            const tableContainer = document.querySelector('.parametros-table-container');
            
            if (chartContainer.style.display === 'none') {
                chartContainer.style.display = 'block';
                tableContainer.style.display = 'none';
                this.innerHTML = '<i class="fas fa-table"></i> Ver Tabela';
            } else {
                chartContainer.style.display = 'none';
                tableContainer.style.display = 'block';
                this.innerHTML = '<i class="fas fa-chart-line"></i> Ver Gráfico';
            }
        });
    }
    
    // Tabs de parâmetros
    const paramTabs = document.querySelectorAll('.parametro-tab');
    if (paramTabs.length > 0) {
        paramTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remover classe ativa de todas as tabs
                paramTabs.forEach(t => t.classList.remove('active'));
                
                // Adicionar classe ativa à tab clicada
                this.classList.add('active');
                
                // Atualizar gráfico para o parâmetro selecionado
                updateParametroChart(this.dataset.param);
            });
        });
    }
    
    // Tabs de custos
    const custoTabs = document.querySelectorAll('.custo-tab');
    if (custoTabs.length > 0) {
        custoTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                custoTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                updateCustoChart(this.dataset.type);
            });
        });
    }
}

// Carregar dados do ciclo
function loadCicloData(cicloId) {
    // Em um ambiente real, isso seria uma chamada de API
    // Simulando carregamento de dados
    showLoading();
    
    setTimeout(() => {
        // Atualizar informações do cabeçalho do ciclo
        updateCicloHeader(cicloId);
        
        // Atualizar cards de resumo
        updateCicloCards(cicloId);
        
        // Atualizar gráficos
        updateParametroChart('oxigenio');
        updateCustoChart('total');
        updateBiometriaChart();
        
        hideLoading();
    }, 800);
}

// Mostrar indicador de carregamento
function showLoading() {
    const loadingEl = document.createElement('div');
    loadingEl.className = 'loading-overlay';
    loadingEl.innerHTML = '<div class="loading-spinner"></div><p>Carregando dados do ciclo...</p>';
    document.body.appendChild(loadingEl);
}

// Ocultar indicador de carregamento
function hideLoading() {
    const loadingEl = document.querySelector('.loading-overlay');
    if (loadingEl) {
        loadingEl.remove();
    }
}

// Atualizar cabeçalho do ciclo
function updateCicloHeader(cicloId) {
    // Dados simulados para diferentes ciclos
    const ciclosData = {
        '1': {
            numero: 1,
            tanque: 'V01',
            status: 'Ativo',
            dataInicio: '15/01/2025',
            diasCultivo: 112
        },
        '2': {
            numero: 2,
            tanque: 'V02',
            status: 'Ativo',
            dataInicio: '03/02/2025',
            diasCultivo: 93
        },
        '3': {
            numero: 3,
            tanque: 'V03',
            status: 'Finalizado',
            dataInicio: '10/11/2024',
            dataFim: '15/03/2025',
            diasCultivo: 125
        },
        '4': {
            numero: 4,
            tanque: 'V04',
            status: 'Ativo',
            dataInicio: '22/03/2025',
            diasCultivo: 45
        },
        '5': {
            numero: 5,
            tanque: 'V05',
            status: 'Ativo',
            dataInicio: '05/04/2025',
            diasCultivo: 31
        }
    };
    
    const ciclo = ciclosData[cicloId] || ciclosData['1'];
    
    // Atualizar elementos do DOM
    const cicloTitle = document.querySelector('.ciclo-title');
    const cicloStatus = document.querySelector('.ciclo-status');
    const cicloDate = document.querySelector('.ciclo-date');
    const cicloDays = document.querySelector('.ciclo-days');
    
    if (cicloTitle) {
        cicloTitle.textContent = `Ciclo #${ciclo.numero} - Tanque ${ciclo.tanque}`;
    }
    
    if (cicloStatus) {
        cicloStatus.textContent = ciclo.status;
        cicloStatus.className = `ciclo-status ${ciclo.status.toLowerCase()}`;
    }
    
    if (cicloDate) {
        let dateText = `<i class="far fa-calendar-alt"></i> Início: ${ciclo.dataInicio}`;
        if (ciclo.dataFim) {
            dateText += ` | Fim: ${ciclo.dataFim}`;
        }
        cicloDate.innerHTML = dateText;
    }
    
    if (cicloDays) {
        cicloDays.innerHTML = `<i class="far fa-clock"></i> Dias de cultivo: ${ciclo.diasCultivo}`;
    }
}

// Atualizar cards de resumo do ciclo
function updateCicloCards(cicloId) {
    // Dados simulados para diferentes ciclos
    const ciclosResumo = {
        '1': {
            biomassa: '4.230 kg',
            biomassaTrend: '+12%',
            sobrevivencia: '87%',
            sobrevivenciaTrend: 'Estável',
            pesoMedio: '12,5g',
            pesoMedioTrend: '+0,8g',
            densidade: '35 cam/m²',
            densidadeTrend: 'Ideal'
        },
        '2': {
            biomassa: '3.850 kg',
            biomassaTrend: '+8%',
            sobrevivencia: '92%',
            sobrevivenciaTrend: '+2%',
            pesoMedio: '10,2g',
            pesoMedioTrend: '+0,6g',
            densidade: '42 cam/m²',
            densidadeTrend: 'Ideal'
        },
        '3': {
            biomassa: '5.120 kg',
            biomassaTrend: 'Final',
            sobrevivencia: '83%',
            sobrevivenciaTrend: 'Final',
            pesoMedio: '14,8g',
            pesoMedioTrend: 'Final',
            densidade: '32 cam/m²',
            densidadeTrend: 'Final'
        },
        '4': {
            biomassa: '1.750 kg',
            biomassaTrend: '+15%',
            sobrevivencia: '95%',
            sobrevivenciaTrend: '-1%',
            pesoMedio: '6,8g',
            pesoMedioTrend: '+1,2g',
            densidade: '38 cam/m²',
            densidadeTrend: 'Ideal'
        },
        '5': {
            biomassa: '980 kg',
            biomassaTrend: '+22%',
            sobrevivencia: '97%',
            sobrevivenciaTrend: 'Estável',
            pesoMedio: '3,5g',
            pesoMedioTrend: '+0,9g',
            densidade: '40 cam/m²',
            densidadeTrend: 'Ideal'
        }
    };
    
    const resumo = ciclosResumo[cicloId] || ciclosResumo['1'];
    
    // Atualizar valores dos cards
    updateCardValue('Biomassa', resumo.biomassa, `Crescimento de ${resumo.biomassaTrend} este mês`);
    updateCardValue('Sobrevivência', resumo.sobrevivencia, `${resumo.sobrevivenciaTrend} nos últimos 30 dias`);
    updateCardValue('Peso Médio', resumo.pesoMedio, `Crescimento de ${resumo.pesoMedioTrend} na última semana`);
    updateCardValue('Densidade', resumo.densidade, `${resumo.densidadeTrend}`);
}

// Atualizar valor de um card específico
function updateCardValue(title, value, label) {
    const cards = document.querySelectorAll('.dashboard-card');
    
    cards.forEach(card => {
        const cardTitle = card.querySelector('.dashboard-card-title');
        if (cardTitle && cardTitle.textContent === title) {
            const valueEl = card.querySelector('.dashboard-card-value');
            const labelEl = card.querySelector('.dashboard-card-label');
            
            if (valueEl) valueEl.textContent = value;
            if (labelEl) labelEl.textContent = label;
        }
    });
}

// Inicializar gráfico de parâmetros
function initParametrosChart() {
    const ctx = document.getElementById('parametros-chart');
    if (!ctx) return;
    
    // Dados simulados para o gráfico
    const data = {
        labels: ['01/05', '02/05', '03/05', '04/05', '05/05', '06/05', '07/05'],
        datasets: [{
            label: 'Oxigênio (mg/L)',
            data: [5.7, 5.9, 5.5, 5.6, 5.8, 5.7, 5.9],
            borderColor: '#0056b3',
            backgroundColor: 'rgba(0, 86, 179, 0.1)',
            tension: 0.4,
            fill: true
        }]
    };
    
    // Configurações do gráfico
    const config = {
        type: 'line',
        data: data,
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
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    };
    
    // Criar gráfico
    window.parametrosChart = new Chart(ctx, config);
}

// Atualizar gráfico de parâmetros
function updateParametroChart(parametro) {
    if (!window.parametrosChart) return;
    
    // Dados simulados para diferentes parâmetros
    const parametrosData = {
        'oxigenio': {
            label: 'Oxigênio (mg/L)',
            data: [5.7, 5.9, 5.5, 5.6, 5.8, 5.7, 5.9],
            color: '#0056b3'
        },
        'temperatura': {
            label: 'Temperatura (°C)',
            data: [28.4, 28.6, 29.0, 28.7, 28.5, 28.3, 28.6],
            color: '#dc3545'
        },
        'ph': {
            label: 'pH',
            data: [7.6, 7.8, 7.7, 7.9, 7.8, 7.7, 7.8],
            color: '#28a745'
        },
        'salinidade': {
            label: 'Salinidade (ppt)',
            data: [12, 11, 11, 12, 12, 13, 12],
            color: '#6610f2'
        },
        'transparencia': {
            label: 'Transparência (cm)',
            data: [35, 36, 33, 34, 35, 37, 36],
            color: '#fd7e14'
        },
        'amonia': {
            label: 'Amônia (mg/L)',
            data: [0.02, 0.02, 0.03, 0.03, 0.02, 0.02, 0.01],
            color: '#20c997'
        }
    };
    
    const paramData = parametrosData[parametro] || parametrosData['oxigenio'];
    
    // Atualizar dados do gráfico
    window.parametrosChart.data.datasets[0].label = paramData.label;
    window.parametrosChart.data.datasets[0].data = paramData.data;
    window.parametrosChart.data.datasets[0].borderColor = paramData.color;
    window.parametrosChart.data.datasets[0].backgroundColor = `${paramData.color}20`;
    
    // Atualizar gráfico
    window.parametrosChart.update();
}

// Inicializar gráfico de custos
function initCustoChart() {
    const ctx = document.getElementById('custo-chart');
    if (!ctx) return;
    
    // Dados simulados para o gráfico
    const data = {
        labels: ['Ração', 'Insumos', 'Energia', 'Mão de obra', 'Outros'],
        datasets: [{
            label: 'Custos (R$)',
            data: [25000, 12000, 8000, 15000, 5000],
            backgroundColor: [
                '#0056b3',
                '#28a745',
                '#ffc107',
                '#dc3545',
                '#6c757d'
            ],
            borderWidth: 0
        }]
    };
    
    // Configurações do gráfico
    const config = {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: R$ ${value.toLocaleString()} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '70%'
        }
    };
    
    // Criar gráfico
    window.custoChart = new Chart(ctx, config);
}

// Atualizar gráfico de custos
function updateCustoChart(type) {
    if (!window.custoChart) return;
    
    // Dados simulados para diferentes tipos de custos
    const custosData = {
        'total': {
            labels: ['Ração', 'Insumos', 'Energia', 'Mão de obra', 'Outros'],
            data: [25000, 12000, 8000, 15000, 5000]
        },
        'mensal': {
            labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio'],
            data: [12000, 15000, 18000, 14000, 16000]
        },
        'kg': {
            labels: ['Ração', 'Insumos', 'Energia', 'Mão de obra', 'Outros'],
            data: [5.9, 2.8, 1.9, 3.5, 1.2]
        }
    };
    
    const custoData = custosData[type] || custosData['total'];
    
    // Atualizar dados do gráfico
    window.custoChart.data.labels = custoData.labels;
    window.custoChart.data.datasets[0].data = custoData.data;
    
    // Atualizar gráfico
    window.custoChart.update();
    
    // Atualizar título da seção
    const custoTitle = document.querySelector('#custos-section .section-title');
    if (custoTitle) {
        if (type === 'total') {
            custoTitle.textContent = 'Custos Totais do Ciclo';
        } else if (type === 'mensal') {
            custoTitle.textContent = 'Custos Mensais do Ciclo';
        } else if (type === 'kg') {
            custoTitle.textContent = 'Custos por Kg Produzido';
        }
    }
}

// Inicializar gráfico de biometria
function initBiometriaChart() {
    const ctx = document.getElementById('biometria-chart');
    if (!ctx) return;
    
    // Dados simulados para o gráfico
    const data = {
        labels: ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Semana 6', 'Semana 7', 'Semana 8', 'Semana 9', 'Semana 10', 'Semana 11', 'Semana 12', 'Semana 13', 'Semana 14', 'Semana 15', 'Semana 16'],
        datasets: [{
            label: 'Peso Médio (g)',
            data: [0.5, 1.2, 2.1, 3.0, 3.8, 4.7, 5.5, 6.4, 7.2, 8.1, 8.9, 9.8, 10.6, 11.5, 12.0, 12.5],
            borderColor: '#0056b3',
            backgroundColor: 'rgba(0, 86, 179, 0.1)',
            tension: 0.4,
            fill: true,
            yAxisID: 'y'
        }, {
            label: 'Sobrevivência (%)',
            data: [100, 98, 96, 95, 94, 93, 92, 91, 90, 89, 89, 88, 88, 87, 87, 87],
            borderColor: '#28a745',
            backgroundColor: 'transparent',
            tension: 0.4,
            borderDash: [5, 5],
            yAxisID: 'y1'
        }]
    };
    
    // Configurações do gráfico
    const config = {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Peso Médio (g)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Sobrevivência (%)'
                    },
                    min: 80,
                    max: 100,
                    grid: {
                        drawOnChartArea: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    };
    
    // Criar gráfico
    window.biometriaChart = new Chart(ctx, config);
}

// Atualizar gráfico de biometria
function updateBiometriaChart() {
    // Implementação futura para atualizar dados de biometria
    // baseado no ciclo selecionado
}
