@extends('layouts.admin')

@section('title', 'Product Details')
@section('page-title', 'Product Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Product Details</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Basic Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6>Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Product Name</label>
                                                <p class="mb-0">{{ $product->name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">SKU</label>
                                                <p class="mb-0">{{ $product->sku }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Category</label>
                                                <p class="mb-0">
                                                    <span class="badge bg-info">{{ $product->category->name }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Brand</label>
                                                <p class="mb-0">{{ $product->brand ?: 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($product->short_description)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Short Description</label>
                                            <p class="mb-0">{{ $product->short_description }}</p>
                                        </div>
                                    @endif

                                    @if($product->description)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Description</label>
                                            <p class="mb-0">{{ $product->description }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6>Pricing</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Regular Price</label>
                                                <p class="mb-0 h5 text-primary">{{ $product->formatted_price }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Sale Price</label>
                                                <p class="mb-0 h5 text-danger">
                                                    {{ $product->formatted_sale_price ?: 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($product->is_on_sale)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Discount</label>
                                            <p class="mb-0">
                                                <span class="badge bg-success">{{ $product->discount_percentage }}% OFF</span>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Inventory -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6>Inventory</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Stock Quantity</label>
                                                <p class="mb-0">
                                                    @if($product->manage_stock)
                                                        @php
                                                            $stockClass = $product->stock_quantity == 0 ? 'danger' : ($product->stock_quantity <= 10 ? 'warning' : 'success');
                                                        @endphp
                                                        <span class="badge bg-{{ $stockClass }}">
                                                            {{ $product->stock_quantity }} {{ $product->unit ?: 'pcs' }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">Unlimited</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Stock Management</label>
                                                <p class="mb-0">
                                                    <span class="badge bg-{{ $product->manage_stock ? 'success' : 'secondary' }}">
                                                        {{ $product->manage_stock ? 'Enabled' : 'Disabled' }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">In Stock</label>
                                                <p class="mb-0">
                                                    <span class="badge bg-{{ $product->in_stock ? 'success' : 'danger' }}">
                                                        {{ $product->in_stock ? 'Yes' : 'No' }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Status & Settings -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6>Status & Settings</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status</label>
                                        <p class="mb-0">
                                            <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Featured Product</label>
                                        <p class="mb-0">
                                            <span class="badge bg-{{ $product->is_featured ? 'warning' : 'secondary' }}">
                                                {{ $product->is_featured ? 'Yes' : 'No' }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Prescription Required</label>
                                        <p class="mb-0">
                                            <span class="badge bg-{{ $product->prescription_required ? 'warning' : 'secondary' }}">
                                                {{ $product->prescription_required ? 'Yes' : 'No' }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Sort Order</label>
                                        <p class="mb-0">{{ $product->sort_order }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Medical Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6>Medical Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Manufacturer</label>
                                        <p class="mb-0">{{ $product->manufacturer ?: 'N/A' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Expiry Date</label>
                                        <p class="mb-0">
                                            {{ $product->expiry_date ? $product->expiry_date->format('d M Y') : 'N/A' }}
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Batch Number</label>
                                        <p class="mb-0">{{ $product->batch_number ?: 'N/A' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Weight</label>
                                        <p class="mb-0">{{ $product->weight ? $product->weight . ' grams' : 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Medical Details -->
                    @if($product->dosage_instructions || $product->side_effects || $product->contraindications)
                        <div class="card">
                            <div class="card-header">
                                <h6>Medical Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if($product->dosage_instructions)
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Dosage Instructions</label>
                                                <p class="mb-0">{{ $product->dosage_instructions }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($product->side_effects)
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Side Effects</label>
                                                <p class="mb-0">{{ $product->side_effects }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($product->contraindications)
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Contraindications</label>
                                                <p class="mb-0">{{ $product->contraindications }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 