/**
 * JavaScript para gerenciar anexos de arquivos no sistema de mensagens
 */

document.addEventListener('DOMContentLoaded', function() {
    initFileAttachments();
});

// Configurações
const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB
const ALLOWED_FILE_TYPES = {
    'image': ['jpg', 'jpeg', 'png', 'gif', 'webp'],
    'document': ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'],
    'audio': ['mp3', 'wav', 'ogg'],
    'video': ['mp4', 'webm', 'avi']
};

// Variáveis globais
let activeAttachments = [];
let attachmentPreviewContainer = null;

function initFileAttachments() {
    // Inicializar anexos na página do messenger
    initMessengerAttachments();
    
    // Inicializar anexos nos chats flutuantes
    initFloatingChatAttachments();
}

/**
 * Inicializa os anexos na página do messenger
 */
function initMessengerAttachments() {
    const attachButton = document.querySelector('.chat-input-area .btn-icon:first-child');
    if (!attachButton) return;
    
    // Criar input de arquivo oculto
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.multiple = true;
    fileInput.accept = getAllowedFileTypesString();
    fileInput.style.display = 'none';
    fileInput.id = 'messenger-file-input';
    document.body.appendChild(fileInput);
    
    // Adicionar evento de clique ao botão de anexo
    attachButton.addEventListener('click', function() {
        fileInput.click();
    });
    
    // Adicionar evento de mudança ao input de arquivo
    fileInput.addEventListener('change', function(e) {
        handleFileSelection(e.target.files, 'messenger');
    });
    
    // Adicionar área de preview de anexos
    attachmentPreviewContainer = document.createElement('div');
    attachmentPreviewContainer.className = 'attachment-preview-container';
    
    const chatInputArea = document.querySelector('.chat-input-area');
    if (chatInputArea) {
        chatInputArea.parentNode.insertBefore(attachmentPreviewContainer, chatInputArea);
    }
    
    // Adicionar suporte para arrastar e soltar
    const chatArea = document.querySelector('.chat-messages');
    if (chatArea) {
        chatArea.addEventListener('dragover', handleDragOver);
        chatArea.addEventListener('drop', function(e) {
            handleDrop(e, 'messenger');
        });
    }
}

/**
 * Inicializa os anexos nos chats flutuantes
 */
function initFloatingChatAttachments() {
    // Observar o DOM para detectar quando novos chats são criados
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1 && node.classList.contains('chat-window')) {
                        setupFloatingChatAttachment(node);
                    }
                });
            }
        });
    });
    
    const floatingChatContainer = document.getElementById('floating-chat-container');
    if (floatingChatContainer) {
        observer.observe(floatingChatContainer, { childList: true });
    }
}

/**
 * Configura os anexos para um chat flutuante específico
 */
function setupFloatingChatAttachment(chatWindow) {
    const chatId = chatWindow.id.replace('chat-window-', '');
    const attachButton = chatWindow.querySelector('.chat-window-input button:first-of-type');
    
    if (!attachButton) return;
    
    // Adicionar ícone de anexo se não existir
    if (!attachButton.querySelector('i.fa-paperclip')) {
        const paperclipIcon = document.createElement('i');
        paperclipIcon.className = 'fas fa-paperclip';
        attachButton.innerHTML = '';
        attachButton.appendChild(paperclipIcon);
    }
    
    // Criar input de arquivo oculto
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.multiple = true;
    fileInput.accept = getAllowedFileTypesString();
    fileInput.style.display = 'none';
    fileInput.id = `floating-chat-file-input-${chatId}`;
    document.body.appendChild(fileInput);
    
    // Adicionar evento de clique ao botão de anexo
    attachButton.addEventListener('click', function() {
        fileInput.click();
    });
    
    // Adicionar evento de mudança ao input de arquivo
    fileInput.addEventListener('change', function(e) {
        handleFileSelection(e.target.files, 'floating', chatId);
    });
    
    // Adicionar área de preview de anexos
    const previewContainer = document.createElement('div');
    previewContainer.className = 'attachment-preview-container';
    previewContainer.id = `attachment-preview-${chatId}`;
    
    const chatInput = chatWindow.querySelector('.chat-window-input');
    if (chatInput) {
        chatInput.parentNode.insertBefore(previewContainer, chatInput);
    }
    
    // Adicionar suporte para arrastar e soltar
    const messagesContainer = chatWindow.querySelector('.chat-window-messages');
    if (messagesContainer) {
        messagesContainer.addEventListener('dragover', handleDragOver);
        messagesContainer.addEventListener('drop', function(e) {
            handleDrop(e, 'floating', chatId);
        });
    }
}

