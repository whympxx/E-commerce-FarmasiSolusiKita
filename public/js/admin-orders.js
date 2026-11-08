// Admin Orders JavaScript

// Delete order
function deleteOrder(orderId, orderNumber) {
    if (confirm(`Are you sure you want to delete order #${orderNumber}? This action cannot be undone.`)) {
        fetch(`/admin/orders/${orderId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                showToast('error', 'Failed to delete order');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'An error occurred while deleting the order');
        });
    }
}

// Get status color
function getStatusColor(status) {
    switch (status) {
        case 'pending': return 'warning';
        case 'processing': return 'info';
        case 'shipped': return 'primary';
        case 'delivered': return 'success';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}

// Get payment status color
function getPaymentStatusColor(paymentStatus) {
    switch (paymentStatus) {
        case 'paid': return 'success';
        case 'pending': return 'warning';
        case 'failed': return 'danger';
        case 'refunded': return 'info';
        default: return 'secondary';
    }
}

// Search functionality
function filterOrders() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const orderRows = document.querySelectorAll('.order-row');
    
    orderRows.forEach(row => {
        const orderNumber = row.querySelector('.order-number').textContent.toLowerCase();
        const customerName = row.querySelector('.customer-name').textContent.toLowerCase();
        const customerEmail = row.querySelector('.customer-email')?.textContent.toLowerCase() || '';
        
        if (orderNumber.includes(searchTerm) || customerName.includes(searchTerm) || customerEmail.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Filter by status
function filterByStatus(status) {
    const orderRows = document.querySelectorAll('.order-row');
    
    orderRows.forEach(row => {
        const orderStatus = row.querySelector('.status-badge').textContent.toLowerCase();
        
        if (status === 'all' || orderStatus === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Filter by payment status
function filterByPaymentStatus(paymentStatus) {
    const orderRows = document.querySelectorAll('.order-row');
    
    orderRows.forEach(row => {
        const orderPaymentStatus = row.querySelector('.payment-badge').textContent.toLowerCase();
        
        if (paymentStatus === 'all' || orderPaymentStatus === paymentStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Initialize page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize search
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', filterOrders);
    }

    // Initialize status filter
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            filterByStatus(this.value);
        });
    }

    // Initialize payment status filter
    const paymentStatusFilter = document.getElementById('paymentStatusFilter');
    if (paymentStatusFilter) {
        paymentStatusFilter.addEventListener('change', function() {
            filterByPaymentStatus(this.value);
        });
    }

    // Initialize date range picker
    const dateFromInput = document.getElementById('dateFrom');
    const dateToInput = document.getElementById('dateTo');
    
    if (dateFromInput && dateToInput) {
        [dateFromInput, dateToInput].forEach(input => {
            input.addEventListener('change', function() {
                // Trigger form submission for date filtering
                const form = this.closest('form');
                if (form) {
                    form.submit();
                }
            });
        });
    }

    // Konfirmasi hapus sudah ada di atribut onsubmit pada form (jangan dipasang dua kali)
});

// Export orders functionality
function exportOrders(format = 'csv') {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('export', format);
    
    window.location.href = currentUrl.toString();
} 