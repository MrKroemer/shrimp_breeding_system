/**
 * Script para gerenciar os parâmetros de qualidade de água
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando parâmetros de qualidade de água...');
    
    // Selecionar todos os botões de parâmetros
    const paramButtons = document.querySelectorAll('.water-param-btn');
    console.log('Botões de parâmetros encontrados:', paramButtons.length);
    
    // Adicionar eventos de clique e hover
    paramButtons.forEach(button => {
        // Adicionar evento de clique
        button.addEventListener('click', function() {
            console.log('Botão de parâmetro clicado:', this);
            showParameterDetails(this);
        });
        
        // Adicionar efeito de hover mais suave
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Configurar o fechamento do modal de detalhes
    const closeButtons = document.querySelectorAll('.param-modal-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = document.getElementById('param-detail-modal');
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    // Configurar o fechamento do modal de histórico completo
    const historyCloseButtons = document.querySelectorAll('#param-history-modal .modal-close, #param-history-modal .modal-close-btn');
    historyCloseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = document.getElementById('param-history-modal');
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    // Configurar o botão "Ver histórico completo"
    const viewHistoryBtn = document.querySelector('.param-detail-actions .btn-primary');
    if (viewHistoryBtn) {
        viewHistoryBtn.addEventListener('click', function() {
            showFullHistory();
        });
    }
    
    // Configurar o seletor de período no modal de histórico
    const periodSelector = document.getElementById('history-period-selector');
    if (periodSelector) {
        periodSelector.addEventListener('change', function() {
            const days = parseInt(this.value);
            if (!isNaN(days)) {
                renderFullHistoryChart(days);
                updateHistoryTable(days);
            }
        });
    }
    
    // Configurar botões de exportação e impressão
    const exportBtn = document.getElementById('export-history-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            exportHistoryData();
        });
    }
    
    const printBtn = document.getElementById('print-history-btn');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            window.print();
        });
    }
    
    // Atualizar valores de parâmetros periodicamente (simulação)
    setInterval(updateRandomParameter, 5000);
});

/**
 * Exibe detalhes do parâmetro quando o botão é clicado
 */
