// Integração real com API RESTful para Coleta de Parâmetros
// URLs baseadas em padrões Laravel REST (ajuste conforme necessário)
// Integração com rotas web Laravel (não RESTful)
// Todas as requisições usam GET/POST e form-data; respostas podem ser HTML ou JSON

let setores = [];
let parametros = [];
let coletas = [];

function showMessage(message, type = 'success') {
    let msgDiv = document.getElementById('msg-feedback');
    if (!msgDiv) {
        msgDiv = document.createElement('div');
        msgDiv.id = 'msg-feedback';
        msgDiv.style.position = 'fixed';
        msgDiv.style.top = '20px';
        msgDiv.style.right = '20px';
        msgDiv.style.zIndex = '2000';
        msgDiv.style.padding = '1rem 2rem';
        msgDiv.style.borderRadius = '6px';
        msgDiv.style.color = '#fff';
        msgDiv.style.fontWeight = 'bold';
        document.body.appendChild(msgDiv);
    }
    msgDiv.style.background = type === 'success' ? '#28a745' : '#dc3545';
    msgDiv.innerText = message;
    msgDiv.style.display = 'block';
    setTimeout(() => { msgDiv.style.display = 'none'; }, 2500);
}

function setLoading(loading) {
    let loader = document.getElementById('loading-feedback');
    if (!loader) {
        loader = document.createElement('div');
        loader.id = 'loading-feedback';
        loader.style.position = 'fixed';
        loader.style.top = '0';
        loader.style.left = '0';
        loader.style.width = '100vw';
        loader.style.height = '100vh';
        loader.style.background = 'rgba(255,255,255,0.55)';
        loader.style.display = 'flex';
        loader.style.alignItems = 'center';
        loader.style.justifyContent = 'center';
        loader.style.zIndex = '3000';
        loader.innerHTML = '<div style="background:#fff;padding:2rem 3rem;border-radius:10px;box-shadow:0 2px 16px rgba(0,0,0,0.15);font-size:1.2rem;">Carregando...</div>';
        document.body.appendChild(loader);
    }
    loader.style.display = loading ? 'flex' : 'none';
}

// Busca setores via rota web (ajustar se necessário)
async function fetchSetores() {
    setLoading(true);
    try {
        // Exemplo: buscar setores em uma rota web que retorna JSON
        // Ajuste a URL conforme necessário, ou adapte para HTML se não houver JSON
        const res = await fetch('/setores?ajax=1');
        if (res.headers.get('content-type').includes('json')) {
            setores = await res.json();
        } else {
            // Se vier HTML, parsear para extrair setores (exemplo básico)
            const html = await res.text();
            setores = extractSetoresFromHTML(html);
        }
    } catch (err) {
        showMessage('Erro ao carregar setores', 'error');
        setores = [];
    } finally {
        setLoading(false);
    }
}

// Busca parâmetros via rota web
async function fetchParametros() {
    setLoading(true);
    try {
        const res = await fetch('/coleta_parametros_tipos?ajax=1');
        if (res.headers.get('content-type').includes('json')) {
            parametros = await res.json();
        } else {
            const html = await res.text();
            parametros = extractParametrosFromHTML(html);
        }
    } catch (err) {
        showMessage('Erro ao carregar parâmetros', 'error');
        parametros = [];
    } finally {
        setLoading(false);
    }
}

// Funções utilitárias para extrair dados de HTML (ajuste conforme o HTML retornado)
function extractSetoresFromHTML(html) {
    // Exemplo: parsear HTML e extrair setores de um <select> ou tabela
    // Retorne array: [{id, nome}]
    // ---
    // Ajuste conforme a estrutura real do HTML
    const div = document.createElement('div');
    div.innerHTML = html;
    const options = div.querySelectorAll('option');
    let arr = [];
    options.forEach(opt => {
        if (opt.value && opt.value !== '') {
            arr.push({ id: opt.value, nome: opt.textContent });
        }
    });
    return arr;
}

