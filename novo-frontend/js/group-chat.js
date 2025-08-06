/**
 * JavaScript para gerenciar grupos de chat
 */

document.addEventListener('DOMContentLoaded', function() {
    initGroupChat();
});

// Dados simulados dos grupos
const groups = {
    'equipe-tecnica': {
        id: 'equipe-tecnica',
        name: 'Equipe Técnica',
        avatar: 'img/group1.jpg',
        members: ['maria', 'carlos', 'ana'],
        createdBy: 'me',
        createdAt: '2023-10-15'
    },
    'producao': {
        id: 'producao',
        name: 'Produção',
        avatar: 'img/group2.jpg',
        members: ['ana', 'pedro'],
        createdBy: 'ana',
        createdAt: '2023-09-20'
    }
};

// Histórico de mensagens de grupo simulado
const groupChatHistory = {
    'equipe-tecnica': [
        { sender: 'maria', message: 'Bom dia equipe! Precisamos revisar os parâmetros de todos os tanques hoje.', time: '09:30' },
        { sender: 'carlos', message: 'Vou começar pelo setor 1.', time: '09:32' },
        { sender: 'ana', message: 'Eu cuido do setor 2.', time: '09:33' },
        { sender: 'me', message: 'Ótimo! Eu vou revisar o setor 3 então.', time: '09:35' }
    ],
    'producao': [
        { sender: 'ana', message: 'Relatório de produção do mês está pronto.', time: 'Ontem' },
        { sender: 'pedro', message: 'Vou revisar e enviar para a diretoria.', time: 'Ontem' }
    ]
};

function initGroupChat() {
    // Verificar se o chat flutuante está inicializado
    if (typeof window.openFloatingChat !== 'function') {
        console.error('O chat flutuante precisa ser inicializado primeiro');
        return;
    }
    
    // Adicionar botão de criar grupo à lista de contatos
    document.addEventListener('contactsListCreated', function(e) {
        const contactsList = document.getElementById('floating-contacts-list');
        if (!contactsList) return;
        
        // Adicionar botão de criar grupo
        const contactsHeader = contactsList.querySelector('.chat-window-header .chat-window-title');
        if (contactsHeader) {
            const createGroupBtn = document.createElement('button');
            createGroupBtn.className = 'create-group-btn';
            createGroupBtn.innerHTML = '<i class="fas fa-users-cog"></i>';
            createGroupBtn.title = 'Criar Grupo';
            
            contactsHeader.appendChild(createGroupBtn);
            
            createGroupBtn.addEventListener('click', showCreateGroupModal);
        }
        
        // Adicionar seção de grupos
        const contactsItems = contactsList.querySelector('.contacts-items');
        if (contactsItems) {
            // Adicionar título da seção
            const groupsTitle = document.createElement('div');
            groupsTitle.className = 'contacts-section-title';
            groupsTitle.textContent = 'Grupos';
            
            // Adicionar antes dos contatos
            contactsItems.insertBefore(groupsTitle, contactsItems.firstChild);
            
            // Adicionar grupos
            const groupsSection = document.createElement('div');
            groupsSection.className = 'groups-section';
            
            // Gerar HTML dos grupos
            groupsSection.innerHTML = generateGroupsList();
            
            // Adicionar antes dos contatos
            contactsItems.insertBefore(groupsSection, groupsTitle.nextSibling);
            
            // Adicionar eventos de clique nos grupos
            const groupItems = groupsSection.querySelectorAll('.group-item');
            groupItems.forEach(item => {
                item.addEventListener('click', function() {
                    const groupId = this.getAttribute('data-group');
                    openGroupChat(groupId);
                    contactsList.remove();
                });
            });
        }
    });
    
    // Expor função para uso global
    window.openGroupChat = openGroupChat;
}

