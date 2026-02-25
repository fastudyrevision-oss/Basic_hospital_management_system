/**
 * Hospital Management System - Frontend Utilities
 * Form validation, animations, and UI interactions
 */

// Scroll reveal animation
function initScrollReveal() {
    const reveals = document.querySelectorAll('.scroll-reveal');

    const revealOnScroll = () => {
        reveals.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (elementTop < windowHeight - 100) {
                element.classList.add('revealed');
            }
        });
    };

    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll(); // Initial check
}

// Smooth scroll to anchor links
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;

            e.preventDefault();
            const target = document.querySelector(href);

            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Form validation helper
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('error');
            showError(input, 'This field is required');
        } else {
            input.classList.remove('error');
            removeError(input);
        }
    });

    return isValid;
}

// Show error message
function showError(input, message) {
    removeError(input);

    const error = document.createElement('div');
    error.className = 'form-error';
    error.textContent = message;
    input.parentNode.appendChild(error);
}

// Remove error message
function removeError(input) {
    const error = input.parentNode.querySelector('.form-error');
    if (error) {
        error.remove();
    }
}

// Toast notification system
const Toast = {
    show(message, type = 'info', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type} animate-fadeInDown`;
        toast.textContent = message;

        // Style the toast
        Object.assign(toast.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '16px 24px',
            borderRadius: '12px',
            color: 'white',
            fontWeight: '500',
            zIndex: '9999',
            maxWidth: '400px',
            boxShadow: '0 10px 25px rgba(0, 0, 0, 0.2)'
        });

        // Set background based on type
        const backgrounds = {
            success: 'linear-gradient(135deg, hsl(142, 71%, 45%) 0%, hsl(158, 64%, 52%) 100%)',
            error: 'linear-gradient(135deg, hsl(4, 90%, 58%) 0%, hsl(340, 82%, 62%) 100%)',
            warning: 'linear-gradient(135deg, hsl(45, 93%, 47%) 0%, hsl(45, 93%, 57%) 100%)',
            info: 'linear-gradient(135deg, hsl(199, 92%, 56%) 0%, hsl(245, 70%, 65%) 100%)'
        };

        toast.style.background = backgrounds[type] || backgrounds.info;

        document.body.appendChild(toast);

        // Auto remove
        setTimeout(() => {
            toast.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    },

    success(message, duration) {
        this.show(message, 'success', duration);
    },

    error(message, duration) {
        this.show(message, 'error', duration);
    },

    warning(message, duration) {
        this.show(message, 'warning', duration);
    },

    info(message, duration) {
        this.show(message, 'info', duration);
    }
};

// Loading state helper
function setLoading(button, isLoading = true) {
    if (isLoading) {
        button.disabled = true;
        button.dataset.originalText = button.textContent;
        button.innerHTML = '<span class="spinner spinner-sm"></span> Loading...';
    } else {
        button.disabled = false;
        button.textContent = button.dataset.originalText || 'Submit';
    }
}

// Password visibility toggle
function initPasswordToggle() {
    const toggleButtons = document.querySelectorAll('[data-password-toggle]');

    toggleButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.dataset.passwordToggle;
            const input = document.getElementById(targetId);

            if (input) {
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                button.textContent = type === 'password' ? '👁️' : '🙈';
            }
        });
    });
}

// Count up animation for numbers
function animateValue(element, start, end, duration) {
    const range = end - start;
    const increment = range / (duration / 16);
    let current = start;

    const timer = setInterval(() => {
        current += increment;
        if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
            current = end;
            clearInterval(timer);
        }
        element.textContent = Math.round(current);
    }, 16);
}

// Initialize count up for stat cards
function initCountUp() {
    const statNumbers = document.querySelectorAll('.stat-number[data-count]');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.dataset.counted) {
                const target = parseInt(entry.target.dataset.count);
                animateValue(entry.target, 0, target, 1500);
                entry.target.dataset.counted = 'true';
            }
        });
    });

    statNumbers.forEach(stat => observer.observe(stat));
}

// Mobile menu toggle
function initMobileMenu() {
    const menuButton = document.querySelector('[data-mobile-menu-toggle]');
    const menu = document.querySelector('[data-mobile-menu]');

    if (menuButton && menu) {
        menuButton.addEventListener('click', () => {
            menu.classList.toggle('active');
            menuButton.classList.toggle('active');
        });
    }
}

// Form auto-save to localStorage
function initAutoSave(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    const inputs = form.querySelectorAll('input, select, textarea');

    // Load saved data
    inputs.forEach(input => {
        const saved = localStorage.getItem(`form_${formId}_${input.name}`);
        if (saved && input.type !== 'password') {
            input.value = saved;
        }
    });

    // Save on change
    inputs.forEach(input => {
        input.addEventListener('change', () => {
            if (input.type !== 'password') {
                localStorage.setItem(`form_${formId}_${input.name}`, input.value);
            }
        });
    });

    // Clear on submit
    form.addEventListener('submit', () => {
        inputs.forEach(input => {
            localStorage.removeItem(`form_${formId}_${input.name}`);
        });
    });
}

// Debounce function for performance
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

// Initialize all features on DOM load
document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
    initSmoothScroll();
    initPasswordToggle();
    initCountUp();
    initMobileMenu();

    // Add stagger animation to grid items
    const gridItems = document.querySelectorAll('.grid > *, .features > *');
    gridItems.forEach((item, index) => {
        item.classList.add('animate-fadeInUp');
        item.style.animationDelay = `${index * 0.1}s`;
    });
});

// Export for use in other scripts
window.HMS = {
    Toast,
    setLoading,
    validateForm,
    animateValue,
    debounce
};
