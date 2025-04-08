@extends('admin.index')
@section('title', 'Supplier')
@section('page-title', 'Supplier')
@section('page', 'Supplier')
<link rel="stylesheet" href="{{ asset('css/supplier.css') }}">

@section('content')
  <!-- Default box -->
  <div class="card-body pb-0">
    <div class="input-group mb-4">
        <input class="form-control p-2 border rounded" type="search" id="supplierSearch" placeholder="Search Supplier..." aria-label="Search" oninput="searchSuppliers()">
        <div class="input-group-append">
            <button class="btn btn-navbar p-2 bg-blue-500 text-white rounded" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    <!-- Add Supplier Button -->
    <button onclick="openModal()" class="btn btn-success mb-4">ADD SUPPLIER</button>

    <!-- Supplier Modal -->
    <div id="supplierModal" class="modal-overlay">
        <div class="modal-content">
            <h2 class="modal-title">Add Supplier</h2>
            <br>
            <form action="{{ route('suppliers.store') }}" method="POST">
              @csrf
                <table class="table table-bordered text-center">
                    <tbody>
                        <tr>
                            <th>Supplier ID</th>
                            <td> <input type="text" name="supplier_id" class="form-control" required></td>
                            <th>Company Name</th>
                            <td> <input type="text" name="company_name" class="form-control" required></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td> <input type="email" name="email" class="form-control" required></td>
                            <th>Address</th>
                            <td>  <input type="text" name="address" class="form-control" required></td>
                        </tr>
                        <tr>
                            <th>Contact No.</th>
                            <td> <input type="text" name="contact_no" class="form-control" required></td>
                            <th>Country</th>
                            <td> <input type="text" name="country" class="form-control" required></td>
                        </tr>
                        <tr>
                            <th>Bank Details</th>
                            <td> <input type="text" name="bank_details" class="form-control" required></td>
                            <th>Product Categories</th>
                            <td>  <input type="text" name="product_categories" class="form-control" required></td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>

    <div class="container">
      <table class="table table-bordered table-hover">
          <thead class="thead-light">
              <tr>
                  <th>supplier id</th>
                  <th>Company_name</th>
                  <th>Email</th>
                  <th>Category</th>
                  <th>bank_details</th>
                  <th>Actions</th>
              </tr>
          </thead>
          <tbody id="supplierTableBody">
              @foreach ($suppliers as $supplier)
              <tr>
                <td><b>{{ $supplier->supplier_id }}</b></td>
                <td>{{ $supplier->company_name }}</td>
                  <td>{{ $supplier->email }}</td>
                  <td>{{ $supplier->product_categories }}</td>
                  <td>{{ $supplier->bank_details }}</td>
                  <td class="text-center">
                    <a href="{{ route('supplier.show', $supplier->id) }}" class="btn btn-sm btn-primary mb-1">View Profile</a>
                    
                    <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this supplier?');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
                
              </tr>
              @endforeach
          </tbody>
      </table>
  </div>
  

    <!-- Pagination -->
    <div class="card-footer">
        <nav aria-label="Contacts Page Navigation">
            <ul class="pagination justify-content-center m-0" id="pagination">
                <li class="page-item active"><a class="page-link" href="#" onclick="changePage(1)">1</a></li>
                <li class="page-item"><a class="page-link" href="#" onclick="changePage(2)">2</a></li>
                <li class="page-item"><a class="page-link" href="#" onclick="changePage(3)">3</a></li>
                <li class="page-item"><a class="page-link" href="#" onclick="changePage(4)">4</a></li>
                <li class="page-item"><a class="page-link" href="#" onclick="changePage(5)">5</a></li>
                <li class="page-item"><a class="page-link" href="#" onclick="changePage(6)">6</a></li>
                <li class="page-item"><a class="page-link" href="#" onclick="changePage(7)">7</a></li>
                <li class="page-item"><a class="page-link" href="#" onclick="changePage(8)">8</a></li>
            </ul>
        </nav>
    </div>
</div>


@if(session('supplier'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: "Success!",
                text: "{{ session('supplier') }}",
                icon: "success",
                confirmButtonText: "OK"
            });
        });
    </script>
  @endif





<script>
    let currentPage = 1;
    const rowsPerPage = 5; // Number of rows to show per page

    // Function to search suppliers by Name
    function searchSuppliers() {
        const input = document.getElementById("supplierSearch").value.toLowerCase().trim();
        const table = document.getElementById("supplierTableBody");
        const rows = table.getElementsByTagName("tr");

        for (let row of rows) {
            const nameCell = row.getElementsByTagName("td")[1]; // Name is in the second column (index 1)
            let found = false;

            // Check if the name matches the search query
            if (nameCell && nameCell.textContent.toLowerCase().includes(input)) {
                found = true;
            }

            // Show or hide row based on search match
            row.style.display = found ? "" : "none";
        }

        // Recalculate pagination after search
        changePage(currentPage);
    }

    // Function to change page
    function changePage(page) {
        currentPage = page;
        const table = document.getElementById("supplierTableBody");
        const rows = Array.from(table.getElementsByTagName("tr"));
        const totalRows = rows.filter(row => row.style.display !== "none").length;

        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });

        // Update pagination links
        const pageLinks = document.querySelectorAll("#pagination .page-link");
        pageLinks.forEach(link => {
            link.parentElement.classList.remove("active");
        });
        pageLinks[page - 1].parentElement.classList.add("active");
    }

    // Initialize page load
    document.addEventListener("DOMContentLoaded", function () {
        changePage(currentPage);
    });
</script>
  <script>
    // Function to search suppliers by Name
    function searchSuppliers() {
        const input = document.getElementById("supplierSearch").value.toLowerCase().trim();
        const table = document.getElementById("supplierTableBody");
        const rows = table.getElementsByTagName("tr");

        for (let row of rows) {
            const nameCell = row.getElementsByTagName("td")[1]; // Name is in the second column (index 1)
            let found = false;

            // Check if the name matches the search query
            if (nameCell && nameCell.textContent.toLowerCase().includes(input)) {
                found = true;
            }

            // Show or hide row based on search match
            row.style.display = found ? "" : "none";
        }
    }
</script>


<script>
    function openModal() {
        const modalOverlay = document.getElementById("supplierModal"); // Correct ID
        modalOverlay.classList.add("show");
        modalOverlay.style.display = "flex";
        document.body.classList.add("modal-open");
        setTimeout(() => {
            modalOverlay.style.pointerEvents = "auto";
        }, 300);
    }

    function closeModal() {
        const modalOverlay = document.getElementById("supplierModal"); // Correct ID
        modalOverlay.classList.remove("show");
        modalOverlay.style.display = "none";
        document.body.classList.remove("modal-open");
    }

    // Close Supplier Modal when clicking outside
    document.getElementById("supplierModal").addEventListener("click", function (event) {
        if (event.target.classList.contains("modal-overlay")) {
            closeModal();
        }
    });
</script>   
    
@endsection


