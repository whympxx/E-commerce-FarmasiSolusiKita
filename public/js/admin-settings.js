// Admin Settings Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize settings page
    initSettingsPage();
});

function initSettingsPage() {
    // Initialize form handling
    initFormHandling();
    
    // Initialize action buttons
    initActionButtons();
    
    // Initialize tab functionality
    initTabFunctionality();
    
    // Initialize real-time validation
    initRealTimeValidation();
    
    // Initialize auto-save functionality
    initAutoSave();
}

function initFormHandling() {
    // Handle form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', handleFormSubmit);
    });
    
    // Handle form changes for auto-save
    const formInputs = document.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('change', handleFormChange);
    });
}

function handleFormSubmit(event) {
    const form = event.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    
    if (submitBtn) {
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
        submitBtn.classList.add('loading');
    }
    
    // Validate form before submission
    if (!validateForm(form)) {
        event.preventDefault();
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Pengaturan';
            submitBtn.classList.remove('loading');
        }
        return false;
    }
}

function handleFormChange(event) {
    const input = event.target;
    const form = input.closest('form');
    
    // Mark form as changed
    form.dataset.changed = 'true';
    
    // Show save indicator
    showSaveIndicator();
    
    // Auto-save after delay
    clearTimeout(window.autoSaveTimeout);
    window.autoSaveTimeout = setTimeout(() => {
        autoSaveForm(form);
    }, 2000);
}

function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            showFieldError(input, 'Field ini wajib diisi');
            isValid = false;
        } else {
            clearFieldError(input);
        }
    });
    
    // Validate email fields
    const emailInputs = form.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        if (input.value && !isValidEmail(input.value)) {
            showFieldError(input, 'Format email tidak valid');
            isValid = false;
        }
    });
    
    // Validate numeric fields
    const numericInputs = form.querySelectorAll('input[type="number"]');
    numericInputs.forEach(input => {
        if (input.value) {
            const min = input.getAttribute('min');
            const max = input.getAttribute('max');
            
            if (min && parseFloat(input.value) < parseFloat(min)) {
                showFieldError(input, `Nilai minimum adalah ${min}`);
                isValid = false;
            }
            
            if (max && parseFloat(input.value) > parseFloat(max)) {
                showFieldError(input, `Nilai maksimum adalah ${max}`);
                isValid = false;
            }
        }
    });
    
    return isValid;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showFieldError(input, message) {
    clearFieldError(input);
    
    input.classList.add('is-invalid');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    
    input.parentNode.appendChild(errorDiv);
}

function clearFieldError(input) {
    input.classList.remove('is-invalid');
    
    const errorDiv = input.parentNode.querySelector('.invalid-feedback');
    if (errorDiv) {
        errorDiv.remove();
    }
}

function initActionButtons() {
    // Backup button
    const backupBtn = document.getElementById('backupBtn');
    if (backupBtn) {
        backupBtn.addEventListener('click', handleBackup);
    }
    
    // Clear cache button
    const clearCacheBtn = document.getElementById('clearCacheBtn');
    if (clearCacheBtn) {
        clearCacheBtn.addEventListener('click', handleClearCache);
    }
}

function handleBackup() {
    const btn = document.getElementById('backupBtn');
    const originalText = btn.innerHTML;
    
    // Show loading state
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Membuat Backup...';
    btn.classList.add('loading');
    
    // Make AJAX request
    fetch('/admin/settings/backup', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Backup berhasil dibuat!', data.message);
        } else {
            showNotification('error', 'Gagal membuat backup', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Terjadi kesalahan', 'Gagal membuat backup database');
    })
    .finally(() => {
        // Reset button state
        btn.disabled = false;
        btn.innerHTML = originalText;
        btn.classList.remove('loading');
    });
}

function handleClearCache() {
    const btn = document.getElementById('clearCacheBtn');
    const originalText = btn.innerHTML;
    
    // Show loading state
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Membersihkan Cache...';
    btn.classList.add('loading');
    
    // Make AJAX request
    fetch('/admin/settings/clear-cache', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Cache berhasil dibersihkan!', data.message);
        } else {
            showNotification('error', 'Gagal membersihkan cache', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Terjadi kesalahan', 'Gagal membersihkan cache');
    })
    .finally(() => {
        // Reset button state
        btn.disabled = false;
        btn.innerHTML = originalText;
        btn.classList.remove('loading');
    });
}

function initTabFunctionality() {
    // Handle tab switching
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Save current form data before switching tabs
            saveCurrentTabData();
            
            // Update active tab indicator
            updateActiveTabIndicator(this);
        });
    });
    
    // Initialize first tab
    const firstTab = document.querySelector('.nav-tabs .nav-link.active');
    if (firstTab) {
        updateActiveTabIndicator(firstTab);
    }
}

