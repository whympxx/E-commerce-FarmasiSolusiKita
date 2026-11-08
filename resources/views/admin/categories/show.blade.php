@extends('layouts.admin')

@section('title', 'Category Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Category: {{ $category->name }}</h1>
        <div>
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit Category
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Categories
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Name:</label>
                                <p>{{ $category->name }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Slug:</label>
                                <p><code>{{ $category->slug }}</code></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Description:</label>
                        <p>{{ $category->description ?: 'No description provided' }}</p>
                    </div>

                    @if($category->image)
                    <div class="form-group">
                        <label class="font-weight-bold">Category Image:</label>
                        <div class="mt-2">
                            <img src="{{ Storage::url($category->image) }}" 
                                 alt="{{ $category->name }}" 
                                 class="img-thumbnail" 
                                 style="max-width: 300px; max-height: 300px;">
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Status:</label>
                                <p>
                                    @if($category->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Created At:</label>
                                <p>{{ $category->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Updated At:</label>
                        <p>{{ $category->updated_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Products in this Category</h6>
                    <span class="badge badge-primary">{{ $category->products->count() }} Products</span>
                </div>
                <div class="card-body">
                    @if($category->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->products as $product)
                                    <tr>
                                        <td>
                                            @if($product->image)
                                                <img src="{{ Storage::url($product->image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $product->name }}</strong>
                                                @if($product->prescription_required)
                                                    <span class="badge badge-warning badge-sm ml-1">Prescription Required</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($product->price, 0, ",", ".") }}</td>
                                        <td>
                                            @if(!$product->manage_stock || $product->stock_quantity > 0)
                                                <span class="badge badge-success">{{ $product->manage_stock ? $product->stock_quantity . " in stock" : "Tersedia" }}</span>
                                            @else
                                                <span class="badge badge-danger">Out of stock</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.products.show', $product) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No products in this category</h5>
                            <p class="text-muted">Products assigned to this category will appear here.</p>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Product
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Products</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $category->products->count() }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-left-success">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Products</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $category->products->where('is_active', true)->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card border-left-warning">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Low Stock</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $category->products->where('stock', '<=', 10)->count() }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-left-danger">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Out of Stock</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $category->products->where('stock', 0)->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Category
                        </a>
                        
                        <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Add Product to Category
                        </a>
                        
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                    {{ $category->products->count() > 0 ? 'disabled' : '' }}>
                                <i class="fas fa-trash"></i> Delete Category
                            </button>
                        </form>
                        
                        @if($category->products->count() > 0)
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Cannot delete category with products. Remove all products first.
                            </small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category URL</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Frontend URL:</label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ url('/categories/' . $category->slug) }}" 
                                   readonly>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="copyToClipboard('{{ url('/categories/' . $category->slug) }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ url('/categories/' . $category->slug) }}" 
                       class="btn btn-outline-primary btn-sm" 
                       target="_blank">
                        <i class="fas fa-external-link-alt"></i> View on Frontend
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        toastr.success('URL copied to clipboard!');
    }, function(err) {
        toastr.error('Failed to copy URL');
    });
}

$(document).ready(function() {
    // Show success message
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif
});
</script>
@endpush
