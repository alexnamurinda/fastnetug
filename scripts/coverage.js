// Coverage Areas JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS (Animate On Scroll)
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });

    // Navbar Scroll Effect
    window.addEventListener('scroll', function() {
        var navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
    

    // Coverage map interactive points
    const coveragePoints = document.querySelectorAll('.coverage-points .point');
    const mapContainer = document.querySelector('.coverage-map');

    coveragePoints.forEach(point => {
        point.addEventListener('mouseenter', function() {
            const areaName = this.dataset.area;
            showAreaTooltip(this, areaName);
            this.style.transform = 'scale(1.5)';
        });

        point.addEventListener('mouseleave', function() {
            hideAreaTooltip();
            this.style.transform = 'scale(1)';
        });

        point.addEventListener('click', function() {
            const areaName = this.dataset.area;
            scrollToArea(areaName);
        });
    });

    function showAreaTooltip(element, areaName) {
        const tooltip = document.createElement('div');
        tooltip.className = 'area-tooltip';
        tooltip.innerHTML = `<strong>${areaName}</strong><br>Click to view details`;
        
        const rect = element.getBoundingClientRect();
        tooltip.style.left = rect.left + 'px';
        tooltip.style.top = (rect.top - 60) + 'px';
        
        document.body.appendChild(tooltip);
        
        setTimeout(() => {
            tooltip.classList.add('show');
        }, 10);
    }

    function hideAreaTooltip() {
        const tooltip = document.querySelector('.area-tooltip');
        if (tooltip) {
            tooltip.remove();
        }
    }

    function scrollToArea(areaName) {
        const areaCards = document.querySelectorAll('.area-card h3');
        for (let card of areaCards) {
            if (card.textContent.includes(areaName)) {
                card.closest('.area-card').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                // Add highlight effect
                card.closest('.area-card').classList.add('highlighted');
                setTimeout(() => {
                    card.closest('.area-card').classList.remove('highlighted');
                }, 2000);
                break;
            }
        }
    }

    // Coverage area cards hover effects
    const areaCards = document.querySelectorAll('.area-card');
    areaCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
            this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.1)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.08)';
        });
    });

    // Hostel cards interactive effects
    const hostelCards = document.querySelectorAll('.hostel-card');
    hostelCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (!this.classList.contains('coming-soon')) {
                this.style.transform = 'translateY(-8px) scale(1.02)';
                this.style.boxShadow = '0 15px 30px rgba(0,0,0,0.15)';
            }
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.08)';
        });
    });

    // Family cards hover effects
    const familyCards = document.querySelectorAll('.family-card');
    familyCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 15px 30px rgba(0,0,0,0.1)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.08)';
        });
    });

    // Contact buttons click tracking
    const contactButtons = document.querySelectorAll('.contact-buttons a, .agent-contact a');
    contactButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            
            // Track contact method
            const isWhatsApp = this.href.includes('wa.me');
            const isPhone = this.href.includes('tel:');
            
            if (isWhatsApp) {
                console.log('WhatsApp contact initiated');
                // Add analytics tracking here if needed
            } else if (isPhone) {
                console.log('Phone contact initiated');
                // Add analytics tracking here if needed
            }
        });
    });

    // Timeline animation on scroll
    const timelineItems = document.querySelectorAll('.timeline-item');
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const timelineObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    timelineItems.forEach(item => {
        timelineObserver.observe(item);
    });

    // Stats counter animation
    const statsItems = document.querySelectorAll('.stat-item h3');
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
            }
        });
    }, { threshold: 0.5 });

    statsItems.forEach(stat => {
        statsObserver.observe(stat);
    });

    function animateCounter(element) {
        const target = parseInt(element.textContent);
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const counter = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(counter);
            }
            element.textContent = Math.floor(current) + '+';
        }, 16);
    }

    // Back to top button
    const backToTop = document.querySelector('.back-to-top');
    
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        backToTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

 function createSearchBox() {
    const searchContainer = document.createElement('div');
    searchContainer.className = 'search-container mb-4'; // Optional margin for spacing

    const coverageSection = document.querySelector('.coverage-areas-section .container');
    const rowElement = coverageSection.querySelector('.row');
    coverageSection.insertBefore(searchContainer, rowElement);

    const searchInput = document.getElementById('areaSearch');
    searchInput.addEventListener('input', function () {
        filterAreas(this.value);
    });
}

    function filterAreas(searchTerm) {
        const allCards = document.querySelectorAll('.area-card, .hostel-card, .family-card');
        const term = searchTerm.toLowerCase();
        
        allCards.forEach(card => {
            const cardText = card.textContent.toLowerCase();
            const cardContainer = card.closest('.col-lg-6, .col-lg-4, .col-md-6');
            
            if (cardText.includes(term) || term === '') {
                cardContainer.style.display = 'block';
                card.style.opacity = '1';
            } else {
                cardContainer.style.display = 'none';
                card.style.opacity = '0.3';
            }
        });
    }

    // Initialize search functionality
    createSearchBox();

    // WiFi signal animation
    function animateWifiIcon() {
        const wifiIcon = document.querySelector('.wifi-animation');
        if (wifiIcon) {
            setInterval(() => {
                wifiIcon.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    wifiIcon.style.transform = 'scale(1)';
                }, 300);
            }, 2000);
        }
    }

    animateWifiIcon();

    // Coverage badge pulse animation
    const coverageBadges = document.querySelectorAll('.coverage-badge');
    coverageBadges.forEach(badge => {
        if (badge.textContent.includes('Full Coverage')) {
            badge.classList.add('pulse');
        }
    });

    // Mobile menu enhancement
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');

    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function() {
            this.classList.toggle('active');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!navbarCollapse.contains(e.target) && !navbarToggler.contains(e.target)) {
                navbarCollapse.classList.remove('show');
                navbarToggler.classList.remove('active');
            }
        });
    }

    // Smooth scrolling for navigation links
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Close mobile menu if open
            if (navbarCollapse.classList.contains('show')) {
                navbarCollapse.classList.remove('show');
                navbarToggler.classList.remove('active');
            }
        });
    });

    // Loading animation for contact buttons
    const loadingSpinner = '<i class="fas fa-spinner fa-spin"></i>';
    
    contactButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const originalContent = this.innerHTML;
            this.innerHTML = loadingSpinner;
            
            setTimeout(() => {
                this.innerHTML = originalContent;
            }, 1000);
        });
    });

    // Lazy loading for images (if any are added later)
    const lazyImages = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => {
        imageObserver.observe(img);
    });

    // Performance optimization: Debounce scroll events
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Apply debounce to scroll events
    const debouncedScroll = debounce(function() {
        // Any scroll-based animations can go here
    }, 10);

    window.addEventListener('scroll', debouncedScroll);
});

// Utility functions
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Error handling
window.addEventListener('error', function(e) {
    console.error('JavaScript error:', e.error);
    // Could add error reporting here
});

// Print styles support
window.addEventListener('beforeprint', function() {
    document.body.classList.add('printing');
});

window.addEventListener('afterprint', function() {
    document.body.classList.remove('printing');
});