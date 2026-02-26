@extends('admin.index')
@section('title', 'Add Supplier Stock')
@section('page-title', 'Add Stock')
@section('page', 'Supplier Stock')

@section('content')

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Add Stock for {{ $supplier->company_name }}</h3>
    </div>
    <form action="{{ route('supplier-stock.store') }}" method="POST">
        @csrf
        <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
        <div class="card-body">
            <div class="form-group">
                <label for="user_id">Added By</label>
                <select name="user_id" id="user_id" class="form-control">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="product">Product</label>
                <input type="text" name="product" id="product" class="form-control" value="{{ old('product') }}" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}" required>
            </div>

            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" required>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ old('date', now()->toDateString()) }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('supplier.show', $supplier->id) }}" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Save Stock</button>
        </div>
    </form>
</div>

@endsection
