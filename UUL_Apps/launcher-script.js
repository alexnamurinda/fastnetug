// Sample apps data
let apps = [
    {
        id: 1,
        name: 'Client Database',
        description: 'Manage customer relationships and communications',
        url: 'https://fastnetug.com/UUL_Apps/client_database',
        category: 'sales',
        icon: 'users',
        badge: 'Active'
    },
    {
        id: 2,
        name: 'Inventory Manager',
        description: 'Track stock levels and orders',
        url: '#',
        category: 'operations',
        icon: 'boxes',
        badge: 'New'
    },
    {
        id: 3,
        name: 'Aging inventory',
        description: 'Monitor and manage aging stock',
        url: 'https://fastnetug.com/UUL_Apps/stock_management',
        category: 'operations',
        icon: 'file-invoice-dollar',
        badge: 'Active'
    },
    {
        id: 4,
        name: 'Sales Analytics',
        description: 'Track sales performance Daily',
        url: 'https://fastnetug.com/UUL_Apps/daily_customers',
        category: 'sales',
        icon: 'chart-line',
        badge: 'Active'
    },
    // {
    //     id: 5,
    //     name: 'HR Portal',
    //     description: 'Employee management system',
    //     url: '#',
    //     category: 'hr',
    //     icon: 'user-tie',
    //     badge: 'New'
    // },
    // {
    //     id: 6,
    //     name: 'Expense Tracker',
    //     description: 'Monitor business expenses',
    //     url: '#',
    //     category: 'finance',
    //     icon: 'money-bill-wave',
    //     badge: 'Active'
    // },
    // {
    //     id: 7,
    //     name: 'Task Manager',
    //     description: 'Organize team tasks',
    //     url: '#',
    //     category: 'operations',
    //     icon: 'tasks',
    //     badge: 'Active'
    // },
    // {
    //     id: 8,
    //     name: 'Warehouse',
    //     description: 'Manage warehouse operations',
    //     url: '#',
    //     category: 'operations',
    //     icon: 'warehouse',
    //     badge: 'New'
    // }
];

// Available icons
const availableIcons = [
    'users', 'chart-line', 'boxes', 'file-invoice-dollar', 'calendar',
    'tasks', 'warehouse', 'shopping-cart', 'money-bill-wave', 'clipboard-list',
    'user-tie', 'handshake', 'truck', 'chart-pie', 'cog',
    'envelope', 'phone', 'comments', 'bell', 'folder',
    'file-alt', 'database', 'server', 'laptop-code', 'mobile-alt',
    'credit-card', 'receipt', 'calculator', 'briefcase', 'building',
    'chart-bar', 'chart-area', 'project-diagram', 'sitemap', 'network-wired',
    'shield-alt', 'lock', 'user-shield', 'key', 'tools'
];

let currentCategory = 'all';
let searchTerm = '';

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadApps();
    loadIcons();
    updateStats();
});

// Load apps
function loadApps(category = 'all', search = '') {
    const grid = document.getElementById('appsGrid');
    const sectionTitle = document.getElementById('sectionTitle');
    const appCount = document.getElementById('appCount');
    
    grid.innerHTML = '';
    currentCategory = category;
    searchTerm = search;

    let filteredApps = apps;

    // Filter by category
    if (category !== 'all') {
        filteredApps = apps.filter(app => app.category === category);
        sectionTitle.textContent = getCategoryName(category);
    } else {
        sectionTitle.textContent = 'UUL Apps';
    }

    // Filter by search
    if (search) {
        filteredApps = filteredApps.filter(app =>
            app.name.toLowerCase().includes(search.toLowerCase()) ||
            app.description.toLowerCase().includes(search.toLowerCase())
        );
        sectionTitle.textContent = `Search Results`;
    }

    // Update count
    appCount.textContent = `${filteredApps.length} ${filteredApps.length === 1 ? 'app' : 'apps'}`;

    // Display apps
    if (filteredApps.length > 0) {
        filteredApps.forEach(app => {
            const appCard = createAppCard(app);
            grid.appendChild(appCard);
        });
    } else {
        grid.innerHTML = `
            <div class="empty-state" style="grid-column: 1 / -1;">
                <i class="fas fa-search"></i>
                <h3>No apps found</h3>
                <p>Try searching with different keywords or adjust filters</p>
            </div>
        `;
    }

    updateStats();
}