/**
 * Manipula a seleção de arquivos
 */
function handleFileSelection(files, chatType, chatId = null) {
    if (!files || files.length === 0) return;
    
    // Converter FileList para Array
    const filesArray = Array.from(files);
    
    // Validar arquivos
    const validFiles = filesArray.filter(file => {
        // Verificar tamanho
        if (file.size > MAX_FILE_SIZE) {
            showNotification(`O arquivo ${file.name} excede o tamanho máximo de 10MB.`, 'error');
            return false;
        }
        
        // Verificar tipo
        const extension = getFileExtension(file.name).toLowerCase();
        const isValidType = Object.values(ALLOWED_FILE_TYPES).some(types => 
            types.includes(extension)
        );
        
        if (!isValidType) {
            showNotification(`O tipo de arquivo ${extension} não é permitido.`, 'error');
            return false;
        }
        
        return true;
    });
    
    if (validFiles.length === 0) return;
    
    // Adicionar arquivos à lista de anexos ativos
    validFiles.forEach(file => {
        const fileId = generateUniqueId();
        activeAttachments.push({
            id: fileId,
            file: file,
            chatType: chatType,
            chatId: chatId
        });
        
        // Criar preview do anexo
        createAttachmentPreview(file, fileId, chatType, chatId);
    });
}

/**
 * Cria uma prévia do anexo
 */
