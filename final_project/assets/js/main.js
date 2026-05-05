// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenu = document.querySelector('.mobile-menu');
    const navLinks = document.querySelector('.nav-links');
    const isMobile = () => window.matchMedia && window.matchMedia('(max-width: 768px)').matches;

    if (!mobileMenu || !navLinks) return;

    const syncMobileState = () => {
        const open = navLinks.classList.contains('open');
        mobileMenu.setAttribute('aria-expanded', open ? 'true' : 'false');
    };

    const closeNav = () => {
        navLinks.classList.remove('open');
        syncMobileState();
    };

    const toggleNav = () => {
        navLinks.classList.toggle('open');
        syncMobileState();
    };

    mobileMenu.addEventListener('click', toggleNav);
    mobileMenu.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            toggleNav();
        }
    });

    document.addEventListener('click', function(event) {
        if (!mobileMenu.contains(event.target) && !navLinks.contains(event.target) && navLinks.classList.contains('open')) {
            closeNav();
        }
    });

    window.addEventListener('resize', function() {
        if (!isMobile()) {
            navLinks.classList.remove('open');
        }
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Newsletter Form Submission
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // Basic email validation
            if (email && email.includes('@')) {
                const target = document.getElementById('newsletterResult');
                const fd = new FormData();
                fd.append('email', email);

                fetch(this.action || 'subscribe.php', {
                    method: 'POST',
                    body: fd
                }).then(r => r.json()).then(data => {
                    if (target) {
                        target.textContent = data.message || 'Done.';
                        target.style.color = data.ok ? '#155724' : '#721c24';
                    } else {
                        alert(data.message || 'Done.');
                    }
                    if (data.ok) this.reset();
                }).catch(() => {
                    if (target) {
                        target.textContent = 'Subscription failed. Please try again.';
                        target.style.color = '#721c24';
                    } else {
                        alert('Subscription failed. Please try again.');
                    }
                });
            } else {
                alert('Please enter a valid email address.');
            }
        });
    }

    // Search Functionality
    const searchBox = document.querySelector('.search-box');
    if (searchBox) {
        const searchInput = searchBox.querySelector('input');
        const searchButton = searchBox.querySelector('.btn-search');

        if (searchInput && searchButton) {
            const submitSearch = () => {
                const searchTerm = searchInput.value.trim();
                if (searchTerm) {
                    window.location.href = `tours.php?q=${encodeURIComponent(searchTerm)}`;
                }
            };

            searchButton.addEventListener('click', submitSearch);
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    submitSearch();
                }
            });
        }
    }

    // Testimonial Slider
    const testimonials = document.querySelectorAll('.testimonial');
    let currentTestimonial = 0;

    function showTestimonial(index) {
        testimonials.forEach((testimonial, i) => {
            testimonial.style.display = i === index ? 'block' : 'none';
        });
    }

    if (testimonials.length > 1) {
        // Show first testimonial
        showTestimonial(0);

        // Auto-rotate testimonials
        setInterval(() => {
            currentTestimonial = (currentTestimonial + 1) % testimonials.length;
            showTestimonial(currentTestimonial);
        }, 5000);
    }

    // Add animation on scroll
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.destination-card, .tour-card, .feature');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementPosition < windowHeight - 100) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    };

    // Set initial styles for animation
    document.querySelectorAll('.destination-card, .tour-card, .feature').forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    });

    // Run animation on scroll
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Run once on page load

    // Add hover effect for destination and tour cards
    document.querySelectorAll('.destination-card, .tour-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}); 