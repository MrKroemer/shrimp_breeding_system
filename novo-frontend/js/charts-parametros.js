// charts-parametros.js
// Lógica para carregar tanques, parâmetros e gerar gráficos

document.addEventListener('DOMContentLoaded', () => {
    carregarTanques();
    carregarParametros();
    document.getElementById('grafico-form').addEventListener('submit', gerarGraficos);
});

// Busca tanques dinamicamente usando a rota de tanques do grupo de rateio
// Ajuste a rota abaixo caso exista uma rota global para tanques no backend
// Busca tanques usando a rota global de tanques
// Prefira JSON, mas parseia HTML se necessário
async function carregarTanques() {
    const select = document.getElementById('tanques');
    select.innerHTML = '<option disabled>Carregando...</option>';
    try {
        const res = await fetch('/tanques?ajax=1');
        let tanques = [];
        if (res.headers.get('content-type') && res.headers.get('content-type').includes('json')) {
            tanques = await res.json();
        } else {
            // Se vier HTML, parseia opções do select ou linhas de tabela
            const html = await res.text();
            const div = document.createElement('div');
            div.innerHTML = html;
            // Tenta encontrar options (caso venha um <select>)
            const options = div.querySelectorAll('option');
            if (options.length > 0) {
                options.forEach(opt => {
                    if (opt.value && opt.value !== '') {
                        tanques.push({ id: opt.value, nome: opt.textContent.trim() });
                    }
                });
            } else {
                // Se vier tabela, tenta pegar linhas
                const rows = div.querySelectorAll('table tbody tr');
                rows.forEach(tr => {
                    const tds = tr.querySelectorAll('td');
                    if (tds.length > 1) {
                        tanques.push({ id: tds[0].textContent.trim(), nome: tds[1].textContent.trim() });
                    }
                });
            }
        }
        select.innerHTML = '';
        tanques.forEach(t => {
            const opt = document.createElement('option');
            opt.value = t.id;
            opt.textContent = t.nome;
            select.appendChild(opt);
        });
    } catch (err) {
        select.innerHTML = '<option disabled>Erro ao carregar tanques</option>';
    }
}



// Busca parâmetros usando a rota global de tipos de parâmetros
// Prefira JSON, mas parseia HTML se necessário
async function carregarParametros() {
    const select = document.getElementById('parametro');
    select.innerHTML = '<option disabled>Carregando...</option>';
    try {
        const res = await fetch('/coleta_parametros_tipos?ajax=1');
        let parametros = [];
        if (res.headers.get('content-type') && res.headers.get('content-type').includes('json')) {
            parametros = await res.json();
        } else {
            // Se vier HTML, parseia opções do select ou linhas de tabela
            const html = await res.text();
            const div = document.createElement('div');
            div.innerHTML = html;
            // Tenta encontrar options (caso venha um <select>)
            const options = div.querySelectorAll('option');
            if (options.length > 0) {
                options.forEach(opt => {
                    if (opt.value && opt.value !== '') {
                        parametros.push({ id: opt.value, nome: opt.textContent.trim() });
                    }
                });
            } else {
                // Se vier tabela, tenta pegar linhas
                const rows = div.querySelectorAll('table tbody tr');
                rows.forEach(tr => {
                    const tds = tr.querySelectorAll('td');
                    if (tds.length > 1) {
                        parametros.push({ id: tds[0].textContent.trim(), nome: tds[1].textContent.trim() });
                    }
                });
            }
        }
        select.innerHTML = '';
        parametros.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.id;
            opt.textContent = p.nome;
            select.appendChild(opt);
        });
    } catch (err) {
        select.innerHTML = '<option disabled>Erro ao carregar parâmetros</option>';
    }
}


function gerarGraficos(e) {
    e.preventDefault();
    const tanques = Array.from(document.getElementById('tanques').selectedOptions).map(o => o.value);
    const parametro = document.getElementById('parametro').value;
    const data_inicial = document.getElementById('data_inicial').value;
    const data_final = document.getElementById('data_final').value;
    // TODO: Substituir por fetch real para obter dados
    renderizarGraficoMock(tanques, parametro, data_inicial, data_final);
}

function renderizarGraficoMock(tanques, parametro, data_inicial, data_final) {
    const area = document.getElementById('graficos-area');
    area.innerHTML = '';
    tanques.forEach(tanque => {
        const canvas = document.createElement('canvas');
        canvas.width = 600;
        canvas.height = 300;
        area.appendChild(canvas);
        // Mock de dados
        const labels = ['01/01','02/01','03/01','04/01','05/01'];
        const dados = labels.map(() => Math.random() * 10 + 5);
        new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: `Tanque ${tanque} - Parâmetro ${parametro}`,
                    data: dados,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0,123,255,0.2)',
                    fill: true
                }]
            },
            options: {
                responsive: false,
                plugins: { legend: { display: true } }
            }
        });
    });
}
