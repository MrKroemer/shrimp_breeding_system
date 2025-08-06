/**
 * JavaScript para gerenciar o chat flutuante
 */

document.addEventListener('DOMContentLoaded', function() {
    initFloatingChat();
});

// Dados simulados dos usuários
const users = {
    'maria': {
        id: 'maria',
        name: 'Maria Santos',
        avatar: 'img/user1.jpg',
        status: 'online',
        title: 'Técnica de Qualidade da Água',
        lastSeen: 'Agora'
    },
    'carlos': {
        id: 'carlos',
        name: 'Carlos Oliveira',
        avatar: 'img/user2.jpg',
        status: 'offline',
        title: 'Biólogo',
        lastSeen: '10 minutos atrás'
    },
    'ana': {
        id: 'ana',
        name: 'Ana Silva',
        avatar: 'img/user3.jpg',
        status: 'away',
        title: 'Engenheira de Produção',
        lastSeen: '30 minutos atrás'
    },
    'pedro': {
        id: 'pedro',
        name: 'Pedro Costa',
        avatar: 'img/user4.jpg',
        status: 'busy',
        title: 'Técnico de Manutenção',
        lastSeen: '1 hora atrás'
    }
};

// Histórico de mensagens simulado
const chatHistory = {
    'maria': [
        { sender: 'maria', message: 'Bom dia João! Como estão os parâmetros do tanque V03 hoje?', time: '09:45' },
        { sender: 'me', message: 'Bom dia Maria! Os parâmetros estão dentro do esperado. O oxigênio está em 5.8 mg/L e o pH em 7.2.', time: '09:48' },
        { sender: 'maria', message: 'Ótimo! E a temperatura?', time: '09:50' },
        { sender: 'me', message: 'A temperatura está em 28.5°C, bem estável nas últimas 24h.', time: '09:52' },
        { sender: 'maria', message: 'Vamos verificar os parâmetros do tanque V03 pessoalmente após o almoço? Precisamos coletar algumas amostras para análise laboratorial.', time: '10:30' }
    ],
    'carlos': [
        { sender: 'carlos', message: 'João, o relatório de biometria está concluído.', time: '09:10' },
        { sender: 'carlos', message: 'Preciso da sua aprovação para enviar ao departamento técnico.', time: '09:15' }
    ],
    'ana': [
        { sender: 'ana', message: 'Precisamos discutir o arraçoamento do ciclo #42 com urgência.', time: 'Ontem' },
        { sender: 'me', message: 'Claro, Ana. Podemos conversar hoje à tarde?', time: 'Ontem' }
    ],
    'pedro': [
        { sender: 'pedro', message: 'Manutenção programada para amanhã nos equipamentos do setor 3.', time: 'Ontem' }
    ]
};

// Variáveis globais
let activeChats = [];
let chatToggleBtn;
let floatingChatContainer;

function initFloatingChat() {
    // Elementos do DOM
    chatToggleBtn = document.getElementById('chat-toggle-btn');
    floatingChatContainer = document.getElementById('floating-chat-container');
    
    if (!chatToggleBtn || !floatingChatContainer) return;
    
    // Evento de clique no botão de chat
    chatToggleBtn.addEventListener('click', toggleChatList);
    
    // Verificar se há janelas de chat para restaurar da sessão anterior
    const savedChats = sessionStorage.getItem('activeChats');
    if (savedChats) {
        try {
            const chatIds = JSON.parse(savedChats);
            chatIds.forEach(userId => {
                openChat(userId);
            });
        } catch (e) {
            console.error('Erro ao restaurar chats:', e);
        }
    }
}

