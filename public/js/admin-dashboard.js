// Admin Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard
    initDashboard();
    
    // Initialize animations
    initAnimations();
    
    // Initialize interactive features
    initInteractiveFeatures();
    
    // Initialize real-time updates
    initRealTimeUpdates();
});

// Dashboard initialization
function initDashboard() {
    console.log('Dashboard initialized');
    
    // Add loading states
    addLoadingStates();
    
    // Initialize tooltips
    initTooltips();
    
    // Initialize charts (if any)
    initCharts();
}

// Animation initialization
function initAnimations() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe all cards and sections
    document.querySelectorAll('.card, .stat-card, .welcome-card').forEach(el => {
        observer.observe(el);
    });
    
    // Add hover animations
    addHoverAnimations();
}

// Interactive features
function initInteractiveFeatures() {
    // Card hover effects
    const cards = document.querySelectorAll('.stat-card, .card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
            this.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
        });
    });
    
    // Table row hover effects
    const tableRows = document.querySelectorAll('.table tbody tr');
    
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8fafc';
            this.style.transform = 'scale(1.01)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
            this.style.transform = 'scale(1)';
        });
    });
    
    // Button click effects
    const buttons = document.querySelectorAll('.btn');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
}

// Real-time updates
function initRealTimeUpdates() {
    // Update time every second
    updateTime();
    setInterval(updateTime, 1000);
    
    // Update statistics periodically (every 30 seconds)
    setInterval(updateStatistics, 30000);
    
    // Add live notifications
    initLiveNotifications();
}

// Time update function
function updateTime() {
    const now = new Date();
    const timeDisplay = document.querySelector('.time-display');
    const dateDisplay = document.querySelector('.date-display');
    
    if (timeDisplay && dateDisplay) {
        timeDisplay.textContent = now.toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        dateDisplay.textContent = now.toLocaleDateString('id-ID', { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric' 
        });
    }
}

// Statistics update function
function updateStatistics() {
    // This would typically fetch updated stats from the server
    // For now, we'll just add a subtle animation to show the data is fresh
    const statValues = document.querySelectorAll('.stat-value');
    
    statValues.forEach(value => {
        value.style.animation = 'pulse 0.5s ease-in-out';
        setTimeout(() => {
            value.style.animation = '';
        }, 500);
    });
}

// Loading states
function addLoadingStates() {
    // Add loading state to cards that might load data
    const loadingCards = document.querySelectorAll('.card[data-loading="true"]');
    
    loadingCards.forEach(card => {
        card.classList.add('loading');
        
        // Remove loading state after content loads
        setTimeout(() => {
            card.classList.remove('loading');
        }, 2000);
    });
}

// Tooltips initialization
function initTooltips() {
    // Initialize Bootstrap tooltips if available
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

// Charts initialization (placeholder for future chart implementations)
function initCharts() {
    // This would initialize charts like Chart.js if needed
    console.log('Charts initialized (placeholder)');
}

// Hover animations
function addHoverAnimations() {
    // Icon hover effects
    const icons = document.querySelectorAll('.stat-icon i, .stat-icon-large i');
    
    icons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.2) rotate(5deg)';
        });
        
        icon.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    });
    
    // Progress bar animations
    const progressBars = document.querySelectorAll('.progress-bar');
    
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
}

// Live notifications
function initLiveNotifications() {
    // Simulate live notifications
    setInterval(() => {
        // Check for new orders, low stock, etc.
        checkForNotifications();
    }, 60000); // Check every minute
}

// Check for notifications
function checkForNotifications() {
    // This would typically make an AJAX call to check for new notifications
    // For now, we'll just add a subtle indicator
    const notificationIndicator = document.querySelector('.notification-indicator');
    
    if (notificationIndicator) {
        notificationIndicator.classList.add('pulse');
        setTimeout(() => {
            notificationIndicator.classList.remove('pulse');
        }, 2000);
    }
}

// Utility functions
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Export functions for global use
window.DashboardUtils = {
    showNotification,
    updateTime,
    updateStatistics
};

// Add CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }
    
    .loading {
        position: relative;
        overflow: hidden;
    }
    
    .loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% {
            left: -100%;
        }
        100% {
            left: 100%;
        }
    }
`;
document.head.appendChild(style); 