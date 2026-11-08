// Admin Products JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize products page
    initializeProductsPage();
    
    // Initialize interactive features
    initializeInteractiveFeatures();
    
    // Initialize form validations
    initializeFormValidations();
    
    // Initialize image preview
    initializeImagePreview();
    
    // Initialize tooltips
    initializeTooltips();
});

// Initialize products page
function initializeProductsPage() {
    // Add animations to page elements
    addPageAnimations();
    
    // Add search functionality
    addSearchFunctionality();
    
    // Add filter functionality
    addFilterFunctionality();
    
    // Add table interactions
    addTableInteractions();
    
    // Add product grid interactions
    addProductGridInteractions();
}

// Add page animations
function addPageAnimations() {
    // Animate cards with delay
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 * (index + 1));
    });
    
    // Animate table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateX(-30px)';
        row.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        
        setTimeout(() => {
            row.style.opacity = '1';
            row.style.transform = 'translateX(0)';
        }, 50 * (index + 1));
    });
    
    // Animate product grid items
    const gridItems = document.querySelectorAll('.product-card, .product-grid .card');
    gridItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, 100 * (index + 1));
    });
}

// Add search functionality
function addSearchFunctionality() {
    const searchInput = document.querySelector('input[type="search"], input[placeholder*="search"], input[placeholder*="cari"]');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            const gridItems = document.querySelectorAll('.product-card, .product-grid .card');
            
            // Search in table
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    row.style.animation = 'fadeInUp 0.3s ease';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Search in grid
            gridItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = '';
                    item.style.animation = 'fadeInUp 0.3s ease';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show/hide empty state
            const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
            const visibleGridItems = Array.from(gridItems).filter(item => item.style.display !== 'none');
            const emptyState = document.querySelector('.empty-state');
            
            if (emptyState) {
                if (visibleRows.length === 0 && visibleGridItems.length === 0 && searchTerm !== '') {
                    emptyState.style.display = 'block';
                    emptyState.innerHTML = `
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4>Tidak ada hasil</h4>
                        <p>Tidak ada produk yang cocok dengan pencarian "${searchTerm}"</p>
                    `;
                } else {
                    emptyState.style.display = 'none';
                }
            }
        });
    }
}

// Add filter functionality
function addFilterFunctionality() {
    const filterButtons = document.querySelectorAll('.filter-btn, .btn-group .btn');
    
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filter = this.dataset.filter || this.textContent.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            const gridItems = document.querySelectorAll('.product-card, .product-grid .card');
            
            // Filter table rows
            tableRows.forEach(row => {
                if (filter === 'all' || filter === 'semua') {
                    row.style.display = '';
                } else {
                    const badges = row.querySelectorAll('.badge');
                    let shouldShow = false;
                    
                    badges.forEach(badge => {
                        if (filter === 'active' && badge.classList.contains('badge-status-active')) {
                            shouldShow = true;
                        } else if (filter === 'inactive' && badge.classList.contains('badge-status-inactive')) {
                            shouldShow = true;
                        } else if (filter === 'low-stock' && badge.classList.contains('badge-stock-low')) {
                            shouldShow = true;
                        } else if (filter === 'out-of-stock' && badge.classList.contains('badge-stock-out')) {
                            shouldShow = true;
                        }
                    });
                    
                    row.style.display = shouldShow ? '' : 'none';
                }
            });
            
            // Filter grid items
            gridItems.forEach(item => {
                if (filter === 'all' || filter === 'semua') {
                    item.style.display = '';
                } else {
                    const badges = item.querySelectorAll('.badge');
                    let shouldShow = false;
                    
                    badges.forEach(badge => {
                        if (filter === 'active' && badge.classList.contains('badge-status-active')) {
                            shouldShow = true;
                        } else if (filter === 'inactive' && badge.classList.contains('badge-status-inactive')) {
                            shouldShow = true;
                        } else if (filter === 'low-stock' && badge.classList.contains('badge-stock-low')) {
                            shouldShow = true;
                        } else if (filter === 'out-of-stock' && badge.classList.contains('badge-stock-out')) {
                            shouldShow = true;
                        }
                    });
                    
                    item.style.display = shouldShow ? '' : 'none';
                }
            });
        });
    });
}

// Add table interactions
function addTableInteractions() {
    const tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        // Add hover effect
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });
        
        // Add click effect
        row.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
}

// Add product grid interactions
function addProductGridInteractions() {
    const gridItems = document.querySelectorAll('.product-card, .product-grid .card');
    
    gridItems.forEach(item => {
        // Add hover effect
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
            this.style.boxShadow = '0 12px 24px rgba(0,0,0,0.15)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '';
        });
        
        // Add click effect
        item.addEventListener('click', function() {
            this.style.transform = 'translateY(-4px) scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            }, 150);
        });
    });
}

