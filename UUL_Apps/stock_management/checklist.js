// State
let currentVisitId = null;
let checklistData = [];
let responses = {};

// API URL - UPDATE THIS TO YOUR SERVER
const API_URL = 'database.php';

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadChecklist();
    loadStats();
});

// Tab Switching
function switchTab(tab) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
    
    event.target.classList.add('active');
    document.getElementById(tab + 'View').classList.add('active');

    if (tab === 'history') loadHistory();
    if (tab === 'actions') loadActions();
    if (tab === 'stats') loadStats();
}

// Load Checklist Template
async function loadChecklist() {
    try {
        const response = await fetch(`${API_URL}?action=get_checklist`);
        const data = await response.json();
        if (data.success) {
            checklistData = data.data;
        }
    } catch (error) {
        showToast('Error loading checklist');
    }
}

// Start Inspection
async function startInspection() {
    const location = document.getElementById('location').value;
    const inspector = document.getElementById('inspectorName').value.trim();
    const notes = document.getElementById('overallNotes').value.trim();

    if (!inspector) {
        showToast('Please enter inspector name');
        return;
    }

    const now = new Date();
    const visitData = {
        location,
        inspector_name: inspector,
        visit_date: now.toISOString().split('T')[0],
        visit_time: now.toTimeString().split(' ')[0],
        overall_notes: notes
    };

    try {
        const formData = new FormData();
        Object.keys(visitData).forEach(key => formData.append(key, visitData[key]));
        formData.append('action', 'create_visit');

        const response = await fetch(API_URL, {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            currentVisitId = data.data.visit_id;
            document.getElementById('currentLocation').textContent = location;
            renderChecklist();
            document.querySelector('.card').style.display = 'none';
            document.getElementById('checklistContainer').style.display = 'block';
            document.getElementById('bottomNav').style.display = 'block';
            showToast('Inspection started');
        } else {
            showToast('Error: ' + data.error);
        }
    } catch (error) {
        showToast('Error starting inspection');
    }
}

// Render Checklist
function renderChecklist() {
    const container = document.getElementById('checklistItems');
    container.innerHTML = '';

    checklistData.forEach(category => {
        const categoryDiv = document.createElement('div');
        categoryDiv.className = 'checklist-category';
        categoryDiv.innerHTML = `<div class="category-title">${category.category_name}</div>`;

        category.items.forEach(item => {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'checklist-item';
            itemDiv.innerHTML = `
                <div class="item-text">${item.item_text}</div>
                <div class="item-controls">
                    <button class="status-btn compliant" onclick="setStatus(${item.item_id}, 'compliant')">✓ Yes</button>
                    <button class="status-btn non-compliant" onclick="setStatus(${item.item_id}, 'non-compliant')">✗ No</button>
                    <button class="status-btn na" onclick="setStatus(${item.item_id}, 'na')">N/A</button>
                </div>
                <textarea class="notes-input" id="notes_${item.item_id}" placeholder="Add notes if needed..."></textarea>
            `;
            categoryDiv.appendChild(itemDiv);
        });

        container.appendChild(categoryDiv);
    });
}

// Set Status
function setStatus(itemId, status) {
    responses[itemId] = { status, notes: document.getElementById(`notes_${itemId}`).value };
    
    const buttons = event.target.parentElement.querySelectorAll('.status-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');

    saveResponse(itemId);
}

// Save Response
async function saveResponse(itemId) {
    if (!currentVisitId || !responses[itemId]) return;

    try {
        const formData = new FormData();
        formData.append('action', 'save_response');
        formData.append('visit_id', currentVisitId);
        formData.append('item_id', itemId);
        formData.append('status', responses[itemId].status);
        formData.append('notes', responses[itemId].notes);

        await fetch(API_URL, { method: 'POST', body: formData });
    } catch (error) {
        console.error('Error saving response');
    }
}

// Save Progress
function saveProgress() {
    showToast('Progress saved automatically');
}

// Complete Inspection
async function completeInspection() {
    if (!currentVisitId) return;

    if (Object.keys(responses).length < checklistData.reduce((sum, cat) => sum + cat.items.length, 0)) {
        if (!confirm('Not all items checked. Complete anyway?')) return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'complete_visit');
        formData.append('visit_id', currentVisitId);

        const response = await fetch(API_URL, { method: 'POST', body: formData });
        const data = await response.json();

        if (data.success) {
            showToast('Inspection completed!');
            resetInspection();
            switchTab('history');
        }
    } catch (error) {
        showToast('Error completing inspection');
    }
}

// Reset Inspection
function resetInspection() {
    currentVisitId = null;
    responses = {};
    document.querySelector('.card').style.display = 'block';
    document.getElementById('checklistContainer').style.display = 'none';
    document.getElementById('bottomNav').style.display = 'none';
    document.getElementById('inspectorName').value = '';
    document.getElementById('overallNotes').value = '';
}

// Load History
async function loadHistory() {
    const filter = document.getElementById('historyFilter').value;
    const url = filter ? `${API_URL}?action=get_visit_history&location=${filter}` : `${API_URL}?action=get_visit_history`;

    try {
        const response = await fetch(url);
        const data = await response.json();

        if (data.success) {
            renderHistory(data.data);
        }
    } catch (error) {
        showToast('Error loading history');
    }
}

