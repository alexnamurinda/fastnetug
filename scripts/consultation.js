document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS animation library
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });

    // Service selection functionality
    const serviceLinks = document.querySelectorAll('.service-nav .nav-link');
    const serviceTypeSelect = document.getElementById('serviceType');
    
    serviceLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').replace('#', '');
            
            // Update the service type in the booking form
            if (serviceTypeSelect) {
                Array.from(serviceTypeSelect.options).forEach(option => {
                    if (option.value === targetId) {
                        serviceTypeSelect.value = targetId;
                    }
                });
            }
            
            // // Scroll to booking section after a delay
            // setTimeout(() => {
            //     const bookingSection = document.getElementById('booking');
            //     if (bookingSection) {
            //         window.scrollTo({
            //             top: bookingSection.offsetTop - 100,
            //             behavior: 'smooth'
            //         });
            //     }
            // }, 500);
        });
    });

    // Update available times based on selected date
    const appointmentDate = document.getElementById('appointmentDate');
    const appointmentTime = document.getElementById('appointmentTime');
    
    if (appointmentDate && appointmentTime) {
        appointmentDate.addEventListener('change', function() {
            updateAvailableTimes(this.value);
        });
        
        // Set minimum date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        appointmentDate.min = tomorrow.toISOString().split('T')[0];
        
        // Set maximum date to 3 months from now
        const maxDate = new Date();
        maxDate.setMonth(maxDate.getMonth() + 3);
        appointmentDate.max = maxDate.toISOString().split('T')[0];
    }
    
    // Form validation and submission
    const consultationForm = document.getElementById('consultationForm');
    if (consultationForm) {
        consultationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateForm()) {
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                submitBtn.disabled = true;
                
                // Simulate form submission (replace with actual API call)
                setTimeout(() => {
                    // Reset form
                    consultationForm.reset();
                    
                    // Show success message
                    showBookingConfirmation();
                    
                    // Reset button
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                }, 1500);
            }
        });
    }
    
    // Display price when selecting service in dropdown
    if (serviceTypeSelect) {
        serviceTypeSelect.addEventListener('change', function() {
            updatePriceDisplay(this.value);
        });
    }
    
    // Toggle additional fields based on consultation mode
    const modeRadios = document.querySelectorAll('input[name="consultationMode"]');
    modeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            toggleLocationFields(this.value);
        });
    });
    
    // Initialize tooltips
    initializeTooltips();
    
    // Check URL for direct service selection
    checkUrlForServiceSelection();
});

/**
 * Update available time slots based on selected date
 * @param {string} selectedDate - The selected date in ISO format
 */
function updateAvailableTimes(selectedDate) {
    const appointmentTime = document.getElementById('appointmentTime');
    if (!appointmentTime) return;
    
    // Clear current options except the first placeholder
    while (appointmentTime.options.length > 1) {
        appointmentTime.remove(1);
    }
    
    // Get day of week (0 = Sunday, 6 = Saturday)
    const dayOfWeek = new Date(selectedDate).getDay();
    
    // Define available times based on day of week
    let availableTimes = [];
    
    if (dayOfWeek === 0) {
        // Sunday - Closed
        appointmentTime.options[0].text = "Closed on Sundays";
    } else if (dayOfWeek === 6) {
        // Saturday - Half day
        availableTimes = [
            {value: '9:00', text: '9:00 AM'},
            {value: '10:00', text: '10:00 AM'},
            {value: '11:00', text: '11:00 AM'},
            {value: '12:00', text: '12:00 PM'}
        ];
    } else {
        // Weekdays - Full schedule
        availableTimes = [
            {value: '9:00', text: '9:00 AM'},
            {value: '10:00', text: '10:00 AM'},
            {value: '11:00', text: '11:00 AM'},
            {value: '13:00', text: '1:00 PM'},
            {value: '14:00', text: '2:00 PM'},
            {value: '15:00', text: '3:00 PM'},
            {value: '16:00', text: '4:00 PM'}
        ];
    }
    
    // Add new time options
    availableTimes.forEach(time => {
        const option = document.createElement('option');
        option.value = time.value;
        option.text = time.text;
        appointmentTime.add(option);
    });
}

/**
 * Toggle location fields based on consultation mode
 * @param {string} mode - The selected consultation mode
 */
function toggleLocationFields(mode) {
    const formContainer = document.getElementById('consultationForm');
    
    // Remove existing location field if any
    const existingField = formContainer.querySelector('.location-field');
    if (existingField) {
        existingField.remove();
    }
    
    if (mode === 'in-person') {
        // Create preferred office location field
        const locationFieldHTML = `
            <div class="mb-3 location-field">
                <label for="officeLocation" class="form-label">Preferred Office Location</label>
                <select class="form-select" id="officeLocation" required>
                    <option value="" selected disabled>Select office location</option>
                    <option value="main">Main Office - 123 Business Ave</option>
                    <option value="downtown">Downtown - 456 Center St</option>
                    <option value="uptown">Uptown - 789 North Blvd</option>
                </select>
            </div>
        `;
        
        // Insert after consultation mode
        const insertAfter = formContainer.querySelector('.form-check-inline').parentNode;
        insertAfter.insertAdjacentHTML('afterend', locationFieldHTML);
    } else if (mode === 'online') {
        // Create preferred platform field
        const platformFieldHTML = `
            <div class="mb-3 location-field">
                <label for="platform" class="form-label">Preferred Platform</label>
                <select class="form-select" id="platform" required>
                    <option value="" selected disabled>Select platform</option>
                    <option value="zoom">Zoom</option>
                    <option value="teams">Microsoft Teams</option>
                    <option value="meet">Google Meet</option>
                </select>
            </div>
        `;
        
        // Insert after consultation mode
        const insertAfter = formContainer.querySelector('.form-check-inline').parentNode;
        insertAfter.insertAdjacentHTML('afterend', platformFieldHTML);
    }
}

