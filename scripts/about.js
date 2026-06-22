document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS (Animate on Scroll)
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });
    
    // Counter Up Animation for Stats
    $('.counter').counterUp({
        delay: 10,
        time: 1000
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
    
    // Back to Top Button
    var backToTopButton = document.querySelector('.back-to-top');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopButton.classList.add('active');
        } else {
            backToTopButton.classList.remove('active');
        }
    });
    
    backToTopButton.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            if (this.getAttribute('href') !== '#') {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    const navbarHeight = document.querySelector('.navbar').offsetHeight;
                    const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - navbarHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
    
    // Timeline Animation
    const timelineItems = document.querySelectorAll('.timeline-item');
    
    function checkTimelineInView() {
        timelineItems.forEach(item => {
            const itemTop = item.getBoundingClientRect().top;
            const itemBottom = item.getBoundingClientRect().bottom;
            const isVisible = (itemTop >= 0) && (itemBottom <= window.innerHeight);
            
            if (isVisible) {
                item.classList.add('animate');
            }
        });
    }
    
    // Check if timeline items are in view on load and scroll
    checkTimelineInView();
    window.addEventListener('scroll', checkTimelineInView);
    
    // Team Member Cards Hover Effect
    const teamCards = document.querySelectorAll('.team-card');
    
    teamCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.querySelector('.team-social').style.opacity = '1';
        });
        
        card.addEventListener('mouseleave', function() {
            this.querySelector('.team-social').style.opacity = '0.7';
        });
    });
    
    // Add animated entrance for value cards
    const valueCards = document.querySelectorAll('.value-card');
    
    function checkValueCardsInView() {
        valueCards.forEach((card, index) => {
            const cardTop = card.getBoundingClientRect().top;
            const cardBottom = card.getBoundingClientRect().bottom;
            const isVisible = (cardTop >= 0) && (cardBottom <= window.innerHeight);
            
            if (isVisible) {
                setTimeout(() => {
                    card.classList.add('animate-value');
                }, index * 100);
            }
        });
    }
    
    // Check if value cards are in view on load and scroll
    checkValueCardsInView();
    window.addEventListener('scroll', checkValueCardsInView);
    
    // Dropdown menu hover behavior for desktop
    if (window.innerWidth > 992) {
        document.querySelectorAll('.navbar .dropdown').forEach(dropdown => {
            dropdown.addEventListener('mouseenter', function() {
                this.querySelector('.dropdown-menu').classList.add('show');
            });
            
            dropdown.addEventListener('mouseleave', function() {
                this.querySelector('.dropdown-menu').classList.remove('show');
            });
        });
    }
});

// Add CSS class for additional timeline animations
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('timeline-visible');
            }
        });
    }, { threshold: 0.2 });
    
    document.querySelectorAll('.timeline-item').forEach(item => {
        observer.observe(item);
    });
});

// Additional CSS for timeline animation
document.head.insertAdjacentHTML('beforeend', `
    <style>
        .timeline-item {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s ease;
        }
        
        .timeline-item.timeline-visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .timeline-item.left.timeline-visible {
            transform: translateX(0);
        }
        
        .timeline-item.right.timeline-visible {
            transform: translateX(0);
        }
        
        .value-card {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .value-card.animate-value {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
`);