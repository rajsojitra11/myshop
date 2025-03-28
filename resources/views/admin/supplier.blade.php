@extends('admin.index')
@section('title', 'Supplier')
@section('page-title', 'Supplier')
@section('page', 'Supplier')
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
@section('content')
  <!-- Default box -->
  {{-- <div class="card card-solid"> --}}
    <div class="card-body pb-0">
      <div class="input-group mb-4">
          <input class="form-control p-2 border rounded" type="search" id="supplierSearch" placeholder="Search Supplier..." aria-label="Search" oninput="searchSuppliers()">
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
      <div class="container">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Profile</th>
                    <th>Name</th>
                    <th>Company Name</th>
                    <th>Product Categories</th>
                    <th>Contact No.</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                        <img src="../../dist/img/user1-128x128.jpg" alt="user-avatar" class="img-circle img-fluid" width="50">
                    </td>
                    <td><b>Raj Sojitra</b></td>
                    <td>Web Designer / UX / Graphic Artist / Coffee Lover</td>
                    <td>Demo Street 123, Demo City 04312, NJ</td>
                    <td>846925846</td>
                    <td class="text-center">
                       <a href="{{Route('viewprofile')}}" class="btn btn-sm btn-primary"><i class=""></i> View Profile</a>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">
                        <img src="../../dist/img/user2-160x160.jpg" alt="user-avatar" class="img-circle img-fluid" width="50">
                    </td>
                    <td><b>Nicole Pearson</b></td>
                    <td>Web Designer / UX / Graphic Artist / Coffee Lover</td>
                    <td>Demo Street 123, Demo City 04312, NJ</td>
                    <td>2578954125</td>
                    <td class="text-center">
                        <a href="{{Route('viewprofile')}}" class="btn btn-sm btn-primary"><i class=""></i> View Profile</a>
                    </td>
                </tr>
                <!-- Repeat similar rows for additional profiles -->
            </tbody>
        </table>
    </div>
    
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
      <nav aria-label="Contacts Page Navigation">
        <ul class="pagination justify-content-center m-0">
          <li class="page-item active"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item"><a class="page-link" href="#">4</a></li>
          <li class="page-item"><a class="page-link" href="#">5</a></li>
          <li class="page-item"><a class="page-link" href="#">6</a></li>
          <li class="page-item"><a class="page-link" href="#">7</a></li>
          <li class="page-item"><a class="page-link" href="#">8</a></li>
        </ul>
      </nav>
    </div>
    <!-- /.card-footer -->
  </div>
  <!-- /.card -->


  <script>
    function searchSuppliers() {
        let input = document.getElementById("supplierSearch").value.toLowerCase();
        let table = document.querySelector("table tbody");
        let rows = table.getElementsByTagName("tr");

        for (let row of rows) {
            let name = row.getElementsByTagName("td")[1]?.textContent.toLowerCase();
            if (name && name.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        }
    }
</script>

<script>
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

    </script>
    
    
@endsection


