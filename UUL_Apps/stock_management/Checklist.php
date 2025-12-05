<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operations Inspection Checklist</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 0;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            background: white;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .header-stats {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            font-size: 0.75rem;
        }

        .stat-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        /* Navigation */
        .nav-tabs {
            display: flex;
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .nav-tab {
            flex: 1;
            min-width: 100px;
            padding: 1rem;
            text-align: center;
            background: none;
            border: none;
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
        }

        .nav-tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
            background: white;
        }

        /* Content */
        .content {
            padding: 1rem;
            padding-bottom: 5rem;
        }

        .view {
            display: none;
        }

        .view.active {
            display: block;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .dashboard-card {
            background: white;
            padding: 1.25rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .dashboard-card .number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .dashboard-card .label {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .dashboard-card.warning .number {
            color: #f59e0b;
        }

        .dashboard-card.danger .number {
            color: #ef4444;
        }

        .dashboard-card.success .number {
            color: #10b981;
        }

        /* Location Selection */
        .location-card {
            background: white;
            padding: 1.25rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .location-card:active {
            transform: scale(0.98);
        }

        .location-card.selected {
            border-color: #667eea;
            background: #f0f4ff;
        }

        .location-card h3 {
            font-size: 1.125rem;
            margin-bottom: 0.5rem;
            color: #1f2937;
        }

        .location-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .location-badge.warehouse {
            background: #dbeafe;
            color: #1e40af;
        }

        .location-badge.shop {
            background: #fef3c7;
            color: #92400e;
        }

        /* Checklist */
        .checklist-category {
            background: white;
            border-radius: 12px;
            margin-bottom: 1rem;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .category-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            font-weight: 600;
            font-size: 1rem;
        }

        .checklist-item {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .checklist-item:last-child {
            border-bottom: none;
        }

        .item-description {
            font-size: 0.9rem;
            color: #374151;
            margin-bottom: 0.75rem;
            line-height: 1.5;
        }

        .status-buttons {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .status-btn {
            flex: 1;
            padding: 0.625rem;
            border: 2px solid #e5e7eb;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }

        .status-btn:active {
            transform: scale(0.95);
        }

        .status-btn.compliant {
            border-color: #10b981;
            background: #d1fae5;
            color: #065f46;
        }

        .status-btn.non-compliant {
            border-color: #ef4444;
            background: #fee2e2;
            color: #991b1b;
        }

        .status-btn.na {
            border-color: #9ca3af;
            background: #f3f4f6;
            color: #4b5563;
        }

        .item-notes {
            width: 100%;
            padding: 0.625rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-family: inherit;
            resize: vertical;
            min-height: 60px;
        }

        .item-notes:focus {
            outline: none;
            border-color: #667eea;
        }

        /* Inspector Info */
        .inspector-form {
            background: white;
            padding: 1.25rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.875rem;
            font-family: inherit;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #667eea;
        }

        /* Buttons */
        .btn {
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #4b5563;
        }

        /* History */
        .history-item {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .history-item:active {
            transform: scale(0.98);
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .history-date {
            font-weight: 600;
            color: #1f2937;
        }

        .history-inspector {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .history-stats {
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stat-compliant {
            color: #10b981;
        }

        .stat-non-compliant {
            color: #ef4444;
        }

        /* Status Badge */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-badge.excellent {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.good {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.needs_improvement {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.critical {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Follow-up Actions */
        .action-card {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            border-left: 4px solid #e5e7eb;
        }

        .action-card.priority-critical {
            border-left-color: #ef4444;
        }

        .action-card.priority-high {
            border-left-color: #f59e0b;
        }

        .action-card.priority-medium {
            border-left-color: #3b82f6;
        }

        .action-item-text {
            font-size: 0.875rem;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .action-meta {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            font-size: 0.75rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .priority-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
            font-weight: 600;
        }

        .priority-critical {
            background: #fee2e2;
            color: #991b1b;
        }

        .priority-high {
            background: #fef3c7;
            color: #92400e;
        }

        .priority-medium {
            background: #dbeafe;
            color: #1e40af;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            flex: 1;
        }

        /* Loading */
        .loading {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            animation: fadeIn 0.3s;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 1.5rem;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6c757d;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .detail-value {
            color: #1f2937;
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (min-width: 768px) {
            .container {
                max-width: 768px;
                border-radius: 12px;
                margin-top: 2rem;
                margin-bottom: 2rem;
                min-height: calc(100vh - 4rem);
            }

            .header {
                border-radius: 12px 12px 0 0;
            }

            .header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🏭 Operations Inspection</h1>
            <div class="header-stats">
                <span class="stat-badge">📊 <span id="monthInspections">0</span> inspections</span>
                <span class="stat-badge">✓ <span id="complianceRate">0</span>% compliance</span>
                <span class="stat-badge">⚠️ <span id="pendingActions">0</span> pending</span>
            </div>
        </div>

        <!-- Navigation -->
        <div class="nav-tabs">
            <button class="nav-tab active" onclick="switchView('dashboard')">Dashboard</button>
            <button class="nav-tab" onclick="switchView('new')">New Inspection</button>
            <button class="nav-tab" onclick="switchView('history')">History</button>
            <button class="nav-tab" onclick="switchView('actions')">Follow-ups</button>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Dashboard View -->
            <div id="dashboard" class="view active">
                <div class="dashboard-grid">
                    <div class="dashboard-card">
                        <div class="number" id="dashTotalInspections">0</div>
                        <div class="label">This Month</div>
                    </div>
                    <div class="dashboard-card warning">
                        <div class="number" id="dashPendingActions">0</div>
                        <div class="label">Pending Actions</div>
                    </div>
                    <div class="dashboard-card danger">
                        <div class="number" id="dashOverdueActions">0</div>
                        <div class="label">Overdue</div>
                    </div>
                    <div class="dashboard-card success">
                        <div class="number"><span id="dashComplianceRate">0</span>%</div>
                        <div class="label">Compliance Rate</div>
                    </div>
                </div>

                <h2 style="font-size: 1.125rem; margin-bottom: 1rem; color: #1f2937;">Quick Actions</h2>
                <button class="btn btn-primary" onclick="switchView('new')">📋 Start New Inspection</button>
                <button class="btn btn-secondary" onclick="switchView('actions')">⚠️ View Pending Actions</button>
            </div>

            <!-- New Inspection View -->
            <div id="new" class="view">
                <!-- Step 1: Location Selection -->
                <div id="step1">
                    <h2 style="font-size: 1.125rem; margin-bottom: 1rem; color: #1f2937;">Select Location</h2>
                    <div id="locationsList"></div>
                </div>

                <!-- Step 2: Inspector Info -->
                <div id="step2" style="display:none;">
                    <button onclick="goToStep(1)" style="background:none;border:none;color:#667eea;margin-bottom:1rem;cursor:pointer;">← Back</button>
                    <div class="inspector-form">
                        <div class="form-group">
                            <label class="form-label">Inspector Name</label>
                            <input type="text" id="inspectorName" class="form-input" placeholder="Enter your name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Inspection Date</label>
                            <input type="date" id="inspectionDate" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Overall Status</label>
                            <select id="overallStatus" class="form-select">
                                <option value="excellent">Excellent</option>
                                <option value="good" selected>Good</option>
                                <option value="needs_improvement">Needs Improvement</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">General Notes (Optional)</label>
                            <textarea id="generalNotes" class="item-notes" placeholder="Any general observations..."></textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary" onclick="goToStep(3)">Continue to Checklist</button>
                </div>

                <!-- Step 3: Checklist -->
                <div id="step3" style="display:none;">
                    <button onclick="goToStep(2)" style="background:none;border:none;color:#667eea;margin-bottom:1rem;cursor:pointer;">← Back</button>
                    <div id="checklistContainer"></div>
                    <button class="btn btn-primary" onclick="submitInspection()">Submit Inspection</button>
                </div>
            </div>

            <!-- History View -->
            <div id="history" class="view">
                <div class="form-group">
                    <label class="form-label">Filter by Location</label>
                    <select id="historyLocationFilter" class="form-select" onchange="loadHistory()">
                        <option value="">All Locations</option>
                    </select>
                </div>
                <div id="historyList"></div>
            </div>

            <!-- Actions View -->
            <div id="actions" class="view">
                <div class="form-group">
                    <label class="form-label">Filter by Location</label>
                    <select id="actionsLocationFilter" class="form-select" onchange="loadPendingActions()">
                        <option value="">All Locations</option>
                    </select>
                </div>
                <div id="actionsList"></div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Inspection Details</h3>
                <button class="modal-close" onclick="closeModal()">×</button>
            </div>
            <div id="detailContent"></div>
        </div>
    </div>

    <script>
        // Configuration
        const API_URL = 'database.php?module=inspection';

        // State
        let currentStep = 1;
        let selectedLocation = null;
        let locations = [];
        let checklist = [];
        let inspectionResults = {};

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Set today's date
            document.getElementById('inspectionDate').valueAsDate = new Date();

            // Load initial data
            loadDashboardStats();
            loadLocations();
            loadChecklist();
        });

        // Navigation
        function switchView(viewName) {
            // Update tabs
            document.querySelectorAll('.nav-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            // Update views
            document.querySelectorAll('.view').forEach(view => {
                view.classList.remove('active');
            });
            document.getElementById(viewName).classList.add('active');

            // Load data for specific views
            if (viewName === 'history') {
                loadHistory();
            } else if (viewName === 'actions') {
                loadPendingActions();
            } else if (viewName === 'new') {
                resetInspectionForm();
            }
        }

        function goToStep(step) {
            document.getElementById(`step${currentStep}`).style.display = 'none';
            document.getElementById(`step${step}`).style.display = 'block';
            currentStep = step;

            if (step === 3) {
                renderChecklist();
            }
        }

        // API Calls
        async function apiCall(action, data = {}, method = 'GET') {
            try {
                const url = method === 'GET' ?
                    `${API_URL}?action=${action}&${new URLSearchParams(data)}` :
                    `${API_URL}?action=${action}`;

                const options = {
                    method: method,
                    headers: method === 'POST' ? {
                        'Content-Type': 'application/json'
                    } : {}
                };

                if (method === 'POST') {
                    options.body = JSON.stringify(data);
                }

                const response = await fetch(url, options);
                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.error || 'API call failed');
                }

                return result.data;
            } catch (error) {
                console.error('API Error:', error);
                alert('Error: ' + error.message);
                return null;
            }
        }

        // Dashboard
        async function loadDashboardStats() {
            const stats = await apiCall('get_stats');
            if (stats) {
                document.getElementById('monthInspections').textContent = stats.inspections_this_month;
                document.getElementById('complianceRate').textContent = stats.compliance_rate;
                document.getElementById('pendingActions').textContent = stats.pending_actions;

                document.getElementById('dashTotalInspections').textContent = stats.inspections_this_month;
                document.getElementById('dashPendingActions').textContent = stats.pending_actions;
                document.getElementById('dashOverdueActions').textContent = stats.overdue_actions;
                document.getElementById('dashComplianceRate').textContent = stats.compliance_rate;
            }
        }

        // Locations
        async function loadLocations() {
            locations = await apiCall('get_locations');
            if (locations) {
                renderLocations();
                populateLocationFilters();
            }
        }

        function renderLocations() {
            const container = document.getElementById('locationsList');
            container.innerHTML = locations.map(loc => `
                <div class="location-card" onclick="selectLocation(${loc.id})">
                    <h3>${loc.location_name}</h3>
                    <span class="location-badge ${loc.location_type}">${loc.location_type}</span>
                </div>
            `).join('');
        }

        function selectLocation(locationId) {
            selectedLocation = locations.find(l => l.id === locationId);
            document.querySelectorAll('.location-card').forEach(card => {
                card.classList.remove('selected');
            });
            event.target.closest('.location-card').classList.add('selected');

            setTimeout(() => goToStep(2), 300);
        }

        function populateLocationFilters() {
            const filters = [
                document.getElementById('historyLocationFilter'),
                document.getElementById('actionsLocationFilter')
            ];

            filters.forEach(filter => {
                filter.innerHTML = '<option value="">All Locations</option>' +
                    locations.map(loc => `<option value="${loc.id}">${loc.location_name}</option>`).join('');
            });
        }

        // Checklist
        async function loadChecklist() {
            checklist = await apiCall('get_checklist');
        }

        function renderChecklist() {
            const container = document.getElementById('checklistContainer');
            container.innerHTML = checklist.map(category => `
                <div class="checklist-category">
                    <div class="category-header">${category.category_name}</div>
                    ${category.items.map(item => `
                        <div class="checklist-item">
                            <div class="item-description">${item.description}</div>
                            <div class="status-buttons">
                                <button class="status-btn" onclick="setItemStatus(${item.id}, 'compliant')">
                                    ✓ Compliant
                                </button>
                                <button class="status-btn" onclick="setItemStatus(${item.id}, 'non_compliant')">
                                    ✗ Non-Compliant
                                </button>
                                <button class="status-btn" onclick="setItemStatus(${item.id}, 'na')">
                                    — N/A
                                </button>
                            </div>
                            <textarea 
                                class="item-notes" 
                                id="notes_${item.id}" 
                                placeholder="Add notes or observations..."
                                style="display:none;"
                            ></textarea>
                        </div>
                    `).join('')}
                </div>
            `).join('');
        }

        function setItemStatus(itemId, status) {
            const buttons = event.target.parentElement.querySelectorAll('.status-btn');
            buttons.forEach(btn => btn.classList.remove('compliant', 'non-compliant', 'na'));
            event.target.classList.add(status.replace('_', '-'));

            const notesField = document.getElementById(`notes_${itemId}`);
            notesField.style.display = status === 'non_compliant' ? 'block' : 'none';

            inspectionResults[itemId] = {
                item_id: itemId,
                status: status,
                notes: notesField.value
            };
        }

        // Submit Inspection
        async function submitInspection() {
            const inspectorName = document.getElementById('inspectorName').value;
            const inspectionDate = document.getElementById('inspectionDate').value;
            const overallStatus = document.getElementById('overallStatus').value;
            const generalNotes = document.getElementById('generalNotes').value;

            if (!inspectorName || !inspectionDate) {
                alert('Please fill in inspector name and date');
                return;
            }

            // Collect all notes
            Object.keys(inspectionResults).forEach(itemId => {
                const notes = document.getElementById(`notes_${itemId}`).value;
                if (notes) {
                    inspectionResults[itemId].notes = notes;
                }
            });

            const results = Object.values(inspectionResults);

            if (results.length === 0) {
                alert('Please complete the checklist');
                return;
            }

            const data = {
                location_id: selectedLocation.id,
                inspector_name: inspectorName,
                inspection_date: inspectionDate,
                overall_status: overallStatus,
                general_notes: generalNotes,
                results: results
            };

            const result = await apiCall('create_inspection', data, 'POST');
            if (result) {
                alert('Inspection submitted successfully!');
                resetInspectionForm();
                switchView('dashboard');
                loadDashboardStats();
            }
        }

        function resetInspectionForm() {
            currentStep = 1;
            selectedLocation = null;
            inspectionResults = {};
            document.getElementById('step1').style.display = 'block';
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step3').style.display = 'none';
            document.getElementById('inspectorName').value = '';
            document.getElementById('inspectionDate').valueAsDate = new Date();
            document.getElementById('overallStatus').value = 'good';
            document.getElementById('generalNotes').value = '';
            document.querySelectorAll('.location-card').forEach(card => {
                card.classList.remove('selected');
            });
        }

        // History
        async function loadHistory() {
            const locationId = document.getElementById('historyLocationFilter').value;
            const container = document.getElementById('historyList');

            container.innerHTML = '<div class="loading"><div class="spinner"></div>Loading...</div>';

            if (!locationId) {
                // Load all locations
                const allHistory = [];
                for (const loc of locations) {
                    const history = await apiCall('get_history', {
                        location_id: loc.id
                    });
                    if (history) {
                        allHistory.push(...history.map(h => ({
                            ...h,
                            location_name: loc.location_name
                        })));
                    }
                }
                renderHistory(allHistory);
            } else {
                const history = await apiCall('get_history', {
                    location_id: locationId
                });
                const loc = locations.find(l => l.id == locationId);
                renderHistory(history ? history.map(h => ({
                    ...h,
                    location_name: loc.location_name
                })) : []);
            }
        }

        function renderHistory(history) {
            const container = document.getElementById('historyList');

            if (!history || history.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>No inspections found</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = history.map(item => {
                const complianceRate = item.total_items > 0 ?
                    Math.round((item.compliant_items / item.total_items) * 100) :
                    0;

                return `
                    <div class="history-item" onclick="viewInspectionDetails(${item.id})">
                        <div class="history-header">
                            <div class="history-date">${formatDate(item.inspection_date)}</div>
                            <span class="status-badge ${item.overall_status}">${formatStatus(item.overall_status)}</span>
                        </div>
                        <div class="history-inspector">
                            <strong>${item.location_name || ''}</strong> • ${item.inspector_name}
                        </div>
                        <div class="history-stats">
                            <div class="stat-item stat-compliant">
                                ✓ ${item.compliant_items}
                            </div>
                            <div class="stat-item stat-non-compliant">
                                ✗ ${item.non_compliant_items}
                            </div>
                            <div class="stat-item">
                                ${complianceRate}% compliant
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Inspection Details
        async function viewInspectionDetails(inspectionId) {
            const details = await apiCall('get_details', {
                inspection_id: inspectionId
            });
            if (!details) return;

            const modal = document.getElementById('detailModal');
            const content = document.getElementById('detailContent');

            const groupedResults = {};
            details.results.forEach(result => {
                if (!groupedResults[result.category_name]) {
                    groupedResults[result.category_name] = [];
                }
                groupedResults[result.category_name].push(result);
            });

            content.innerHTML = `
                <div class="detail-row">
                    <span class="detail-label">Location</span>
                    <span class="detail-value">${details.location_name}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Inspector</span>
                    <span class="detail-value">${details.inspector_name}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value">${formatDate(details.inspection_date)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="status-badge ${details.overall_status}">${formatStatus(details.overall_status)}</span>
                </div>
                ${details.general_notes ? `
                    <div class="detail-row" style="flex-direction:column;align-items:flex-start;">
                        <span class="detail-label">General Notes</span>
                        <span class="detail-value" style="margin-top:0.5rem;">${details.general_notes}</span>
                    </div>
                ` : ''}
                
                <h3 style="margin-top:1.5rem;margin-bottom:1rem;font-size:1rem;">Checklist Results</h3>
                
                ${Object.keys(groupedResults).map(category => `
                    <div class="checklist-category" style="margin-bottom:1rem;">
                        <div class="category-header">${category}</div>
                        ${groupedResults[category].map(result => `
                            <div class="checklist-item">
                                <div class="item-description">${result.item_description}</div>
                                <div style="margin-top:0.5rem;">
                                    <span class="status-badge ${result.status.replace('_', '-')}">${formatStatus(result.status)}</span>
                                </div>
                                ${result.notes ? `
                                    <div style="margin-top:0.5rem;font-size:0.875rem;color:#6c757d;">
                                        <strong>Notes:</strong> ${result.notes}
                                    </div>
                                ` : ''}
                                ${result.action_required ? `
                                    <div style="margin-top:0.5rem;padding:0.5rem;background:#fef3c7;border-radius:8px;font-size:0.875rem;">
                                        <strong>Action Required:</strong> ${result.action_required}<br>
                                        <span style="font-size:0.75rem;color:#92400e;">
                                            Due: ${formatDate(result.due_date)} | 
                                            Status: ${formatStatus(result.action_status)}
                                        </span>
                                    </div>
                                ` : ''}
                            </div>
                        `).join('')}
                    </div>
                `).join('')}
            `;

            modal.classList.add('active');
        }

        function closeModal() {
            document.getElementById('detailModal').classList.remove('active');
        }

        // Follow-up Actions
        async function loadPendingActions() {
            const locationId = document.getElementById('actionsLocationFilter').value;
            const container = document.getElementById('actionsList');

            container.innerHTML = '<div class="loading"><div class="spinner"></div>Loading...</div>';

            const actions = await apiCall('get_pending_actions', locationId ? {
                location_id: locationId
            } : {});

            if (!actions || actions.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>No pending actions</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = actions.map(action => {
                const isOverdue = new Date(action.due_date) < new Date();
                return `
                    <div class="action-card priority-${action.priority}">
                        <div class="action-item-text">${action.item_description}</div>
                        <div style="font-weight:600;color:#1f2937;margin-bottom:0.5rem;">
                            ${action.action_required}
                        </div>
                        <div class="action-meta">
                            <span class="priority-badge priority-${action.priority}">
                                ${action.priority.toUpperCase()}
                            </span>
                            <span>${action.location_name}</span>
                            <span>Due: ${formatDate(action.due_date)}</span>
                            ${isOverdue ? '<span style="color:#ef4444;font-weight:600;">⚠️ OVERDUE</span>' : ''}
                        </div>
                        ${action.assigned_to ? `<div style="font-size:0.875rem;color:#6c757d;margin-top:0.5rem;">Assigned to: ${action.assigned_to}</div>` : ''}
                        <div class="action-buttons">
                            <button class="btn btn-small btn-secondary" onclick="updateActionStatus(${action.id}, 'in_progress')">
                                In Progress
                            </button>
                            <button class="btn btn-small btn-primary" onclick="completeAction(${action.id})">
                                Complete
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        async function updateActionStatus(actionId, status) {
            const result = await apiCall('update_action', {
                action_id: actionId,
                status: status
            }, 'POST');

            if (result) {
                loadPendingActions();
                loadDashboardStats();
            }
        }

        async function completeAction(actionId) {
            const notes = prompt('Completion notes (optional):');

            const result = await apiCall('update_action', {
                action_id: actionId,
                status: 'completed',
                completion_notes: notes
            }, 'POST');

            if (result) {
                alert('Action marked as complete!');
                loadPendingActions();
                loadDashboardStats();
            }
        }

        // Utility Functions
        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            return date.toLocaleDateString('en-US', options);
        }

        function formatStatus(status) {
            const statusMap = {
                'compliant': 'Compliant',
                'non_compliant': 'Non-Compliant',
                'na': 'N/A',
                'excellent': 'Excellent',
                'good': 'Good',
                'needs_improvement': 'Needs Improvement',
                'critical': 'Critical',
                'pending': 'Pending',
                'in_progress': 'In Progress',
                'completed': 'Completed',
                'overdue': 'Overdue'
            };
            return statusMap[status] || status;
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>

</html>