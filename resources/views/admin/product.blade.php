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
    @if($products->count() > 0) 
        @foreach($products as $product)
            <div class="product border p-4 rounded-lg shadow-md">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default.png') }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-40 object-cover rounded-lg"
                     onerror="this.src='{{ asset('images/default.png') }}';" />

                <div class="product-details mt-3">
                    <div><label class="font-bold">Code:</label> {{ $product->code }}</div>
                    <div><label class="font-bold">Name:</label> {{ $product->name }}</div>
                    <div><label class="font-bold">Price:</label> ₹{{ $product->price }}</div>
                    <div><label class="font-bold">Quantity:</label>{{ $product->stock_quantity }}</div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <!-- Edit Button -->
                    <button onclick="openEditModal({{ $product->id }}, '{{ $product->code }}', '{{ $product->name }}', {{ $product->price }}, {{ $product->stock_quantity }})"
                        class="bg-blue-500 text-white text-xs px-3 py-1 rounded">
                        Edit
                    </button>
                
                    <!-- Delete Button -->
                    <form action="{{ route('product.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white text-xs px-3 py-1 rounded">
                            Delete
                        </button>
                    </form>
                </div>
                        
            </div>
        @endforeach
    @else
        <p>No products found.</p>
    @endif
</div>
<!-- Edit Product Modal -->
<div id="editProductModal" class="modal-overlay">
    <div class="modal-content">
        <h2 class="text-lg font-bold mb-4">Edit Product</h2>
        <form id="editProductForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="editProductId" name="id">

            <label class="block">Code:</label>
            <input type="text" id="editProductCode" name="code" class="w-full p-2 border rounded">

            <label class="block mt-2">Name:</label>
            <input type="text" id="editProductName" name="name" class="w-full p-2 border rounded">

            <label class="block mt-2">Price:</label>
            <input type="number" id="editProductPrice" name="price" class="w-full p-2 border rounded">

            <label class="block mt-2">Quantity:</label>
            <input type="number" id="editProductQuantity" name="quantity" class="w-full p-2 border rounded">


            <div class="mt-4 flex justify-between">
                <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</div>
</div>




<!-- SweetAlert2 for Success Message -->
@if(session('psuccess'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: "Success!",
                text: "{{ session('psuccess') }}",
                icon: "success",
                confirmButtonText: "OK"
            });
        });
    </script>
@endif

@if(session('pasuccess'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: "Success!",
                text: "{{ session('pasuccess') }}",
                icon: "success",
                confirmButtonText: "OK"
            });
        });
    </script>
@endif

@if(session('pdsuccess'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: "Success!",
                text: "{{ session('pdsuccess') }}",
                icon: "success",
                confirmButtonText: "OK"
            });
        });
    </script>
@endif
<script>
document.addEventListener("DOMContentLoaded", function () {

    /** ===========================
     *  Open & Close Edit Product Modal
     *  =========================== */
    function openEditModal(id, code, name, price, quantity) {
        document.getElementById('editProductId').value = id;
        document.getElementById('editProductCode').value = code;
        document.getElementById('editProductName').value = name;
        document.getElementById('editProductPrice').value = price;
        document.getElementById('editProductQuantity').value = quantity;

        document.getElementById('editProductForm').action = "/products/" + id;

        const modal = document.getElementById('editProductModal');
        modal.style.display = "flex";
        modal.classList.add('show');
        document.body.classList.add('modal-open');
    }

    function closeEditModal() {
        const modal = document.getElementById('editProductModal');
        modal.style.display = "none";
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
    }

    // Close Edit Modal when clicking outside
    document.getElementById("editProductModal").addEventListener("click", function (event) {
        if (event.target.classList.contains("modal-overlay")) {
            closeEditModal();
        }
    });

    /** ===========================
     *  Open & Close Product Modal
     *  =========================== */
    function openModal() {
        const modalOverlay = document.getElementById("productModal");
        modalOverlay.classList.add("show");
        modalOverlay.style.display = "flex";
        document.body.classList.add("modal-open");
        setTimeout(() => {
            modalOverlay.style.pointerEvents = "auto";
        }, 300);
    }

    function closeModal() {
        const modalOverlay = document.getElementById("productModal");
        modalOverlay.classList.remove("show");
        modalOverlay.style.display = "none";
        document.body.classList.remove("modal-open");
    }

    // Close Product Modal when clicking outside
    document.getElementById("productModal").addEventListener("click", function (event) {
        if (event.target.classList.contains("modal-overlay")) {
            closeModal();
        }
    });

    /*  ===========================
     *  Search Product Functionality
     *  =========================== */
    document.getElementById("supplierSearch").addEventListener("input", function () {
        let input = this.value.toLowerCase();
        let products = document.querySelectorAll(".product");

        products.forEach(product => {
            let productText = product.querySelector(".product-details").textContent.toLowerCase();
            product.style.display = productText.includes(input) ? "block" : "none";
        });
    });

    /** ===========================
     *  Expose Functions Globally
     *  =========================== */
    window.openEditModal = openEditModal;
    window.closeEditModal = closeEditModal;
    window.openModal = openModal;
    window.closeModal = closeModal;
});
</script>


@endsection  