// Create app card
function createAppCard(app) {
    const card = document.createElement('div');
    card.className = 'app-card';
    card.onclick = () => openApp(app.url);
    
    const badgeClass = app.badge.toLowerCase();
    
    card.innerHTML = `
        <div class="app-icon">
            <i class="fas fa-${app.icon}"></i>
        </div>
        <div class="app-name">${app.name}</div>
        <div class="app-description">${app.description}</div>
        <span class="app-badge ${badgeClass}">${app.badge}</span>
    `;

    // Add context menu for edit
    card.addEventListener('contextmenu', (e) => {
        e.preventDefault();
        if (confirm(`Edit "${app.name}"?`)) {
            editApp(app.id);
        }
    });

    // Add animation
    card.style.animation = 'fadeIn 0.5s ease';

    return card;
}

// Get category name
function getCategoryName(category) {
    const names = {
        'sales': 'Sales & CRM',
        'finance': 'Finance',
        'operations': 'Operations',
        'hr': 'HR',
        'custom': 'Custom'
    };
    return names[category] || 'UUL Apps';
}

// Open app
function openApp(url) {
    if (url === '#') {
        showNotification('This app is coming soon!', 'success');
    } else {
        window.location.href = url;
    }
}

// Filter by category
function filterByCategory(category) {
    const tabs = document.querySelectorAll('.category-tab');
    tabs.forEach(tab => {
        tab.classList.remove('active');
        if (tab.dataset.category === category) {
            tab.classList.add('active');
        }
    });
    loadApps(category, searchTerm);
}

// Search apps
function searchApps() {
    const search = document.getElementById('searchApps').value;
    loadApps(currentCategory, search);
}

// Update stats
function updateStats() {
    document.getElementById('totalApps').textContent = apps.length;
    const activeApps = apps.filter(app => app.badge === 'Active').length;
    document.getElementById('activeApps').textContent = activeApps;
}

// Show modal
function showModal(isEdit = false) {
    document.getElementById('modalTitle').textContent = isEdit ? 'Edit App' : 'Add New App';
    document.getElementById('appModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Hide modal
function hideModal() {
    document.getElementById('appModal').style.display = 'none';
    document.getElementById('appForm').reset();
    document.getElementById('appId').value = '';
    document.body.style.overflow = 'auto';
    
    // Clear selected icon
    document.querySelectorAll('.icon-option').forEach(icon => {
        icon.classList.remove('selected');
    });
}

// Load icons
function loadIcons() {
    const picker = document.getElementById('iconPicker');
    picker.innerHTML = '';

    availableIcons.forEach(icon => {
        const iconDiv = document.createElement('div');
        iconDiv.className = 'icon-option';
        iconDiv.innerHTML = `<i class="fas fa-${icon}"></i>`;
        iconDiv.onclick = () => selectIcon(icon, iconDiv);
        iconDiv.title = icon.replace(/-/g, ' ');
        picker.appendChild(iconDiv);
    });
}

// Select icon
function selectIcon(icon, element) {
    document.querySelectorAll('.icon-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    element.classList.add('selected');
    document.getElementById('appIcon').value = icon;
}

// Save app
function saveApp(event) {
    event.preventDefault();

    const id = document.getElementById('appId').value;
    const appData = {
        id: id || Date.now(),
        name: document.getElementById('appName').value,
        description: document.getElementById('appDescription').value,
        url: document.getElementById('appUrl').value,
        category: document.getElementById('appCategory').value,
        icon: document.getElementById('appIcon').value,
        badge: id ? apps.find(app => app.id == id).badge : 'New'
    };

    if (id) {
        // Update existing app
        const index = apps.findIndex(app => app.id == id);
        apps[index] = appData;
        showNotification('App updated successfully!', 'success');
    } else {
        // Add new app
        apps.push(appData);
        showNotification('App added successfully!', 'success');
    }

    hideModal();
    loadApps(currentCategory, searchTerm);
}

// Edit app
function editApp(id) {
    const app = apps.find(a => a.id === id);
    if (!app) return;

    document.getElementById('appId').value = app.id;
    document.getElementById('appName').value = app.name;
    document.getElementById('appDescription').value = app.description;
    document.getElementById('appUrl').value = app.url;
    document.getElementById('appCategory').value = app.category;
    document.getElementById('appIcon').value = app.icon;

    // Select the icon
    const iconElements = document.querySelectorAll('.icon-option');
    iconElements.forEach((el) => {
        const iconName = el.querySelector('i').className.split('fa-')[1];
        if (iconName === app.icon) {
            el.classList.add('selected');
        }
    });

    showModal(true);
}

// Show notification
function showNotification(message, type) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = `notification ${type}`;
    notification.classList.add('show');

    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('appModal');
    if (event.target === modal) {
        hideModal();
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(event) {
    // Ctrl/Cmd + K for search
    if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
        event.preventDefault();
        document.getElementById('searchApps').focus();
    }
    
    // Escape to close modal
    if (event.key === 'Escape') {
        hideModal();
    }
});