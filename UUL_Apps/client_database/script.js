// Global variables
let clients = [];
let filteredClients = [];
let currentCategory = '';

// Category configurations
const categories = {
    art_paper: { name: 'Art Paper', icon: 'fas fa-palette', color: '#4299e1' },
    art_board: { name: 'Art Board', icon: 'fas fa-clipboard', color: '#48bb78' },
    chip_board: { name: 'Chip Board', icon: 'fas fa-layer-group', color: '#805ad5' },
    ncr: { name: 'NCR', icon: 'fas fa-copy', color: '#9f7aea' },
    manilla: { name: 'Manilla', icon: 'fas fa-file-alt', color: '#ed8936' },
    sticker_paper: { name: 'Sticker Paper', icon: 'fas fa-sticky-note', color: '#ec4899' },
    chemicals: { name: 'Chemicals', icon: 'fas fa-flask', color: '#f56565' },
    plates: { name: 'Plates', icon: 'fas fa-circle', color: '#38b2ac' },
    resellers: { name: 'Resellers', icon: 'fas fa-store', color: '#a0aec0' },
    operators: { name: 'Operators', icon: 'fas fa-cogs', color: '#718096' },
    corporate_clients: { name: 'Corporate Clients', icon: 'fas fa-building', color: '#2d3748' },
    freelancers: { name: 'Freelancers', icon: 'fas fa-user-tie', color: '#ecc94b' },
    other: { name: 'Other', icon: 'fas fa-ellipsis-h', color: '#cbd5e0' }
};

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function () {
    loadClients();
    setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
    // Search input
    document.getElementById('searchInput').addEventListener('input', debounce(searchClients, 300));

    // Form submissions
    document.getElementById('addClientForm').addEventListener('submit', handleAddClient);
    document.getElementById('editClientForm').addEventListener('submit', handleEditClient);
    document.getElementById('bulkMessageForm').addEventListener('submit', handleBulkMessage);

    // Category filter for bulk messages
    document.getElementById('messageCategory').addEventListener('change', updateTargetCount);

    // Close modals when clicking outside
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('modal')) {
            hideAllModals();
        }
    });

    // Enter key for search
    document.getElementById('searchInput').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            searchClients();
        }
    });
}

// Toggle mobile search
function toggleMobileSearch() {
    const searchBox = document.querySelector('.search-box');
    const isHidden = searchBox.classList.contains('mobile-hidden');

    if (isHidden) {
        searchBox.classList.remove('mobile-hidden');
        searchBox.querySelector('input').focus();
    } else {
        searchBox.classList.add('mobile-hidden');
        searchBox.querySelector('input').value = '';
        hideSearchResults();
    }
}

// Initialize mobile search state
function initMobileSearch() {
    if (window.innerWidth <= 768) {
        document.querySelector('.search-box').classList.add('mobile-hidden');
    }
}

window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
        document.querySelector('.search-box').classList.remove('mobile-hidden');
    } else if (!document.querySelector('.search-box:focus-within')) {
        document.querySelector('.search-box').classList.add('mobile-hidden');
    }
});