function showParameterDetails(button) {
    console.log('Exibindo detalhes do parâmetro');
    
    // Obter informações do parâmetro
    let paramName, paramValue, paramStatus;
    
    // Verificar qual tipo de botão foi clicado (diferentes estruturas HTML)
    if (button.classList.contains('water-param-btn')) {
        // Botões no formato do ciclo-dashboard.html
        paramName = button.querySelector('.param-name').textContent;
        paramValue = button.querySelector('.param-value').textContent;
        paramStatus = button.querySelector('.param-status').classList.contains('good') ? 'Bom' : 
                     button.querySelector('.param-status').classList.contains('warning') ? 'Atenção' : 'Crítico';
    } else {
        // Formato alternativo
        paramName = button.getAttribute('data-param') || 'Parâmetro';
        paramValue = button.querySelector('.param-value') ? button.querySelector('.param-value').textContent : '0';
        paramStatus = 'Bom';
    }
    
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
    
    // Atualizar o modal existente com os detalhes do parâmetro
    const modal = document.getElementById('param-detail-modal');
    if (!modal) {
        console.error('Modal não encontrado!');
        return;
    }
    
    console.log('Modal encontrado:', modal);
    
    // Remover classes de cores anteriores
    modal.classList.remove('modal-oxygen', 'modal-ph', 'modal-temperature', 'modal-salinity', 'modal-ammonia', 'modal-nitrite');
    
    // Determinar o tipo de parâmetro e adicionar classe de cor
    let paramType = '';
    if (button.classList.contains('oxygen-btn')) {
        modal.classList.add('modal-oxygen');
        paramType = 'oxygen';
    } else if (button.classList.contains('ph-btn')) {
        modal.classList.add('modal-ph');
        paramType = 'ph';
    } else if (button.classList.contains('temperature-btn')) {
        modal.classList.add('modal-temperature');
        paramType = 'temperature';
    } else if (button.classList.contains('salinity-btn')) {
        modal.classList.add('modal-salinity');
        paramType = 'salinity';
    } else if (button.classList.contains('ammonia-btn')) {
        modal.classList.add('modal-ammonia');
        paramType = 'ammonia';
    } else if (button.classList.contains('nitrite-btn')) {
        modal.classList.add('modal-nitrite');
        paramType = 'nitrite';
    }
    
    // Armazenar o tipo de parâmetro e nome para uso posterior
    window.currentParamType = paramType;
    window.currentParamName = paramName;
    window.currentParamUnit = paramValue.replace(/[0-9.]/g, '').trim();
    
    // Atualizar conteúdo do modal
    const titleElement = document.getElementById('param-detail-title');
    if (titleElement) titleElement.textContent = paramName;
    
    const valueElement = document.getElementById('param-detail-value');
    if (valueElement) valueElement.textContent = paramValue;
    
    // Atualizar status
    const statusElement = document.getElementById('param-detail-status');
    if (statusElement) {
        statusElement.className = 'param-detail-status ' + statusColorClass;
        statusElement.textContent = paramStatus;
    }
    
    // Atualizar informações adicionais
    const lastUpdateElement = document.getElementById('param-last-update');
    if (lastUpdateElement) lastUpdateElement.textContent = 'Hoje, ' + formatTime(new Date());
    
    const averageElement = document.getElementById('param-average');
    if (averageElement) averageElement.textContent = getAverageValue(paramValue);
    
    const trendElement = document.getElementById('param-trend');
    if (trendElement) trendElement.innerHTML = getTrend();
    
    // Exibir o modal
    modal.style.display = 'flex';
    
    // Renderizar o gráfico com um atraso maior para garantir que o DOM esteja pronto
    setTimeout(() => {
        renderHistoryChart();
    }, 300);
    
    // Configurar o botão "Ver histórico completo"
    const viewHistoryBtn = modal.querySelector('.param-detail-actions .btn-primary');
    if (viewHistoryBtn) {
        // Remover event listeners anteriores para evitar duplicação
        const newViewHistoryBtn = viewHistoryBtn.cloneNode(true);
        viewHistoryBtn.parentNode.replaceChild(newViewHistoryBtn, viewHistoryBtn);
        
        // Adicionar novo event listener
        newViewHistoryBtn.addEventListener('click', function() {
            showFullHistory();
        });
    }
}

/**
 * Renderiza o gráfico de histórico do parâmetro
 */
function renderHistoryChart() {
    console.log('Renderizando gráfico de histórico...');
    
    // Garantir que o modal esteja visível antes de renderizar o gráfico
    const modal = document.getElementById('param-detail-modal');
    if (!modal) {
        console.error('Modal não encontrado!');
        return;
    }
    
    if (modal.style.display !== 'flex') {
        console.log('Modal não está visível, ajustando...');
        modal.style.display = 'flex';
    }
    
    // Pequeno atraso para garantir que o DOM esteja pronto
    setTimeout(function() {
        const ctx = document.getElementById('param-history-chart');
        if (!ctx) {
            console.error('Canvas para gráfico não encontrado!');
            return;
        }
        
        console.log('Canvas encontrado:', ctx);
        
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
    }, 300); // Aumentado o tempo de espera para 300ms
}

/**
 * Calcula a média com base no valor atual (simulação)
 */
