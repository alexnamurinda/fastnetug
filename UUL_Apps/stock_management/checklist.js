// Questions for reference
const questions = [
    "Incident Register: Is an incident register maintained to record all accidents, near-misses, and security incidents?",
    "Security Systems: Are security systems, including motion sensors and cameras, installed and functional?",
    "Fire Alarm Systems: Are fire alarm systems installed and regularly tested?",
    "Power Backups: Are power backups available in case of outages?",
    "PPE for Employees: Are employees provided with personal protective equipment (PPE)?",
    "Reflector Jackets for Casuals: Are casual laborers provided with reflector jackets?",
    "Food Handlers License: Do food handlers possess valid licenses and certifications?",
    "Regular Cleaning: Are premises regularly cleaned and maintained to prevent pest infestations?",
    "First Aid Kit: Is a first aid kit available, stocked, and easily accessible?",
    "Visitors Register Book: Is a visitors register book maintained to record all visitors?",
    "Assets Register: Is an assets register maintained to track inventory, equipment, and other assets?",
    "Adequate Lighting Systems: Are lighting systems adequate and functional?"
];

// Show/hide tabs
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
    
    // Load history if history tab
    if (tabName === 'history') {
        loadHistory();
    }
}

// Save checklist
function saveChecklist() {
    const location = document.getElementById('location').value;
    const inspector = document.getElementById('inspector').value.trim();
    
    if (!inspector) {
        showNotification('Please enter inspector name');
        return;
    }
    
    // Collect all answers
    const answers = [];
    for (let i = 1; i <= 12; i++) {
        const radio = document.querySelector(`input[name="q${i}"]:checked`);
        const notes = document.querySelectorAll('.notes')[i-1].value;
        
        answers.push({
            question: questions[i-1],
            answer: radio ? radio.value : 'Not Answered',
            notes: notes
        });
    }
    
    // Create inspection record
    const inspection = {
        id: Date.now(),
        date: new Date().toLocaleDateString(),
        time: new Date().toLocaleTimeString(),
        location: location,
        inspector: inspector,
        answers: answers
    };
    
    // Save to localStorage
    let inspections = JSON.parse(localStorage.getItem('inspections') || '[]');
    inspections.unshift(inspection); // Add to beginning
    localStorage.setItem('inspections', JSON.stringify(inspections));
    
    // Show success message
    showNotification('Inspection saved successfully!');
    
    // Reset form after 1 second
    setTimeout(() => {
        resetForm();
    }, 1000);
}

// Reset form
function resetForm() {
    document.getElementById('inspector').value = '';
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.checked = false;
    });
    document.querySelectorAll('.notes').forEach(notes => {
        notes.value = '';
    });
}

// Load history
function loadHistory() {
    const historyList = document.getElementById('historyList');
    const inspections = JSON.parse(localStorage.getItem('inspections') || '[]');
    
    if (inspections.length === 0) {
        historyList.innerHTML = '<p style="text-align: center; padding: 40px; color: #999;">No inspection records yet</p>';
        return;
    }
    
    historyList.innerHTML = inspections.map((inspection, index) => `
        <div class="history-item" onclick="toggleDetails(${index})">
            <h3>${inspection.location}</h3>
            <p><strong>Inspector:</strong> ${inspection.inspector}</p>
            <p><strong>Date:</strong> ${inspection.date} at ${inspection.time}</p>
            <div id="details-${index}" style="display: none;" class="history-detail">
                <h4>Inspection Results:</h4>
                ${inspection.answers.map(ans => `
                    <div class="answer ${ans.answer.toLowerCase()}">
                        <strong>${ans.question}</strong><br>
                        <span style="color: ${ans.answer === 'Yes' ? '#16a34a' : ans.answer === 'No' ? '#dc2626' : '#6c757d'}">
                            ${ans.answer}
                        </span>
                        ${ans.notes ? `<br><em>Notes: ${ans.notes}</em>` : ''}
                    </div>
                `).join('')}
            </div>
        </div>
    `).join('');
}

// Toggle details
function toggleDetails(index) {
    const details = document.getElementById(`details-${index}`);
    if (details.style.display === 'none') {
        details.style.display = 'block';
    } else {
        details.style.display = 'none';
    }
}

// Show notification
function showNotification(message) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.classList.add('show');
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

// Load history on page load
window.addEventListener('load', () => {
    console.log('Page loaded - ready to use');
});