/**
 * Admin Customers JavaScript
 * Handles all interactive functionality for customer management
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initializeSearchAndFilter();
    initializeTableInteractions();
    initializeFormValidation();
    initializeAnimations();
    initializeTooltips();
    initializeConfirmations();
});

/**
 * Initialize search and filter functionality
 */
function initializeSearchAndFilter() {
    const searchInput = document.querySelector('input[name="search"]');
    const statusSelect = document.querySelector('select[name="status"]');
    const sortSelect = document.querySelector('select[name="sort_by"]');
    
    // Debounce function for search
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.closest('form').submit();
            }, 500);
        });
    }
    
    // Auto-submit on filter change
    [statusSelect, sortSelect].forEach(select => {
        if (select) {
            select.addEventListener('change', function() {
                this.closest('form').submit();
            });
        }
    });
}

/**
 * Initialize table interactions
 */
function initializeTableInteractions() {
    const tableRows = document.querySelectorAll('.table tbody tr');
    
    tableRows.forEach(row => {
        // Add click effect
        row.addEventListener('click', function(e) {
            // Don't trigger if clicking on buttons or links
            if (e.target.closest('.btn') || e.target.closest('a')) {
                return;
            }
            
            // Add visual feedback
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
        
        // Add hover effects
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(30, 64, 175, 0.05)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
}

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                return false;
            }
        });
        
        // Real-time validation
        const inputs = form.querySelectorAll('input[required], select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
    });
}

/**
 * Validate a single form field
 */
function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.name;
    
    // Clear previous errors
    clearFieldError(field);
    
    // Required field validation
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, 'Field ini wajib diisi');
        return false;
    }
    
    // Email validation
    if (fieldName === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showFieldError(field, 'Format email tidak valid');
            return false;
        }
    }
    
    // Password validation
    if (fieldName === 'password' && value) {
        if (value.length < 8) {
            showFieldError(field, 'Password minimal 8 karakter');
            return false;
        }
    }
    
    // Password confirmation validation
    if (fieldName === 'password_confirmation' && value) {
        const passwordField = field.closest('form').querySelector('input[name="password"]');
        if (passwordField && passwordField.value !== value) {
            showFieldError(field, 'Password tidak cocok');
            return false;
        }
    }
    
    return true;
}

/**
 * Show field error
 */
function showFieldError(field, message) {
    field.classList.add('is-invalid');
    
    // Remove existing error message
    const existingError = field.parentNode.querySelector('.invalid-feedback');
    if (existingError) {
        existingError.remove();
    }
    
    // Add new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

/**
 * Clear field error
 */
function clearFieldError(field) {
    field.classList.remove('is-invalid');
    const errorDiv = field.parentNode.querySelector('.invalid-feedback');
    if (errorDiv) {
        errorDiv.remove();
    }
}

/**
 * Validate entire form
 */
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    // Special validation for password confirmation
    const passwordField = form.querySelector('input[name="password"]');
    const confirmPasswordField = form.querySelector('input[name="password_confirmation"]');
    
    if (passwordField && confirmPasswordField && passwordField.value && confirmPasswordField.value) {
        if (passwordField.value !== confirmPasswordField.value) {
            showFieldError(confirmPasswordField, 'Password tidak cocok');
            isValid = false;
        }
    }
    
    return isValid;
}

/**
 * Initialize animations
 */
function initializeAnimations() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe cards and table rows
    const animatedElements = document.querySelectorAll('.card, .table tbody tr');
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
}

/**
 * Initialize tooltips
 */
function initializeTooltips() {
    // Create tooltip elements for buttons without titles
    const buttons = document.querySelectorAll('.btn:not([title])');
    
    buttons.forEach(button => {
        const icon = button.querySelector('i');
        if (icon) {
            const tooltipText = getTooltipText(icon.className);
            if (tooltipText) {
                button.setAttribute('title', tooltipText);
            }
        }
    });
}

/**
 * Get tooltip text based on icon class
 */
function getTooltipText(iconClass) {
    const tooltipMap = {
        'fa-eye': 'Lihat Detail',
        'fa-edit': 'Edit',
        'fa-trash': 'Hapus',
        'fa-plus': 'Tambah',
        'fa-download': 'Export',
        'fa-search': 'Cari',
        'fa-save': 'Simpan',
        'fa-times': 'Batal',
        'fa-arrow-left': 'Kembali',
        'fa-check-circle': 'Terverifikasi',
        'fa-exclamation-circle': 'Belum Verifikasi',
        'fa-shopping-bag': 'Aktif',
        'fa-user-clock': 'Tidak Aktif'
    };
    
    for (const [className, tooltip] of Object.entries(tooltipMap)) {
        if (iconClass.includes(className)) {
            return tooltip;
        }
    }
    
    return null;
}

/**
 * Initialize confirmation dialogs
 */