function getAverageValue(currentValue) {
    // Extrair o valor numérico
    const numValue = parseFloat(currentValue);
    
    // Gerar uma pequena variação
    const variation = (Math.random() * 0.4 - 0.2); // Variação de -0.2 a +0.2
    
    // Formatar o resultado
    const result = (numValue + variation).toFixed(2);
    
    // Adicionar a unidade apropriada
    if (currentValue.includes('mg/L')) {
        return `${result} mg/L`;
    } else if (currentValue.includes('°C')) {
        return `${result} °C`;
    } else if (currentValue.includes('ppt')) {
        return `${result} ppt`;
    } else {
        return result;
    }
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
 * Formata a data no formato DD/MM/YYYY
 */
function formatDate(date) {
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

/**
 * Exibe o modal de histórico completo
 */
function showFullHistory() {
    console.log('Exibindo histórico completo do parâmetro:', window.currentParamName);
    
    // Verificar se temos as informações necessárias
    if (!window.currentParamName || !window.currentParamType) {
        console.error('Informações do parâmetro não disponíveis');
        return;
    }
    
    // Atualizar o título do modal
    const historyTitle = document.getElementById('param-history-title');
    if (historyTitle) {
        historyTitle.textContent = `Histórico Completo - ${window.currentParamName}`;
    }
    
    // Obter o modal de histórico
    const historyModal = document.getElementById('param-history-modal');
    if (!historyModal) {
        console.error('Modal de histórico não encontrado');
        return;
    }
    
    // Adicionar classe de cor com base no tipo de parâmetro
    historyModal.classList.remove('modal-oxygen', 'modal-ph', 'modal-temperature', 'modal-salinity', 'modal-ammonia', 'modal-nitrite');
    historyModal.classList.add(`modal-${window.currentParamType}`);
    
    // Resetar o seletor de período para 30 dias
    const periodSelector = document.getElementById('history-period-selector');
    if (periodSelector) {
        periodSelector.value = '30';
    }
    
    // Renderizar o gráfico e a tabela para 30 dias (padrão)
    renderFullHistoryChart(30);
    updateHistoryTable(30);
    
    // Exibir o modal
    historyModal.style.display = 'block';
    
    // Adicionar event listener para fechar o modal quando clicar fora dele
    window.addEventListener('click', function(event) {
        if (event.target === historyModal) {
            historyModal.style.display = 'none';
        }
    });
}

/**
 * Renderiza o gráfico de histórico completo
 */
function renderFullHistoryChart(days) {
    console.log(`Renderizando gráfico de histórico para ${days} dias`);
    
    // Obter o canvas para o gráfico
    const ctx = document.getElementById('full-history-chart');
    if (!ctx) {
        console.error('Canvas para gráfico de histórico não encontrado');
        return;
    }
    
    // Destruir gráfico anterior se existir
    if (window.fullHistoryChart) {
        window.fullHistoryChart.destroy();
    }
    
    // Obter data atual
    const today = new Date();
    
    // Gerar dados para o período especificado
    const data = [];
    const labels = [];
    const minValues = [];
    const maxValues = [];
    
    // Definir limites com base no tipo de parâmetro
    let minLimit, maxLimit, warningLow, warningHigh;
    
    switch (window.currentParamType) {
        case 'oxygen':
            minLimit = 4.0;
            maxLimit = 7.0;
            warningLow = 5.0;
            warningHigh = 6.5;
            break;
        case 'ph':
            minLimit = 6.5;
            maxLimit = 9.0;
            warningLow = 7.0;
            warningHigh = 8.5;
            break;
        case 'temperature':
            minLimit = 22;
            maxLimit = 33;
            warningLow = 25;
            warningHigh = 30;
            break;
        case 'salinity':
            minLimit = 0;
            maxLimit = 35;
            warningLow = 10;
            warningHigh = 25;
            break;
        case 'ammonia':
            minLimit = 0;
            maxLimit = 1.0;
            warningLow = 0.1;
            warningHigh = 0.5;
            break;
        case 'nitrite':
            minLimit = 0;
            maxLimit = 1.0;
            warningLow = 0.1;
            warningHigh = 0.3;
            break;
        default:
            minLimit = 0;
            maxLimit = 10;
            warningLow = 3;
            warningHigh = 7;
    }
    
    // Gerar dados aleatórios para o período
    let sum = 0;
    let count = 0;
    let min = Number.MAX_VALUE;
    let max = Number.MIN_VALUE;
    
    for (let i = days - 1; i >= 0; i--) {
        const date = new Date(today);
        date.setDate(date.getDate() - i);
        
        // Gerar valor aleatório com base no tipo de parâmetro
        const baseValue = (window.currentParamType === 'oxygen') ? 5.5 :
                         (window.currentParamType === 'ph') ? 7.5 :
                         (window.currentParamType === 'temperature') ? 28 :
                         (window.currentParamType === 'salinity') ? 15 :
                         (window.currentParamType === 'ammonia') ? 0.2 :
                         (window.currentParamType === 'nitrite') ? 0.1 : 5;
        
        // Adicionar variação aleatória
        const variation = (Math.random() - 0.5) * (maxLimit - minLimit) * 0.1;
        let value = baseValue + variation;
        
        // Garantir que o valor esteja dentro dos limites
        value = Math.max(minLimit, Math.min(maxLimit, value));
        value = parseFloat(value.toFixed(2));
        
        // Adicionar valor ao gráfico
        data.push(value);
        labels.push(formatDate(date));
        
        // Adicionar valores para min/max
        const minValue = value - Math.random() * 0.5;
        const maxValue = value + Math.random() * 0.5;
        minValues.push(parseFloat(minValue.toFixed(2)));
        maxValues.push(parseFloat(maxValue.toFixed(2)));
        
        // Atualizar estatísticas
        sum += value;
        count++;
        min = Math.min(min, value);
        max = Math.max(max, value);
    }
    
    // Calcular média e desvio padrão
    const avg = sum / count;
    
    // Calcular desvio padrão
    let sumSquaredDiff = 0;
    for (let i = 0; i < data.length; i++) {
        sumSquaredDiff += Math.pow(data[i] - avg, 2);
    }
    const stdDev = Math.sqrt(sumSquaredDiff / count);
    
    // Atualizar estatísticas no modal
    document.getElementById('history-average').textContent = `${avg.toFixed(2)} ${window.currentParamUnit}`;
    document.getElementById('history-min').textContent = `${min.toFixed(2)} ${window.currentParamUnit}`;
    document.getElementById('history-max').textContent = `${max.toFixed(2)} ${window.currentParamUnit}`;
    document.getElementById('history-std').textContent = `${stdDev.toFixed(2)} ${window.currentParamUnit}`;
    
    // Criar o gráfico
    window.fullHistoryChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: `${window.currentParamName} (Média)`,
                    data: data,
                    borderColor: getColorForParam(window.currentParamType),
                    backgroundColor: getColorForParam(window.currentParamType, 0.1),
                    borderWidth: 2,
                    tension: 0.3,
                    pointRadius: 3,
                    pointHoverRadius: 5
                },
                {
                    label: 'Mínimo',
                    data: minValues,
                    borderColor: 'rgba(150, 150, 150, 0.5)',
                    backgroundColor: 'rgba(0, 0, 0, 0)',
                    borderWidth: 1,
                    borderDash: [5, 5],
                    tension: 0.3,
                    pointRadius: 0
                },
                {
                    label: 'Máximo',
                    data: maxValues,
                    borderColor: 'rgba(150, 150, 150, 0.5)',
                    backgroundColor: 'rgba(0, 0, 0, 0)',
                    borderWidth: 1,
                    borderDash: [5, 5],
                    tension: 0.3,
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false
                },
                legend: {
                    position: 'top',
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: false,
                    suggestedMin: minLimit,
                    suggestedMax: maxLimit,
                    grid: {
                        color: function(context) {
                            if (context.tick.value === warningLow || context.tick.value === warningHigh) {
                                return 'rgba(255, 193, 7, 0.5)';
                            }
                            return 'rgba(0, 0, 0, 0.1)';
                        },
                        lineWidth: function(context) {
                            if (context.tick.value === warningLow || context.tick.value === warningHigh) {
                                return 2;
                            }
                            return 1;
                        }
                    }
                }
            }
        }
    });
}

