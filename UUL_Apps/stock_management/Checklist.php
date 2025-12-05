<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safety Checklist</title>
    <link rel="stylesheet" href="checklistcss.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🛡️ Safety & Compliance Checklist</h1>
        </header>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" onclick="showTab('checklist')">New Check</button>
            <button class="tab" onclick="showTab('history')">History</button>
        </div>

        <!-- Checklist Tab -->
        <div id="checklist" class="tab-content active">
            <div class="form-card">
                <div class="form-group">
                    <label>Location:</label>
                    <select id="location">
                        <option>Warehouse</option>
                        <option>Shop 08</option>
                        <option>Main Office</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Inspector Name:</label>
                    <input type="text" id="inspector" placeholder="Your name">
                </div>
            </div>

            <!-- Safety and Security -->
            <div class="category">
                <h2>Safety and Security</h2>
                
                <div class="checklist-item">
                    <p>Incident Register: Is an incident register maintained to record all accidents, near-misses, and security incidents?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="q1" value="Yes"> ✓ Yes</label>
                        <label><input type="radio" name="q1" value="No"> ✗ No</label>
                        <label><input type="radio" name="q1" value="N/A"> N/A</label>
                    </div>
                    <textarea class="notes" placeholder="Notes (optional)"></textarea>
                </div>

                <div class="checklist-item">
                    <p>Security Systems: Are security systems, including motion sensors and cameras, installed and functional?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="q2" value="Yes"> ✓ Yes</label>
                        <label><input type="radio" name="q2" value="No"> ✗ No</label>
                        <label><input type="radio" name="q2" value="N/A"> N/A</label>
                    </div>
                    <textarea class="notes" placeholder="Notes (optional)"></textarea>
                </div>

                <div class="checklist-item">
                    <p>Fire Alarm Systems: Are fire alarm systems installed and regularly tested?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="q3" value="Yes"> ✓ Yes</label>
                        <label><input type="radio" name="q3" value="No"> ✗ No</label>
                        <label><input type="radio" name="q3" value="N/A"> N/A</label>
                    </div>
                    <textarea class="notes" placeholder="Notes (optional)"></textarea>
                </div>

                <div class="checklist-item">
                    <p>Power Backups: Are power backups available in case of outages?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="q4" value="Yes"> ✓ Yes</label>
                        <label><input type="radio" name="q4" value="No"> ✗ No</label>
                        <label><input type="radio" name="q4" value="N/A"> N/A</label>
                    </div>
                    <textarea class="notes" placeholder="Notes (optional)"></textarea>
                </div>
            </div>

            <!-- Employee Safety -->
            <div class="category">
                <h2>Employee Safety</h2>
                
                <div class="checklist-item">
                    <p>PPE for Employees: Are employees provided with personal protective equipment (PPE) such as boots, gloves, and face masks?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="q5" value="Yes"> ✓ Yes</label>
                        <label><input type="radio" name="q5" value="No"> ✗ No</label>
                        <label><input type="radio" name="q5" value="N/A"> N/A</label>
                    </div>
                    <textarea class="notes" placeholder="Notes (optional)"></textarea>
                </div>

                <div class="checklist-item">
                    <p>Reflector Jackets for Casuals: Are casual laborers provided with reflector jackets for visibility and safety?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="q6" value="Yes"> ✓ Yes</label>
                        <label><input type="radio" name="q6" value="No"> ✗ No</label>
                        <label><input type="radio" name="q6" value="N/A"> N/A</label>
                    </div>
                    <textarea class="notes" placeholder="Notes (optional)"></textarea>
                </div>
            </div>

            <!-- Hygiene and Cleanliness -->
            <div class="category">
                <h2>Hygiene and Cleanliness</h2>
                
                <div class="checklist-item">
                    <p>Food Handlers License: Do food handlers possess valid licenses and certifications?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="q7" value="Yes"> ✓ Yes</label>
                        <label><input type="radio" name="q7" value="No"> ✗ No</label>
                        <label><input type="radio" name="q7" value="N/A"> N/A</label>
                    </div>
                    <textarea class="notes" placeholder="Notes (optional)"></textarea>
                </div>

                <div class="checklist-item">
                    <p>Regular Cleaning: Are premises regularly cleaned and maintained to prevent pest infestations?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="q8" value="Yes"> ✓ Yes</label>
                        <label><input type="radio" name="q8" value="No"> ✗ No</label>
                        <label><input type="radio" name="q8" value="N/A"> N/A</label>
                    </div>
                    <textarea class="notes" placeholder="Notes (optional)"></textarea>
                </div>
            </div>

            <!-- Emergency Preparedness -->
            <div class="category">
                <h2>Emergency Preparedness</h2>
                
                <div class="checklist-item">
                    <p>First Aid Kit: Is a first aid kit available, stocked, and easily accessible?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="q9" value="Yes"> ✓ Yes</label>
                        <label><input type="radio" name="q9" value="No"> ✗ No</label>
                        <label><input type="radio" name="q9" value="N/A"> N/A</label>
                    </div>
                    <textarea class="notes" placeholder="Notes (optional)"></textarea>
                </div>
            </div>

            <!-- Administration -->
            <div class="category">
                <h2>Administration</h2>
                
                <div class="checklist-item">
                    <p>Visitors Register Book: Is a visitors register book maintained to record all visitors?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="q10" value="Yes"> ✓ Yes</label>
                        <label><input type="radio" name="q10" value="No"> ✗ No</label>
                        <label><input type="radio" name="q10" value="N/A"> N/A</label>
                    </div>
                    <textarea class="notes" placeholder="Notes (optional)"></textarea>
                </div>

                <div class="checklist-item">
                    <p>Assets Register: Is an assets register maintained to track inventory, equipment, and other assets?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="q11" value="Yes"> ✓ Yes</label>
                        <label><input type="radio" name="q11" value="No"> ✗ No</label>
                        <label><input type="radio" name="q11" value="N/A"> N/A</label>
                    </div>
                    <textarea class="notes" placeholder="Notes (optional)"></textarea>
                </div>
            </div>

            <!-- General -->
            <div class="category">
                <h2>General</h2>
                
                <div class="checklist-item">
                    <p>Adequate Lighting Systems: Are lighting systems adequate and functional to ensure a safe working environment?</p>
                    <div class="radio-group">
                        <label><input type="radio" name="q12" value="Yes"> ✓ Yes</label>
                        <label><input type="radio" name="q12" value="No"> ✗ No</label>
                        <label><input type="radio" name="q12" value="N/A"> N/A</label>
                    </div>
                    <textarea class="notes" placeholder="Notes (optional)"></textarea>
                </div>
            </div>

            <button class="btn-submit" onclick="saveChecklist()">Save Inspection</button>
        </div>

        <!-- History Tab -->
        <div id="history" class="tab-content">
            <div id="historyList"></div>
        </div>
    </div>

    <div id="notification" class="notification"></div>

    <script src="checklist.js"></script>
</body>
</html>