function initializeConfirmations() {
    const deleteButtons = document.querySelectorAll('form[action*="destroy"] button[type="submit"]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const customerName = this.closest('tr')?.querySelector('.fw-semibold')?.textContent || 'pelanggan ini';
            
            if (!confirm(`Apakah Anda yakin ingin menghapus ${customerName}? Tindakan ini tidak dapat dibatalkan.`)) {
                e.preventDefault();
                return false;
            }
        });
    });
}

/**
 * Show success/error messages
 */
function showMessage(message, type = 'success') {
    // Remove existing messages
    const existingMessages = document.querySelectorAll('.alert-message');
    existingMessages.forEach(msg => msg.remove());
    
    // Create message element
    const messageDiv = document.createElement('div');
    messageDiv.className = `alert alert-${type} alert-message alert-dismissible fade show position-fixed`;
    messageDiv.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    `;
    
    messageDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(messageDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.remove();
        }
    }, 5000);
}

/**
 * Export functionality
 */
function exportCustomers(format = 'csv') {
    const url = `/admin/customers/export?format=${format}`;
    
    // Show loading state
    const exportBtn = document.querySelector('a[href*="export"]');
    if (exportBtn) {
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Exporting...';
        exportBtn.style.pointerEvents = 'none';
        
        // Reset after download
        setTimeout(() => {
            exportBtn.innerHTML = originalText;
            exportBtn.style.pointerEvents = '';
        }, 3000);
    }
    
    // Trigger download
    window.location.href = url;
}

/**
 * Bulk actions functionality
 */
function initializeBulkActions() {
    const selectAllCheckbox = document.querySelector('#select-all');
    const customerCheckboxes = document.querySelectorAll('input[name="selected_customers[]"]');
    const bulkActionSelect = document.querySelector('#bulk-action');
    const bulkActionBtn = document.querySelector('#bulk-action-btn');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            customerCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionButton();
        });
    }
    
    customerCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllCheckbox();
            updateBulkActionButton();
        });
    });
    
    if (bulkActionSelect) {
        bulkActionSelect.addEventListener('change', updateBulkActionButton);
    }
}

/**
 * Update select all checkbox state
 */
function updateSelectAllCheckbox() {
    const selectAllCheckbox = document.querySelector('#select-all');
    const customerCheckboxes = document.querySelectorAll('input[name="selected_customers[]"]');
    const checkedCheckboxes = document.querySelectorAll('input[name="selected_customers[]"]:checked');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = checkedCheckboxes.length === customerCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < customerCheckboxes.length;
    }
}

/**
 * Update bulk action button state
 */
function updateBulkActionButton() {
    const bulkActionSelect = document.querySelector('#bulk-action');
    const bulkActionBtn = document.querySelector('#bulk-action-btn');
    const checkedCheckboxes = document.querySelectorAll('input[name="selected_customers[]"]:checked');
    
    if (bulkActionBtn && bulkActionSelect) {
        const hasSelection = checkedCheckboxes.length > 0;
        const hasAction = bulkActionSelect.value !== '';
        
        bulkActionBtn.disabled = !hasSelection || !hasAction;
        
        if (hasSelection && hasAction) {
            bulkActionBtn.textContent = `${bulkActionSelect.options[bulkActionSelect.selectedIndex].text} (${checkedCheckboxes.length})`;
        } else {
            bulkActionBtn.textContent = 'Terapkan Aksi';
        }
    }
}

/**
 * Search functionality with debouncing
 */
function initializeAdvancedSearch() {
    const searchInput = document.querySelector('input[name="search"]');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(() => {
                const searchTerm = this.value.trim();
                
                if (searchTerm.length >= 2 || searchTerm.length === 0) {
                    // Highlight search terms in table
                    highlightSearchTerms(searchTerm);
                }
            }, 300);
        });
    }
}

/**
 * Highlight search terms in table
 */
function highlightSearchTerms(searchTerm) {
    const tableRows = document.querySelectorAll('.table tbody tr');
    
    tableRows.forEach(row => {
        const textElements = row.querySelectorAll('td');
        let hasMatch = false;
        
        textElements.forEach(cell => {
            const text = cell.textContent;
            if (searchTerm && text.toLowerCase().includes(searchTerm.toLowerCase())) {
                hasMatch = true;
                cell.innerHTML = text.replace(
                    new RegExp(searchTerm, 'gi'),
                    match => `<mark class="bg-warning">${match}</mark>`
                );
            }
        });
        
        // Show/hide rows based on search
        if (searchTerm) {
            row.style.display = hasMatch ? '' : 'none';
        } else {
            row.style.display = '';
            // Remove highlights
            const marks = row.querySelectorAll('mark');
            marks.forEach(mark => {
                mark.outerHTML = mark.textContent;
            });
        }
    });
}

// Global functions for external use
window.AdminCustomers = {
    showMessage,
    exportCustomers,
    initializeBulkActions,
    initializeAdvancedSearch
}; 