/**
 * Atualiza a tabela de histórico
 */
function updateHistoryTable(days) {
    console.log(`Atualizando tabela de histórico para ${days} dias`);
    
    // Obter a tabela
    const tbody = document.querySelector('#param-history-table tbody');
    if (!tbody) {
        console.error('Tabela de histórico não encontrada');
        return;
    }
    
    // Limpar a tabela
    tbody.innerHTML = '';
    
    // Obter data atual
    const today = new Date();
    
    // Gerar dados para o período especificado
    for (let i = 0; i < days; i++) {
        const date = new Date(today);
        date.setDate(date.getDate() - (days - 1 - i));
        
        // Gerar 1-3 registros por dia
        const numEntries = Math.floor(Math.random() * 3) + 1;
        
        for (let j = 0; j < numEntries; j++) {
            // Gerar horário aleatório
            const hours = Math.floor(Math.random() * 24);
            const minutes = Math.floor(Math.random() * 60);
            date.setHours(hours, minutes);
            
            // Gerar valor aleatório com base no tipo de parâmetro
            const baseValue = (window.currentParamType === 'oxygen') ? 5.5 :
                             (window.currentParamType === 'ph') ? 7.5 :
                             (window.currentParamType === 'temperature') ? 28 :
                             (window.currentParamType === 'salinity') ? 15 :
                             (window.currentParamType === 'ammonia') ? 0.2 :
                             (window.currentParamType === 'nitrite') ? 0.1 : 5;
            
            // Adicionar variação aleatória
            const variation = (Math.random() - 0.5) * 2;
            let value = baseValue + variation;
            value = parseFloat(value.toFixed(2));
            
            // Determinar status
            let status, statusClass;
            if (window.currentParamType === 'oxygen') {
                if (value < 5.0) {
                    status = 'Crítico';
                    statusClass = 'status-critical';
                } else if (value > 6.5) {
                    status = 'Atenção';
                    statusClass = 'status-warning';
                } else {
                    status = 'Bom';
                    statusClass = 'status-good';
                }
            } else if (window.currentParamType === 'ph') {
                if (value < 7.0 || value > 8.5) {
                    status = 'Atenção';
                    statusClass = 'status-warning';
                } else {
                    status = 'Bom';
                    statusClass = 'status-good';
                }
            } else {
                // Lógica simplificada para outros parâmetros
                const random = Math.random();
                if (random < 0.7) {
                    status = 'Bom';
                    statusClass = 'status-good';
                } else if (random < 0.9) {
                    status = 'Atenção';
                    statusClass = 'status-warning';
                } else {
                    status = 'Crítico';
                    statusClass = 'status-critical';
                }
            }
            
            // Gerar observações aleatórias
            let obs = '';
            if (statusClass === 'status-critical') {
                obs = 'Verificar equipamentos e realizar ações corretivas imediatamente.';
            } else if (statusClass === 'status-warning') {
                obs = 'Monitorar de perto nas próximas horas.';
            } else if (Math.random() < 0.3) {
                obs = 'Leitura de rotina.';
            }
            
            // Criar linha da tabela
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${formatDate(date)}</td>
                <td>${formatTime(date)}</td>
                <td>${value} ${window.currentParamUnit}</td>
                <td><span class="${statusClass}">${status}</span></td>
                <td>${obs}</td>
            `;
            
            tbody.appendChild(tr);
        }
    }
    
    // Ordenar a tabela por data/hora (mais recente primeiro)
    const rows = Array.from(tbody.querySelectorAll('tr'));
    rows.sort((a, b) => {
        const dateA = a.cells[0].textContent + ' ' + a.cells[1].textContent;
        const dateB = b.cells[0].textContent + ' ' + b.cells[1].textContent;
        return dateB.localeCompare(dateA);
    });
    
    // Limpar e readicionar as linhas ordenadas
    tbody.innerHTML = '';
    rows.forEach(row => tbody.appendChild(row));
}

/**
 * Exporta os dados de histórico para CSV
 */
function exportHistoryData() {
    console.log('Exportando dados de histórico');
    
    // Obter a tabela
    const table = document.getElementById('param-history-table');
    if (!table) {
        console.error('Tabela de histórico não encontrada');
        return;
    }
    
    // Criar cabeçalho CSV
    let csv = 'Data,Hora,Valor,Status,Observações\n';
    
    // Adicionar linhas da tabela
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const data = row.cells[0].textContent;
        const hora = row.cells[1].textContent;
        const valor = row.cells[2].textContent;
        const status = row.cells[3].textContent;
        const obs = row.cells[4].textContent;
        
        csv += `"${data}","${hora}","${valor}","${status}","${obs}"\n`;
    });
    
    // Criar blob e link para download
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    
    // Criar link de download
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', `historico_${window.currentParamType}_${formatDate(new Date()).replace(/\//g, '-')}.csv`);
    link.style.display = 'none';
    
    // Adicionar ao documento e clicar
    document.body.appendChild(link);
    link.click();
    
    // Limpar
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}

/**
 * Retorna a cor para o tipo de parâmetro
 */
function getColorForParam(paramType, alpha = 1) {
    const colors = {
        'oxygen': `rgba(0, 86, 179, ${alpha})`,
        'ph': `rgba(40, 167, 69, ${alpha})`,
        'temperature': `rgba(220, 53, 69, ${alpha})`,
        'salinity': `rgba(23, 162, 184, ${alpha})`,
        'ammonia': `rgba(102, 16, 242, ${alpha})`,
        'nitrite': `rgba(253, 126, 20, ${alpha})`
    };
    
    return colors[paramType] || `rgba(108, 117, 125, ${alpha})`;
}

/**
 * Atualiza um parâmetro aleatório com um novo valor (simulação)
 */
function updateRandomParameter() {
    const paramButtons = document.querySelectorAll('.water-param-btn');
    if (paramButtons.length === 0) return;
    
    // Selecionar um botão aleatório
    const randomIndex = Math.floor(Math.random() * paramButtons.length);
    const button = paramButtons[randomIndex];
    
    // Obter o valor atual
    const valueElement = button.querySelector('.param-value');
    const currentValue = valueElement.textContent;
    
    // Extrair o valor numérico e a unidade
    let numValue, unit;
    
    if (currentValue.includes('mg/L')) {
        [numValue, unit] = [parseFloat(currentValue), 'mg/L'];
    } else if (currentValue.includes('°C')) {
        [numValue, unit] = [parseFloat(currentValue), '°C'];
    } else if (currentValue.includes('ppt')) {
        [numValue, unit] = [parseFloat(currentValue), 'ppt'];
    } else {
        [numValue, unit] = [parseFloat(currentValue), ''];
    }
    
    // Gerar uma pequena variação
    const variation = (Math.random() * 0.4 - 0.2); // Variação de -0.2 a +0.2
    const newValue = Math.max(0, numValue + variation).toFixed(2);
    
    // Atualizar o valor
    valueElement.textContent = `${newValue} ${unit}`;
    
    // Adicionar classe de animação
    button.classList.add('param-updated');
    
    // Remover classe após a animação
    setTimeout(() => {
        button.classList.remove('param-updated');
    }, 1000);
    
    // Atualizar o status com base no novo valor
    updateParameterStatus(button, newValue, unit);
}

/**
 * Atualiza o status do parâmetro com base no valor
 */
function updateParameterStatus(button, value, unit) {
    const statusElement = button.querySelector('.param-status');
    const paramName = button.querySelector('.param-name').textContent.toLowerCase();
    
    // Remover classes atuais
    statusElement.classList.remove('good', 'warning', 'danger');
    
    // Definir limites para cada parâmetro
    let status;
    
    switch (paramName) {
        case 'oxigênio':
            if (value >= 5) status = 'good';
            else if (value >= 3) status = 'warning';
            else status = 'danger';
            break;
            
        case 'ph':
            if (value >= 6.8 && value <= 8.0) status = 'good';
            else if ((value >= 6.0 && value < 6.8) || (value > 8.0 && value <= 8.5)) status = 'warning';
            else status = 'danger';
            break;
            
        case 'temperatura':
            if (value >= 26 && value <= 30) status = 'good';
            else if ((value >= 24 && value < 26) || (value > 30 && value <= 32)) status = 'warning';
            else status = 'danger';
            break;
            
        case 'salinidade':
            if (value >= 10 && value <= 15) status = 'good';
            else if ((value >= 5 && value < 10) || (value > 15 && value <= 20)) status = 'warning';
            else status = 'danger';
            break;
            
        case 'amônia':
            if (value <= 0.1) status = 'good';
            else if (value <= 0.3) status = 'warning';
            else status = 'danger';
            break;
            
        case 'nitrito':
            if (value <= 0.05) status = 'good';
            else if (value <= 0.2) status = 'warning';
            else status = 'danger';
            break;
            
        default:
            status = 'good';
    }
    
    // Adicionar a classe apropriada
    statusElement.classList.add(status);
    
    // Atualizar a animação de pulso se o status for de alerta ou perigo
    if (status === 'warning' || status === 'danger') {
        button.classList.add('animate-pulse');
    } else {
        button.classList.remove('animate-pulse');
    }
}