// Função para abrir o chat com um usuário específico
function openChat(userId) {
    // Verificar se o usuário existe
    if (!users[userId]) {
        console.error(`Usuário ${userId} não encontrado`);
        return;
    }
    
    // Verificar se o chat já está aberto
    if (activeChats.includes(userId)) {
        // Focar no chat existente
        const chatWindow = document.getElementById(`chat-window-${userId}`);
        if (chatWindow) {
            // Se estiver minimizado, maximizar
            if (chatWindow.classList.contains('minimized')) {
                chatWindow.classList.remove('minimized');
            }
            // Focar no input
            const chatInput = chatWindow.querySelector('.chat-input-field');
            if (chatInput) chatInput.focus();
        }
        return;
    }
    
    // Adicionar à lista de chats ativos
    activeChats.push(userId);
    saveActiveChats();
    
    // Criar janela de chat
    const chatWindow = document.createElement('div');
    chatWindow.className = 'chat-window';
    chatWindow.id = `chat-window-${userId}`;
    
    // Cabeçalho do chat
    const user = users[userId];
    chatWindow.innerHTML = `
        <div class="chat-window-header">
            <div class="chat-window-title">
                <img src="${user.avatar}" alt="${user.name}" class="chat-window-avatar">
                <div class="chat-window-name">
                    ${user.name}
                    <span class="status-badge status-${user.status}"></span>
                </div>
            </div>
            <div class="chat-window-actions">
                <button class="chat-window-btn minimize-btn">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="chat-window-btn close-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="chat-window-messages" id="chat-messages-${userId}"></div>
        <div class="chat-window-input">
            <input type="text" placeholder="Digite sua mensagem..." class="chat-input-field">
            <button class="chat-send-btn">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    `;
    
    // Adicionar ao container
    floatingChatContainer.insertBefore(chatWindow, floatingChatContainer.firstChild);
    
    // Carregar histórico de mensagens
    loadChatHistory(userId);
    
    // Eventos
    const minimizeBtn = chatWindow.querySelector('.minimize-btn');
    const closeBtn = chatWindow.querySelector('.chat-window-btn.close-btn');
    const chatHeader = chatWindow.querySelector('.chat-window-header');
    const chatInput = chatWindow.querySelector('.chat-input-field');
    const sendBtn = chatWindow.querySelector('.chat-send-btn');
    
    minimizeBtn.addEventListener('click', () => {
        chatWindow.classList.toggle('minimized');
    });
    
    closeBtn.addEventListener('click', () => {
        closeChat(userId);
    });
    
    chatHeader.addEventListener('click', (e) => {
        if (!e.target.closest('.chat-window-btn')) {
            chatWindow.classList.toggle('minimized');
        }
    });
    
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage(userId);
        }
    });
    
    sendBtn.addEventListener('click', () => {
        sendMessage(userId);
    });
    
    // Focar no input
    chatInput.focus();
}

// Função para carregar o histórico de mensagens
function loadChatHistory(userId) {
    const messagesContainer = document.getElementById(`chat-messages-${userId}`);
    if (!messagesContainer) return;
    
    // Limpar mensagens existentes
    messagesContainer.innerHTML = '';
    
    // Adicionar histórico
    if (chatHistory[userId]) {
        chatHistory[userId].forEach(msg => {
            const messageType = msg.sender === 'me' ? 'sent' : 'received';
            const messageElement = createMessageElement(msg.message, messageType, msg.time);
            messagesContainer.appendChild(messageElement);
        });
    }
    
    // Rolar para o final
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Função para criar elemento de mensagem
function createMessageElement(text, type, time) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `chat-message ${type}`;
    
    const bubbleDiv = document.createElement('div');
    bubbleDiv.className = 'chat-message-bubble';
    bubbleDiv.textContent = text;
    
    const timeDiv = document.createElement('div');
    timeDiv.className = 'chat-message-time';
    timeDiv.textContent = time || getCurrentTime();
    
    messageDiv.appendChild(bubbleDiv);
    messageDiv.appendChild(timeDiv);
    
    return messageDiv;
}

// Função para enviar mensagem
function sendMessage(userId) {
    const chatWindow = document.getElementById(`chat-window-${userId}`);
    if (!chatWindow) return;
    
    const chatInput = chatWindow.querySelector('.chat-input-field');
    const messagesContainer = document.getElementById(`chat-messages-${userId}`);
    
    const messageText = chatInput.value.trim();
    if (messageText === '') return;
    
    // Adicionar mensagem ao chat
    const messageElement = createMessageElement(messageText, 'sent');
    messagesContainer.appendChild(messageElement);
    
    // Limpar input
    chatInput.value = '';
    
    // Rolar para o final
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    
    // Adicionar ao histórico
    if (!chatHistory[userId]) {
        chatHistory[userId] = [];
    }
    
    chatHistory[userId].push({
        sender: 'me',
        message: messageText,
        time: getCurrentTime()
    });
    
    // Simular resposta após 1-3 segundos
    simulateResponse(userId);
}