// Initialize interactive features
function initializeInteractiveFeatures() {
    // Add loading states to buttons
    addLoadingStates();
    
    // Add confirmation dialogs
    addConfirmationDialogs();
    
    // Add form enhancements
    addFormEnhancements();
    
    // Add stock management
    addStockManagement();
}

// Add loading states
function addLoadingStates() {
    const actionButtons = document.querySelectorAll('.btn');
    
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.type === 'submit' || this.classList.contains('btn-primary')) {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
                this.classList.add('loading');
                this.disabled = true;
                
                // Reset after 3 seconds (for demo purposes)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('loading');
                    this.disabled = false;
                }, 3000);
            }
        });
    });
}

// Add confirmation dialogs
function addConfirmationDialogs() {
    const deleteButtons = document.querySelectorAll('.btn-danger, .btn-delete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.')) {
                e.preventDefault();
                return false;
            }
        });
    });
}

// Add form enhancements
function addFormEnhancements() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        // Add form validation feedback
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });
        
        // Add form submission enhancement
        form.addEventListener('submit', function(e) {
            const isValid = validateForm(this);
            if (!isValid) {
                e.preventDefault();
                showFormError('Mohon periksa kembali form yang diisi');
            }
        });
    });
}

// Add stock management
function addStockManagement() {
    const stockInputs = document.querySelectorAll('input[name*="stock"], input[name*="quantity"]');
    
    stockInputs.forEach(input => {
        input.addEventListener('input', function() {
            const value = parseInt(this.value) || 0;
            const manageStockCheckbox = document.querySelector('input[name="manage_stock"]');
            
            if (manageStockCheckbox && manageStockCheckbox.checked) {
                if (value <= 0) {
                    this.classList.add('is-invalid');
                    showFieldError(this, 'Stok harus lebih dari 0');
                } else if (value <= 10) {
                    this.classList.add('is-warning');
                    showFieldWarning(this, 'Stok rendah, pertimbangkan untuk restok');
                } else {
                    this.classList.remove('is-invalid', 'is-warning');
                    hideFieldError(this);
                }
            }
        });
    });
}

// Initialize image preview
function initializeImagePreview() {
    const imageInputs = document.querySelectorAll('input[type="file"]');
    
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create or update preview
                    let preview = document.querySelector('.image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.className = 'image-preview mt-3';
                        input.parentNode.appendChild(preview);
                    }
                    
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" style="max-width: 200px; border-radius: 8px; border: 2px solid var(--admin-border);">
                        <p class="text-muted mt-2">${file.name} (${(file.size / 1024).toFixed(1)} KB)</p>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    });
}

// Validate individual field
function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.name || field.id;
    
    // Remove existing validation classes
    field.classList.remove('is-valid', 'is-invalid', 'is-warning');
    
    // Basic validation rules
    if (field.hasAttribute('required') && !value) {
        field.classList.add('is-invalid');
        showFieldError(field, 'Field ini wajib diisi');
        return false;
    }
    
    if (field.type === 'email' && value && !isValidEmail(value)) {
        field.classList.add('is-invalid');
        showFieldError(field, 'Format email tidak valid');
        return false;
    }
    
    if (field.type === 'number' && value) {
        const numValue = parseFloat(value);
        if (isNaN(numValue) || numValue < 0) {
            field.classList.add('is-invalid');
            showFieldError(field, 'Nilai harus berupa angka positif');
            return false;
        }
    }
    
    if (field.minLength && value.length < field.minLength) {
        field.classList.add('is-invalid');
        showFieldError(field, `Minimal ${field.minLength} karakter`);
        return false;
    }
    
    if (value) {
        field.classList.add('is-valid');
        hideFieldError(field);
    }
    
    return true;
}

// Validate entire form
function validateForm(form) {
    const inputs = form.querySelectorAll('input, textarea, select');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    return isValid;
}

// Show field error
function showFieldError(field, message) {
    // Remove existing error message
    hideFieldError(field);
    
    // Create error message element
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
    
    // Insert after field
    field.parentNode.appendChild(errorDiv);
}

// Show field warning
function showFieldWarning(field, message) {
    // Remove existing warning message
    hideFieldError(field);
    
    // Create warning message element
    const warningDiv = document.createElement('div');
    warningDiv.className = 'warning-feedback';
    warningDiv.textContent = message;
    warningDiv.style.display = 'block';
    
    // Insert after field
    field.parentNode.appendChild(warningDiv);
}

