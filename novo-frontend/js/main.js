/**
 * Main JavaScript file for Camanor System
 * Modern, efficient and animated frontend
 */

// DOM Elements
document.addEventListener('DOMContentLoaded', function() {
    // Login form validation
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Simple validation
            let isValid = true;
            let errors = [];
            
            if (!email || !validateEmail(email)) {
                isValid = false;
                errors.push('Por favor, insira um email válido.');
                document.getElementById('email').classList.add('is-invalid');
            } else {
                document.getElementById('email').classList.remove('is-invalid');
            }
            
            if (!password) {
                isValid = false;
                errors.push('Por favor, insira sua senha.');
                document.getElementById('password').classList.add('is-invalid');
            } else {
                document.getElementById('password').classList.remove('is-invalid');
            }
            
            // Display errors or submit
            const alertContainer = document.getElementById('alert-container');
            if (!isValid) {
                alertContainer.innerHTML = `
                    <div class="alert alert-danger">
                        ${errors.map(error => `<p>${error}</p>`).join('')}
                    </div>
                `;
            } else {
                alertContainer.innerHTML = `
                    <div class="alert alert-success">
                        <p>Login realizado com sucesso! Redirecionando...</p>
                    </div>
                `;
                
                // Simulate login success and redirect
                // In a real application, this would be an API call
                setTimeout(() => {
                    window.location.href = 'dashboard.html';
                }, 1500);
            }
        });
    }
    
    // Toggle sidebar in dashboard
    const sidebarToggle = document.getElementById('sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('sidebar-collapsed');
            document.querySelectorAll('.sidebar-menu-text').forEach(item => {
                item.classList.toggle('d-none');
            });
        });
    }
    
    // Initialize tooltips
    initTooltips();
    
    // Initialize charts if on dashboard
    if (document.getElementById('main-chart')) {
        initCharts();
    }
    
    // Add animation classes to elements
    animateElements();
});

// Email validation helper
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

// Initialize tooltips
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Initialize charts
function initCharts() {
    // This would use a charting library like Chart.js
    // Sample implementation:
    if (typeof Chart !== 'undefined') {
        const ctx = document.getElementById('main-chart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Produção',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: 'rgba(0, 86, 179, 0.2)',
                    borderColor: 'rgba(0, 86, 179, 1)',
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
}

// Add animation classes to elements
function animateElements() {
    // Add animation classes with delay to create a sequence effect
    const animatedElements = document.querySelectorAll('.animate-on-load');
    animatedElements.forEach((element, index) => {
        setTimeout(() => {
            element.classList.add('animated');
        }, 100 * index);
    });
}

// Dashboard data functions
function loadDashboardData() {
    // This would be an API call in a real application
    // For now, we'll just simulate data loading
    
    // Update cards with sample data
    updateDashboardCard('total-users', '1,234');
    updateDashboardCard('active-projects', '42');
    updateDashboardCard('total-revenue', 'R$ 567.890');
    updateDashboardCard('completion-rate', '87%');
    
    // Load table data
    loadTableData();
}

function updateDashboardCard(id, value) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = value;
        element.classList.add('value-updated');
        setTimeout(() => {
            element.classList.remove('value-updated');
        }, 1000);
    }
}

function loadTableData() {
    // This would be an API call in a real application
    const tableBody = document.querySelector('.data-table tbody');
    if (!tableBody) return;
    
    // Sample data
    const data = [
        { id: 1, name: 'Projeto A', status: 'Ativo', progress: 75 },
        { id: 2, name: 'Projeto B', status: 'Concluído', progress: 100 },
        { id: 3, name: 'Projeto C', status: 'Em pausa', progress: 30 },
        { id: 4, name: 'Projeto D', status: 'Ativo', progress: 60 },
        { id: 5, name: 'Projeto E', status: 'Ativo', progress: 45 }
    ];
    
    // Clear existing rows
    tableBody.innerHTML = '';
    
    // Add new rows
    data.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.id}</td>
            <td>${item.name}</td>
            <td><span class="status-badge status-${item.status.toLowerCase().replace(' ', '-')}">${item.status}</span></td>
            <td>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ${item.progress}%" 
                         aria-valuenow="${item.progress}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="progress-text">${item.progress}%</span>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Editar">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Excluir">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });
    
    // Reinitialize tooltips for new elements
    initTooltips();
}

// User profile functions
function loadUserProfile() {
    // This would be an API call in a real application
    // For now, we'll just simulate data loading
    
    const profileData = {
        name: 'João Silva',
        email: 'joao.silva@camanor.com.br',
        role: 'Administrador',
        lastLogin: '04/05/2025 15:30',
        avatar: 'img/user.png'
    };
    
    // Update profile elements
    document.getElementById('user-name').textContent = profileData.name;
    document.getElementById('user-email').textContent = profileData.email;
    document.getElementById('user-role').textContent = profileData.role;
    document.getElementById('user-last-login').textContent = profileData.lastLogin;
    document.getElementById('user-avatar').src = profileData.avatar;
}

// Notification functions
function loadNotifications() {
    // This would be an API call in a real application
    const notificationList = document.getElementById('notification-list');
    if (!notificationList) return;
    
    // Sample notifications
    const notifications = [
        { id: 1, title: 'Nova mensagem', message: 'Você recebeu uma nova mensagem de Maria.', time: '5 min atrás', read: false },
        { id: 2, title: 'Atualização do sistema', message: 'O sistema foi atualizado para a versão 2.0.', time: '1 hora atrás', read: false },
        { id: 3, title: 'Lembrete', message: 'Reunião agendada para amanhã às 10h.', time: '3 horas atrás', read: true }
    ];
    
    // Update notification count
    const unreadCount = notifications.filter(n => !n.read).length;
    const notificationBadge = document.getElementById('notification-badge');
    if (notificationBadge) {
        notificationBadge.textContent = unreadCount;
        notificationBadge.style.display = unreadCount > 0 ? 'block' : 'none';
    }
    
    // Clear existing notifications
    notificationList.innerHTML = '';
    
    // Add new notifications
    notifications.forEach(notification => {
        const item = document.createElement('div');
        item.className = `notification-item ${notification.read ? 'read' : 'unread'}`;
        item.innerHTML = `
            <div class="notification-title">${notification.title}</div>
            <div class="notification-message">${notification.message}</div>
            <div class="notification-time">${notification.time}</div>
        `;
        notificationList.appendChild(item);
    });
}