// Call on load
initMobileSearch();

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Load clients from server
async function loadClients() {
    showLoading('statsGrid');

    try {
        const response = await fetch('api.php?action=get_clients');
        const data = await response.json();

        if (data.success) {
            clients = data.clients;
            renderStats();
        } else {
            showNotification('Error loading clients: ' + data.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Failed to load clients', 'error');
        // Load demo data for testing
        loadDemoData();
    }
}

// Render statistics
// Render statistics
function renderStats() {
    const statsGrid = document.getElementById('statsGrid');
    const stats = calculateStats();

    statsGrid.innerHTML = '';

    // Add total card FIRST with distinct styling
    const totalCard = document.createElement('div');
    totalCard.className = 'stat-card fade-in';
    totalCard.onclick = () => showCategoryClients('all');
    totalCard.innerHTML = `
        <div class="stat-icon">
            <i class="fas fa-users" style="color: #2d3748"></i>
        </div>
        <div class="stat-number">${clients.length}</div>
        <div class="stat-label">Total Clients</div>
    `;
    statsGrid.appendChild(totalCard);

    // Then add category cards
    Object.keys(categories).forEach(categoryKey => {
        const category = categories[categoryKey];
        const count = stats[categoryKey] || 0;

        const statCard = document.createElement('div');
        statCard.className = 'stat-card fade-in';
        statCard.onclick = () => showCategoryClients(categoryKey);
        statCard.innerHTML = `
            <div class="stat-icon">
                <i class="${category.icon}" style="color: ${category.color}"></i>
            </div>
            <div class="stat-number">${count}</div>
            <div class="stat-label">${category.name}</div>
        `;
        statsGrid.appendChild(statCard);
    });
}

// Calculate statistics
function calculateStats() {
    const stats = {};

    Object.keys(categories).forEach(category => {
        stats[category] = clients.filter(client => client.category === category).length;
    });

    return stats;
}

// Create stat card
function createStatCard(categoryKey, category, count) {
    const card = document.createElement('div');
    card.className = 'stat-card fade-in';
    card.onclick = () => showCategoryClients(categoryKey);

    card.innerHTML = `
        <div class="stat-icon">
            <i class="${category.icon}" style="color: ${category.color}"></i>
        </div>
        <div class="stat-number">${count}</div>
        <div class="stat-label">${category.name}</div>
    `;

    return card;
}

// Show clients by category
function showCategoryClients(categoryKey) {
    currentCategory = categoryKey;

    if (categoryKey === 'all') {
        filteredClients = [...clients];
        document.getElementById('sectionTitle').textContent = 'All Clients';
    } else {
        filteredClients = clients.filter(client => client.category === categoryKey);
        document.getElementById('sectionTitle').textContent = categories[categoryKey].name + ' Clients';
    }

    renderClientList();
    showSection('clientListSection');
}

// Render client list
function renderClientList() {
    const clientGrid = document.getElementById('clientGrid');

    if (filteredClients.length === 0) {
        clientGrid.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h3>No clients found</h3>
                <p>Try adjusting your search or add some clients.</p>
            </div>
        `;
        return;
    }

    clientGrid.innerHTML = '';

    filteredClients.forEach(client => {
        const clientCard = createClientCard(client);
        clientGrid.appendChild(clientCard);
    });
}

// Create client card
function createClientCard(client) {
    const card = document.createElement('div');
    card.className = 'client-card slide-in';

    const category = categories[client.category];
    const categoryColor = category ? category.color : '#718096';

    card.innerHTML = `
        <div class="client-header">
            <div class="client-info">
                <h4>${client.name}</h4>
                <span class="client-category" style="background: ${categoryColor}">
                    ${category ? category.name : client.category}
                </span>
            </div>
        </div>
        <div class="client-details">
            <div class="client-detail">
                <i class="fas fa-phone"></i>
                <span>${client.phone}</span>
            </div>
            ${client.email ? `
                <div class="client-detail">
                    <i class="fas fa-envelope"></i>
                    <span>${client.email}</span>
                </div>
            ` : ''}
            ${client.company ? `
                <div class="client-detail">
                    <i class="fas fa-building"></i>
                    <span>${client.company}</span>
                </div>
            ` : ''}
            ${client.address ? `
                <div class="client-detail">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>${client.address}</span>
                </div>
            ` : ''}
        </div>
        <div class="client-actions">
            <button class="btn btn-success btn-small" onclick="callClient('${client.phone}')">
                <i class="fas fa-phone"></i> Call
            </button>
            <button class="btn btn-warning btn-small" onclick="whatsappClient('${client.phone}')">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </button>
            <button class="btn btn-secondary btn-small" onclick="editClient(${client.id})">
                <i class="fas fa-edit"></i> Edit
            </button>
            <button class="btn btn-danger btn-small" onclick="deleteClient(${client.id})">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    `;

    return card;
}

// Search clients
function searchClients() {
    const query = document.getElementById('searchInput').value.trim().toLowerCase();

    if (query.length === 0) {
        hideSearchResults();
        return;
    }

    const results = clients.filter(client =>
        client.name.toLowerCase().includes(query) ||
        client.phone.includes(query) ||
        (client.email && client.email.toLowerCase().includes(query)) ||
        (client.company && client.company.toLowerCase().includes(query))
    );

    displaySearchResults(results, query);
}

// Display search results
function displaySearchResults(results, query) {
    const searchGrid = document.getElementById('searchGrid');

    if (results.length === 0) {
        searchGrid.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h3>No results found</h3>
                <p>No clients match "${query}"</p>
            </div>
        `;
    } else {
        searchGrid.innerHTML = '';
        results.forEach(client => {
            const clientCard = createClientCard(client);
            searchGrid.appendChild(clientCard);
        });
    }

    showSection('searchResults');
}

// Filter by category
function filterByCategory() {
    const category = document.getElementById('categoryFilter').value;

    if (category === '') {
        hideSearchResults();
        return;
    }

    showCategoryClients(category);
}

// Handle add client
async function handleAddClient(e) {
    e.preventDefault();

    const formData = {
        name: document.getElementById('clientName').value,
        phone: document.getElementById('clientPhone').value,
        email: document.getElementById('clientEmail').value,
        company: document.getElementById('clientCompany').value,
        category: document.getElementById('clientCategory').value,
        address: document.getElementById('clientAddress').value,
        notes: document.getElementById('clientNotes').value
    };

    // Check if phone number already exists
    if (clients.some(client => client.phone === formData.phone)) {
        showNotification('Phone number already exists in the system', 'warning');
        return;
    }

    try {
        const response = await fetch('api.php?action=add_client', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            // Add to local array for demo
            const newClient = {
                id: Date.now(),
                ...formData,
                created_at: new Date().toISOString().split('T')[0]
            };
            clients.push(newClient);

            hideAddClientModal();
            renderStats();
            showNotification('Client added successfully', 'success');
            document.getElementById('addClientForm').reset();
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        // For demo purposes, add locally
        const newClient = {
            id: Date.now(),
            ...formData,
            created_at: new Date().toISOString().split('T')[0]
        };
        clients.push(newClient);

        hideAddClientModal();
        renderStats();
        showNotification('Client added successfully (demo mode)', 'success');
        document.getElementById('addClientForm').reset();
    }
}

// Handle edit client
async function handleEditClient(e) {
    e.preventDefault();

    const clientId = document.getElementById('editClientId').value;
    const formData = {
        id: clientId,
        name: document.getElementById('editClientName').value,
        phone: document.getElementById('editClientPhone').value,
        email: document.getElementById('editClientEmail').value,
        company: document.getElementById('editClientCompany').value,
        category: document.getElementById('editClientCategory').value,
        address: document.getElementById('editClientAddress').value,
        notes: document.getElementById('editClientNotes').value
    };

    try {
        const response = await fetch('api.php?action=update_client', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            // Update local array
            const clientIndex = clients.findIndex(client => client.id == clientId);
            if (clientIndex !== -1) {
                clients[clientIndex] = { ...clients[clientIndex], ...formData };
            }

            hideEditClientModal();
            renderStats();
            if (currentCategory) {
                showCategoryClients(currentCategory);
            }
            showNotification('Client updated successfully', 'success');
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        // For demo purposes, update locally
        const clientIndex = clients.findIndex(client => client.id == clientId);
        if (clientIndex !== -1) {
            clients[clientIndex] = { ...clients[clientIndex], ...formData };
        }

        hideEditClientModal();
        renderStats();
        if (currentCategory) {
            showCategoryClients(currentCategory);
        }
        showNotification('Client updated successfully (demo mode)', 'success');
    }
}

// Handle bulk message
async function handleBulkMessage(e) {
    e.preventDefault();

    const category = document.getElementById('messageCategory').value;
    const message = document.getElementById('messageText').value;

    const targetClients = category === 'all' ? clients : clients.filter(client => client.category === category);

    if (targetClients.length === 0) {
        showNotification('No clients found in selected category', 'warning');
        return;
    }

    try {
        const response = await fetch('api.php?action=send_bulk_message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                category: category,
                message: message,
                clients: targetClients.map(client => ({ id: client.id, phone: client.phone, name: client.name }))
            })
        });

        const data = await response.json();

        if (data.success) {
            hideBulkMessageModal();
            showNotification(`Bulk message sent to ${targetClients.length} clients`, 'success');
            document.getElementById('bulkMessageForm').reset();
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        // For demo purposes
        hideBulkMessageModal();
        showNotification(`Bulk message sent to ${targetClients.length} clients (demo mode)`, 'success');
        document.getElementById('bulkMessageForm').reset();
    }
}

// Edit client
function editClient(clientId) {
    const client = clients.find(c => c.id == clientId);
    if (!client) return;

    document.getElementById('editClientId').value = client.id;
    document.getElementById('editClientName').value = client.name;
    document.getElementById('editClientPhone').value = client.phone;
    document.getElementById('editClientEmail').value = client.email || '';
    document.getElementById('editClientCompany').value = client.company || '';
    document.getElementById('editClientCategory').value = client.category;
    document.getElementById('editClientAddress').value = client.address || '';
    document.getElementById('editClientNotes').value = client.notes || '';

    showEditClientModal();
}

// Delete client
async function deleteClient(clientId) {
    if (!confirm('Are you sure you want to delete this client?')) {
        return;
    }

    try {
        const response = await fetch(`api.php?action=delete_client&id=${clientId}`, {
            method: 'DELETE'
        });

        const data = await response.json();

        if (data.success) {
            // Remove from local array
            clients = clients.filter(client => client.id != clientId);

            renderStats();
            if (currentCategory) {
                showCategoryClients(currentCategory);
            }
            showNotification('Client deleted successfully', 'success');
        } else {
            showNotification('Error: ' + data.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        // For demo purposes, delete locally
        clients = clients.filter(client => client.id != clientId);

        renderStats();
        if (currentCategory) {
            showCategoryClients(currentCategory);
        }
        showNotification('Client deleted successfully (demo mode)', 'success');
    }
}

// Call client
function callClient(phone) {
    window.open(`tel:${phone}`, '_self');
}

// WhatsApp client
function whatsappClient(phone) {
    // Remove + from phone number for WhatsApp
    const cleanPhone = phone.replace('+', '');
    window.open(`https://wa.me/${cleanPhone}`, '_blank');
}

// Update target count for bulk messages
function updateTargetCount() {
    const category = document.getElementById('messageCategory').value;
    const targetClients = category === 'all' ? clients : clients.filter(client => client.category === category);
    document.getElementById('targetCount').textContent = targetClients.length;
}

// Modal functions
function showAddClientModal() {
    document.getElementById('addClientModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function hideAddClientModal() {
    document.getElementById('addClientModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function showEditClientModal() {
    document.getElementById('editClientModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function hideEditClientModal() {
    document.getElementById('editClientModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function showBulkMessageModal() {
    document.getElementById('bulkMessageModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
    updateTargetCount();
}

function hideBulkMessageModal() {
    document.getElementById('bulkMessageModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function hideAllModals() {
    hideAddClientModal();
    hideEditClientModal();
    hideBulkMessageModal();
}

// Section functions
function showSection(sectionId) {
    // Hide other sections
    document.getElementById('clientListSection').style.display = 'none';
    document.getElementById('searchResults').style.display = 'none';

    // Show target section
    document.getElementById(sectionId).style.display = 'block';

    // Scroll to section
    document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' });
}

function hideClientList() {
    document.getElementById('clientListSection').style.display = 'none';
    currentCategory = '';
}

function hideSearchResults() {
    document.getElementById('searchResults').style.display = 'none';
    document.getElementById('searchInput').value = '';
}

// Utility functions
function showLoading(containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = `
        <div class="loading">
            <div class="spinner"></div>
            Loading...
        </div>
    `;
}

function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = `notification ${type}`;

    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);

    // Hide notification after 4 seconds
    setTimeout(() => {
        notification.classList.remove('show');
    }, 4000);
}

// Export functions for potential future use
window.UULDashboard = {
    loadClients,
    showCategoryClients,
    searchClients,
    editClient,
    deleteClient,
    callClient,
    whatsappClient
};