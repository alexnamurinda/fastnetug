// API Configuration
const API_URL = 'api.php';
// Authentication API Configuration
const AUTH_API_URL = 'report_api.php';
let currentUser = null;

// Global variables
let salesChart, topClientsChart, salesPersonChart, monthlyChart, acquisitionChart, distributionChart;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    initializeApp();
    setupEventListeners();
    loadDashboardData();
});

function initializeApp() {
    // Check session first
    checkUserSession();

    // Set today's date for filters
    const today = new Date();
    const todayStr = today.toISOString().split('T')[0];

    // Set first day of current month
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    const firstDayStr = firstDayOfMonth.toISOString().split('T')[0];

    if (document.getElementById('dateFrom')) {
        document.getElementById('dateFrom').value = firstDayStr;
        document.getElementById('dateTo').value = todayStr;
    }
    if (document.getElementById('reportDateFrom')) {
        document.getElementById('reportDateFrom').value = firstDayStr;
        document.getElementById('reportDateTo').value = todayStr;
    }
    if (document.getElementById('reportDate')) {
        document.getElementById('reportDate').value = todayStr;
    }
    if (document.getElementById('approvalDateFrom')) {
        document.getElementById('approvalDateFrom').value = firstDayStr;
        document.getElementById('approvalDateTo').value = todayStr;
    }

    // Initialize charts
    initializeCharts();
}

async function updateNotificationBadge() {
    if (!currentUser) return;

    try {
        const response = await fetch(`${AUTH_API_URL}?action=getReportStats`);
        const data = await response.json();

        if (data.success && data.stats.pending > 0) {
            const badge = document.getElementById('notificationBadge');
            badge.textContent = data.stats.pending;
            badge.style.display = 'block';
        } else {
            document.getElementById('notificationBadge').style.display = 'none';
        }
    } catch (error) {
        console.error('Error updating notifications:', error);
    }
}

function showNotifications() {
    // Navigate to reports page to see pending items
    if (currentUser.role === 'supervisor') {
        navigateToPage('approvals');
    } else {
        navigateToPage('reports');
    }
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

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function (e) {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menuToggle');

    if (window.innerWidth <= 768) {
        if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    }
});

