<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UUL Apps - Your Business Suite</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="launcher-styles.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <img src="logo.png" alt="UUL Ltd Logo" onerror="this.style.display='none'">
                <!-- <div class="logo-text">
                    <h1>UUL Apps</h1>
                    <span class="tagline">Business Suite</span>
                </div> -->
            </div>
            <div class="header-actions">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchApps" placeholder="Search apps..." oninput="searchApps()">
                </div>
                <button class="btn-icon" onclick="showModal()" title="Add New App">
                    <i class="fas fa-plus"></i>
                </button>
                <div class="user-profile" title="User Profile">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Stats Bar -->
        <!-- <div class="stats-bar">
            <div class="stat-item">
                <i class="fas fa-th-large"></i>
                <div class="stat-content">
                    <span class="stat-number" id="totalApps">0</span>
                    <span class="stat-label">Total Apps</span>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-star"></i>
                <div class="stat-content">
                    <span class="stat-number" id="activeApps">0</span>
                    <span class="stat-label">Active</span>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-clock"></i>
                <div class="stat-content">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Availability</span>
                </div>
            </div>
        </div> -->

        <!-- Category Tabs -->
        <div class="category-section">
            <div class="category-tabs" id="categoryTabs">
                <button class="category-tab active" data-category="all" onclick="filterByCategory('all')">
                    <i class="fas fa-th"></i>
                    <span>All Apps</span>
                </button>
                <button class="category-tab" data-category="sales" onclick="filterByCategory('sales')">
                    <i class="fas fa-chart-line"></i>
                    <span>Sales</span>
                </button>
                <button class="category-tab" data-category="finance" onclick="filterByCategory('finance')">
                    <i class="fas fa-dollar-sign"></i>
                    <span>Admin and Accounts</span>
                </button>
                <button class="category-tab" data-category="operations" onclick="filterByCategory('operations')">
                    <i class="fas fa-cogs"></i>
                    <span>Operations</span>
                </button>
                <!-- <button class="category-tab" data-category="hr" onclick="filterByCategory('hr')">
                    <i class="fas fa-users"></i>
                    <span>HR</span>
                </button> -->
                <button class="category-tab" data-category="custom" onclick="filterByCategory('custom')">
                    <i class="fas fa-star"></i>
                    <span>Custom</span>
                </button>
            </div>
        </div>

        <!-- Section Title -->
        <div class="section-title">
            <h2 id="sectionTitle">UUL Apps</h2>
            <span class="app-count" id="appCount">0 apps</span>
        </div>

        <!-- Apps Grid -->
        <div class="apps-grid" id="appsGrid">
            <!-- Apps will be loaded here -->
        </div>

        <!-- Quick Add Section -->
        <!-- <div class="quick-add-section">
            <button class="quick-add-btn" onclick="showModal()">
                <i class="fas fa-plus-circle"></i>
                <span>Add New Application</span>
            </button>
        </div> -->
    </div>

    <!-- Add/Edit App Modal -->
    <div class="modal" id="appModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-plus-circle"></i> <span id="modalTitle">Add New App</span></h3>
                <span class="close" onclick="hideModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="appForm" onsubmit="saveApp(event)">
                    <input type="hidden" id="appId">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="appName">App Name *</label>
                            <input type="text" id="appName" required placeholder="Enter app name">
                        </div>

                        <div class="form-group">
                            <label for="appCategory">Category *</label>
                            <select id="appCategory" required>
                                <option value="sales">Sales</option>
                                <option value="finance">Finance</option>
                                <option value="operations">Operations</option>
                                <!-- <option value="hr">HR</option> -->
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="appDescription">Description *</label>
                        <textarea id="appDescription" rows="2" required placeholder="Brief description of the app"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="appUrl">App URL *</label>
                        <input type="url" id="appUrl" required placeholder="https://uulapps.com/app1">
                    </div>

                    <div class="form-group">
                        <label>Select Icon *</label>
                        <div class="icon-picker" id="iconPicker"></div>
                        <input type="hidden" id="appIcon" required>
                    </div>

                    <div class="form-actions">
                        <button type="button" onclick="hideModal()" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save App
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification"></div>

    <script src="launcher-script.js"></script>
</body>
</html>