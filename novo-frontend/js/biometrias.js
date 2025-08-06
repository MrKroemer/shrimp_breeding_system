// JS para renderização dinâmica e exportação da tabela de biometrias
// Supondo um endpoint /api/biometrias que retorna os dados necessários

document.addEventListener('DOMContentLoaded', function() {
    fetchBiometrias();
    document.getElementById('export-excel').addEventListener('click', exportToExcel);
});

function fetchBiometrias() {
    fetch('/api/biometrias')
        .then(response => response.json())
        .then(data => renderBiometriasTable(data))
        .catch(error => {
            document.getElementById('biometrias-table-container').innerHTML = '<p>Erro ao carregar dados.</p>';
        });
}

function renderBiometriasTable(analises) {
    let html = '<table border="1"><thead><tr><th>Viveiro</th>';
    for (let i = 1; i <= 20; i++) {
        html += `<th>${i}ª Semana</th>`;
    }
    html += '<th>Observações</th></tr></thead><tbody>';
    for (const viveiro in analises) {
        html += `<tr><td>${viveiro}</td>`;
        for (let i = 1; i <= 20; i++) {
            html += `<td>${analises[viveiro][i] ? analises[viveiro][i] : ''}</td>`;
        }
        html += `<td>${analises[viveiro]['observacoes'] || ''}</td></tr>`;
    }
    html += '</tbody></table>';
    document.getElementById('biometrias-table-container').innerHTML = html;
}

function exportToExcel() {
    // Exportação simples usando tabela HTML para Excel
    let table = document.querySelector('#biometrias-table-container table');
    if (!table) return;
    let html = table.outerHTML.replace(/ /g, '%20');
    let a = document.createElement('a');
    a.href = 'data:application/vnd.ms-excel,' + html;
    a.download = 'biometrias.xls';
    a.click();
}