// ===== UPDATED NAVIGATE TO PAGE =====
function navigateToPage(page) {
    // Hide sidebar on mobile after navigation
    document.getElementById('sidebar').classList.remove('active');

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
            const addClientBtn = document.querySelector('[onclick="showAddClientModal()"]');
            if (addClientBtn && currentUser) {
                addClientBtn.style.display = currentUser.role === 'supervisor' ? 'inline-flex' : 'none';
            }
            break;
        case 'reports':
            loadMyReports();
            loadReportStats();
            break;
        case 'approvals':
            loadAllReports();
            loadSalesPersons();
            break;
        case 'upload':
            loadUploadHistory();
            break;
        case 'schedule':
        case 'items':
        case 'margin':
            // Coming soon pages - no data to load
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
    document.getElementById('monthlyReports').textContent = stats.monthlyReports || 0;

    // Initialize carousel after stats are loaded
    initStatsCarousel();
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
        tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:40px;">No clients found</td></tr>';
        return;
    }

    tbody.innerHTML = clients.map(client => `
    <tr onclick="viewClientDetail(${client.id})">
        <td>
            <div style="display: flex; align-items: center; gap: 8px;">
                <strong>${client.client_name}</strong>
            </div>
        </td>
        <td>${client.contact || '-'}</td>
        <td>${client.sales_person || '-'}</td>
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
                document.getElementById('editClientAddress').value = data.client.address || '';  // ADD THIS LINE
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
    formData.append('address', document.getElementById('editClientAddress').value);  // ADD THIS LINE
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

// Export Functions
async function exportClients() {
    // Get current filter values
    const searchTerm = document.getElementById('clientSearch')?.value.toLowerCase() || '';
    const salesPersonFilter = document.getElementById('salesPersonFilter')?.value || '';

    try {
        const response = await fetch(`${API_URL}?action=getClients`);
        const data = await response.json();

        if (data.success) {
            // Apply filters to match what's displayed
            let filteredClients = data.clients;

            if (searchTerm) {
                filteredClients = filteredClients.filter(c =>
                    c.client_name.toLowerCase().includes(searchTerm) ||
                    (c.contact && c.contact.toLowerCase().includes(searchTerm)) ||
                    (c.sales_person && c.sales_person.toLowerCase().includes(searchTerm))
                );
            }

            if (salesPersonFilter) {
                filteredClients = filteredClients.filter(c =>
                    c.sales_person === salesPersonFilter
                );
            }

            const ws = XLSX.utils.json_to_sheet(filteredClients.map(c => ({
                'Client Name': c.client_name,
                'Contact': c.contact || '',
                'Sales Person': c.sales_person || '',
                'Total Orders': c.total_orders,
                'Last Order': c.last_order_date || ''
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


// ===== AUTHENTICATION FUNCTIONS =====

async function checkUserSession() {
    try {
        const response = await fetch(`${AUTH_API_URL}?action=checkSession`);
        const data = await response.json();

        if (data.success && data.loggedIn) {
            currentUser = data.user;
            hideLoginModal();
            updateUIForUser();
            loadDashboardData();
        } else {
            showLoginModal();
        }
    } catch (error) {
        console.error('Error checking session:', error);
        showLoginModal();
    }
}

async function loginUser() {
    const passcode = document.getElementById('loginPasscode').value;

    if (!passcode) {
        showNotification('Please enter passcode', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('action', 'login');
    formData.append('passcode', passcode);

    try {
        const response = await fetch(AUTH_API_URL, {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            currentUser = data.user;
            hideLoginModal();
            updateUIForUser();
            loadDashboardData();
            showNotification('Login successful', 'success');
        } else {
            showNotification(data.message, 'error');
            document.getElementById('loginPasscode').value = '';
        }
    } catch (error) {
        showNotification('Login failed', 'error');
    }
}

async function logoutUser() {
    try {
        await fetch(`${AUTH_API_URL}?action=logout`);
        currentUser = null;
        showLoginModal();
        // Hide all pages
        document.querySelectorAll('.page-content').forEach(p => {
            p.style.display = 'none';
        });
    } catch (error) {
        console.error('Logout error:', error);
    }
}

function showLoginModal() {
    document.getElementById('loginModal').classList.add('active');
}

function hideLoginModal() {
    document.getElementById('loginModal').classList.remove('active');
    document.getElementById('loginPasscode').value = '';
}

// ===== UPDATED UI FOR USER PERMISSIONS =====
function updateUIForUser() {
    if (!currentUser) return;

    // Update user profile display
    document.querySelector('.user-profile span').textContent = currentUser.name;
    document.querySelector('.user-profile img').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(currentUser.name)}&background=4F46E5&color=fff`;

    // Show/hide navigation based on role
    if (currentUser.role === 'supervisor') {
        document.getElementById('approvalsLink').style.display = 'flex';
        document.getElementById('scheduleLink').style.display = 'flex';
        document.getElementById('itemsLink').style.display = 'flex';
        document.getElementById('marginLink').style.display = 'flex';
        document.getElementById('uploadLink').style.display = 'flex';
    } else {
        document.getElementById('approvalsLink').style.display = 'none';
        document.getElementById('scheduleLink').style.display = 'none';
        document.getElementById('itemsLink').style.display = 'flex';
        document.getElementById('marginLink').style.display = 'none';
        document.getElementById('uploadLink').style.display = 'none';
    }

    // Update notification badge
    updateNotificationBadge();

    // Refresh notifications every 60 seconds
    setInterval(updateNotificationBadge, 60000);
}

// ===== REPORT FUNCTIONS =====

// ===== UPDATED SHOW ADD REPORT MODAL =====
function showAddReportModal() {
    document.getElementById('reportDate').value = new Date().toISOString().split('T')[0];
    document.getElementById('reportClientSearch').value = '';
    document.getElementById('reportClient').value = '';
    document.getElementById('reportMethod').value = 'C';
    document.getElementById('reportDiscussion').value = '';
    document.getElementById('reportFeedback').value = '';
    document.getElementById('clientNotFound').style.display = 'none';
    document.getElementById('clientDropdown').classList.remove('active');

    // Load clients for autocomplete
    loadClientsForReport();

    openModal('addReportModal');
}

