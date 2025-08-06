/**
 * JavaScript para gerenciar o status do usuário
 */

document.addEventListener('DOMContentLoaded', function() {
    initUserStatus();
});

function initUserStatus() {
    // Elementos do DOM
    const currentStatus = document.querySelector('.current-status');
    const statusDropdown = document.querySelector('.status-dropdown');
    const statusOptions = document.querySelectorAll('.status-option');
    
    if (!currentStatus || !statusDropdown || !statusOptions.length) return;
    
    // Carregar status salvo
    loadUserStatus();
    
    // Toggle do dropdown de status
    currentStatus.addEventListener('click', function(e) {
        e.stopPropagation();
        statusDropdown.classList.toggle('show');
    });
    
    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function() {
        statusDropdown.classList.remove('show');
    });
    
    // Selecionar status
    statusOptions.forEach(option => {
        option.addEventListener('click', function() {
            const status = this.getAttribute('data-status');
            setUserStatus(status);
            statusDropdown.classList.remove('show');
        });
    });
    
    // Verificar inatividade para status "ausente"
    let inactivityTimer;
    const resetInactivityTimer = () => {
        clearTimeout(inactivityTimer);
        
        // Se o usuário estiver online, definir timer para mudar para ausente após 5 minutos
        const currentStatusBadge = currentStatus.querySelector('.status-badge');
        if (currentStatusBadge.classList.contains('status-online')) {
            inactivityTimer = setTimeout(() => {
                setUserStatus('away');
            }, 5 * 60 * 1000); // 5 minutos
        }
    };
    
    // Eventos para resetar o timer de inatividade
    ['mousemove', 'mousedown', 'keypress', 'touchstart', 'scroll'].forEach(event => {
        document.addEventListener(event, resetInactivityTimer);
    });
    
    // Iniciar timer
    resetInactivityTimer();
}

// Função para definir o status do usuário
function setUserStatus(status) {
    const currentStatus = document.querySelector('.current-status');
    const statusBadge = currentStatus.querySelector('.status-badge');
    const statusText = currentStatus.querySelector('.status-text');
    
    // Remover classes de status anteriores
    statusBadge.className = 'status-badge';
    
    // Adicionar nova classe de status
    statusBadge.classList.add(`status-${status}`);
    
    // Atualizar texto
    switch (status) {
        case 'online':
            statusText.textContent = 'Online';
            break;
        case 'away':
            statusText.textContent = 'Ausente';
            break;
        case 'busy':
            statusText.textContent = 'Ocupado';
            break;
        case 'offline':
            statusText.textContent = 'Offline';
            break;
    }
    
    // Salvar status
    localStorage.setItem('userStatus', status);
    
    // Broadcast para outras abas/janelas
    broadcastStatus(status);
    
    // Notificar sistema de chat sobre mudança de status
    updateChatStatus(status);
}

// Função para carregar o status salvo
function loadUserStatus() {
    const savedStatus = localStorage.getItem('userStatus') || 'online';
    setUserStatus(savedStatus);
}

// Função para broadcast do status para outras abas/janelas
function broadcastStatus(status) {
    // Usar localStorage para comunicação entre abas
    localStorage.setItem('statusUpdate', Date.now().toString());
    localStorage.setItem('userStatus', status);
    
    // Ou usar BroadcastChannel API se suportado
    if ('BroadcastChannel' in window) {
        const bc = new BroadcastChannel('user_status');
        bc.postMessage({ status: status });
    }
}

// Função para atualizar o status no sistema de chat
function updateChatStatus(status) {
    // Se o sistema de chat estiver carregado, atualizar o status
    if (typeof window.updateUserStatusInChat === 'function') {
        window.updateUserStatusInChat(status);
    }
}

// Escutar por mudanças de status de outras abas/janelas
window.addEventListener('storage', function(e) {
    if (e.key === 'statusUpdate') {
        loadUserStatus();
    }
});

// Expor função para uso global
window.setUserStatus = setUserStatus;