// Render History
function renderHistory(visits) {
    const container = document.getElementById('historyList');
    
    if (visits.length === 0) {
        container.innerHTML = '<div class="empty-state">No inspections yet</div>';
        return;
    }

    container.innerHTML = visits.map(visit => `
        <div class="history-item" onclick="viewDetails(${visit.id})">
            <div class="history-header">
                <div class="history-location">${visit.location}</div>
                <div class="history-date">${formatDate(visit.visit_date)}</div>
            </div>
            <div class="history-inspector">Inspector: ${visit.inspector_name}</div>
            <span class="compliance-badge ${getComplianceClass(visit.status)}">
                ${visit.status === 'completed' ? 'Completed' : 'In Progress'}
            </span>
        </div>
    `).join('');
}

// Load Actions
async function loadActions() {
    const filter = document.getElementById('actionFilter').value;
    const url = filter ? `${API_URL}?action=get_action_items&status=${filter}` : `${API_URL}?action=get_action_items`;

    try {
        const response = await fetch(url);
        const data = await response.json();

        if (data.success) {
            renderActions(data.data);
        }
    } catch (error) {
        showToast('Error loading actions');
    }
}

// Render Actions
function renderActions(actions) {
    const container = document.getElementById('actionsList');
    
    if (actions.length === 0) {
        container.innerHTML = '<div class="empty-state">No action items</div>';
        return;
    }

    container.innerHTML = actions.map(action => `
        <div class="action-item ${action.status === 'resolved' ? 'resolved' : ''}">
            <div class="action-header">
                <div>
                    <div style="font-weight: 600; margin-bottom: 0.25rem;">${action.action_description}</div>
                    <div style="font-size: 0.875rem; color: var(--gray-600);">${action.location} - ${formatDate(action.visit_date)}</div>
                </div>
                <span class="priority-badge priority-${action.priority}">${action.priority.toUpperCase()}</span>
            </div>
            ${action.assigned_to ? `<div style="font-size: 0.875rem; color: var(--gray-600); margin-top: 0.5rem;">Assigned to: ${action.assigned_to}</div>` : ''}
            ${action.due_date ? `<div style="font-size: 0.875rem; color: var(--gray-600);">Due: ${formatDate(action.due_date)}</div>` : ''}
        </div>
    `).join('');
}

// Load Stats
async function loadStats() {
    try {
        const response = await fetch(`${API_URL}?action=get_compliance_stats`);
        const data = await response.json();

        if (data.success) {
            document.getElementById('statVisits').textContent = data.data.total_visits || 0;
            document.getElementById('statCompliance').textContent = (data.data.compliance_rate || 0) + '%';
            document.getElementById('statOpen').textContent = data.data.open_actions || 0;
            document.getElementById('statOverdue').textContent = data.data.overdue_actions || 0;
        }
    } catch (error) {
        showToast('Error loading stats');
    }
}

// View Details
async function viewDetails(visitId) {
    try {
        const response = await fetch(`${API_URL}?action=get_visit_details&visit_id=${visitId}`);
        const data = await response.json();

        if (data.success) {
            showDetailModal(data.data);
        }
    } catch (error) {
        showToast('Error loading details');
    }
}

// Show Detail Modal
function showDetailModal(visit) {
    const modal = document.getElementById('detailModal');
    const modalBody = document.getElementById('modalBody');
    
    modalBody.innerHTML = `
        <div style="margin-bottom: 1rem;">
            <strong>Location:</strong> ${visit.location}<br>
            <strong>Inspector:</strong> ${visit.inspector_name}<br>
            <strong>Date:</strong> ${formatDate(visit.visit_date)} ${visit.visit_time}<br>
            <strong>Status:</strong> ${visit.status}
        </div>
        ${visit.overall_notes ? `<div style="margin-bottom: 1rem;"><strong>Notes:</strong><br>${visit.overall_notes}</div>` : ''}
        <div style="margin-top: 1.5rem;">
            <h4 style="margin-bottom: 0.75rem;">Checklist Items</h4>
            ${visit.responses ? visit.responses.map(r => `
                <div style="padding: 0.75rem; border: 1px solid var(--gray-200); border-radius: 0.5rem; margin-bottom: 0.5rem;">
                    <div style="font-weight: 600; margin-bottom: 0.25rem;">${r.item_text}</div>
                    <div style="color: var(--gray-600); font-size: 0.875rem;">Status: ${r.status}</div>
                    ${r.notes ? `<div style="color: var(--gray-600); font-size: 0.875rem; margin-top: 0.25rem;">Notes: ${r.notes}</div>` : ''}
                </div>
            `).join('') : '<p>No responses recorded</p>'}
        </div>
    `;
    
    modal.classList.add('active');
}

// Close Modal
function closeModal() {
    document.getElementById('detailModal').classList.remove('active');
}

// Utility Functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function getComplianceClass(status) {
    if (status === 'completed') return 'high';
    if (status === 'in_progress') return 'medium';
    return 'low';
}

function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}