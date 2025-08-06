/**
 * JavaScript para o Messenger - Camanor
 */

document.addEventListener('DOMContentLoaded', function() {
    initMessenger();
});

function initMessenger() {
    // Elementos do DOM
    const contactItems = document.querySelectorAll('.contact-item');
    const newMessageBtn = document.getElementById('new-message-btn');
    const closeModalBtn = document.getElementById('close-modal');
    const newMessageModal = document.getElementById('new-message-modal');
    const closeInfoPanelBtn = document.getElementById('close-info-panel');
    const contactInfoPanel = document.querySelector('.contact-info-panel');
    const chatInput = document.querySelector('.chat-input');
    const sendBtn = document.querySelector('.send-btn');
    const chatMessages = document.querySelector('.chat-messages');
    const recipientSuggestions = document.querySelectorAll('.recipient-suggestion');
    const recipientInput = document.getElementById('recipient');
    const attachButton = document.querySelector('.chat-input-area .btn-icon:first-child');
    
    // Inicializar eventos
    
    // Alternar entre contatos
    contactItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remover classe ativa de todos os contatos
            contactItems.forEach(contact => contact.classList.remove('active'));
            // Adicionar classe ativa ao contato clicado
            this.classList.add('active');
            
            // Aqui você carregaria as mensagens do contato selecionado
            // simulateLoadMessages(this.querySelector('.contact-name').textContent);
            
            // Em telas menores, esconder a lista de contatos e mostrar a área de chat
            if (window.innerWidth <= 768) {
                document.querySelector('.contacts-list').style.display = 'none';
                document.querySelector('.chat-area').style.display = 'flex';
            }
        });
    });
    
    // Abrir modal de nova mensagem
    if (newMessageBtn) {
        newMessageBtn.addEventListener('click', function() {
            newMessageModal.classList.add('show');
        });
    }
    
    // Fechar modal de nova mensagem
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            newMessageModal.classList.remove('show');
        });
    }
    
    // Fechar painel de informações do contato em telas menores
    if (closeInfoPanelBtn) {
        closeInfoPanelBtn.addEventListener('click', function() {
            contactInfoPanel.style.display = 'none';
            document.querySelector('.chat-area').style.flex = '1';
        });
    }
    
    // Enviar mensagem ao pressionar Enter
    if (chatInput) {
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }
    
    // Enviar mensagem ao clicar no botão
    if (sendBtn) {
        sendBtn.addEventListener('click', sendMessage);
    }
    
    // Selecionar destinatário na modal de nova mensagem
    recipientSuggestions.forEach(suggestion => {
        suggestion.addEventListener('click', function() {
            const recipientName = this.querySelector('.recipient-name').textContent;
            recipientInput.value = recipientName;
        });
    });
    
    // Rolar para o final das mensagens
    scrollToBottom();
    
    // Função para enviar mensagem
    function sendMessage() {
        const messageText = chatInput.value.trim();
        const hasAttachments = window.fileAttachments && document.querySelector('.attachment-preview-container').children.length > 0;
        
        if (messageText === '' && !hasAttachments) return;
        
        // Criar elemento de mensagem
        const messageElement = document.createElement('div');
        messageElement.className = 'message sent';
        
        const messageTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        // Conteúdo básico da mensagem
        let messageHTML = `
            <div class="message-content">
                <div class="message-bubble">
                    ${messageText}
                </div>
                <div class="message-info">
                    <span class="message-time">${messageTime}</span>
                    <span class="message-status">
                        <i class="fas fa-check-double"></i>
                    </span>
                </div>
            </div>
        `;
        
        // Processar anexos se existirem
        if (hasAttachments) {
            const sentAttachments = window.fileAttachments.sendAttachments('messenger');
            
            if (sentAttachments.length > 0) {
                let attachmentsHTML = '<div class="message-attachments">';
                
                sentAttachments.forEach(attachment => {
                    if (attachment.type === 'image') {
                        // Anexo de imagem
                        attachmentsHTML += `
                            <div class="message-attachment-image" data-attachment-id="${attachment.id}">
                                <img src="${attachment.url}" alt="${attachment.name}" onclick="openAttachmentViewer('${attachment.url}', '${attachment.name}')">
                            </div>
                        `;
                    } else {
                        // Outros tipos de anexo
                        let iconClass = 'fa-file';
                        
                        if (attachment.type === 'document') {
                            if (['pdf'].includes(attachment.extension)) {
                                iconClass = 'fa-file-pdf';
                            } else if (['doc', 'docx'].includes(attachment.extension)) {
                                iconClass = 'fa-file-word';
                            } else if (['xls', 'xlsx'].includes(attachment.extension)) {
                                iconClass = 'fa-file-excel';
                            } else if (['ppt', 'pptx'].includes(attachment.extension)) {
                                iconClass = 'fa-file-powerpoint';
                            } else if (['txt'].includes(attachment.extension)) {
                                iconClass = 'fa-file-alt';
                            }
                        } else if (attachment.type === 'audio') {
                            iconClass = 'fa-file-audio';
                        } else if (attachment.type === 'video') {
                            iconClass = 'fa-file-video';
                        }
                        
                        attachmentsHTML += `
                            <div class="message-attachment" data-attachment-id="${attachment.id}" onclick="openAttachment('${attachment.url}', '${attachment.name}')">
                                <div class="message-attachment-icon">
                                    <i class="fas ${iconClass}"></i>
                                </div>
                                <div class="message-attachment-info">
                                    <div class="message-attachment-name">${attachment.name}</div>
                                    <div class="message-attachment-size">${formatFileSize(attachment.size)}</div>
                                </div>
                            </div>
                        `;
                    }
                });
                
                attachmentsHTML += '</div>';
                
                // Adicionar HTML de anexos à mensagem
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = messageHTML;
                
                const messageContent = tempDiv.querySelector('.message-content');
                const messageBubble = tempDiv.querySelector('.message-bubble');
                
                // Se não houver texto, remover a bolha vazia
                if (messageText === '') {
                    messageBubble.remove();
                }
                
                // Adicionar anexos ao conteúdo da mensagem
                messageContent.insertAdjacentHTML('beforeend', attachmentsHTML);
                
                // Atualizar o HTML da mensagem
                messageHTML = tempDiv.innerHTML;
            }
        }
        
        // Definir o HTML da mensagem
        messageElement.innerHTML = messageHTML;
        
        // Adicionar à área de chat
        chatMessages.appendChild(messageElement);
        
        // Limpar input
        chatInput.value = '';
        
        // Rolar para o final
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        // Simular resposta após alguns segundos
        simulateResponse();
    }
    
    // Função para formatar o tamanho do arquivo
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Função para simular resposta
    function simulateResponse() {
        // Simular "digitando..."
        const typingIndicator = document.createElement('div');
        typingIndicator.className = 'message received typing-indicator';
        typingIndicator.innerHTML = `
            <div class="message-avatar">
                <img src="img/user1.jpg" alt="Maria Santos">
            </div>
            <div class="message-content">
                <div class="message-bubble">
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                </div>
            </div>
        `;
        
        chatMessages.appendChild(typingIndicator);
        scrollToBottom();
        
        // Tempo aleatório entre 1 e 3 segundos
        const responseTime = Math.floor(Math.random() * 2000) + 1000;
        
        setTimeout(() => {
            // Remover indicador de digitação
            chatMessages.removeChild(typingIndicator);
            
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
            
            // Criar e adicionar mensagem de resposta
            const responseElement = createMessageElement(randomResponse, 'received');
            chatMessages.appendChild(responseElement);
            
            // Rolar para o final
            scrollToBottom();
        }, responseTime);
    }
    
    // Função para criar elemento de mensagem
    function createMessageElement(text, type) {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const timeString = `${hours}:${minutes}`;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}`;
        
        if (type === 'received') {
            const avatarDiv = document.createElement('div');
            avatarDiv.className = 'message-avatar';
            
            const avatarImg = document.createElement('img');
            avatarImg.src = 'img/user1.jpg';
            avatarImg.alt = 'Maria Santos';
            
            avatarDiv.appendChild(avatarImg);
            messageDiv.appendChild(avatarDiv);
        }
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        
        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = 'message-bubble';
        bubbleDiv.textContent = text;
        
        const timeDiv = document.createElement('div');
        timeDiv.className = 'message-time';
        timeDiv.textContent = timeString;
        
        contentDiv.appendChild(bubbleDiv);
        contentDiv.appendChild(timeDiv);
        messageDiv.appendChild(contentDiv);
        
        return messageDiv;
    }
    
    // Função para rolar para o final das mensagens
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Inicializar funcionalidade de anexos se o script estiver carregado
    if (typeof initFileAttachments === 'function') {
        initFileAttachments();
    }
    
    // Adicionar classe para animação de entrada
    document.querySelector('.messenger-container').classList.add('animate-on-load');
}

// Adicionar ao objeto window para acesso global
window.messengerFunctions = {
    toggleInfoPanel() {
        const contactInfoPanel = document.querySelector('.contact-info-panel');
        if (contactInfoPanel) {
            contactInfoPanel.classList.toggle('show');
        }
    }
};

// Funções para visualização de anexos
function openAttachment(url, filename) {
    // Abrir o arquivo em uma nova aba
    window.open(url, '_blank');
}

function openAttachmentViewer(url, filename) {
    // Verificar se já existe um visualizador
    let viewer = document.getElementById('attachment-viewer');
    
    if (!viewer) {
        // Criar o visualizador
        viewer = document.createElement('div');
        viewer.className = 'attachment-viewer-modal';
        viewer.id = 'attachment-viewer';
        
        viewer.innerHTML = `
            <div class="attachment-viewer-content">
                <img src="" alt="" class="attachment-viewer-image">
                <button class="attachment-viewer-close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="attachment-viewer-filename"></div>
                <button class="attachment-viewer-download">
                    <i class="fas fa-download"></i> Baixar
                </button>
            </div>
        `;
        
        document.body.appendChild(viewer);
        
        // Adicionar eventos
        const closeBtn = viewer.querySelector('.attachment-viewer-close');
        const downloadBtn = viewer.querySelector('.attachment-viewer-download');
        
        closeBtn.addEventListener('click', function() {
            viewer.classList.remove('show');
        });
        
        viewer.addEventListener('click', function(e) {
            if (e.target === viewer) {
                viewer.classList.remove('show');
            }
        });
        
        downloadBtn.addEventListener('click', function() {
            const image = viewer.querySelector('.attachment-viewer-image');
            const link = document.createElement('a');
            link.href = image.src;
            link.download = image.getAttribute('data-filename');
            link.click();
        });
    }
    
    // Atualizar conteúdo do visualizador
    const image = viewer.querySelector('.attachment-viewer-image');
    const filenameElement = viewer.querySelector('.attachment-viewer-filename');
    
    image.src = url;
    image.alt = filename;
    image.setAttribute('data-filename', filename);
    filenameElement.textContent = filename;
    
    // Mostrar o visualizador
    viewer.classList.add('show');
}