// Função para gerar HTML da lista de grupos
function generateGroupsList() {
    let html = '';
    
    Object.values(groups).forEach(group => {
        html += `
            <div class="group-item" data-group="${group.id}">
                <div class="group-avatar">
                    <img src="${group.avatar}" alt="${group.name}">
                </div>
                <div class="group-info">
                    <div class="group-name">${group.name}</div>
                    <div class="group-members">${group.members.length + 1} membros</div>
                </div>
            </div>
        `;
    });
    
    return html;
}

// Função para abrir chat de grupo
function openGroupChat(groupId) {
    // Verificar se o grupo existe
    if (!groups[groupId]) {
        console.error(`Grupo ${groupId} não encontrado`);
        return;
    }
    
    // Verificar se o chat já está aberto
    const chatWindow = document.getElementById(`chat-window-group-${groupId}`);
    if (chatWindow) {
        // Focar no chat existente
        if (chatWindow.classList.contains('minimized')) {
            chatWindow.classList.remove('minimized');
        }
        // Focar no input
        const chatInput = chatWindow.querySelector('.chat-input-field');
        if (chatInput) chatInput.focus();
        return;
    }
    
    // Criar janela de chat
    const floatingChatContainer = document.getElementById('floating-chat-container');
    if (!floatingChatContainer) return;
    
    const newChatWindow = document.createElement('div');
    newChatWindow.className = 'chat-window group-chat-window';
    newChatWindow.id = `chat-window-group-${groupId}`;
    
    // Cabeçalho do chat
    const group = groups[groupId];
    newChatWindow.innerHTML = `
        <div class="chat-window-header">
            <div class="chat-window-title">
                <img src="${group.avatar}" alt="${group.name}" class="chat-window-avatar">
                <div class="chat-window-name">
                    ${group.name}
                    <span class="group-members-count">${group.members.length + 1} membros</span>
                </div>
            </div>
            <div class="chat-window-actions">
                <button class="chat-window-btn group-info-btn" title="Informações do grupo">
                    <i class="fas fa-info-circle"></i>
                </button>
                <button class="chat-window-btn minimize-btn">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="chat-window-btn close-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="chat-window-messages" id="chat-messages-group-${groupId}"></div>
        <div class="chat-window-input">
            <input type="text" placeholder="Digite sua mensagem..." class="chat-input-field">
            <button class="chat-send-btn">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    `;
    
    // Adicionar ao container
    floatingChatContainer.insertBefore(newChatWindow, floatingChatContainer.firstChild);
    
    // Carregar histórico de mensagens
    loadGroupChatHistory(groupId);
    
    // Eventos
    const minimizeBtn = newChatWindow.querySelector('.minimize-btn');
    const closeBtn = newChatWindow.querySelector('.chat-window-btn.close-btn');
    const infoBtn = newChatWindow.querySelector('.group-info-btn');
    const chatHeader = newChatWindow.querySelector('.chat-window-header');
    const chatInput = newChatWindow.querySelector('.chat-input-field');
    const sendBtn = newChatWindow.querySelector('.chat-send-btn');
    
    minimizeBtn.addEventListener('click', () => {
        newChatWindow.classList.toggle('minimized');
    });
    
    closeBtn.addEventListener('click', () => {
        newChatWindow.remove();
    });
    
    infoBtn.addEventListener('click', () => {
        showGroupInfoModal(groupId);
    });
    
    chatHeader.addEventListener('click', (e) => {
        if (!e.target.closest('.chat-window-btn')) {
            newChatWindow.classList.toggle('minimized');
        }
    });
    
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendGroupMessage(groupId);
        }
    });
    
    sendBtn.addEventListener('click', () => {
        sendGroupMessage(groupId);
    });
    
    // Focar no input
    chatInput.focus();
}