// Hide field error
function hideFieldError(field) {
    const existingError = field.parentNode.querySelector('.invalid-feedback, .warning-feedback');
    if (existingError) {
        existingError.remove();
    }
}

// Show form error
function showFormError(message) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.innerHTML = `
        <i class="fas fa-exclamation-circle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at top of form
    const form = document.querySelector('form');
    if (form) {
        form.insertBefore(alertDiv, form.firstChild);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
}

// Email validation helper
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Initialize tooltips
function initializeTooltips() {
    // Add tooltips to action buttons
    const actionButtons = document.querySelectorAll('.btn');
    actionButtons.forEach(button => {
        if (!button.title) {
            button.title = button.textContent.trim();
        }
    });
    
    // Add tooltips to table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.title = 'Klik untuk detail';
        row.style.cursor = 'pointer';
    });
    
    // Add tooltips to product images
    const productImages = document.querySelectorAll('.product-image');
    productImages.forEach(img => {
        img.title = 'Klik untuk memperbesar';
        img.style.cursor = 'pointer';
        
        img.addEventListener('click', function() {
            showImageModal(this.src, this.alt);
        });
    });
}

// Show image modal
function showImageModal(src, alt) {
    const modal = document.createElement('div');
    modal.className = 'image-modal';
    modal.innerHTML = `
        <div class="image-modal-content">
            <span class="image-modal-close">&times;</span>
            <img src="${src}" alt="${alt}">
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Close modal
    modal.addEventListener('click', function(e) {
        if (e.target === modal || e.target.classList.contains('image-modal-close')) {
            modal.remove();
        }
    });
}

// Add ripple effect to buttons
function addRippleEffect() {
    const buttons = document.querySelectorAll('.btn');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            createRipple(e, this);
        });
    });
}

// Create ripple effect
function createRipple(event, element) {
    const ripple = document.createElement('span');
    const rect = element.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;
    
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    ripple.classList.add('ripple');
    
    element.appendChild(ripple);
    
    setTimeout(() => {
        ripple.remove();
    }, 600);
}

// Add CSS for ripple effect and animations
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
    
    .filter-btn.active,
    .btn-group .btn.active {
        background: var(--admin-primary);
        color: white;
        border-color: var(--admin-primary);
    }
    
    .form-control.is-valid {
        border-color: var(--admin-success);
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
    }
    
    .form-control.is-invalid {
        border-color: var(--admin-danger);
        box-shadow: 0 0 0 0.2rem rgba(239, 68, 68, 0.25);
    }
    
    .form-control.is-warning {
        border-color: var(--admin-warning);
        box-shadow: 0 0 0 0.2rem rgba(245, 158, 11, 0.25);
    }
    
    .invalid-feedback {
        display: block;
        color: var(--admin-danger);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .warning-feedback {
        display: block;
        color: var(--admin-warning);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    tbody tr {
        transition: all 0.2s ease;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .product-card {
        transition: all 0.3s ease;
    }
    
    .image-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .image-modal-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
    }
    
    .image-modal-content img {
        width: 100%;
        height: auto;
        border-radius: 8px;
    }
    
    .image-modal-close {
        position: absolute;
        top: -40px;
        right: 0;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        font-weight: bold;
    }
    
    .image-preview {
        text-align: center;
    }
`;
document.head.appendChild(style);

// Initialize ripple effect
addRippleEffect(); 

// Toggle featured status
function toggleFeatured(productId, element) {
    fetch(`/admin/products/${productId}/toggle-featured`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
        } else {
            showAlert('error', 'Failed to update featured status');
            // Revert checkbox state
            element.checked = !element.checked;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while updating featured status');
        // Revert checkbox state
        element.checked = !element.checked;
    });
}

// Delete product
function deleteProduct(productId, productName) {
    if (confirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`)) {
        fetch(`/admin/products/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                showAlert('error', 'Failed to delete product');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while deleting the product');
        });
    }
}

// Select all products
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Update select all checkbox when individual checkboxes change
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(productCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(productCheckboxes).some(cb => cb.checked);
            
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }
        });
    });
});

// Show alert message
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.products-container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
}

// Bulk stock update
function bulkUpdateStock() {
    const selectedProducts = Array.from(document.querySelectorAll('.product-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedProducts.length === 0) {
        showAlert('error', 'Please select at least one product');
        return;
    }

    // Create form data
    const formData = new FormData();
    formData.append('products', JSON.stringify(selectedProducts.map(id => ({
        id: id,
        stock_quantity: 0 // Default value, should be updated with actual input
    }))));

    fetch('/admin/products/bulk-update-stock', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => {
        if (response.ok) {
            window.location.reload();
        } else {
            showAlert('error', 'Failed to update stock');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while updating stock');
    });
} 