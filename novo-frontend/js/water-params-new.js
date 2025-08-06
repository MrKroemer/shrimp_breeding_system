/**
 * Script para gerenciar os parâmetros de qualidade de água
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando parâmetros de qualidade de água (nova versão)...');
    
    // Selecionar todos os botões de parâmetros
    const paramButtons = document.querySelectorAll('.water-param-btn');
    console.log('Botões de parâmetros encontrados:', paramButtons.length);
    
    // Adicionar eventos de clique e hover
    paramButtons.forEach(button => {
        // Adicionar evento de clique
        button.addEventListener('click', function() {
            console.log('Botão de parâmetro clicado:', this);
            openParameterModal(this);
        });
        
        // Adicionar efeito de hover mais suave
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Configurar o fechamento do modal
    const closeButtons = document.querySelectorAll('.param-modal-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            closeParameterModal();
        });
    });
    
    // Fechar modal ao clicar fora do conteúdo
    const modal = document.getElementById('param-detail-modal');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeParameterModal();
            }
        });
    }
    
    // Atualizar valores de parâmetros periodicamente (simulação)
    setInterval(updateRandomParameter, 5000);
});

/**
 * Abre o modal com os detalhes do parâmetro
 */
function openParameterModal(button) {
    // Obter informações do parâmetro
    const paramName = button.querySelector('.param-name').textContent;
    const paramValue = button.querySelector('.param-value').textContent;
    const paramStatus = button.querySelector('.param-status').classList.contains('good') ? 'Bom' : 
                      button.querySelector('.param-status').classList.contains('warning') ? 'Atenção' : 'Crítico';
    
    console.log('Parâmetro:', paramName, 'Valor:', paramValue, 'Status:', paramStatus);
    
    // Determinar a classe de cor com base no status
    let statusColorClass = '';
    if (paramStatus === 'Bom') {
        statusColorClass = 'status-good';
    } else if (paramStatus === 'Atenção') {
        statusColorClass = 'status-warning';
    } else {
        statusColorClass = 'status-critical';
    }
    
    // Atualizar o modal com os detalhes do parâmetro
    const modal = document.getElementById('param-detail-modal');
    if (!modal) {
        console.error('Modal não encontrado!');
        return;
    }
    
    // Atualizar conteúdo do modal
    document.getElementById('param-detail-title').textContent = paramName;
    document.getElementById('param-detail-value').textContent = paramValue;
    
    // Atualizar status
    const statusElement = document.getElementById('param-detail-status');
    statusElement.className = 'param-detail-status ' + statusColorClass;
    statusElement.textContent = paramStatus;
    
    // Atualizar informações adicionais
    document.getElementById('param-last-update').textContent = 'Hoje, ' + formatTime(new Date());
    document.getElementById('param-average').textContent = getAverageValue(paramValue);
    document.getElementById('param-trend').innerHTML = getTrend();
    
    // Exibir o modal
    modal.style.display = 'flex';
    
    // Renderizar o gráfico após um pequeno atraso
    setTimeout(function() {
        renderParameterChart(paramName);
    }, 300);
}

/**
 * Fecha o modal de detalhes do parâmetro
 */
function closeParameterModal() {
    const modal = document.getElementById('param-detail-modal');
    if (modal) {
        modal.style.display = 'none';
        
        // Destruir o gráfico ao fechar o modal
        if (window.paramHistoryChart) {
            window.paramHistoryChart.destroy();
            window.paramHistoryChart = null;
        }
    }
}

/**
 * Renderiza o gráfico de histórico do parâmetro
 */
function renderParameterChart(paramName) {
    console.log('Renderizando gráfico para:', paramName);
    
    const ctx = document.getElementById('param-history-chart');
    if (!ctx) {
        console.error('Canvas para gráfico não encontrado!');
        return;
    }
    
    // Destruir gráfico anterior se existir
    if (window.paramHistoryChart) {
        window.paramHistoryChart.destroy();
    }
    
    // Obter data atual
    const today = new Date();
    
    // Gerar dados para os últimos 30 dias
    const data = [];
    const labels = [];
    
    for (let i = 29; i >= 0; i--) {
        const date = new Date(today);
        date.setDate(today.getDate() - i);
        
        // Formatar a data como dia/mês
        const day = date.getDate();
        const month = date.getMonth() + 1;
        labels.push(`${day}/${month}`);
        
        // Gerar valor aleatório com tendência
        const baseValue = 5.5;
        const trend = i / 10; // Cria uma tendência ascendente ao longo do tempo
        const random = (Math.random() * 0.8 - 0.4); // Variação aleatória
        data.push(baseValue + trend + random);
    }
    
    // Criar e salvar a referência do gráfico para poder destruí-lo depois
    window.paramHistoryChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Valor',
                data: data,
                borderColor: '#0056b3',
                backgroundColor: 'rgba(0, 86, 179, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        title: function(tooltipItems) {
                            return 'Dia ' + tooltipItems[0].label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxTicksLimit: 10,
                        maxRotation: 0
                    },
                    title: {
                        display: true,
                        text: 'Últimos 30 dias'
                    }
                },
                y: {
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'Valor'
                    }
                }
            },
            animation: {
                duration: 1000
            }
        }
    });
    
    console.log('Gráfico renderizado com sucesso!');
}

/**
 * Calcula a média com base no valor atual (simulação)
 */