function createAttachmentPreview(file, fileId, chatType, chatId) {
    const previewElement = document.createElement('div');
    previewElement.className = 'attachment-preview';
    previewElement.setAttribute('data-file-id', fileId);
    
    // Determinar o tipo de arquivo
    const extension = getFileExtension(file.name).toLowerCase();
    let fileType = 'document';
    
    Object.keys(ALLOWED_FILE_TYPES).forEach(type => {
        if (ALLOWED_FILE_TYPES[type].includes(extension)) {
            fileType = type;
        }
    });
    
    // Criar conteúdo da prévia
    let previewContent = '';
    
    if (fileType === 'image') {
        // Criar prévia de imagem
        previewContent = `
            <div class="attachment-preview-image">
                <img src="${URL.createObjectURL(file)}" alt="${file.name}">
            </div>
        `;
    } else {
        // Criar ícone baseado no tipo de arquivo
        let iconClass = 'fa-file';
        
        if (fileType === 'document') {
            if (['pdf'].includes(extension)) {
                iconClass = 'fa-file-pdf';
            } else if (['doc', 'docx'].includes(extension)) {
                iconClass = 'fa-file-word';
            } else if (['xls', 'xlsx'].includes(extension)) {
                iconClass = 'fa-file-excel';
            } else if (['ppt', 'pptx'].includes(extension)) {
                iconClass = 'fa-file-powerpoint';
            } else if (['txt'].includes(extension)) {
                iconClass = 'fa-file-alt';
            }
        } else if (fileType === 'audio') {
            iconClass = 'fa-file-audio';
        } else if (fileType === 'video') {
            iconClass = 'fa-file-video';
        }
        
        previewContent = `
            <div class="attachment-preview-icon">
                <i class="fas ${iconClass}"></i>
            </div>
        `;
    }
    
    // Adicionar informações do arquivo
    previewElement.innerHTML = `
        ${previewContent}
        <div class="attachment-preview-info">
            <div class="attachment-preview-name">${truncateFileName(file.name, 20)}</div>
            <div class="attachment-preview-size">${formatFileSize(file.size)}</div>
        </div>
        <button class="attachment-preview-remove" data-file-id="${fileId}">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Adicionar ao container apropriado
    let container;
    
    if (chatType === 'messenger') {
        container = document.querySelector('.attachment-preview-container');
    } else if (chatType === 'floating') {
        container = document.getElementById(`attachment-preview-${chatId}`);
    }
    
    if (container) {
        container.appendChild(previewElement);
        container.style.display = 'flex';
        
        // Adicionar evento para remover anexo
        const removeButton = previewElement.querySelector('.attachment-preview-remove');
        if (removeButton) {
            removeButton.addEventListener('click', function() {
                removeAttachment(fileId);
            });
        }
    }
}

/**
 * Remove um anexo da lista de anexos ativos
 */
function removeAttachment(fileId) {
    // Remover do array
    activeAttachments = activeAttachments.filter(attachment => attachment.id !== fileId);
    
    // Remover elemento de prévia
    const previewElement = document.querySelector(`.attachment-preview[data-file-id="${fileId}"]`);
    if (previewElement) {
        const container = previewElement.parentNode;
        previewElement.remove();
        
        // Esconder container se não houver mais anexos
        if (container && container.children.length === 0) {
            container.style.display = 'none';
        }
    }
}

/**
 * Manipula o evento de arrastar sobre a área de chat
 */
function handleDragOver(e) {
    e.preventDefault();
    e.stopPropagation();
    e.dataTransfer.dropEffect = 'copy';
    
    // Adicionar classe de destaque
    this.classList.add('drag-over');
}

/**
 * Manipula o evento de soltar arquivos na área de chat
 */
function handleDrop(e, chatType, chatId = null) {
    e.preventDefault();
    e.stopPropagation();
    
    // Remover classe de destaque
    e.currentTarget.classList.remove('drag-over');
    
    // Processar arquivos
    const files = e.dataTransfer.files;
    if (files && files.length > 0) {
        handleFileSelection(files, chatType, chatId);
    }
}

/**
 * Envia os anexos ativos
 */
function sendAttachments(chatType, chatId = null) {
    // Filtrar anexos para o chat específico
    const attachmentsToSend = activeAttachments.filter(attachment => {
        return attachment.chatType === chatType && attachment.chatId === chatId;
    });
    
    if (attachmentsToSend.length === 0) return [];
    
    // Aqui você implementaria o upload real para o servidor
    // Por enquanto, vamos simular o envio
    
    const sentAttachments = attachmentsToSend.map(attachment => {
        const file = attachment.file;
        const extension = getFileExtension(file.name).toLowerCase();
        
        // Determinar o tipo de arquivo
        let fileType = 'document';
        Object.keys(ALLOWED_FILE_TYPES).forEach(type => {
            if (ALLOWED_FILE_TYPES[type].includes(extension)) {
                fileType = type;
            }
        });
        
        // Criar objeto de anexo enviado
        return {
            id: attachment.id,
            name: file.name,
            size: file.size,
            type: fileType,
            extension: extension,
            url: URL.createObjectURL(file) // Na implementação real, seria a URL do servidor
        };
    });
    
    // Limpar anexos enviados
    attachmentsToSend.forEach(attachment => {
        removeAttachment(attachment.id);
    });
    
    return sentAttachments;
}

/**
 * Funções auxiliares
 */

// Obtém a extensão de um arquivo
function getFileExtension(filename) {
    return filename.split('.').pop();
}

// Formata o tamanho do arquivo
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Trunca o nome do arquivo se for muito longo
function truncateFileName(filename, maxLength) {
    if (filename.length <= maxLength) return filename;
    
    const extension = getFileExtension(filename);
    const nameWithoutExtension = filename.substring(0, filename.length - extension.length - 1);
    
    if (nameWithoutExtension.length <= maxLength - 3 - extension.length) {
        return filename;
    }
    
    return nameWithoutExtension.substring(0, maxLength - 3 - extension.length) + '...' + extension;
}

// Gera um ID único
function generateUniqueId() {
    return Date.now().toString(36) + Math.random().toString(36).substr(2, 5);
}

// Retorna uma string com todos os tipos de arquivo permitidos
function getAllowedFileTypesString() {
    const types = [];
    
    Object.values(ALLOWED_FILE_TYPES).forEach(extensions => {
        extensions.forEach(ext => {
            types.push('.' + ext);
        });
    });
    
    return types.join(',');
}

// Mostra uma notificação
function showNotification(message, type = 'info') {
    // Implementação básica de notificação
    console.log(`[${type.toUpperCase()}] ${message}`);
    
    // Criar elemento de notificação
    const notification = document.createElement('div');
    notification.className = `attachment-notification ${type}`;
    notification.textContent = message;
    
    // Adicionar ao body
    document.body.appendChild(notification);
    
    // Remover após alguns segundos
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Expor funções para uso global
window.fileAttachments = {
    sendAttachments: sendAttachments,
    removeAllAttachments: function(chatType, chatId = null) {
        const attachmentsToRemove = activeAttachments.filter(attachment => {
            return attachment.chatType === chatType && attachment.chatId === chatId;
        });
        
        attachmentsToRemove.forEach(attachment => {
            removeAttachment(attachment.id);
        });
    }
};
