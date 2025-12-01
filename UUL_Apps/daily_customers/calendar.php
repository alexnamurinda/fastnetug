<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christmas Calendar Distribution Tracker</title>
    <link rel="stylesheet" href="calendar_css.css">
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
            <div class="table-section">
                <div class="section-header">
                    <h2 class="section-title">Distribution Records</h2>
                    <button class="btn btn-primary" id="openModalBtn">
                        <span class="btn-icon">+</span> Add Distribution
                    </button>
                </div>
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

    <!-- Modal -->
    <div id="formModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Distribution</h2>
                <button class="close-btn" id="closeModalBtn">&times;</button>
            </div>
            <div class="modal-body">
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

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                        <button type="submit" class="btn btn-primary">Record Distribution</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const API_URL = 'calendar_api.php';
        let companies = [];

        // Modal elements
        const modal = document.getElementById('formModal');
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');

        // Set today's date as default
        document.getElementById('issueDate').valueAsDate = new Date();

        // Load initial data
        loadCompanies();
        loadCalendars();
        loadStats();

        // Modal controls
        openModalBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });

        closeModalBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

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
                    loadCompanies();
                    
                    // Close modal after successful submission
                    setTimeout(() => {
                        closeModal();
                    }, 1500);
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