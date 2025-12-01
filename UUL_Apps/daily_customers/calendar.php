<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christmas Calendar Distribution Tracker</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #c41e3a;
            --primary-dark: #9a1829;
            --secondary: #165b33;
            --bg-light: #f8f9fa;
            --bg-white: #ffffff;
            --text-dark: #212529;
            --text-muted: #6c757d;
            --border: #dee2e6;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --shadow: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
            padding: 1rem;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px var(--shadow);
        }

        header h1 {
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        header p {
            opacity: 0.9;
            font-size: clamp(0.875rem, 2vw, 1rem);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-white);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px var(--shadow);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px var(--shadow);
        }

        .stat-card h3 {
            font-size: 0.875rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .stat-card .value {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary);
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 1024px) {
            .main-content {
                grid-template-columns: 400px 1fr;
            }
        }

        .form-section, .table-section {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px var(--shadow);
        }

        .section-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--text-dark);
            border-bottom: 3px solid var(--primary);
            padding-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        input, select, textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
            font-family: inherit;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .company-input-wrapper {
            position: relative;
        }

        #companyList {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--bg-white);
            border: 2px solid var(--border);
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 10;
            box-shadow: 0 4px 6px var(--shadow);
        }

        .company-option {
            padding: 0.75rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .company-option:hover {
            background-color: var(--bg-light);
        }

        .company-option strong {
            color: var(--text-dark);
            display: block;
        }

        .company-option small {
            color: var(--text-muted);
        }

        .btn {
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(196, 30, 58, 0.3);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .table-wrapper {
            overflow-x: auto;
            margin-top: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        thead {
            background: var(--secondary);
            color: white;
        }

        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }

        tbody tr {
            transition: background-color 0.2s;
        }

        tbody tr:hover {
            background-color: var(--bg-light);
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-new {
            background-color: #d4edda;
            color: #155724;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-muted);
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            opacity: 0.3;
            margin-bottom: 1rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            animation: slideIn 0.3s ease-out;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid var(--success);
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid var(--danger);
        }

        @keyframes slideIn {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
        }

        .spinner {
            border: 3px solid var(--border);
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            body {
                padding: 0.5rem;
            }

            header {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .form-section, .table-section {
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                gap: 0.75rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-card .value {
                font-size: 2rem;
            }

            th, td {
                padding: 0.75rem 0.5rem;
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🎄 Christmas Calendar Distribution Tracker</h1>
            <!-- <p>Track and manage your calendar distributions this holiday season</p> -->
        </header>

        <div class="stats-grid" id="statsGrid">
            <div class="stat-card">
                <h3>Total Distributed</h3>
                <div class="value" id="totalStat">0</div>
            </div>
            <div class="stat-card">
                <h3>Companies Reached</h3>
                <div class="value" id="companiesStat">0</div>
            </div>
            <div class="stat-card">
                <h3>Today</h3>
                <div class="value" id="todayStat">0</div>
            </div>
            <div class="stat-card">
                <h3>This Month</h3>
                <div class="value" id="monthStat">0</div>
            </div>
        </div>

        <div class="main-content">
            <div class="form-section">
                <h2 class="section-title">Add New Distribution</h2>
                <div id="alertContainer"></div>
                <form id="calendarForm">
                    <div class="form-group">
                        <label for="recipientName">Recipient Name *</label>
                        <input type="text" id="recipientName" required placeholder="Enter recipient name">
                    </div>

                    <div class="form-group">
                        <label for="contact">Contact *</label>
                        <input type="text" id="contact" required placeholder="Phone or email">
                    </div>

                    <div class="form-group">
                        <label for="companyName">Company *</label>
                        <div class="company-input-wrapper">
                            <input type="text" id="companyName" required placeholder="Select or enter company name" autocomplete="off">
                            <div id="companyList" style="display: none;"></div>
                        </div>
                        <input type="hidden" id="companyId">
                    </div>

                    <div class="form-group">
                        <label for="issueDate">Date of Issue *</label>
                        <input type="date" id="issueDate" required>
                    </div>

                    <div class="form-group">
                        <label for="otherComment">Other Comments</label>
                        <textarea id="otherComment" placeholder="Any additional notes..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Record Distribution</button>
                </form>
            </div>

            <div class="table-section">
                <h2 class="section-title">Distribution Records</h2>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Recipient</th>
                                <th>Contact</th>
                                <th>Company</th>
                                <th>Comments</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="recordsTable">
                            <tr>
                                <td colspan="6" class="loading">
                                    <div class="spinner"></div>
                                    Loading records...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_URL = 'calendar_api.php';
        let companies = [];

        // Set today's date as default
        document.getElementById('issueDate').valueAsDate = new Date();

        // Load initial data
        loadCompanies();
        loadCalendars();
        loadStats();

        // Company autocomplete
        const companyInput = document.getElementById('companyName');
        const companyList = document.getElementById('companyList');
        const companyIdInput = document.getElementById('companyId');

        companyInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            if (searchTerm.length === 0) {
                companyList.style.display = 'none';
                companyIdInput.value = '';
                return;
            }

            const filtered = companies.filter(c => 
                c.client_name.toLowerCase().includes(searchTerm)
            );

            if (filtered.length > 0) {
                companyList.innerHTML = filtered.map(c => `
                    <div class="company-option" data-id="${c.id}" data-name="${c.client_name}">
                        <strong>${c.client_name}</strong>
                        ${c.contact ? `<small>${c.contact}</small>` : ''}
                    </div>
                `).join('');
                companyList.style.display = 'block';
            } else {
                companyList.innerHTML = `
                    <div class="company-option" data-id="" data-name="${this.value}">
                        <strong>Add "${this.value}" as new company</strong>
                        <small>This will be added to your database</small>
                    </div>
                `;
                companyList.style.display = 'block';
            }
        });

        companyList.addEventListener('click', function(e) {
            const option = e.target.closest('.company-option');
            if (option) {
                const name = option.dataset.name;
                const id = option.dataset.id;
                
                companyInput.value = name;
                companyIdInput.value = id;
                companyList.style.display = 'none';
            }
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.company-input-wrapper')) {
                companyList.style.display = 'none';
            }
        });

        // Form submission
        document.getElementById('calendarForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const data = {
                recipient_name: document.getElementById('recipientName').value,
                contact: document.getElementById('contact').value,
                company_name: document.getElementById('companyName').value,
                company_id: document.getElementById('companyId').value || null,
                issue_date: document.getElementById('issueDate').value,
                other_comment: document.getElementById('otherComment').value
            };

            try {
                const response = await fetch(`${API_URL}?action=add_calendar`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Calendar distribution recorded successfully!', 'success');
                    this.reset();
                    document.getElementById('issueDate').valueAsDate = new Date();
                    companyIdInput.value = '';
                    loadCalendars();
                    loadStats();
                    loadCompanies(); // Refresh in case new company was added
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Error submitting form: ' + error.message, 'error');
            }
        });

        async function loadCompanies() {
            try {
                const response = await fetch(`${API_URL}?action=get_companies`);
                const result = await response.json();
                if (result.success) {
                    companies = result.companies;
                }
            } catch (error) {
                console.error('Error loading companies:', error);
            }
        }

        async function loadCalendars() {
            try {
                const response = await fetch(`${API_URL}?action=get_calendars`);
                const result = await response.json();

                if (result.success) {
                    const tbody = document.getElementById('recordsTable');
                    
                    if (result.calendars.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <div>📅</div>
                                    <p>No distribution records yet. Start adding calendars!</p>
                                </td>
                            </tr>
                        `;
                    } else {
                        tbody.innerHTML = result.calendars.map(cal => `
                            <tr>
                                <td>${formatDate(cal.issue_date)}</td>
                                <td><strong>${cal.recipient_name}</strong></td>
                                <td>${cal.contact}</td>
                                <td>
                                    ${cal.company_name}
                                    ${cal.client_type ? '' : '<span class="badge badge-new">New</span>'}
                                </td>
                                <td>${cal.other_comment || '-'}</td>
                                <td>
                                    <button class="btn btn-danger" onclick="deleteRecord(${cal.id})">Delete</button>
                                </td>
                            </tr>
                        `).join('');
                    }
                }
            } catch (error) {
                console.error('Error loading calendars:', error);
            }
        }

        async function loadStats() {
            try {
                const response = await fetch(`${API_URL}?action=get_stats`);
                const result = await response.json();

                if (result.success) {
                    document.getElementById('totalStat').textContent = result.stats.total;
                    document.getElementById('companiesStat').textContent = result.stats.companies;
                    document.getElementById('todayStat').textContent = result.stats.today;
                    document.getElementById('monthStat').textContent = result.stats.this_month;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        async function deleteRecord(id) {
            if (!confirm('Are you sure you want to delete this record?')) return;

            try {
                const response = await fetch(`${API_URL}?action=delete_calendar`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id})
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Record deleted successfully', 'success');
                    loadCalendars();
                    loadStats();
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Error deleting record: ' + error.message, 'error');
            }
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        function showAlert(message, type) {
            const container = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            container.innerHTML = '';
            container.appendChild(alert);

            setTimeout(() => {
                alert.remove();
            }, 5000);
        }
    </script>
</body>
</html>