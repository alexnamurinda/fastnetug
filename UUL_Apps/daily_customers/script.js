// API Configuration
const API_URL = 'api.php';

// Global variables
let salesChart, topClientsChart, salesPersonChart, monthlyChart, acquisitionChart, distributionChart;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    initializeApp();
    setupEventListeners();
    loadDashboardData();
});

function initializeApp() {
    // Set today's date for filters
    const today = new Date().toISOString().split('T')[0];
    if (document.getElementById('dateFrom')) {
        document.getElementById('dateFrom').value = today;
        document.getElementById('dateTo').value = today;
    }

    // Initialize charts
    initializeCharts();
}

function setupEventListeners() {
    // Navigation
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const page = this.dataset.page;
            navigateToPage(page);
        });
    });

    // Menu toggle for mobile
    const menuToggle = document.getElementById('menuToggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('active');
        });
    }

    // File upload
    const fileInput = document.getElementById('fileInput');
    if (fileInput) {
        fileInput.addEventListener('change', handleFileUpload);
    }

    // Client search
    const clientSearch = document.getElementById('clientSearch');
    if (clientSearch) {
        clientSearch.addEventListener('input', filterClients);
    }

    // Sales person filter
    const salesPersonFilter = document.getElementById('salesPersonFilter');
    if (salesPersonFilter) {
        salesPersonFilter.addEventListener('change', filterClients);
    }

    // Chart period change
    const chartPeriod = document.getElementById('chartPeriod');
    if (chartPeriod) {
        chartPeriod.addEventListener('change', () => {
            loadSalesChartData(chartPeriod.value);
        });
    }
}

function navigateToPage(page) {
    // Update active nav link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelector(`[data-page="${page}"]`).classList.add('active');

    // Hide all pages
    document.querySelectorAll('.page-content').forEach(p => {
        p.style.display = 'none';
    });

    // Show selected page
    const pageElement = document.getElementById(`${page}Page`);
    if (pageElement) {
        pageElement.style.display = 'block';
    }

    // Update page title
    document.querySelector('.page-title').textContent =
        page.charAt(0).toUpperCase() + page.slice(1);

    // Load page-specific data
    switch (page) {
        case 'clients':
            loadClients();
            break;
        case 'sales':
            loadSalesHistory();
            break;
        case 'analytics':
            loadAnalytics();
            break;
        case 'upload':
            loadUploadHistory();
            break;
    }
}

// Dashboard Functions
async function loadDashboardData() {
    try {
        const response = await fetch(`${API_URL}?action=getDashboardStats`);
        const data = await response.json();

        if (data.success) {
            updateDashboardStats(data.stats);
            loadSalesChartData(30);
            loadTopClientsChart();
            loadDailySales();
        }
    } catch (error) {
        console.error('Error loading dashboard:', error);
    }
}

function updateDashboardStats(stats) {
    document.getElementById('totalClients').textContent = stats.totalClients || 0;
    document.getElementById('todayOrders').textContent = stats.todayOrders || 0;
    document.getElementById('totalOrders').textContent = stats.totalOrders || 0;
    document.getElementById('newClients').textContent = stats.newClients || 0;

    // Update change percentages
    document.getElementById('clientsChange').textContent = stats.clientsChange || 0;
    document.getElementById('ordersChange').textContent = stats.ordersChange || 0;
    document.getElementById('newClientsChange').textContent = stats.weeklyNewClients || 0;
}

async function loadDailySales() {
    try {
        const response = await fetch(`${API_URL}?action=getRecentSales&limit=10`);
        const data = await response.json();

        if (data.success) {
            displayRecentActivity(data.sales);
        }
    } catch (error) {
        console.error('Error loading recent sales:', error);
    }
}

function displayRecentActivity(sales) {
    const container = document.getElementById('recentActivity');
    if (!sales || sales.length === 0) {
        container.innerHTML = '<p style="text-align:center;color:var(--text-light);padding:20px;">No recent activity</p>';
        return;
    }

    container.innerHTML = sales.map(sale => `
        <div class="activity-item">
            <div class="activity-info">
                <h4>${sale.customer_name}</h4>
                <p>${sale.sale_date}</p>
            </div>
            <div class="activity-count">${sale.order_count} orders</div>
        </div>
    `).join('');
}

// Client Functions
async function loadClients() {
    try {
        const response = await fetch(`${API_URL}?action=getClients`);
        const data = await response.json();

        if (data.success) {
            displayClients(data.clients);
            populateSalesPersonFilter(data.clients);
        }
    } catch (error) {
        console.error('Error loading clients:', error);
    }
}

