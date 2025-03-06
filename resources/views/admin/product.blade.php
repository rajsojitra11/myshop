@extends('admin.index')
@section('title', 'Product')
@section('page-title', 'Product')
@section('page', 'Product')
@section('content')
<link rel="stylesheet" href="{{ asset('css/product.css') }}">

        <!-- Search Input -->
        <div class="card-body pb-0">
          <div class="input-group mb-4">
            <input class="form-control p-2 border rounded" type="search" id="supplierSearch" placeholder="Search Supplier..." aria-label="Search" oninput="searchProducts()">
            <div class="input-group-append">
                <button class="btn btn-navbar p-2 bg-blue-500 text-white rounded" type="submit">
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
                <form action="{{ route('pstore') }}" method="POST" enctype="multipart/form-data">
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

            <!-- Example Product -->
            <div class="product">
                <img src="img/3.jpg" alt="Bambino Suji Regular" />
                <div class="product-details">
                    <div><label>Code:</label> BAM-004</div>
                    <div><label>Name:</label> Bambino 100% Suji Regular</div>
                    <div><label>Price:</label> ₹84</div>
                    <div><label>Qty:</label> Low Qty</div>
                </div>
                <button class="edit-button">EDIT</button>
            </div>

            <div class="product">
                <img src="img/2.jpg" alt="MTR Seviyan" />
                <div class="product-details">
                    <div><label>Code:</label> MTR-005</div>
                    <div><label>Name:</label> MTR Seviyan</div>
                    <div><label>Price:</label> ₹85 ₹95</div>
                    <div><label>Qty:</label> In Qty</div>
                </div>
                <button class="edit-button">EDIT</button>
            </div>  

            <div class="product">
                <img src="img/2.jpg" alt="Bambino Roasted" />
                <div class="product-details">
                    <div><label>Code:</label> BAM-003</div>
                    <div><label>Name:</label> Bambino Roasted</div>
                    <div><label>Price:</label> ₹126</div>
                    <div><label>Qty:</label> In Qty</div>
                </div>
                <button class="edit-button">EDIT</button>
            </div>

            <!-- More Products -->
            <div class="product">
                <img src="img/3.jpg" alt="Bambino Suji Regular" />
                <div class="product-details">
                    <div><label>Code:</label> BAM-004</div>
                    <div><label>Name:</label> Bambino 100% Suji Regular</div>
                    <div><label>Price:</label> ₹84</div>
                    <div><label>Qty:</label> Low Qty</div>
                </div>
                <button class="edit-button">EDIT</button>
            </div>

            <div class="product">
                <img src="img/2.jpg" alt="MTR Seviyan" />
                <div class="product-details">
                    <div><label>Code:</label> MTR-005</div>
                    <div><label>Name:</label> MTR Seviyan</div>
                    <div><label>Price:</label> ₹85 ₹95</div>
                    <div><label>Qty:</label> In Qty</div>
                </div>
                <button class="edit-button">EDIT</button>
            </div>

            <div class="product">
                <img src="img/3.jpg" alt="Bambino Regular" />
                <div class="product-details">
                    <div><label>Code:</label> BAM-006</div>
                    <div><label>Name:</label> Bambino Regular</div>
                    <div><label>Price:</label> ₹55</div>
                    <div><label>Qty:</label> Out of Qty</div>
                </div>
                <button class="edit-button">EDIT</button>
            </div>

            <div class="product">
                <img src="img/2.jpg" alt="Bambino Roasted" />
                <div class="product-details">
                    <div><label>Code:</label> BAM-007</div>
                    <div><label>Name:</label> Bambino Roasted</div>
                    <div><label>Price:</label> ₹85</div>
                    <div><label>Qty:</label> In Qty</div>
                </div>
                <button class="edit-button">EDIT</button>
            </div>
        </div>
    </div>
</div>
<script>

    document.addEventListener("DOMContentLoaded", function () {
    const modalOverlay = document.getElementById("productModal");
    const searchInput = document.getElementById("supplierSearch");
    const editButtons = document.querySelectorAll(".edit-button");

    // Open Modal
    function openModal() {
        modalOverlay.classList.add("show");
        modalOverlay.style.display = "flex"; // Ensure modal appears
        document.body.classList.add("modal-open"); // Prevent background scrolling

        // Fix pointer-events issue
        setTimeout(() => {
            modalOverlay.style.pointerEvents = "auto";
        }, 300); 
    }

    // Close Modal
    function closeModal() {
        modalOverlay.classList.remove("show");
        modalOverlay.style.display = "none"; // Hide modal properly
        document.body.classList.remove("modal-open");

        // Ensure buttons behind modal are interactable
        setTimeout(() => {
            document.querySelectorAll(".edit-button").forEach(button => {
                button.style.pointerEvents = "auto";
            });
        }, 300); 
    }

    // Close modal when clicking outside the content
    modalOverlay.addEventListener("click", function (event) {
        if (event.target.classList.contains("modal-overlay")) {
            closeModal();
        }
    });

    // Prevent modal from closing when clicking inside modal content
    document.querySelector(".modal-content").addEventListener("click", function (event) {
        event.stopPropagation(); // Prevent event from reaching modal overlay
    });

    // Search Products
    function searchProducts() {
        let input = searchInput.value.toLowerCase();
        let products = document.querySelectorAll(".product");

        products.forEach(product => {
            let productName = product.querySelector(".product-details div:nth-child(2)").textContent.toLowerCase();
            
            if (productName.includes(input)) {
                product.style.display = "block";
            } else {
                product.style.display = "none";
            }
        });
    }

    // Attach event listeners
    searchInput.addEventListener("input", searchProducts);
    window.openModal = openModal;
    window.closeModal = closeModal;
});


</script>
@endsection  

