// JS/MAIN.JS - FastNet UG Interactive Features

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS (Animate On Scroll)
    AOS.init({
        duration: 1000,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });

    // Navbar scroll effect
    const navbar = document.getElementById('mainNav');
    const navLinks = document.querySelectorAll('.nav-link');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            navbar.style.background = 'rgba(13, 27, 62, 0.98)';
            navbar.style.backdropFilter = 'blur(20px)';
            navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
        } else {
            navbar.style.background = 'rgba(13, 27, 62, 0.95)';
            navbar.style.boxShadow = 'none';
        }
    });

    // Smooth scrolling for navigation links
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            
            if (targetId.startsWith('#')) {
                const targetSection = document.querySelector(targetId);
                if (targetSection) {
                    const offsetTop = targetSection.offsetTop - 80;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // Active navigation highlighting
    const sections = document.querySelectorAll('section[id]');
    
    window.addEventListener('scroll', function() {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100;
            const sectionHeight = section.offsetHeight;
            if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    });

    // Counter animation for hero stats
    const animateCounters = () => {
        const counters = document.querySelectorAll('.stat-number');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-count'));
            const increment = target / 100;
            let current = 0;
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    counter.textContent = Math.ceil(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                    // Add + or % suffix based on the stat
                    if (counter.parentElement.querySelector('.stat-label').textContent.includes('%')) {
                        counter.textContent = target + '%';
                    } else if (target >= 1000) {
                        counter.textContent = target + '+';
                    }
                }
            };
            
            updateCounter();
        });
    };

    // Trigger counter animation when hero section is in view
    const heroSection = document.querySelector('.hero-section');
    const heroObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                heroObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    if (heroSection) {
        heroObserver.observe(heroSection);
    }

    // Service cards hover effects
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) rotateX(5deg)';
            this.style.transition = 'all 0.3s ease';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) rotateX(0)';
        });
    });

    // Package cards interactive effects
    const packageCards = document.querySelectorAll('.package-card');
    packageCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (!this.classList.contains('popular')) {
                this.style.transform = 'translateY(-10px) scale(1.02)';
                this.style.boxShadow = '0 20px 50px rgba(0, 123, 255, 0.15)';
            }
        });

        card.addEventListener('mouseleave', function() {
            if (!this.classList.contains('popular')) {
                this.style.transform = 'translateY(0) scale(1)';
                this.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.1)';
            }
        });
    });

    // Location cards flip effect enhancement
    const locationCards = document.querySelectorAll('.location-card');
    locationCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const img = this.querySelector('.location-image img');
            const overlay = this.querySelector('.location-overlay');
            
            img.style.transform = 'scale(1.1) rotate(2deg)';
            overlay.style.transform = 'scale(1.1)';
            overlay.style.background = 'rgba(0, 123, 255, 0.9)';
        });

        card.addEventListener('mouseleave', function() {
            const img = this.querySelector('.location-image img');
            const overlay = this.querySelector('.location-overlay');
            
            img.style.transform = 'scale(1) rotate(0deg)';
            overlay.style.transform = 'scale(1)';
            overlay.style.background = 'rgba(255, 255, 255, 0.9)';
        });
    });

    // Contact form handling
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const formObject = {};
            formData.forEach((value, key) => {
                formObject[key] = value;
            });

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            submitBtn.disabled = true;

            // Simulate form submission (replace with actual API call)
            setTimeout(() => {
                // Show success message
                showNotification('Message sent successfully! We\'ll get back to you soon.', 'success');
                
                // Reset form
                this.reset();
                
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
    }

    // Newsletter form handling
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            if (email) {
                showNotification('Successfully subscribed to newsletter!', 'success');
                this.reset();
            }
        });
    }

    // Back to top button
    const backToTop = document.getElementById('backToTop');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    });

    backToTop.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Modal handling for purchase
    const purchaseModal = new bootstrap.Modal(document.getElementById('purchaseModal'));
    let selectedPackage = '';

    // Package purchase function
    window.purchasePackage = function(packageType) {
        selectedPackage = packageType;
        const modal = document.getElementById('purchaseModal');
        const modalTitle = modal.querySelector('.modal-title');
        
        // Update modal title based on package
        const packageNames = {
            'daily': 'Daily Package - UGX 1,000',
            'weekly': 'Weekly Package - UGX 6,000',
            'monthly': 'Monthly Package - UGX 20,000',
            'semester': 'Semester Package - UGX 50,000'
        };
        
        modalTitle.textContent = `Purchase ${packageNames[packageType]}`;
        purchaseModal.show();
    };

    // Payment method selection
    const paymentButtons = document.querySelectorAll('.payment-methods .btn');
    paymentButtons.forEach(button => {
        button.addEventListener('click', function() {
            const paymentMethod = this.textContent.trim();
            
            if (paymentMethod.includes('Mobile Money')) {
                handleMobileMoneyPayment();
            } else if (paymentMethod.includes('Voucher')) {
                handleVoucherRequest();
            }
        });
    });

    function handleMobileMoneyPayment() {
        purchaseModal.hide();
        showNotification('Redirecting to mobile money payment...', 'info');
        
        // Simulate mobile money integration
        setTimeout(() => {
            showNotification('Payment successful! You will receive connection details via SMS.', 'success');
        }, 3000);
    }

    function handleVoucherRequest() {
        purchaseModal.hide();
        showNotification('Please visit your hostel\'s front desk to get a voucher from our agent.', 'info');
    }

    // Notification system
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} notification-toast`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 400px;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transform: translateX(400px);
            transition: all 0.3s ease;
        `;
        
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }

    // Parallax effect for hero section
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const heroBackground = document.querySelector('.hero-bg-animation');
        
        if (heroBackground) {
            heroBackground.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });

    // Loading screen (if needed)
    window.addEventListener('load', function() {
        const loadingScreen = document.querySelector('.loading-screen');
        if (loadingScreen) {
            loadingScreen.style.opacity = '0';
            setTimeout(() => {
                loadingScreen.remove();
            }, 500);
        }
    });

    // Dynamic content loading for customer portal link
    const portalLink = document.querySelector('a[href="pages/customer-portal.html"]');
    if (portalLink) {
        portalLink.addEventListener('click', function(e) {
            e.preventDefault();
            showNotification('Customer portal is coming soon! Use mobile money for instant access.', 'info');
        });
    }

    // Enhanced scroll effects for sections
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fadeInUp');
                
                // Add stagger effect to child elements
                const children = entry.target.querySelectorAll('.service-card, .package-card, .location-card');
                children.forEach((child, index) => {
                    setTimeout(() => {
                        child.style.animation = `fadeInUp 0.6s ease-out ${index * 0.1}s both`;
                    }, index * 100);
                });
            }
        });
    }, observerOptions);

    // Observe all main sections
    sections.forEach(section => {
        sectionObserver.observe(section);
    });

    // Typing effect for hero title (optional enhancement)
    const heroTitle = document.querySelector('.hero-title');
    if (heroTitle) {
        const text = heroTitle.innerHTML;
        heroTitle.innerHTML = '';
        
        let i = 0;
        const typeWriter = () => {
            if (i < text.length) {
                heroTitle.innerHTML += text.charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            }
        };
        
        // Start typing effect after a short delay
        setTimeout(typeWriter, 1000);
    }

    // Interactive WiFi pulse animation
    const wifiPulse = document.querySelector('.wifi-pulse');
    if (wifiPulse) {
        wifiPulse.addEventListener('click', function() {
            this.style.animation = 'none';
            setTimeout(() => {
                this.style.animation = 'pulse 0.5s ease-in-out 3';
            }, 100);
        });
    }

    // Keyboard navigation support
    document.addEventListener('keydown', function(e) {
        // Close modals with Escape key
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                bootstrap.Modal.getInstance(openModal).hide();
            }
        }
        
        // Quick navigation with number keys
        if (e.altKey) {
            switch(e.key) {
                case '1':
                    document.querySelector('#home').scrollIntoView({ behavior: 'smooth' });
                    break;
                case '2':
                    document.querySelector('#services').scrollIntoView({ behavior: 'smooth' });
                    break;
                case '3':
                    document.querySelector('#packages').scrollIntoView({ behavior: 'smooth' });
                    break;
                case '4':
                    document.querySelector('#contact').scrollIntoView({ behavior: 'smooth' });
                    break;
            }
        }
    });

    // Performance optimization: Lazy load images
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('loading');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => {
        imageObserver.observe(img);
    });

    // Console welcome message
    console.log(`
    🚀 FastNet UG - Lightning Fast Internet Solutions
    ================================================
    Website loaded successfully!
    
    🔧 Debug Commands:
    - showNotification('message', 'success') - Show notification
    - purchasePackage('daily') - Trigger purchase modal
    
    📱 Contact: info@fastnetug.com
    🌐 Locations: Kampala Hostels
    ⚡ Speed: Up to 100Mbps
    `);

    // Initialize everything
    console.log('✅ FastNet UG JavaScript initialized successfully!');
});