/**
 * Update price display based on selected service
 * @param {string} serviceType - The selected service type
 */
function updatePriceDisplay(serviceType) {
    // Price mapping
    const prices = {
        'initial': '$99',
        'visa-type': '$129',
        'eligibility': '$149',
        'documentation': '$119',
        'interview': '$179',
        'comprehensive': '$399'
    };
    
    // Check if price display exists, if not create it
    let priceDisplay = document.getElementById('servicePrice');
    
    if (!priceDisplay) {
        const serviceTypeField = document.getElementById('serviceType').parentNode;
        const priceHTML = `
            <div class="mb-3" id="servicePriceContainer">
                <label class="form-label">Price</label>
                <div class="form-control bg-light" id="servicePrice">Select a service to see price</div>
            </div>
        `;
        serviceTypeField.insertAdjacentHTML('afterend', priceHTML);
        priceDisplay = document.getElementById('servicePrice');
    }
    
    // Update price
    if (prices[serviceType]) {
        priceDisplay.textContent = prices[serviceType];
        priceDisplay.classList.add('text-primary', 'fw-bold');
    } else {
        priceDisplay.textContent = 'Select a service to see price';
        priceDisplay.classList.remove('text-primary', 'fw-bold');
    }
}

/**
 * Form validation
 * @returns {boolean} - Whether the form is valid
 */
function validateForm() {
    const form = document.getElementById('consultationForm');
    
    // Add validation classes to all required inputs
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        }
    });
    
    // Validate email format
    const emailField = document.getElementById('email');
    if (emailField && emailField.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailField.value)) {
            emailField.classList.add('is-invalid');
            isValid = false;
        }
    }
    
    // Validate phone format (simple validation)
    const phoneField = document.getElementById('phone');
    if (phoneField && phoneField.value) {
        const phoneRegex = /^[+]?[(]?[0-9]{3}[)]?[-\s.]?[0-9]{3}[-\s.]?[0-9]{4,6}$/;
        if (!phoneRegex.test(phoneField.value)) {
            phoneField.classList.add('is-invalid');
            isValid = false;
        }
    }
    
    return isValid;
}

/**
 * Show booking confirmation message
 */
function showBookingConfirmation() {
    const formContainer = document.getElementById('consultationForm').parentNode;
    
    // Create confirmation message
    const confirmationHTML = `
        <div class="booking-confirmation text-center py-4" data-aos="fade-up">
            <div class="confirmation-icon mb-3">
                <i class="fas fa-check-circle text-success fa-4x"></i>
            </div>
            <h3 class="mb-3">Booking Confirmed!</h3>
            <p>Your consultation request has been received. We'll send a confirmation email shortly with all the details.</p>
            <p class="mb-4">Our team will contact you within 24 hours to confirm your appointment.</p>
            <button class="btn btn-outline-primary" id="newBookingBtn">Make Another Booking</button>
        </div>
    `;
    
    // Hide the form and show confirmation
    document.getElementById('consultationForm').style.display = 'none';
    formContainer.insertAdjacentHTML('beforeend', confirmationHTML);
    
    // Add event listener to "Make Another Booking" button
    document.getElementById('newBookingBtn').addEventListener('click', function() {
        document.querySelector('.booking-confirmation').remove();
        document.getElementById('consultationForm').style.display = 'block';
    });
}

/**
 * Initialize tooltips for additional information
 */
function initializeTooltips() {
    // Add tooltip elements
    const tooltipTriggers = [
        {
            selector: 'label[for="serviceType"]',
            content: 'Select the service that best matches your visa needs'
        },
        {
            selector: 'label[for="appointmentDate"]',
            content: 'Appointments are available up to 3 months in advance'
        },
        {
            selector: 'label[for="consultationMode"]',
            content: 'Online consultations are available worldwide. In-person consultations are available at our office locations.'
        }
    ];
    
    tooltipTriggers.forEach(trigger => {
        const element = document.querySelector(trigger.selector);
        if (element) {
            element.innerHTML += ` <i class="fas fa-info-circle text-muted info-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="${trigger.content}"></i>`;
        }
    });
    
    // Initialize Bootstrap tooltips
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
    }
}

/**
 * Check URL for direct service selection
 */
function checkUrlForServiceSelection() {
    const urlParams = new URLSearchParams(window.location.search);
    const serviceParam = urlParams.get('service');
    
    if (serviceParam) {
        // Select the appropriate tab
        const serviceTab = document.getElementById(`${serviceParam}-tab`);
        if (serviceTab) {
            // Simulate a click on the tab
            if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                const tab = new bootstrap.Tab(serviceTab);
                tab.show();
            } else {
                serviceTab.click();
            }
            
            // Update service type in form
            const serviceTypeSelect = document.getElementById('serviceType');
            if (serviceTypeSelect) {
                Array.from(serviceTypeSelect.options).forEach(option => {
                    if (option.value === serviceParam) {
                        serviceTypeSelect.value = serviceParam;
                        // Update price display
                        updatePriceDisplay(serviceParam);
                    }
                });
            }
        }
    }
}