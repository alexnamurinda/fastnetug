// Real Estate Services Page Specific JavaScript

document.addEventListener('DOMContentLoaded', function () {
    // Initialize AOS (Animate on Scroll)
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });

    // Navbar color change on scroll
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });

    // Back to top button functionality
    const backToTopButton = document.querySelector('.back-to-top');

    window.addEventListener('scroll', function () {
        if (window.scrollY > 300) {
            backToTopButton.classList.add('active');
        } else {
            backToTopButton.classList.remove('active');
        }
    });

    backToTopButton.addEventListener('click', function (e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Property filter functionality (if needed)
    const viewAllPropertiesBtn = document.querySelector('.featured-properties .btn-primary');
    if (viewAllPropertiesBtn) {
        viewAllPropertiesBtn.addEventListener('click', function (e) {
            e.preventDefault();
            // Simulate loading more properties
            alert('This would navigate to a full property listing page in a real implementation');
        });
    }

    const rows = document.querySelectorAll('.property-row');
    const pageButtons = document.querySelectorAll('.page-btn');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    let currentIndex = 0;
    let interval;

    function showRow(index) {
        rows.forEach((row, i) => {
            row.classList.toggle('active', i === index);
        });
        pageButtons.forEach((btn, i) => {
            btn.classList.toggle('active', i === index);
        });
        currentIndex = index;
    }

    function startAutoSlide() {
        interval = setInterval(() => {
            currentIndex = (currentIndex + 1) % rows.length;
            showRow(currentIndex);
        }, 3000);
    }

    function stopAutoSlide() {
        clearInterval(interval);
    }

    // Event listeners
    pageButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            stopAutoSlide();
            showRow(parseInt(btn.dataset.page));
        });
    });

    prevBtn.addEventListener('click', () => {
        stopAutoSlide();
        currentIndex = (currentIndex - 1 + rows.length) % rows.length;
        showRow(currentIndex);
    });

    nextBtn.addEventListener('click', () => {
        stopAutoSlide();
        currentIndex = (currentIndex + 1) % rows.length;
        showRow(currentIndex);
    });

    // Start
    showRow(0);
    startAutoSlide();

    // Property detail view functionality
    const propertyDetailBtns = document.querySelectorAll('.property-card .btn-outline-primary');
    propertyDetailBtns.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            // Get the property title from the card
            const propertyTitle = this.closest('.property-info').querySelector('h3').textContent;
            // Show message (in real implementation, this would navigate to property detail page)
            alert(`You clicked on property: ${propertyTitle}\nThis would open a detailed view in a real implementation.`);
        });
    });

    // Form validation for CTA buttons that might lead to contact forms
    const ctaButtons = document.querySelectorAll('.btn-primary, .btn-light');
    ctaButtons.forEach(function (btn) {
        if (btn.getAttribute('href') === 'contact.html') {
            btn.addEventListener('click', function (e) {
                // Add any pre-navigation logic here if needed
                // For example, storing the service type in session storage
                sessionStorage.setItem('inquiryType', 'Real Estate');
            });
        }
    });

    // Initialize property image hover effect
    const propertyImages = document.querySelectorAll('.property-img img');
    propertyImages.forEach(function (img) {
        img.addEventListener('mouseover', function () {
            this.style.transform = 'scale(1.1)';
        });

        img.addEventListener('mouseout', function () {
            this.style.transform = 'scale(1)';
        });
    });

    // Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId !== '#' && document.querySelector(targetId)) {
                e.preventDefault();
                document.querySelector(targetId).scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Optional: Add testimonial carousel if you want to add one later
    // This would require adding HTML markup for testimonials

    // Optional: Property search functionality
    // This could be expanded if you add a search form to the page
});

// Function to handle property filtering (placeholder for future implementation)
function filterProperties(category) {
    // This function would filter properties based on category
    console.log(`Filtering properties by: ${category}`);
    // Implementation would depend on your specific requirements
}

// Function to handle property sorting (placeholder for future implementation)
function sortProperties(sortBy) {
    // This function would sort properties based on criteria
    console.log(`Sorting properties by: ${sortBy}`);
    // Implementation would depend on your specific requirements
}

// Function to simulate property inquiry (for demonstration purposes)
function inquireProperty(propertyId) {
    console.log(`Inquiry for property ID: ${propertyId}`);
    // In a real implementation, this might open a modal form
    // or navigate to a contact page with pre-filled information
}

// Function to toggle property favorites (for demonstration purposes)
function toggleFavorite(propertyId) {
    console.log(`Toggling favorite status for property ID: ${propertyId}`);
    // In a real implementation, this might update a user's saved properties
}

// Window resize handler for responsive adjustments
window.addEventListener('resize', function () {
    // Add any specific resize logic here if needed
    // For example, adjusting property card layouts on very small screens
});