function displayClients(clients) {
    const tbody = document.getElementById('clientsTableBody');

    if (!clients || clients.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px;">No clients found</td></tr>';
        return;
    }

    tbody.innerHTML = clients.map(client => `
    <tr>
        <td><strong>${client.client_name}</strong></td>
        <td>${client.contact || '-'}</td>
        <td>${client.sales_person || '-'}</td>
        <td><strong>${client.total_orders || 0}</strong></td>
        <td>${client.last_order_date || '-'}</td>
            <td>
                <button class="action-btn" onclick="editClient(${client.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn delete" onclick="deleteClient(${client.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function filterClients() {
    const searchTerm = document.getElementById('clientSearch').value.toLowerCase();
    const salesPerson = document.getElementById('salesPersonFilter').value;

    const rows = document.querySelectorAll('#clientsTableBody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const personCell = row.cells[2]?.textContent || '';

        const matchesSearch = text.includes(searchTerm);
        const matchesPerson = !salesPerson || personCell === salesPerson;

        row.style.display = (matchesSearch && matchesPerson) ? '' : 'none';
    });
}

function populateSalesPersonFilter(clients) {
    const filter = document.getElementById('salesPersonFilter');
    const persons = [...new Set(clients.map(c => c.sales_person).filter(Boolean))];

    filter.innerHTML = '<option value="">All Sales Persons</option>' +
        persons.map(p => `<option value="${p}">${p}</option>`).join('');
}

function editClient(id) {
    fetch(`${API_URL}?action=getClient&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('editClientId').value = data.client.id;
                document.getElementById('editClientName').value = data.client.client_name;
                document.getElementById('editClientPhone').value = data.client.contact || '';
                document.getElementById('editClientSalesPerson').value = data.client.sales_person || '';
                openModal('editClientModal');
            }
        });
}

async function updateClient() {
    const formData = new FormData();
    formData.append('action', 'updateClient');
    formData.append('id', document.getElementById('editClientId').value);
    formData.append('name', document.getElementById('editClientName').value);
    formData.append('phone', document.getElementById('editClientPhone').value);
    formData.append('salesPerson', document.getElementById('editClientSalesPerson').value);

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            closeModal('editClientModal');
            loadClients();
            showNotification('Client updated successfully', 'success');
        }
    } catch (error) {
        showNotification('Error updating client', 'error');
    }
}

function showAddClientModal() {
    document.getElementById('addClientName').value = '';
    document.getElementById('addClientPhone').value = '';
    document.getElementById('addClientSalesPerson').value = '';
    openModal('addClientModal');
}

async function addNewClient() {
    const name = document.getElementById('addClientName').value.trim();
    if (!name) {
        showNotification('Client name is required', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('action', 'addClient');
    formData.append('name', name);
    formData.append('phone', document.getElementById('addClientPhone').value);
    formData.append('salesPerson', document.getElementById('addClientSalesPerson').value);

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            closeModal('addClientModal');
            loadClients();
            showNotification('Client added successfully', 'success');
        }
    } catch (error) {
        showNotification('Error adding client', 'error');
    }
}

async function deleteClient(id) {
    if (!confirm('Are you sure you want to delete this client?')) return;

    try {
        const response = await fetch(`${API_URL}?action=deleteClient&id=${id}`, {
            method: 'DELETE'
        });
        const data = await response.json();

        if (data.success) {
            loadClients();
            showNotification('Client deleted successfully', 'success');
        }
    } catch (error) {
        showNotification('Error deleting client', 'error');
    }
}

// File Upload
async function handleFileUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    const statusDiv = document.getElementById('uploadStatus');
    statusDiv.textContent = 'Processing file...';
    statusDiv.className = 'upload-status';

    try {
        const data = await readExcelFile(file);
        await uploadSalesData(data);
    } catch (error) {
        statusDiv.textContent = 'Error: ' + error.message;
        statusDiv.className = 'upload-status error';
    }

    event.target.value = '';
}

function readExcelFile(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();

        reader.onload = (e) => {
            try {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                const jsonData = XLSX.utils.sheet_to_json(firstSheet);
                resolve(jsonData);
            } catch (error) {
                reject(new Error('Failed to read Excel file'));
            }
        };

        reader.onerror = () => reject(new Error('Failed to read file'));
        reader.readAsArrayBuffer(file);
    });
}

async function uploadSalesData(data) {
    const formData = new FormData();
    formData.append('action', 'uploadSales');
    formData.append('data', JSON.stringify(data));

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        const statusDiv = document.getElementById('uploadStatus');
        if (result.success) {
            statusDiv.textContent = `✓ Success! ${result.newClients} new clients, ${result.totalOrders} orders recorded`;
            statusDiv.className = 'upload-status success';
            loadDashboardData();
            loadUploadHistory();
        } else {
            statusDiv.textContent = 'Error: ' + result.message;
            statusDiv.className = 'upload-status error';
        }
    } catch (error) {
        throw new Error('Failed to upload data');
    }
}