function extractParametrosFromHTML(html) {
    // Exemplo: parsear HTML e extrair parâmetros de um <select> ou tabela
    const div = document.createElement('div');
    div.innerHTML = html;
    const options = div.querySelectorAll('option');
    let arr = [];
    options.forEach(opt => {
        if (opt.value && opt.value !== '') {
            // Exemplo: "O2 - (Oxigênio Dissolvido)"
            let [sigla, descricao] = opt.textContent.split(' - (');
            descricao = descricao ? descricao.replace(')', '') : '';
            arr.push({ id: opt.value, sigla: sigla.trim(), descricao: descricao.trim() });
        }
    });
    return arr;
}


function renderSelect(selectId, items, valueKey, labelKey, extra = null) {
    const select = document.getElementById(selectId);
    select.innerHTML = '<option value="">Selecione</option>';
    items.forEach(item => {
        let label = item[labelKey];
        if (extra && item[extra]) label += ' - ' + item[extra];
        select.innerHTML += `<option value="${item[valueKey]}">${label}</option>`;
    });
}

// Busca coletas via rota web (listagem ou busca)
async function fetchColetas(filters = {}) {
    setLoading(true);
    try {
        let url = '/coleta_parametros_new';
        let options = { method: 'GET' };
        let isSearch = false;
        // Se houver filtros, faz busca via POST
        if (filters && Object.keys(filters).length > 0) {
            url = '/coleta_parametros_new/search';
            const formData = new FormData();
            Object.entries(filters).forEach(([k, v]) => { if (v) formData.append(k, v); });
            options = { method: 'POST', body: formData };
            isSearch = true;
        }
        const res = await fetch(url, options);
        if (res.headers.get('content-type').includes('json')) {
            coletas = await res.json();
        } else {
            // Se vier HTML, extrair dados da tabela
            const html = await res.text();
            coletas = extractColetasFromHTML(html);
        }
    } catch (err) {
        showMessage('Erro ao carregar coletas', 'error');
        coletas = [];
    } finally {
        setLoading(false);
        renderTable();
    }
}

// Extrai dados de coletas de uma tabela HTML (ajuste conforme estrutura real)
function extractColetasFromHTML(html) {
    const div = document.createElement('div');
    div.innerHTML = html;
    const rows = div.querySelectorAll('table tbody tr');
    let arr = [];
    rows.forEach(tr => {
        const tds = tr.querySelectorAll('td');
        if (tds.length >= 5) {
            arr.push({
                id: tds[0].textContent.trim(),
                data_coleta: tds[1].textContent.trim(),
                setor_nome: tds[2].textContent.trim(),
                parametro_nome: tds[3].textContent.trim(),
                // IDs reais para editar/excluir podem ser extraídos de atributos data-* ou links
            });
        }
    });
    return arr;
}


// Renderiza a tabela de coletas na tela
function renderTable() {
    const tbody = document.querySelector('#coletas-table tbody');
    tbody.innerHTML = '';
    coletas.forEach(coleta => {
        // Se vier só nomes, exibe-os; se vier IDs, busca nomes nos arrays
        let setorNome = coleta.setor_nome || (setores.find(s => s.id == coleta.setor_id)?.nome) || '';
        let parametroNome = coleta.parametro_nome || (parametros.find(p => p.id == coleta.parametro_id) ?
            parametros.find(p => p.id == coleta.parametro_id).sigla + ' - ' + parametros.find(p => p.id == coleta.parametro_id).descricao : '');
        tbody.innerHTML += `
            <tr>
                <td>${coleta.id}</td>
                <td>${coleta.data_coleta || ''}</td>
                <td>${setorNome}</td>
                <td>${parametroNome}</td>
                <td>
                    <button class="btn-edit" data-id="${coleta.id}">Editar</button>
                    <button class="btn-delete" data-id="${coleta.id}">Excluir</button>
                </td>
            </tr>
        `;
    });
}


function openForm(editId = null) {
    document.getElementById('coleta-form-section').classList.remove('hidden');
    document.getElementById('form-title').innerText = editId ? 'Editar Coleta de Parâmetros' : 'Nova Coleta de Parâmetros';
    if (editId) {
        const coleta = coletas.find(c => c.id == editId);
        document.getElementById('form_data_coleta').value = coleta.data_coleta;
        document.getElementById('form_setor_id').value = coleta.setor_id;
        document.getElementById('form_parametro_id').value = coleta.parametro_id;
        document.getElementById('coleta-form').dataset.editId = editId;
    } else {
        document.getElementById('coleta-form').reset();
        document.getElementById('coleta-form').dataset.editId = '';
    }
}

