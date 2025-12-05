<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Safety & Compliance Checklist</title>
    <link rel="stylesheet" href="checklistcss.css">
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <h1>🛡️ Safety & Compliance</h1>
            <span class="location-badge" id="currentLocation">Warehouse</span>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="tabs">
        <button class="tab active" onclick="switchTab('new')">New Inspection</button>
        <button class="tab" onclick="switchTab('history')">History</button>
        <button class="tab" onclick="switchTab('actions')">Actions</button>
        <button class="tab" onclick="switchTab('stats')">Stats</button>
    </div>

    <div class="container">
        <!-- New Inspection View -->
        <div id="newView" class="view active">
            <div class="card">
                <div class="card-header">
                    <h3>Start New Inspection</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <select class="form-input" id="location">
                            <option value="Warehouse">Warehouse</option>
                            <option value="Shop 08">Shop 08</option>
                            <option value="Main Office">Main Office</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Inspector Name</label>
                        <input type="text" class="form-input" id="inspectorName" placeholder="Your name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Overall Notes (Optional)</label>
                        <textarea class="form-input" id="overallNotes" placeholder="General observations..." rows="3"></textarea>
                    </div>
                    <button class="btn btn-primary" onclick="startInspection()">Start Inspection</button>
                </div>
            </div>

            <!-- Checklist (hidden until started) -->
            <div id="checklistContainer" style="display: none;">
                <div id="checklistItems"></div>
            </div>
        </div>

        <!-- History View -->
        <div id="historyView" class="view">
            <div class="form-group">
                <select class="form-input" id="historyFilter" onchange="loadHistory()">
                    <option value="">All Locations</option>
                    <option value="Warehouse">Warehouse</option>
                    <option value="Shop 08">Shop 08</option>
                    <option value="Main Office">Main Office</option>
                </select>
            </div>
            <div id="historyList"></div>
        </div>

        <!-- Actions View -->
        <div id="actionsView" class="view">
            <div class="form-group">
                <select class="form-input" id="actionFilter" onchange="loadActions()">
                    <option value="">All Actions</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>
            <div id="actionsList"></div>
        </div>

        <!-- Stats View -->
        <div id="statsView" class="view">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" id="statVisits">0</div>
                    <div class="stat-label">Recent Visits</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="statCompliance">0%</div>
                    <div class="stat-label">Compliance</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="statOpen">0</div>
                    <div class="stat-label">Open Actions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="statOverdue">0</div>
                    <div class="stat-label">Overdue</div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>Compliance Trend</h3>
                </div>
                <div class="card-body">
                    <p style="color: var(--gray-600); text-align: center; padding: 2rem;">Chart coming soon...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation (shown during inspection) -->
    <div class="bottom-nav" id="bottomNav" style="display: none;">
        <div class="nav-buttons">
            <button class="btn btn-outline" onclick="saveProgress()">Save Progress</button>
            <button class="btn btn-success" onclick="completeInspection()">Complete</button>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast"></div>

    <!-- Detail Modal -->
    <div class="modal" id="detailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Inspection Details</h3>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div id="modalBody"></div>
        </div>
    </div>

    <script src="checklist.js"></script>
</body>

</html>