// Charts
function initializeCharts() {
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
            }
        }
    };

    // Sales Trend Chart
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Orders',
                    data: [],
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: chartOptions
        });
    }

    // Top Clients Chart
    const topCtx = document.getElementById('topClientsChart');
    if (topCtx) {
        topClientsChart = new Chart(topCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Orders',
                    data: [],
                    backgroundColor: '#10B981'
                }]
            },
            options: {
                ...chartOptions,
                indexAxis: 'y'
            }
        });
    }
}

async function loadSalesChartData(days) {
    try {
        const response = await fetch(`${API_URL}?action=getSalesChart&days=${days}`);
        const data = await response.json();

        if (data.success && salesChart) {
            salesChart.data.labels = data.labels;
            salesChart.data.datasets[0].data = data.values;
            salesChart.update();
        }
    } catch (error) {
        console.error('Error loading sales chart:', error);
    }
}

async function loadTopClientsChart() {
    try {
        const response = await fetch(`${API_URL}?action=getTopClients&limit=10`);
        const data = await response.json();

        if (data.success && topClientsChart) {
            topClientsChart.data.labels = data.labels;
            topClientsChart.data.datasets[0].data = data.values;
            topClientsChart.update();
        }
    } catch (error) {
        console.error('Error loading top clients chart:', error);
    }
}

// Sales History
async function loadSalesHistory() {
    try {
        const response = await fetch(`${API_URL}?action=getSalesHistory`);
        const data = await response.json();

        if (data.success) {
            displaySalesHistory(data.sales);
        }
    } catch (error) {
        console.error('Error loading sales history:', error);
    }
}

function displaySalesHistory(sales) {
    const tbody = document.getElementById('salesTableBody');

    if (!sales || sales.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:40px;">No sales history</td></tr>';
        return;
    }

    tbody.innerHTML = sales.map(sale => `
        <tr>
            <td>${sale.sale_date}</td>
            <td><strong>${sale.customer_name}</strong></td>
            <td>${sale.order_count}</td>
            <td><span class="status-badge ${sale.is_new_client ? 'new' : 'existing'}">
                ${sale.is_new_client ? 'New Client' : 'Existing'}
            </span></td>
        </tr>
    `).join('');
}

function filterSales() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;

    fetch(`${API_URL}?action=getSalesHistory&dateFrom=${dateFrom}&dateTo=${dateTo}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                displaySalesHistory(data.sales);
            }
        });
}

// Analytics
async function loadAnalytics() {
    loadSalesPersonAnalytics();
    loadMonthlyAnalytics();
    loadAcquisitionAnalytics();
    loadDistributionAnalytics();
}

async function loadSalesPersonAnalytics() {
    try {
        const response = await fetch(`${API_URL}?action=getSalesByPerson`);
        const data = await response.json();

        if (data.success) {
            const ctx = document.getElementById('salesPersonChart');
            if (!salesPersonChart && ctx) {
                salesPersonChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.values,
                            backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6']
                        }]
                    }
                });
            } else if (salesPersonChart) {
                salesPersonChart.data.labels = data.labels;
                salesPersonChart.data.datasets[0].data = data.values;
                salesPersonChart.update();
            }
        }
    } catch (error) {
        console.error('Error loading sales person analytics:', error);
    }
}

// Export Functions
async function exportClients() {
    try {
        const response = await fetch(`${API_URL}?action=getClients`);
        const data = await response.json();

        if (data.success) {
            const ws = XLSX.utils.json_to_sheet(data.clients.map(c => ({
                'Client Name': c.client_name,
                'Contact': c.contact || '',
                'Sales Person': c.sales_person || '',
                'Total Orders': c.total_orders,
                'Last Order': c.last_order_date
            })));
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Clients');
            XLSX.writeFile(wb, `clients_${new Date().toISOString().split('T')[0]}.xlsx`);
        }
    } catch (error) {
        showNotification('Error exporting clients', 'error');
    }
}

// Upload History
async function loadUploadHistory() {
    try {
        const response = await fetch(`${API_URL}?action=getUploadHistory`);
        const data = await response.json();

        if (data.success) {
            displayUploadHistory(data.uploads);
        }
    } catch (error) {
        console.error('Error loading upload history:', error);
    }
}

function displayUploadHistory(uploads) {
    const container = document.getElementById('uploadHistory');

    if (!uploads || uploads.length === 0) {
        container.innerHTML = '<p style="text-align:center;color:var(--text-light);">No upload history</p>';
        return;
    }

    container.innerHTML = uploads.map(upload => `
        <div class="activity-item">
            <div class="activity-info">
                <h4>${upload.upload_date}</h4>
                <p>${upload.new_clients} new clients, ${upload.total_orders} orders</p>
            </div>
        </div>
    `).join('');
}

// Utility Functions
function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

function showNotification(message, type) {
    // Simple notification - can be enhanced
    alert(message);
}