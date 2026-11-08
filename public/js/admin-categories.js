// Admin Categories JavaScript

// Toggle category status
function toggleCategoryStatus(categoryId, element) {
    fetch(`/admin/categories/${categoryId}/toggle-status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            // Update the status badge
            const statusBadge = element.closest('tr').querySelector('.status-badge');
            if (statusBadge) {
                statusBadge.className = `badge status-badge bg-${data.is_active ? 'success' : 'secondary'}`;
                statusBadge.textContent = data.is_active ? 'Active' : 'Inactive';
            }
        } else {
            showAlert('error', 'Failed to update category status');
            // Revert switch state
            element.checked = !element.checked;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while updating category status');
        // Revert switch state
        element.checked = !element.checked;
    });
}

// Delete category
function deleteCategory(categoryId, categoryName) {
    if (confirm(`Are you sure you want to delete "${categoryName}"? This action cannot be undone.`)) {
        fetch(`/admin/categories/${categoryId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                showAlert('error', 'Failed to delete category');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while deleting the category');
        });
    }
}

// Show alert message
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.categories-container');
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

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const categoryForm = document.querySelector('#categoryForm');
    if (categoryForm) {
        categoryForm.addEventListener('submit', function(e) {
            const nameInput = document.getElementById('name');
            const name = nameInput.value.trim();
            
            if (name.length === 0) {
                e.preventDefault();
                showAlert('error', 'Category name is required');
                nameInput.focus();
                return false;
            }
            
            if (name.length > 255) {
                e.preventDefault();
                showAlert('error', 'Category name must be less than 255 characters');
                nameInput.focus();
                return false;
            }
        });
    }
});

// Search functionality
function filterCategories() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryRows = document.querySelectorAll('.category-row');
    
    categoryRows.forEach(row => {
        const categoryName = row.querySelector('.category-name').textContent.toLowerCase();
        const categoryDescription = row.querySelector('.category-description')?.textContent.toLowerCase() || '';
        
        if (categoryName.includes(searchTerm) || categoryDescription.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Initialize search
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', filterCategories);
    }
}); 