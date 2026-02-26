@extends('admin.index')
@section('title', 'View Supplier')
@section('page-title', 'Supplier Details')
@section('page', 'Supplier')

@section('content')

<!-- Supplier Header Card -->
<div class="card card-primary card-outline">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-building mr-2"></i>{{ $supplier->company_name }}
            </h3>
            <span class="badge badge-light">ID: {{ $supplier->supplier_id }}</span>
        </div>
    </div>
    
    <!-- Supplier Details Section -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold text-muted">Company Name</label>
                    <p class="text-dark">{{ $supplier->company_name }}</p>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-muted">Email Address</label>
                    <p class="text-dark">
                        <i class="fas fa-envelope mr-2"></i>
                        <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                    </p>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-muted">Contact Number</label>
                    <p class="text-dark">
                        <i class="fas fa-phone mr-2"></i>{{ $supplier->contact_no }}
                    </p>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-muted">Country</label>
                    <p class="text-dark">{{ $supplier->country }}</p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold text-muted">Supplier ID</label>
                    <p class="text-dark">{{ $supplier->supplier_id }}</p>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-muted">Address</label>
                    <p class="text-dark">
                        <i class="fas fa-map-marker-alt mr-2"></i>{{ $supplier->address }}
                    </p>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-muted">Bank Details</label>
                    <p class="text-dark">{{ $supplier->bank_details }}</p>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-muted">Product Categories</label>
                    <p class="text-dark">
                        <span class="badge badge-info">{{ $supplier->product_categories }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="card-footer bg-light">
        <div class="btn-group-flex d-flex gap-2" role="group">
            <a href="{{ route('supplier-stock.create', $supplier->id) }}" class="btn btn-success">
                <i class="fas fa-plus mr-2"></i>Add Stock
            </a>
            <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-warning">
                <i class="fas fa-edit mr-2"></i>Edit Supplier
            </a>
            <a href="{{ route('supplier') }}" class="btn btn-secondary">
                <i class="fas fa-times mr-2"></i>Close
            </a>
        </div>
    </div>
</div>

<!-- Stock Records Section -->
<div class="card card-outline card-secondary mt-4">
    <div class="card-header bg-secondary text-white">
        <h3 class="card-title mb-0">
            <i class="fas fa-boxes mr-2"></i>Stock Records
        </h3>
    </div>
    
    <div class="card-body table-responsive p-0">
        @forelse ($stocks as $index => $stock)
            @if ($loop->first)
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 15%">Product</th>
                            <th style="width: 10%">Quantity</th>
                            <th style="width: 20%">Description</th>
                            <th style="width: 15%">Amount</th>
                            <th style="width: 12%">Date</th>
                            <th style="width: 12%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            @endif
                        <tr>
                            <td>{{ $stocks->firstItem() + $index }}</td>
                            <td>
                                <span class="badge badge-primary">{{ $stock->product }}</span>
                            </td>
                            <td class="font-weight-bold">{{ $stock->quantity }}</td>
                            <td>{{ $stock->description ?? '-' }}</td>
                            <td class="text-success font-weight-bold">₹ {{ number_format($stock->amount, 2) }}</td>
                            <td>
                                <small class="text-muted">{{ $stock->date->format('d-m-Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('supplier-stock.edit', $stock->id) }}" class="btn btn-info" title="Edit Stock">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('supplier-stock.destroy', $stock->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this stock record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Delete Stock">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
            @if ($loop->last)
                    </tbody>
                </table>
            @endif
        @empty
            <div class="alert alert-info m-0 text-center">
                <i class="fas fa-info-circle mr-2"></i>
                No stock records found. <a href="{{ route('supplier-stock.create', $supplier->id) }}">Add the first stock entry</a>.
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($stocks->total() > 0)
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-center">
                {{ $stocks->links() }}
            </div>
        </div>
    @endif
</div>

<style>
    .btn-group-flex {
        display: flex;
        gap: 10px;
    }
    
    .btn-group-flex .btn {
        flex: 1;
        white-space: nowrap;
    }
    
    .badge {
        padding: 0.5rem 0.75rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-group p {
        font-size: 1rem;
        margin-bottom: 0;
        padding: 0.5rem 0;
    }
</style>

@endsection