// ===== CLIENT AUTOCOMPLETE FOR REPORTS =====
let allClients = [];

async function loadClientsForReport() {
    try {
        const response = await fetch(`${API_URL}?action=getClients`);
        const data = await response.json();

        if (data.success) {
            allClients = data.clients;
            setupClientAutocomplete();
        }
    } catch (error) {
        console.error('Error loading clients:', error);
    }
}

function setupClientAutocomplete() {
    const searchInput = document.getElementById('reportClientSearch');
    const dropdown = document.getElementById('clientDropdown');
    const hiddenInput = document.getElementById('reportClient');
    const notFoundMsg = document.getElementById('clientNotFound');

    searchInput.addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase().trim();

        if (searchTerm.length < 2) {
            dropdown.classList.remove('active');
            hiddenInput.value = '';
            notFoundMsg.style.display = 'none';
            return;
        }

        const matches = allClients.filter(client =>
            client.client_name.toLowerCase().includes(searchTerm)
        ).slice(0, 10);

        if (matches.length > 0) {
            dropdown.innerHTML = matches.map(client => `
                <div class="client-dropdown-item" data-id="${client.id}">
                    <strong>${client.client_name}</strong>
                    <small>${client.contact || 'No contact'} • ${client.sales_person || 'Unassigned'}</small>
                </div>
            `).join('');
            dropdown.classList.add('active');
            notFoundMsg.style.display = 'none';

            // Add click handlers
            dropdown.querySelectorAll('.client-dropdown-item').forEach(item => {
                item.addEventListener('click', function () {
                    const clientId = this.dataset.id;
                    const clientName = this.querySelector('strong').textContent;

                    searchInput.value = clientName;
                    hiddenInput.value = clientId;
                    dropdown.classList.remove('active');
                    notFoundMsg.style.display = 'none';
                });
            });
        } else {
            dropdown.classList.remove('active');
            hiddenInput.value = '';
            notFoundMsg.style.display = 'block';
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('active');
        }
    });
}