// Função para carregar o histórico de mensagens do grupo
function loadGroupChatHistory(groupId) {
    const messagesContainer = document.getElementById(`chat-messages-group-${groupId}`);
    if (!messagesContainer) return;
    
    // Limpar mensagens existentes
    messagesContainer.innerHTML = '';
    
    // Adicionar histórico
    if (groupChatHistory[groupId]) {
        groupChatHistory[groupId].forEach(msg => {
            const messageType = msg.sender === 'me' ? 'sent' : 'received';
            const messageElement = createGroupMessageElement(msg, messageType);
            messagesContainer.appendChild(messageElement);
        });
    }
    
    // Rolar para o final
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Função para criar elemento de mensagem de grupo
function createGroupMessageElement(msg, type) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `chat-message ${type}`;
    
    // Se for mensagem recebida, adicionar avatar e nome do remetente
    if (type === 'received') {
        const sender = users[msg.sender] || { name: msg.sender, avatar: 'img/default-avatar.jpg' };
        
        const avatarDiv = document.createElement('div');
        avatarDiv.className = 'message-avatar';
        avatarDiv.innerHTML = `<img src="${sender.avatar}" alt="${sender.name}">`;
        
        const nameDiv = document.createElement('div');
        nameDiv.className = 'message-sender-name';
        nameDiv.textContent = sender.name;
        
        messageDiv.appendChild(avatarDiv);
        messageDiv.appendChild(nameDiv);
    }
    
    const bubbleDiv = document.createElement('div');
    bubbleDiv.className = 'chat-message-bubble';
    bubbleDiv.textContent = msg.message;
    
    const timeDiv = document.createElement('div');
    timeDiv.className = 'chat-message-time';
    timeDiv.textContent = msg.time || getCurrentTime();
    
    messageDiv.appendChild(bubbleDiv);
    messageDiv.appendChild(timeDiv);
    
    return messageDiv;
}

// Função para enviar mensagem para o grupo
function sendGroupMessage(groupId) {
    const chatWindow = document.getElementById(`chat-window-group-${groupId}`);
    if (!chatWindow) return;
    
    const chatInput = chatWindow.querySelector('.chat-input-field');
    const messagesContainer = document.getElementById(`chat-messages-group-${groupId}`);
    
    const messageText = chatInput.value.trim();
    if (messageText === '') return;
    
    // Adicionar mensagem ao chat
    const messageElement = createGroupMessageElement({ 
        sender: 'me', 
        message: messageText, 
        time: getCurrentTime() 
    }, 'sent');
    messagesContainer.appendChild(messageElement);
    
    // Limpar input
    chatInput.value = '';
    
    // Rolar para o final
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    
    // Adicionar ao histórico
    if (!groupChatHistory[groupId]) {
        groupChatHistory[groupId] = [];
    }
    
    groupChatHistory[groupId].push({
        sender: 'me',
        message: messageText,
        time: getCurrentTime()
    });
    
    // Simular respostas dos membros do grupo após 1-3 segundos
    simulateGroupResponses(groupId);
}