function saveCurrentTabData() {
    const activeTab = document.querySelector('.tab-pane.active');
    if (activeTab) {
        const form = activeTab.querySelector('form');
        if (form && form.dataset.changed === 'true') {
            // Save form data to session storage
            const formData = new FormData(form);
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            sessionStorage.setItem('settings_' + activeTab.id, JSON.stringify(data));
        }
    }
}

function updateActiveTabIndicator(activeButton) {
    // Remove active class from all tabs
    document.querySelectorAll('.nav-tabs .nav-link').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Add active class to clicked tab
    activeButton.classList.add('active');
    
    // Load saved data for the new tab
    const targetId = activeButton.getAttribute('data-bs-target').substring(1);
    loadTabData(targetId);
}

function loadTabData(tabId) {
    const savedData = sessionStorage.getItem('settings_' + tabId);
    if (savedData) {
        const data = JSON.parse(savedData);
        const tab = document.getElementById(tabId);
        const form = tab.querySelector('form');
        
        if (form) {
            Object.keys(data).forEach(key => {
                const input = form.querySelector(`[name="${key}"]`);
                if (input && input.value !== data[key]) {
                    input.value = data[key];
                    input.classList.add('is-modified');
                }
            });
        }
    }
}

function initRealTimeValidation() {
    const inputs = document.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            // Clear error on input
            if (this.classList.contains('is-invalid')) {
                clearFieldError(this);
            }
        });
    });
}

function validateField(input) {
    // Required validation
    if (input.hasAttribute('required') && !input.value.trim()) {
        showFieldError(input, 'Field ini wajib diisi');
        return false;
    }
    
    // Email validation
    if (input.type === 'email' && input.value && !isValidEmail(input.value)) {
        showFieldError(input, 'Format email tidak valid');
        return false;
    }
    
    // Number validation
    if (input.type === 'number' && input.value) {
        const min = input.getAttribute('min');
        const max = input.getAttribute('max');
        
        if (min && parseFloat(input.value) < parseFloat(min)) {
            showFieldError(input, `Nilai minimum adalah ${min}`);
            return false;
        }
        
        if (max && parseFloat(input.value) > parseFloat(max)) {
            showFieldError(input, `Nilai maksimum adalah ${max}`);
            return false;
        }
    }
    
    return true;
}

function initAutoSave() {
    // Auto-save every 30 seconds if there are changes
    setInterval(() => {
        const forms = document.querySelectorAll('form[data-changed="true"]');
        forms.forEach(form => {
            autoSaveForm(form);
        });
    }, 30000);
}

function autoSaveForm(form) {
    if (form.dataset.changed !== 'true') return;
    
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            form.dataset.changed = 'false';
            showAutoSaveIndicator();
        } else {
            console.error('Auto-save failed:', data.message);
        }
    })
    .catch(error => {
        console.error('Auto-save error:', error);
    });
}

function showSaveIndicator() {
    // Show a small indicator that changes are being saved
    const indicator = document.createElement('div');
    indicator.id = 'save-indicator';
    indicator.className = 'save-indicator';
    indicator.innerHTML = '<i class="fas fa-clock me-2"></i>Menyimpan...';
    
    document.body.appendChild(indicator);
    
    // Remove after 3 seconds
    setTimeout(() => {
        if (indicator.parentNode) {
            indicator.parentNode.removeChild(indicator);
        }
    }, 3000);
}

function showAutoSaveIndicator() {
    // Show auto-save success indicator
    const indicator = document.createElement('div');
    indicator.id = 'auto-save-indicator';
    indicator.className = 'auto-save-indicator';
    indicator.innerHTML = '<i class="fas fa-check me-2"></i>Otomatis tersimpan';
    
    document.body.appendChild(indicator);
    
    // Remove after 2 seconds
    setTimeout(() => {
        if (indicator.parentNode) {
            indicator.parentNode.removeChild(indicator);
        }
    }, 2000);
}

function showNotification(type, title, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <strong>${title}</strong><br>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

function resetForms() {
    // Reset all forms to their original values
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.reset();
        form.dataset.changed = 'false';
        
        // Clear all error states
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            clearFieldError(input);
            input.classList.remove('is-modified');
        });
    });
    
    // Clear session storage
    Object.keys(sessionStorage).forEach(key => {
        if (key.startsWith('settings_')) {
            sessionStorage.removeItem(key);
        }
    });
    
    showNotification('success', 'Form berhasil direset', 'Semua pengaturan telah dikembalikan ke nilai awal');
}

// Global functions for external use
window.SettingsPage = {
    resetForms,
    validateForm,
    showNotification
};

// Add CSS for indicators
const style = document.createElement('style');
style.textContent = `
    .save-indicator {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #ffc107;
        color: #212529;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        font-weight: 500;
    }
    
    .auto-save-indicator {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        font-weight: 500;
    }
    
    .is-modified {
        border-color: #ffc107 !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
    }
    
    .is-modified:focus {
        border-color: #ffc107 !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
    }
`;
document.head.appendChild(style); 