function closeForm() {
    document.getElementById('coleta-form-section').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', async () => {
    await fetchSetores();
    await fetchParametros();
    renderSelect('setor_id', setores, 'id', 'nome');
    renderSelect('parametro_id', parametros, 'id', 'sigla', 'descricao');
    renderSelect('form_setor_id', setores, 'id', 'nome');
    renderSelect('form_parametro_id', parametros, 'id', 'sigla', 'descricao');
    await fetchColetas();

    document.getElementById('btn-add-coleta').addEventListener('click', () => openForm());
    document.getElementById('btn-cancel').addEventListener('click', closeForm);

    document.getElementById('coleta-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const editId = this.dataset.editId;
        const data_coleta = document.getElementById('form_data_coleta').value;
        const setor_id = document.getElementById('form_setor_id').value;
        const parametro_id = document.getElementById('form_parametro_id').value;
        // Validação básica
        if (!data_coleta || !setor_id || !parametro_id) {
            showMessage('Preencha todos os campos obrigatórios', 'error');
            return;
        }
        setLoading(true);
        try {
            let url, formData, res;
            formData = new FormData();
            formData.append('data_coleta', data_coleta);
            formData.append('setor_id', setor_id);
            formData.append('parametro_id', parametro_id);
            if (editId) {
                url = `/coleta_parametros_new/${editId}/update`;
            } else {
                url = '/coleta_parametros_new/store';
            }
            res = await fetch(url, { method: 'POST', body: formData });
            if (!res.ok) throw new Error('Erro ao salvar coleta');
            showMessage('Coleta salva com sucesso', 'success');
            await fetchColetas();
            closeForm();
        } catch (err) {
            showMessage(err.message || 'Erro ao salvar coleta', 'error');
        } finally {
            setLoading(false);
        }
    });


    document.querySelector('#coletas-table tbody').addEventListener('click', async function(e) {
        if (e.target.classList.contains('btn-edit')) {
            // Carrega dados da coleta para edição
            const id = parseInt(e.target.dataset.id);
            setLoading(true);
            try {
                const res = await fetch(`/coleta_parametros_new/${id}/edit`);
                if (res.headers.get('content-type').includes('json')) {
                    const coleta = await res.json();
                    document.getElementById('form_data_coleta').value = coleta.data_coleta;
                    document.getElementById('form_setor_id').value = coleta.setor_id;
                    document.getElementById('form_parametro_id').value = coleta.parametro_id;
                } else {
                    // Se vier HTML, parsear para extrair valores dos campos
                    const html = await res.text();
                    const div = document.createElement('div');
                    div.innerHTML = html;
                    document.getElementById('form_data_coleta').value = div.querySelector('input[name="data_coleta"]').value;
                    document.getElementById('form_setor_id').value = div.querySelector('select[name="setor_id"]').value;
                    document.getElementById('form_parametro_id').value = div.querySelector('select[name="parametro_id"], select[name="coletas_parametros_tipos_id"]').value;
                }
                document.getElementById('coleta-form').dataset.editId = id;
                openForm(id);
            } catch (err) {
                showMessage('Erro ao carregar coleta para edição', 'error');
            } finally {
                setLoading(false);
            }
        } else if (e.target.classList.contains('btn-delete')) {
            if (!confirm('Deseja realmente excluir esta coleta?')) return;
            setLoading(true);
            try {
                const id = parseInt(e.target.dataset.id);
                const res = await fetch(`/coleta_parametros_new/${id}/remove`, { method: 'GET' });
                if (!res.ok) throw new Error('Erro ao excluir coleta');
                showMessage('Coleta excluída com sucesso', 'success');
                await fetchColetas();
            } catch (err) {
                showMessage(err.message || 'Erro ao excluir coleta', 'error');
            } finally {
                setLoading(false);
            }
        }
    });


    document.getElementById('search-coletas-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const data_coleta = document.getElementById('data_coleta').value;
        const setor_id = document.getElementById('setor_id').value;
        const parametro_id = document.getElementById('parametro_id').value;
        await fetchColetas({ data_coleta, setor_id, parametro_id });
    });
});