// ===== UPDATED SUBMIT REPORT =====
async function submitReport() {
    const reportDate = document.getElementById('reportDate').value;
    const clientId = document.getElementById('reportClient').value;
    const method = document.getElementById('reportMethod').value;
    const discussion = document.getElementById('reportDiscussion').value;
    const feedback = document.getElementById('reportFeedback').value;

    if (!reportDate) {
        showNotification('Please select a report date', 'error');
        return;
    }

    if (!clientId) {
        showNotification('Please select a client from the list', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('action', 'addReport');
    formData.append('reportDate', reportDate);
    formData.append('clientId', clientId);
    formData.append('method', method);
    formData.append('discussion', discussion);
    formData.append('feedback', feedback);

    try {
        const response = await fetch(AUTH_API_URL, {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            closeModal('addReportModal');
            loadMyReports();
            loadReportStats();
            showNotification('Report submitted successfully', 'success');
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('Error submitting report', 'error');
    }
}

async function loadMyReports() {
    const dateFrom = document.getElementById('reportDateFrom').value;
    const dateTo = document.getElementById('reportDateTo').value;
    const search = document.getElementById('reportSearch')?.value || '';

    console.log('Loading reports with:', { dateFrom, dateTo, search }); // DEBUG

    try {
        const response = await fetch(`${AUTH_API_URL}?action=getMyReports&dateFrom=${dateFrom}&dateTo=${dateTo}&search=${encodeURIComponent(search)}`);
        const data = await response.json();

        console.log('Reports API Response:', data); // DEBUG

        if (data.success) {
            console.log('Number of reports:', data.reports.length); // DEBUG
            displayMyReports(data.reports);
        } else {
            console.error('API returned error:', data.message); // DEBUG
        }
    } catch (error) {
        console.error('Error loading reports:', error);
    }
}

function displayMyReports(reports) {
    const tbody = document.getElementById('reportsTableBody');

    if (!tbody) {
        console.error('ERROR: reportsTableBody element not found!');
        return;
    }

    if (!reports || reports.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;padding:40px;">No reports found</td></tr>';
        return;
    }

    tbody.innerHTML = reports.map(report => `
        <tr onclick="viewReportDetail(${report.id}, 'my')" style="cursor: pointer;">
            <td>${report.report_date}</td>
            <td>
                <strong>${report.client_name}</strong><br>
                <small style="color: var(--text-secondary);">${report.client_type || ''}</small>
            </td>
            <td>
                <span class="status-badge ${report.approved}">
                    ${report.approved.charAt(0).toUpperCase() + report.approved.slice(1)}
                </span>
            </td>
        </tr>
    `).join('');
}

function filterMyReports() {
    loadMyReports();
}

async function loadReportStats() {
    try {
        const response = await fetch(`${AUTH_API_URL}?action=getReportStats`);
        const data = await response.json();

        if (data.success) {
            document.getElementById('todayReportsCount').textContent = data.stats.todayReports;
            document.getElementById('monthReportsCount').textContent = data.stats.monthReports;
            document.getElementById('pendingCount').textContent = data.stats.pending;
            document.getElementById('approvedCount').textContent = data.stats.approved;

            // Update notification badge
            updateNotificationBadge();
        }
    } catch (error) {
        console.error('Error loading report stats:', error);
    }
}

// ===== EXPORT MY REPORTS WITH PROPER FILENAME =====
async function exportMyReports() {
    const dateFrom = document.getElementById('reportDateFrom').value;
    const dateTo = document.getElementById('reportDateTo').value;

    try {
        const response = await fetch(`${AUTH_API_URL}?action=getMyReports&dateFrom=${dateFrom}&dateTo=${dateTo}`);
        const data = await response.json();

        if (data.success) {
            const ws = XLSX.utils.json_to_sheet(data.reports.map(r => ({
                'Date': r.report_date,
                'Client': r.client_name,
                'Type': r.client_type,
                'Method': r.method === 'M' ? 'Met' : 'Call',
                'Discussion': r.discussion || '',
                'Feedback': r.feedback || '',
                'Status': r.approved
            })));

            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'My Reports');

            const today = new Date().toISOString().split('T')[0];
            XLSX.writeFile(wb, `my_reports_${today}.xlsx`);

            showNotification('Reports exported successfully', 'success');
        }
    } catch (error) {
        showNotification('Error exporting reports', 'error');
    }
}

// ===== APPROVAL FUNCTIONS (SUPERVISOR) =====

async function loadAllReports() {
    const salesPersonId = document.getElementById('approvalSalesPerson').value;
    const status = document.getElementById('approvalStatus').value;
    const dateFrom = document.getElementById('approvalDateFrom').value;
    const dateTo = document.getElementById('approvalDateTo').value;

    try {
        const response = await fetch(`${AUTH_API_URL}?action=getAllReports&salesPersonId=${salesPersonId}&status=${status}&dateFrom=${dateFrom}&dateTo=${dateTo}`);
        const data = await response.json();

        if (data.success) {
            displayApprovals(data.reports);
        }
    } catch (error) {
        console.error('Error loading reports:', error);
    }
}

function displayApprovals(reports) {
    const tbody = document.getElementById('approvalsTableBody');

    if (!reports || reports.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:40px;">No reports found</td></tr>';
        return;
    }

    tbody.innerHTML = reports.map(report => `
        <tr onclick="viewReportDetail(${report.id}, 'approval')" style="cursor: pointer;">
            <td>${report.report_date}</td>
            <td><strong>${report.sales_person_name}</strong></td>
            <td>
                <strong>${report.client_name}</strong><br>
                <small style="color: var(--text-secondary);">${report.client_type}</small>
            </td>
            <td>
                <span class="status-badge ${report.approved}">
                    ${report.approved.charAt(0).toUpperCase() + report.approved.slice(1)}
                </span>
            </td>
        </tr>
    `).join('');
}
function filterApprovals() {
    loadAllReports();
}

async function approveReport(reportId) {
    if (!confirm('Approve this report?')) return;

    const formData = new FormData();
    formData.append('action', 'approveReport');
    formData.append('reportId', reportId);

    try {
        const response = await fetch(AUTH_API_URL, {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            loadAllReports();
            showNotification('Report approved', 'success');
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('Error approving report', 'error');
    }
}

async function rejectReport(reportId) {
    if (!confirm('Reject this report?')) return;

    const formData = new FormData();
    formData.append('action', 'rejectReport');
    formData.append('reportId', reportId);

    try {
        const response = await fetch(AUTH_API_URL, {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            loadAllReports();
            showNotification('Report rejected', 'success');
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('Error rejecting report', 'error');
    }
}

// ===== USER MANAGEMENT FUNCTIONS (SUPERVISOR) =====

async function loadSalesPersons() {
    try {
        const response = await fetch(`${AUTH_API_URL}?action=getSalesPersons`);
        const data = await response.json();

        if (data.success) {
            displaySalesPersons(data.persons);
            populateApprovalSalesPersonFilter(data.persons);
        }
    } catch (error) {
        console.error('Error loading sales persons:', error);
    }
}

function populateApprovalSalesPersonFilter(persons) {
    const filter = document.getElementById('approvalSalesPerson');
    if (!filter) return;

    const salespeople = persons.filter(p => p.role === 'salesperson');
    filter.innerHTML = '<option value="">All Sales Persons</option>' +
        salespeople.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
}

// ===== CHANGE PASSCODE FUNCTIONS =====

function showChangePasscodeModal() {
    if (!currentUser) return;

    document.getElementById('currentPasscode').value = '';
    document.getElementById('newPasscode').value = '';
    document.getElementById('confirmPasscode').value = '';
    openModal('changePasscodeModal');
}

async function changePasscode() {
    const currentPasscode = document.getElementById('currentPasscode').value;
    const newPasscode = document.getElementById('newPasscode').value;
    const confirmPasscode = document.getElementById('confirmPasscode').value;

    // Validation
    if (!currentPasscode || !newPasscode || !confirmPasscode) {
        showNotification('Please fill all fields', 'error');
        return;
    }

    if (newPasscode !== confirmPasscode) {
        showNotification('New passcodes do not match', 'error');
        return;
    }

    if (newPasscode.length < 4) {
        showNotification('New passcode must be at least 4 characters', 'error');
        return;
    }

    if (currentPasscode === newPasscode) {
        showNotification('New passcode must be different from current passcode', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('action', 'changePasscode');
    formData.append('currentPasscode', currentPasscode);
    formData.append('newPasscode', newPasscode);

    try {
        const response = await fetch(AUTH_API_URL, {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            closeModal('changePasscodeModal');
            showNotification('Passcode changed successfully', 'success');
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('Error changing passcode', 'error');
    }
}

// ===== STATS CAROUSEL FUNCTIONS =====

let currentCarouselPage = 0;
let cardsPerPage = 3;
let totalCards = 4;
let carouselInitialized = false;

function initStatsCarousel() {
    if (carouselInitialized) return; // Prevent double initialization

    updateCardsPerPage();

    // Hide all cards initially, then show first page
    const carousel = document.getElementById('statsCarousel');
    const allCards = carousel.querySelectorAll('.stat-card');
    allCards.forEach(card => {
        card.style.display = 'none';
    });

    // Show first page
    showCurrentPage();
    updateCarouselButtons();
    createCarouselIndicators();

    carouselInitialized = true;

    // Update on window resize
    window.addEventListener('resize', handleCarouselResize);
}

function handleCarouselResize() {
    const oldCardsPerPage = cardsPerPage;
    updateCardsPerPage();

    // Only reset if cards per page actually changed
    if (oldCardsPerPage !== cardsPerPage) {
        currentCarouselPage = 0;
        showCurrentPage();
        updateCarouselButtons();
        createCarouselIndicators();
    }
}

function updateCardsPerPage() {
    const width = window.innerWidth;
    if (width > 1024) {
        cardsPerPage = 3;
    } else {
        cardsPerPage = 2;
    }
}

function showCurrentPage() {
    const carousel = document.getElementById('statsCarousel');
    const allCards = carousel.querySelectorAll('.stat-card');
    const startIndex = currentCarouselPage * cardsPerPage;
    const endIndex = startIndex + cardsPerPage;

    allCards.forEach((card, index) => {
        if (index >= startIndex && index < endIndex) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function scrollStatsCarousel(direction) {
    const totalPages = Math.ceil(totalCards / cardsPerPage);

    currentCarouselPage += direction;

    // Boundary checks
    if (currentCarouselPage < 0) currentCarouselPage = 0;
    if (currentCarouselPage >= totalPages) currentCarouselPage = totalPages - 1;

    showCurrentPage();
    updateCarouselButtons();
    updateCarouselIndicators();
}

function updateCarouselButtons() {
    const totalPages = Math.ceil(totalCards / cardsPerPage);
    const prevBtn = document.getElementById('statsPrevBtn');
    const nextBtn = document.getElementById('statsNextBtn');

    // Hide buttons if only one page
    if (totalPages <= 1) {
        prevBtn.style.display = 'none';
        nextBtn.style.display = 'none';
        return;
    }

    prevBtn.style.display = 'flex';
    nextBtn.style.display = 'flex';

    prevBtn.disabled = currentCarouselPage === 0;
    nextBtn.disabled = currentCarouselPage === totalPages - 1;
}

function createCarouselIndicators() {
    const totalPages = Math.ceil(totalCards / cardsPerPage);
    const indicatorsContainer = document.getElementById('carouselIndicators');

    if (totalPages <= 1) {
        indicatorsContainer.innerHTML = '';
        return;
    }

    indicatorsContainer.innerHTML = '';
    for (let i = 0; i < totalPages; i++) {
        const dot = document.createElement('div');
        dot.className = 'indicator-dot';
        if (i === currentCarouselPage) dot.classList.add('active');
        dot.onclick = () => goToCarouselPage(i);
        indicatorsContainer.appendChild(dot);
    }
}

function updateCarouselIndicators() {
    const dots = document.querySelectorAll('.indicator-dot');
    dots.forEach((dot, index) => {
        if (index === currentCarouselPage) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
}

function goToCarouselPage(pageIndex) {
    const totalPages = Math.ceil(totalCards / cardsPerPage);
    if (pageIndex >= 0 && pageIndex < totalPages) {
        currentCarouselPage = pageIndex;
        showCurrentPage();
        updateCarouselButtons();
        updateCarouselIndicators();
    }
}

// ===== CLIENT DETAIL VIEW FUNCTIONS =====

let currentClientDetail = null;

async function viewClientDetail(clientId) {
    try {
        const response = await fetch(`${API_URL}?action=getClient&id=${clientId}`);
        const data = await response.json();

        if (data.success) {
            currentClientDetail = data.client;
            displayClientDetail(data.client);
            loadClientActivity(clientId);

            // Navigate to detail page
            document.querySelectorAll('.page-content').forEach(p => p.style.display = 'none');
            document.getElementById('clientDetailPage').style.display = 'block';
            document.querySelector('.page-title').textContent = 'Client Details';
        }
    } catch (error) {
        console.error('Error loading client details:', error);
        showNotification('Error loading client details', 'error');
    }
}

function displayClientDetail(client) {
    // Profile Section
    const avatar = document.getElementById('clientAvatar');
    avatar.innerHTML = `<i class="fas fa-user"></i>`;
    avatar.style.background = `linear-gradient(135deg, ${getClientColor(client.client_name)}, ${getClientColorDark(client.client_name)})`;

    document.getElementById('clientDetailName').textContent = client.client_name;
    document.getElementById('clientDetailCategory').textContent = client.client_type || 'Regular Client';

    // Contact Information
    document.getElementById('clientDetailPhone').textContent = client.contact || 'Not provided';
    document.getElementById('clientDetailAddress').textContent = client.address || 'Not provided';

    // Business Information
    document.getElementById('clientDetailSalesPerson').textContent = client.sales_person || 'Unassigned';
    document.getElementById('clientDetailType').textContent = client.client_type || 'Regular';

    // Activity Summary
    document.getElementById('clientDetailOrders').textContent = client.total_orders || '0';
    document.getElementById('clientDetailLastOrder').textContent = client.last_order_date
        ? formatDate(client.last_order_date)
        : 'No orders yet';
    document.getElementById('clientDetailCreated').textContent = formatDate(client.created_at);

    // Update action buttons
    const hasPhone = client.contact && client.contact.trim() !== '';
    document.getElementById('detailCallBtn').disabled = !hasPhone;
    document.getElementById('detailWhatsappBtn').disabled = !hasPhone;
}

async function loadClientActivity(clientId) {
    const activityContainer = document.getElementById('clientRecentActivity');

    try {
        const response = await fetch(`${API_URL}?action=getClientActivity&clientId=${clientId}`);
        const data = await response.json();

        if (data.success && data.activities && data.activities.length > 0) {
            activityContainer.innerHTML = data.activities.map(activity => `
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-${activity.type === 'order' ? 'shopping-cart' : 'calendar-check'}"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">${formatDate(activity.date)}</div>
                        <div class="timeline-title">${activity.title}</div>
                        <div class="timeline-description">${activity.description || ''}</div>
                    </div>
                </div>
            `).join('');
        } else {
            activityContainer.innerHTML = '<p style="text-align: center; color: var(--text-secondary); padding: 20px;">No recent activity</p>';
        }
    } catch (error) {
        console.error('Error loading client activity:', error);
        activityContainer.innerHTML = '<p style="text-align: center; color: var(--danger-color); padding: 20px;">Error loading activity</p>';
    }
}

function editClientFromDetail() {
    if (currentClientDetail) {
        editClient(currentClientDetail.id);
    }
}

function deleteClientFromDetail() {
    if (currentClientDetail) {
        deleteClient(currentClientDetail.id);
    }
}

function callClient() {
    if (currentClientDetail && currentClientDetail.contact) {
        const phoneNumber = currentClientDetail.contact.replace(/\s+/g, '');
        window.location.href = `tel:${phoneNumber}`;
    } else {
        showNotification('No phone number available', 'error');
    }
}

function whatsappClient() {
    if (currentClientDetail && currentClientDetail.contact) {
        let phoneNumber = currentClientDetail.contact.replace(/\s+/g, '').replace(/\+/g, '');

        // Add country code if not present (assuming Uganda +256)
        if (!phoneNumber.startsWith('256')) {
            if (phoneNumber.startsWith('0')) {
                phoneNumber = '256' + phoneNumber.substring(1);
            } else {
                phoneNumber = '256' + phoneNumber;
            }
        }

        const message = encodeURIComponent(`Hello ${currentClientDetail.client_name}, `);
        window.open(`https://wa.me/${phoneNumber}?text=${message}`, '_blank');
    } else {
        showNotification('No phone number available', 'error');
    }
}

// Helper Functions
function getClientColor(name) {
    const colors = ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#3B82F6', '#EC4899'];
    const hash = name.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0);
    return colors[hash % colors.length];
}

function getClientColorDark(name) {
    const colors = ['#4338CA', '#059669', '#D97706', '#DC2626', '#7C3AED', '#2563EB', '#DB2777'];
    const hash = name.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0);
    return colors[hash % colors.length];
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

// ===== REPORT DETAIL VIEW FUNCTIONS =====

let currentReportDetail = null;
let returnToPage = 'reports'; // Track where to return to

async function viewReportDetail(reportId, source = 'my') {
    returnToPage = source === 'approval' ? 'approvals' : 'reports';

    try {
        // Determine which API call to use based on source
        const action = source === 'approval' ? 'getAllReports' : 'getMyReports';
        const response = await fetch(`${AUTH_API_URL}?action=${action}`);
        const data = await response.json();

        if (data.success) {
            const report = data.reports.find(r => r.id === reportId);
            if (report) {
                currentReportDetail = report;
                displayReportDetail(report, source);

                // Navigate to detail page
                document.querySelectorAll('.page-content').forEach(p => p.style.display = 'none');
                document.getElementById('reportDetailPage').style.display = 'block';
                document.querySelector('.page-title').textContent = 'Report Details';
            }
        }
    } catch (error) {
        console.error('Error loading report details:', error);
        showNotification('Error loading report details', 'error');
    }
}

function displayReportDetail(report, source) {
    // Profile Section
    const avatar = document.getElementById('reportAvatar');
    avatar.innerHTML = `<i class="fas fa-file-alt"></i>`;

    // Set avatar color based on status
    let avatarColor = '#4F46E5';
    if (report.approved === 'approved') avatarColor = '#10B981';
    if (report.approved === 'rejected') avatarColor = '#EF4444';
    if (report.approved === 'pending') avatarColor = '#F59E0B';

    avatar.style.background = `linear-gradient(135deg, ${avatarColor}, ${avatarColor}dd)`;

    document.getElementById('reportDetailClient').textContent = report.client_name;
    document.getElementById('reportDetailDate').textContent = formatDate(report.report_date);

    // Report Information
    document.getElementById('reportDetailReportDate').textContent = formatDate(report.report_date);
    document.getElementById('reportDetailClientName').textContent = report.client_name;
    document.getElementById('reportDetailSalesPerson').textContent = report.sales_person_name || '-';
    document.getElementById('reportDetailMethod').textContent = report.method === 'M' ? 'Meeting (M)' : 'Call (C)';

    // Status with badge
    const statusElement = document.getElementById('reportDetailStatus');
    statusElement.innerHTML = `<span class="status-badge ${report.approved}">
        ${report.approved.charAt(0).toUpperCase() + report.approved.slice(1)}
    </span>`;

    // Discussion and Feedback
    document.getElementById('reportDetailDiscussion').textContent = report.discussion || 'No discussion points provided';
    document.getElementById('reportDetailFeedback').textContent = report.feedback || 'No feedback provided';

    // Approval Information
    const approvalInfo = document.getElementById('reportApprovalInfo');
    if (report.approved !== 'pending' && report.approved_by_name) {
        approvalInfo.style.display = 'block';
        document.getElementById('reportDetailApprovedBy').textContent = report.approved_by_name;
        document.getElementById('reportDetailApprovedAt').textContent = formatDate(report.approved_at);
    } else {
        approvalInfo.style.display = 'none';
    }

    // Update action buttons based on source and status
    const actionsContainer = document.getElementById('reportDetailActions');
    actionsContainer.innerHTML = '';

    // Show approve/reject buttons only for supervisors on pending reports
    if (source === 'approval' && report.approved === 'pending' && currentUser && currentUser.role === 'supervisor') {
        actionsContainer.innerHTML = `
            <button class="btn-primary" onclick="approveReportFromDetail(${report.id})">
                <i class="fas fa-check"></i> Approve
            </button>
            <button class="btn-secondary delete" onclick="rejectReportFromDetail(${report.id})">
                <i class="fas fa-times"></i> Reject
            </button>
        `;
    }
}

function navigateBackFromReportDetail() {
    navigateToPage(returnToPage);
}

async function approveReportFromDetail(reportId) {
    if (!confirm('Approve this report?')) return;

    const formData = new FormData();
    formData.append('action', 'approveReport');
    formData.append('reportId', reportId);

    try {
        const response = await fetch(AUTH_API_URL, {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            showNotification('Report approved', 'success');
            navigateToPage('approvals');
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('Error approving report', 'error');
    }
}

async function rejectReportFromDetail(reportId) {
    if (!confirm('Reject this report?')) return;

    const formData = new FormData();
    formData.append('action', 'rejectReport');
    formData.append('reportId', reportId);

    try {
        const response = await fetch(AUTH_API_URL, {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            showNotification('Report rejected', 'success');
            navigateToPage('approvals');
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('Error rejecting report', 'error');
    }
}