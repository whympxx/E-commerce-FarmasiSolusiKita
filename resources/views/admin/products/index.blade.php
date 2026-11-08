@extends('layouts.admin')

@section('title', 'Products Management')
@section('page-title', 'Products Management')

@section('content')
<div class="dashboard-container">
    <!-- Page Header with Animation -->
    <div class="row mb-4 animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">Products Management</h3>
                            <p class="text-muted mb-0">Manage pharmaceutical products and inventory</p>
                        </div>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Product
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4 animate-slide-in-left" style="animation-delay: 0.2s;">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Search & Filter</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.products.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="search-box">
                                    <input type="text" 
                                           class="form-control" 
                                           name="search" 
                                           placeholder="Search products, SKU, brand..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="stock" class="form-select">
                                    <option value="">All Stock</option>
                                    <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Low Stock</option>
                                    <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-times me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="row animate-slide-in-right" style="animation-delay: 0.3s;">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Products List ({{ $products->total() }})</h6>
                    <div class="d-flex gap-2">
                        <button id="bulkActions" class="btn btn-sm btn-warning" style="display: none;" onclick="showBulkActions()">
                            <i class="fas fa-tasks me-1"></i>Bulk Actions
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">
                                            <input type="checkbox" class="form-check-input" id="selectAll">
                                        </th>
                                        <th>Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                        <th>Unggulan</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input product-checkbox" value="{{ $product->id }}">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3 overflow-hidden flex-shrink-0" style="width: 50px; height: 50px;">
                                                        @if($product->image_url)
                                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <i class="fas fa-pills text-muted"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $product->name }}</h6>
                                                        <small class="text-muted">{{ $product->sku }} | {{ $product->brand }}</small>
                                                        @if($product->prescription_required)
                                                            <br><span class="badge bg-warning text-dark small">
                                                                <i class="fas fa-prescription me-1"></i>Resep
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $product->category->name }}</span>
                                            </td>
                                            <td>
                                                @if($product->is_on_sale)
                                                    <div class="text-danger fw-bold">{{ $product->formatted_sale_price }}</div>
                                                    <small class="text-muted text-decoration-line-through">{{ $product->formatted_price }}</small>
                                                @else
                                                    <div class="fw-bold">{{ $product->formatted_price }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($product->manage_stock)
                                                    @php
                                                        $stockClass = $product->stock_quantity == 0 ? 'danger' : ($product->stock_quantity <= 10 ? 'warning' : 'success');
                                                    @endphp
                                                    <span class="badge bg-{{ $stockClass }}">
                                                        {{ $product->stock_quantity }} {{ $product->unit }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Unlimited</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($product->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           {{ $product->is_featured ? 'checked' : '' }}
                                                           onchange="toggleFeatured({{ $product->id }}, this)">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('admin.products.show', $product) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')"
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-pills fa-3x text-muted mb-3"></i>
                            <h4>No products found</h4>
                            <p class="text-muted">
                                @if(request()->hasAny(['search', 'category', 'status', 'stock']))
                                    Try changing your search filters or 
                                    <a href="{{ route('admin.products.index') }}">clear all filters</a>.
                                @else
                                    Start by <a href="{{ route('admin.products.create') }}">adding your first product</a>.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/admin-products.js') }}"></script>
@endpush

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Pilih aksi untuk <span id="selectedCount">0</span> produk yang dipilih:</p>
                <div class="d-grid gap-2">
                    <button class="btn btn-warning" onclick="bulkUpdateStock()">
                        <i class="fas fa-boxes me-2"></i>Update Stok
                    </button>
                    <button class="btn btn-success" onclick="bulkActivate()">
                        <i class="fas fa-check me-2"></i>Aktifkan
                    </button>
                    <button class="btn btn-secondary" onclick="bulkDeactivate()">
                        <i class="fas fa-times me-2"></i>Nonaktifkan
                    </button>
                    <button class="btn btn-danger" onclick="bulkDelete()">
                        <i class="fas fa-trash me-2"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk <strong id="productName"></strong>?</p>
                <p class="text-muted">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Select all checkbox functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkActions();
});

// Individual checkbox change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('product-checkbox')) {
        updateBulkActions();
    }
});

// Update bulk actions button
function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
    const selectAllBox = document.getElementById('selectAll');
    const bulkButton = document.getElementById('bulkActions');
    
    // Update select all checkbox state
    if (checkedBoxes.length === 0) {
        selectAllBox.indeterminate = false;
        selectAllBox.checked = false;
    } else if (checkedBoxes.length === document.querySelectorAll('.product-checkbox').length) {
        selectAllBox.indeterminate = false;
        selectAllBox.checked = true;
    } else {
        selectAllBox.indeterminate = true;
        selectAllBox.checked = false;
    }
    
    // Show/hide bulk actions button
    if (checkedBoxes.length > 0) {
        bulkButton.style.display = 'block';
    } else {
        bulkButton.style.display = 'none';
    }
}

// Show bulk actions modal
function showBulkActions() {
    const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
    document.getElementById('selectedCount').textContent = checkedBoxes.length;
    new bootstrap.Modal(document.getElementById('bulkActionsModal')).show();
}

// Toggle featured status
function toggleFeatured(productId, checkbox) {
    fetch(`/admin/products/${productId}/toggle-featured`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            checkbox.checked = !checkbox.checked; // Revert on error
            showToast(data.message || 'Terjadi kesalahan', 'error');
        }
    })
    .catch(error => {
        checkbox.checked = !checkbox.checked; // Revert on error
        showToast('Terjadi kesalahan saat mengubah status unggulan', 'error');
    });
}

// Delete product
function deleteProduct(productId, productName) {
    document.getElementById('productName').textContent = productName;
    document.getElementById('deleteForm').action = `/admin/products/${productId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Show toast notification
function showToast(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const toastHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 1050; min-width: 300px;" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', toastHtml);
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        const toast = document.querySelector('.alert');
        if (toast) {
            toast.remove();
        }
    }, 3000);
}

// Bulk actions (placeholder functions)
function bulkUpdateStock() {
    showToast('Fitur update stok massal akan segera tersedia', 'info');
}

function bulkActivate() {
    showToast('Fitur aktifkan massal akan segera tersedia', 'info');
}

function bulkDeactivate() {
    showToast('Fitur nonaktifkan massal akan segera tersedia', 'info');
}

function bulkDelete() {
    showToast('Fitur hapus massal akan segera tersedia', 'info');
}
</script>