// Função para simular respostas dos membros do grupo
function simulateGroupResponses(groupId) {
    const group = groups[groupId];
    if (!group) return;
    
    // Escolher um membro aleatório para responder
    const randomMemberIndex = Math.floor(Math.random() * group.members.length);
    const randomMember = group.members[randomMemberIndex];
    
    // Respostas possíveis
    const responses = [
        "Entendido!",
        "Vamos discutir isso na reunião de amanhã.",
        "Concordo com a sua abordagem.",
        "Já estou trabalhando nisso.",
        "Podemos revisar isso juntos mais tarde?"
    ];
    
    // Escolher resposta aleatória
    const randomResponse = responses[Math.floor(Math.random() * responses.length)];
    
    // Tempo aleatório para resposta (1-3 segundos)
    setTimeout(() => {
        // Adicionar ao histórico
        groupChatHistory[groupId].push({
            sender: randomMember,
            message: randomResponse,
            time: getCurrentTime()
        });
        
        // Adicionar mensagem ao chat
        const messagesContainer = document.getElementById(`chat-messages-group-${groupId}`);
        if (messagesContainer) {
            const messageElement = createGroupMessageElement({
                sender: randomMember,
                message: randomResponse,
                time: getCurrentTime()
            }, 'received');
            messagesContainer.appendChild(messageElement);
            
            // Rolar para o final
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        // Se o chat estiver minimizado, mostrar notificação
        const chatWindow = document.getElementById(`chat-window-group-${groupId}`);
        if (chatWindow && chatWindow.classList.contains('minimized')) {
            // Adicionar indicador de mensagem não lida
            chatWindow.classList.add('has-unread');
            
            // Adicionar notificação
            if (typeof addNotification === 'function') {
                const sender = users[randomMember] || { name: randomMember, avatar: 'img/default-avatar.jpg' };
                addNotification({
                    userId: randomMember,
                    chatId: `group-${groupId}`,
                    name: `${sender.name} (${group.name})`,
                    avatar: sender.avatar,
                    message: randomResponse,
                    time: getCurrentTime()
                });
            }
        }
    }, Math.floor(Math.random() * 2000) + 1000);
}

// Função para mostrar modal de criação de grupo
function showCreateGroupModal() {
    // Verificar se já existe um modal aberto
    let modal = document.getElementById('create-group-modal');
    if (modal) {
        modal.remove();
    }
    
    // Criar modal
    modal = document.createElement('div');
    modal.className = 'modal';
    modal.id = 'create-group-modal';
    
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>Criar Novo Grupo</h3>
                <button class="modal-close-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="group-name">Nome do Grupo</label>
                    <input type="text" id="group-name" placeholder="Digite o nome do grupo">
                </div>
                <div class="form-group">
                    <label>Selecionar Membros</label>
                    <div class="members-selection">
                        ${generateMembersSelection()}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn cancel-btn">Cancelar</button>
                <button class="btn create-btn">Criar Grupo</button>
            </div>
        </div>
    `;
    
    // Adicionar ao body
    document.body.appendChild(modal);
    
    // Mostrar modal
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
    
    // Eventos
    const closeBtn = modal.querySelector('.modal-close-btn');
    const cancelBtn = modal.querySelector('.cancel-btn');
    const createBtn = modal.querySelector('.create-btn');
    
    closeBtn.addEventListener('click', () => {
        closeModal(modal);
    });
    
    cancelBtn.addEventListener('click', () => {
        closeModal(modal);
    });
    
    createBtn.addEventListener('click', () => {
        createNewGroup(modal);
    });
    
    // Fechar ao clicar fora
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal(modal);
        }
    });
}

// Função para gerar HTML da seleção de membros
function generateMembersSelection() {
    let html = '';
    
    Object.values(users).forEach(user => {
        html += `
            <div class="member-option">
                <input type="checkbox" id="member-${user.id}" value="${user.id}">
                <label for="member-${user.id}">
                    <img src="${user.avatar}" alt="${user.name}" class="member-avatar">
                    <span class="member-name">${user.name}</span>
                </label>
            </div>
        `;
    });
    
    return html;
}

// Função para criar novo grupo
function createNewGroup(modal) {
    const groupNameInput = modal.querySelector('#group-name');
    const memberCheckboxes = modal.querySelectorAll('.member-option input[type="checkbox"]:checked');
    
    const groupName = groupNameInput.value.trim();
    if (groupName === '') {
        alert('Por favor, digite um nome para o grupo');
        return;
    }
    
    if (memberCheckboxes.length === 0) {
        alert('Por favor, selecione pelo menos um membro para o grupo');
        return;
    }
    
    // Coletar IDs dos membros selecionados
    const members = Array.from(memberCheckboxes).map(checkbox => checkbox.value);
    
    // Gerar ID do grupo
    const groupId = 'group-' + Date.now();
    
    // Criar novo grupo
    groups[groupId] = {
        id: groupId,
        name: groupName,
        avatar: 'img/group-default.jpg',
        members: members,
        createdBy: 'me',
        createdAt: new Date().toISOString().split('T')[0]
    };
    
    // Inicializar histórico de mensagens
    groupChatHistory[groupId] = [];
    
    // Fechar modal
    closeModal(modal);
    
    // Abrir chat do novo grupo
    openGroupChat(groupId);
    
    // Adicionar mensagem de sistema
    const welcomeMessage = {
        sender: 'system',
        message: `Grupo "${groupName}" criado por você`,
        time: getCurrentTime()
    };
    
    groupChatHistory[groupId].push(welcomeMessage);
    
    const messagesContainer = document.getElementById(`chat-messages-group-${groupId}`);
    if (messagesContainer) {
        const messageElement = document.createElement('div');
        messageElement.className = 'chat-message system-message';
        messageElement.textContent = welcomeMessage.message;
        messagesContainer.appendChild(messageElement);
    }
}

// Função para mostrar modal de informações do grupo
function showGroupInfoModal(groupId) {
    // Verificar se o grupo existe
    const group = groups[groupId];
    if (!group) return;
    
    // Verificar se já existe um modal aberto
    let modal = document.getElementById('group-info-modal');
    if (modal) {
        modal.remove();
    }
    
    // Criar modal
    modal = document.createElement('div');
    modal.className = 'modal';
    modal.id = 'group-info-modal';
    
    // Gerar lista de membros
    let membersHtml = '';
    group.members.forEach(memberId => {
        const member = users[memberId] || { name: memberId, avatar: 'img/default-avatar.jpg', status: 'offline' };
        membersHtml += `
            <div class="group-member">
                <img src="${member.avatar}" alt="${member.name}" class="member-avatar">
                <div class="member-info">
                    <div class="member-name">${member.name}</div>
                    <div class="member-status">
                        <span class="status-badge status-${member.status}"></span>
                        <span>${getStatusText(member.status)}</span>
                    </div>
                </div>
            </div>
        `;
    });
    
    // Adicionar o próprio usuário como membro
    membersHtml += `
        <div class="group-member">
            <img src="img/profile.jpg" alt="João Silva" class="member-avatar">
            <div class="member-info">
                <div class="member-name">João Silva (você)</div>
                <div class="member-status">
                    <span class="status-badge status-online"></span>
                    <span>Online</span>
                </div>
            </div>
        </div>
    `;
    
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>Informações do Grupo</h3>
                <button class="modal-close-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="group-info-header">
                    <img src="${group.avatar}" alt="${group.name}" class="group-avatar">
                    <div class="group-details">
                        <h4>${group.name}</h4>
                        <p>Criado em ${group.createdAt}</p>
                        <p>${group.members.length + 1} membros</p>
                    </div>
                </div>
                <div class="group-members-list">
                    <h5>Membros</h5>
                    ${membersHtml}
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn leave-group-btn">Sair do Grupo</button>
                <button class="btn add-member-btn">Adicionar Membros</button>
            </div>
        </div>
    `;
    
    // Adicionar ao body
    document.body.appendChild(modal);
    
    // Mostrar modal
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
    
    // Eventos
    const closeBtn = modal.querySelector('.modal-close-btn');
    const leaveBtn = modal.querySelector('.leave-group-btn');
    const addMemberBtn = modal.querySelector('.add-member-btn');
    
    closeBtn.addEventListener('click', () => {
        closeModal(modal);
    });
    
    leaveBtn.addEventListener('click', () => {
        if (confirm(`Tem certeza que deseja sair do grupo "${group.name}"?`)) {
            // Remover grupo
            delete groups[groupId];
            
            // Fechar janela de chat
            const chatWindow = document.getElementById(`chat-window-group-${groupId}`);
            if (chatWindow) {
                chatWindow.remove();
            }
            
            // Fechar modal
            closeModal(modal);
        }
    });
    
    addMemberBtn.addEventListener('click', () => {
        closeModal(modal);
        showAddMembersModal(groupId);
    });
    
    // Fechar ao clicar fora
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal(modal);
        }
    });
}

// Função para fechar modal
function closeModal(modal) {
    modal.classList.remove('show');
    setTimeout(() => {
        modal.remove();
    }, 300);
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
