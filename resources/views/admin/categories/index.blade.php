@extends('layouts.admin')

@section('title', 'Categories')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-categories.css') }}">
<style>
    /* Animasi fadeIn */
    .fade-in {
        animation: fadeIn 0.8s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    /* Animasi bounce untuk empty state */
    .bounce-in {
        animation: bounceIn 1s;
    }
    @keyframes bounceIn {
        0% { transform: scale(0.8); opacity: 0; }
        60% { transform: scale(1.05); opacity: 1; }
        80% { transform: scale(0.95); }
        100% { transform: scale(1); }
    }
    /* Hover animasi tombol */
    .btn-animate:hover {
        transform: translateY(-2px) scale(1.04);
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        transition: all 0.2s;
    }
    /* Animasi baris tabel */
    tbody tr { opacity: 0; animation: fadeIn 0.7s forwards; }
    tbody tr:nth-child(n) { animation-delay: calc(0.05s * var(--i)); }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 fade-in">
                <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div>
                        <h1 class="h3 mb-1">Categories Management</h1>
                        <p class="mb-0 text-muted">Manage product categories and organize your inventory</p>
                    </div>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-animate mt-3 mt-md-0">
                        <i class="fas fa-plus me-1"></i> Add New Category
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-3 fade-in">
        <div class="col-12">
            <form method="GET" action="{{ route('admin.categories.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" id="search" class="form-control" 
                                   value="{{ request('search') }}" placeholder="Search categories...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="sort" id="sort" class="form-select">
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                            <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date Created</option>
                            <option value="products_count" {{ request('sort') === 'products_count' ? 'selected' : '' }}>Product Count</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-animate w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-animate w-100">
                            <i class="fas fa-times me-1"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="row fade-in">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0">
                    <h5 class="mb-0">Categories List</h5>
                </div>
                <div class="card-body">
                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Description</th>
                                        <th>Products</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $i => $category)
                                    <tr style="--i: {{ $i }};">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($category->image)
                                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                                         alt="{{ $category->name }}" 
                                                         class="rounded me-2 border" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center border" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $category->name }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code>{{ $category->slug }}</code>
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($category->description, 50) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark">{{ $category->products_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" 
                                                       type="checkbox" 
                                                       data-id="{{ $category->id }}"
                                                       {{ $category->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <small>{{ $category->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.categories.show', $category->id) }}" 
                                                   class="btn btn-info btn-sm btn-animate" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                                   class="btn btn-primary btn-sm btn-animate" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm btn-animate" 
                                                        onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')" 
                                                        title="Delete">
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
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">
                                    Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} results
                                </small>
                            </div>
                            <div>
                                {{ $categories->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <div class="empty-state text-center py-5 bounce-in">
                            <i class="fas fa-folder-open fa-3x mb-3 text-muted"></i>
                            <h4>No categories found</h4>
                            <p class="text-muted">Start by creating your first category.</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-animate mt-2">
                                <i class="fas fa-plus me-1"></i> Create Category
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/admin-categories.js') }}"></script>
@endpush

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content fade-in">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the category <strong id="categoryName"></strong>?</p>
                <p class="text-danger"><small>This action cannot be undone. All products in this category will be uncategorized.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-animate" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" id="deleteForm" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-animate">Delete Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Status toggle
    $('.status-toggle').change(function() {
        const categoryId = $(this).data('id');
        const isActive = $(this).prop('checked');
        
        $.ajax({
            url: `/admin/categories/${categoryId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                is_active: isActive
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Category status updated successfully');
                    setTimeout(() => location.reload(), 700);
                } else {
                    toastr.error('Failed to update category status');
                }
            },
            error: function() {
                toastr.error('An error occurred while updating category status');
            }
        });
    });

    // Show success message
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    // Show error message
    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif

    // Animasi baris tabel
    $('tbody tr').each(function(i) {
        $(this).css('animation-delay', (i * 0.05) + 's');
    });
});

function deleteCategory(id, name) {
    document.getElementById('categoryName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/categories/${id}`;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endpush
