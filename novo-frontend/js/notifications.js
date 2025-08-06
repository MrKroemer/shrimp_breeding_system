/**
 * JavaScript para gerenciar o sistema de notificações
 */

document.addEventListener('DOMContentLoaded', function() {
    initNotifications();
});

function initNotifications() {
    // Elementos do DOM
    const notificationToggle = document.getElementById('notification-toggle');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    const markAllReadBtn = document.querySelector('.mark-all-read');
    const notificationItems = document.querySelectorAll('.notification-item');
    
    // Contador de notificações não lidas
    updateNotificationCount();
    
    // Toggle do dropdown de notificações
    if (notificationToggle) {
        notificationToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationsDropdown.classList.toggle('show');
        });
    }
    
    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(e) {
        if (notificationsDropdown && notificationsDropdown.classList.contains('show')) {
            if (!notificationsDropdown.contains(e.target) && e.target !== notificationToggle) {
                notificationsDropdown.classList.remove('show');
            }
        }
    });
    
    // Marcar todas como lidas
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function() {
            notificationItems.forEach(item => {
                item.classList.remove('unread');
            });
            updateNotificationCount();
        });
    }
    
    // Abrir chat ao clicar em uma notificação
    notificationItems.forEach(item => {
        item.addEventListener('click', function() {
            const userId = this.getAttribute('data-user');
            const chatId = this.getAttribute('data-chat-id');
            
            // Marcar como lida
            this.classList.remove('unread');
            updateNotificationCount();
            
            // Fechar dropdown
            notificationsDropdown.classList.remove('show');
            
            // Abrir chat (será implementado no próximo passo)
            console.log(`Abrir chat com usuário ${userId}, chat ID: ${chatId}`);
            
            // Se o chat flutuante estiver implementado, chamar a função para abri-lo
            if (typeof openFloatingChat === 'function') {
                openFloatingChat(userId, chatId);
            } else {
                // Caso contrário, redirecionar para a página de mensagens
                window.location.href = `messenger.html?user=${userId}`;
            }
        });
    });
    
    // Atualizar contador de notificações
    function updateNotificationCount() {
        const unreadCount = document.querySelectorAll('.notification-item.unread').length;
        const badge = notificationToggle.querySelector('.badge');
        
        if (badge) {
            if (unreadCount > 0) {
                badge.textContent = unreadCount;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    }
}

// Função para adicionar uma nova notificação
function addNotification(data) {
    const notificationsBody = document.querySelector('.notifications-body');
    if (!notificationsBody) return;
    
    // Criar elemento de notificação
    const notificationItem = document.createElement('div');
    notificationItem.className = 'notification-item unread';
    notificationItem.setAttribute('data-user', data.userId);
    notificationItem.setAttribute('data-chat-id', data.chatId);
    
    // Conteúdo da notificação
    notificationItem.innerHTML = `
        <img src="${data.avatar}" alt="${data.name}" class="notification-avatar">
        <div class="notification-content">
            <div class="notification-title">${data.name}</div>
            <div class="notification-message">${data.message}</div>
            <div class="notification-time">${data.time}</div>
        </div>
    `;
    
    // Adicionar ao início da lista
    notificationsBody.insertBefore(notificationItem, notificationsBody.firstChild);
    
    // Atualizar contador
    updateNotificationCount();
    
    // Adicionar evento de clique
    notificationItem.addEventListener('click', function() {
        this.classList.remove('unread');
        updateNotificationCount();
        
        // Fechar dropdown
        document.getElementById('notifications-dropdown').classList.remove('show');
        
        // Abrir chat
        if (typeof openFloatingChat === 'function') {
            openFloatingChat(data.userId, data.chatId);
        } else {
            window.location.href = `messenger.html?user=${data.userId}`;
        }
    });
    
    // Mostrar notificação nativa (se suportado pelo navegador)
    if (Notification.permission === 'granted') {
        new Notification(data.name, {
            body: data.message,
            icon: data.avatar
        });
    }
    
    // Função para atualizar contador (definida localmente para evitar erros)
    function updateNotificationCount() {
        const unreadCount = document.querySelectorAll('.notification-item.unread').length;
        const badge = document.querySelector('.notification-btn .badge');
        
        if (badge) {
            if (unreadCount > 0) {
                badge.textContent = unreadCount;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    }
}