function getAverageValue(currentValue) {
    // Extrair o valor numérico
    let value = parseFloat(currentValue);
    if (isNaN(value)) {
        // Tentar extrair número de uma string com unidade
        const match = currentValue.match(/(\d+(\.\d+)?)/);
        if (match) {
            value = parseFloat(match[0]);
        } else {
            return '0';
        }
    }
    
    // Gerar uma média ligeiramente diferente do valor atual
    const variation = (Math.random() * 0.4 - 0.2); // Variação de até 20% para mais ou para menos
    const average = value + variation;
    
    // Determinar a unidade a partir do valor atual
    let unit = '';
    if (currentValue.includes('mg/L')) {
        unit = ' mg/L';
    } else if (currentValue.includes('°C')) {
        unit = ' °C';
    } else if (currentValue.includes('ppt')) {
        unit = ' ppt';
    }
    
    return average.toFixed(1) + unit;
}

/**
 * Gera uma tendência aleatória (simulação)
 */
function getTrend() {
    const trends = ['Estável', 'Em alta', 'Em queda'];
    const icons = ['fas fa-equals', 'fas fa-arrow-up', 'fas fa-arrow-down'];
    const colors = ['#6c757d', '#28a745', '#dc3545'];
    
    const index = Math.floor(Math.random() * 3);
    
    return `<span style="color: ${colors[index]}"><i class="${icons[index]}"></i> ${trends[index]}</span>`;
}

/**
 * Formata a hora no formato HH:MM
 */
function formatTime(date) {
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}`;
}

/**
 * Atualiza um parâmetro aleatório com um novo valor (simulação)
 */
function updateRandomParameter() {
    const buttons = document.querySelectorAll('.water-param-btn');
    if (buttons.length === 0) return;
    
    // Selecionar um botão aleatório
    const randomIndex = Math.floor(Math.random() * buttons.length);
    const button = buttons[randomIndex];
    
    // Obter o tipo de parâmetro
    let paramType = '';
    if (button.classList.contains('oxygen-btn')) {
        paramType = 'oxygen';
    } else if (button.classList.contains('ph-btn')) {
        paramType = 'ph';
    } else if (button.classList.contains('temperature-btn')) {
        paramType = 'temperature';
    } else if (button.classList.contains('salinity-btn')) {
        paramType = 'salinity';
    } else if (button.classList.contains('ammonia-btn')) {
        paramType = 'ammonia';
    } else if (button.classList.contains('nitrite-btn')) {
        paramType = 'nitrite';
    }
    
    // Gerar um novo valor com base no tipo de parâmetro
    let newValue, unit;
    switch (paramType) {
        case 'oxygen':
            newValue = (5 + Math.random() * 1.5).toFixed(1);
            unit = 'mg/L';
            break;
        case 'ph':
            newValue = (7 + Math.random() * 1).toFixed(1);
            unit = '';
            break;
        case 'temperature':
            newValue = (26 + Math.random() * 4).toFixed(1);
            unit = '°C';
            break;
        case 'salinity':
            newValue = Math.floor(10 + Math.random() * 5);
            unit = 'ppt';
            break;
        case 'ammonia':
            newValue = (0.01 + Math.random() * 0.09).toFixed(2);
            unit = 'mg/L';
            break;
        case 'nitrite':
            newValue = (0.005 + Math.random() * 0.02).toFixed(3);
            unit = 'mg/L';
            break;
        default:
            newValue = (Math.random() * 10).toFixed(1);
            unit = '';
    }
    
    // Atualizar o valor no botão
    const valueElement = button.querySelector('.param-value');
    if (valueElement) {
        valueElement.textContent = newValue + (unit ? ' ' + unit : '');
    }
    
    // Atualizar o status com base no novo valor
    updateParameterStatus(button, newValue, paramType);
}

/**
 * Atualiza o status do parâmetro com base no valor
 */
function updateParameterStatus(button, value, paramType) {
    const statusElement = button.querySelector('.param-status');
    if (!statusElement) return;
    
    // Remover classes de status anteriores
    statusElement.classList.remove('good', 'warning', 'critical');
    
    // Determinar o novo status com base no tipo de parâmetro e valor
    let newStatus = 'good';
    
    switch (paramType) {
        case 'oxygen':
            if (value < 4.5) newStatus = 'critical';
            else if (value < 5.0) newStatus = 'warning';
            break;
        case 'ph':
            if (value < 6.5 || value > 8.5) newStatus = 'critical';
            else if (value < 7.0 || value > 8.0) newStatus = 'warning';
            break;
        case 'temperature':
            if (value < 25 || value > 32) newStatus = 'critical';
            else if (value < 26 || value > 30) newStatus = 'warning';
            break;
        case 'salinity':
            if (value < 5 || value > 20) newStatus = 'critical';
            else if (value < 8 || value > 15) newStatus = 'warning';
            break;
        case 'ammonia':
            if (value > 0.1) newStatus = 'critical';
            else if (value > 0.05) newStatus = 'warning';
            break;
        case 'nitrite':
            if (value > 0.02) newStatus = 'critical';
            else if (value > 0.01) newStatus = 'warning';
            break;
    }
    
    // Aplicar a nova classe de status
    statusElement.classList.add(newStatus);
    
    // Adicionar ou remover a animação de pulso com base na criticidade
    if (newStatus === 'critical') {
        if (!button.classList.contains('animate-pulse')) {
            button.classList.add('animate-pulse');
        }
    } else if (newStatus === 'warning') {
        if (Math.random() > 0.5 && !button.classList.contains('animate-pulse')) {
            button.classList.add('animate-pulse');
        } else {
            button.classList.remove('animate-pulse');
        }
    } else {
        button.classList.remove('animate-pulse');
    }
}
