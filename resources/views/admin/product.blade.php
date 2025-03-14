@extends('admin.index')
@section('title', 'Product')
@section('page-title', 'Product')
@section('page', 'Product')
@section('content')

<!-- Include CSS -->
<link rel="stylesheet" href="{{ asset('css/product.css') }}">

<div class="card-body pb-0">

    <!-- Search Input -->
    <div class="input-group mb-4">
        <input class="form-control p-2 border rounded" type="search" id="supplierSearch" placeholder="Search Product..." aria-label="Search">
        <div class="input-group-append">
            <button class="btn btn-navbar p-2 bg-blue-500 text-white rounded" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    <!-- Add Product Button -->
    <button onclick="openModal()" class="btn btn-success mb-4">ADD PRODUCT</button>

    <!-- Product Modal -->
    <div id="productModal" class="modal-overlay">
        <div class="modal-content">
            <h2 class="modal-title">Add New Product</h2>
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="form-label">Code:</label>
                <input type="text" name="code" class="form-input" required>

                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-input" required>

                <label class="form-label">Price:</label>
                <input type="number" name="price" class="form-input" required>

                <label class="form-label">Quantity:</label>
                <input type="number" name="quantity" class="form-input" required>

                <label class="form-label">Image:</label>
                <input type="file" name="image" class="form-input" required>

                <div class="modal-actions">
                    <button type="button" onclick="closeModal()" class="cancel-btn">Cancel</button>
                    <button type="submit" class="save-btn">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="product-grid grid grid-cols-3 gap-4 mt-6" id="productGrid">
        @if($products->count() > 0)  {{-- ✅ Fix: Check count() instead of isEmpty() --}}
            @foreach($products as $product)
                <div class="product">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default.png') }}" 
                         alt="{{ $product->name }}" 
                         onerror="this.src='{{ asset('images/default.png') }}';" />
                    <div class="product-details">
                        <div><label>Code:</label> {{ $product->code }}</div>
                        <div><label>Name:</label> {{ $product->name }}</div>
                        <div><label>Price:</label> ₹{{ $product->price }}</div>
                    </div>
                </div>
            @endforeach
        @else
            <p>No products found.</p>
        @endif
    </div>    
</div>

<script>
    // Open Modal
    function openModal() {
        const modalOverlay = document.getElementById("productModal");
        modalOverlay.classList.add("show");
        modalOverlay.style.display = "flex";
        document.body.classList.add("modal-open");
        setTimeout(() => {
            modalOverlay.style.pointerEvents = "auto";
        }, 300);
    }

    // Close Modal
    function closeModal() {
        const modalOverlay = document.getElementById("productModal");
        modalOverlay.classList.remove("show");
        modalOverlay.style.display = "none";
        document.body.classList.remove("modal-open");
    }

    // Close modal when clicking outside the content
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("productModal").addEventListener("click", function (event) {
            if (event.target.classList.contains("modal-overlay")) {
                closeModal();
            }
        });

        // Search Products
        document.getElementById("supplierSearch").addEventListener("input", function () {
            let input = this.value.toLowerCase();
            let products = document.querySelectorAll(".product");

            products.forEach(product => {
                let productText = product.querySelector(".product-details").textContent.toLowerCase();
                product.style.display = productText.includes(input) ? "block" : "none";
            });
        });
    });
</script>

@endsection  