// Função para simular resposta
function simulateResponse(userId) {
    setTimeout(() => {
        // Respostas possíveis
        const responses = [
            "Ok, entendi!",
            "Vou verificar isso agora.",
            "Precisamos discutir isso na reunião de amanhã.",
            "Já atualizei os dados no sistema.",
            "Os parâmetros estão dentro do esperado."
        ];
        
        // Escolher resposta aleatória
        const randomResponse = responses[Math.floor(Math.random() * responses.length)];
        
        // Adicionar ao histórico
        chatHistory[userId].push({
            sender: userId,
            message: randomResponse,
            time: getCurrentTime()
        });
        
        // Adicionar mensagem ao chat
        const messagesContainer = document.getElementById(`chat-messages-${userId}`);
        if (messagesContainer) {
            const messageElement = createMessageElement(randomResponse, 'received');
            messagesContainer.appendChild(messageElement);
            
            // Rolar para o final
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        // Se o chat estiver minimizado, mostrar notificação
        const chatWindow = document.getElementById(`chat-window-${userId}`);
        if (chatWindow && chatWindow.classList.contains('minimized')) {
            // Adicionar indicador de mensagem não lida
            chatWindow.classList.add('has-unread');
            
            // Adicionar notificação
            if (typeof addNotification === 'function') {
                addNotification({
                    userId: userId,
                    chatId: userId,
                    name: users[userId].name,
                    avatar: users[userId].avatar,
                    message: randomResponse,
                    time: getCurrentTime()
                });
            }
        }
    }, Math.floor(Math.random() * 2000) + 1000);
}

// Função para fechar chat
function closeChat(userId) {
    const chatWindow = document.getElementById(`chat-window-${userId}`);
    if (chatWindow) {
        chatWindow.remove();
    }
    
    // Remover da lista de chats ativos
    const index = activeChats.indexOf(userId);
    if (index !== -1) {
        activeChats.splice(index, 1);
        saveActiveChats();
    }
}

// Função para alternar lista de contatos
function toggleChatList() {
    // Verificar se a lista já existe
    let contactsList = document.getElementById('floating-contacts-list');
    
    if (contactsList) {
        // Se existir, remover
        contactsList.remove();
        return;
    }
    
    // Criar lista de contatos
    contactsList = document.createElement('div');
    contactsList.className = 'chat-window contacts-list-window';
    contactsList.id = 'floating-contacts-list';
    
    // Cabeçalho
    contactsList.innerHTML = `
        <div class="chat-window-header">
            <div class="chat-window-title">
                <i class="fas fa-users"></i>
                <div class="chat-window-name">Contatos</div>
            </div>
            <div class="chat-window-actions">
                <button class="chat-window-btn close-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="contacts-search">
            <input type="text" placeholder="Buscar contatos..." class="contacts-search-input">
        </div>
        <div class="contacts-items">
            ${generateContactsList()}
        </div>
    `;
    
    // Adicionar ao container
    floatingChatContainer.insertBefore(contactsList, floatingChatContainer.firstChild);
    
    // Eventos
    const closeBtn = contactsList.querySelector('.close-btn');
    closeBtn.addEventListener('click', () => {
        contactsList.remove();
    });
    
    const contactItems = contactsList.querySelectorAll('.contact-item');
    contactItems.forEach(item => {
        item.addEventListener('click', () => {
            const userId = item.getAttribute('data-user');
            openChat(userId);
            contactsList.remove();
        });
    });
    
    const searchInput = contactsList.querySelector('.contacts-search-input');
    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();
        filterContacts(query);
    });
    
    // Focar na busca
    searchInput.focus();
}

// Função para gerar HTML da lista de contatos
function generateContactsList() {
    let html = '';
    
    Object.values(users).forEach(user => {
        html += `
            <div class="contact-item" data-user="${user.id}">
                <div class="contact-avatar">
                    <img src="${user.avatar}" alt="${user.name}">
                    <span class="status-indicator ${user.status}"></span>
                </div>
                <div class="contact-info">
                    <div class="contact-name">${user.name}</div>
                    <div class="contact-last-message">${user.title}</div>
                </div>
                <div class="contact-meta">
                    <div class="contact-status">${getStatusText(user.status)}</div>
                </div>
            </div>
        `;
    });
    
    return html;
}

// Função para filtrar contatos
function filterContacts(query) {
    const contactItems = document.querySelectorAll('.contact-item');
    
    contactItems.forEach(item => {
        const name = item.querySelector('.contact-name').textContent.toLowerCase();
        const title = item.querySelector('.contact-last-message').textContent.toLowerCase();
        
        if (name.includes(query) || title.includes(query)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// Função para obter texto do status
function getStatusText(status) {
    switch (status) {
        case 'online':
            return 'Online';
        case 'away':
            return 'Ausente';
        case 'busy':
            return 'Ocupado';
        case 'offline':
            return 'Offline';
        default:
            return '';
    }
}

// Função para obter hora atual
function getCurrentTime() {
    const now = new Date();
    return `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;
}

// Função para salvar chats ativos na sessão
function saveActiveChats() {
    sessionStorage.setItem('activeChats', JSON.stringify(activeChats));
}

// Expor função para uso global
window.openFloatingChat = function(userId, chatId) {
    // chatId é ignorado por enquanto, pois estamos usando userId como identificador único
    openChat(userId);
};
