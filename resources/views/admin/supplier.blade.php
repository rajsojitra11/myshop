@extends('admin.index')
@section('title', 'Supplier')
@section('page-title', 'Supplier')
@section('page', 'Supplier')
<link rel="stylesheet" href="{{ asset('css/supplier.css') }}">
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
      <!-- Add Supplier Button -->
<button onclick="openModal()" class="btn btn-success mb-4"  >ADD SUPPLIER</button>

<!-- Supplier Modal -->
<div id="supplierModal" class="modal-overlay">
    <div class="modal-content">
        <h2 class="modal-title">Add Supplier</h2>
        <br>
        <form action="#" method="POST">
            <table class="table table-bordered text-center">
              <tbody>
            
                <tr>
                  <th>Supplier ID</th>
                  <td><input type="text" class="form-control" name="name" value="admin"></td>
                  <th>Company Name</th>
                  <td><input type="text" class="form-control" name="cname" value="admin"></td>
                </tr>
                <tr>
                  <th>Email</th>
                  <td><input type="email" class="form-control" name="email" ></td>
                  <th>Address</th>
                  <td><input type="text" class="form-control" name="address" ></td>
                </tr>
                <tr>
                  <th>Contact No.</th>
                  <td><input type="text" class="form-control" name="mobile"></td>
                  <th>Country</th>
                  <td><input type="text" class="form-control" name="country"></td>
                </tr>
                <tr>
                  <th>Bank Details</th>
                  <td><input type="text" class="form-control" name="bankdetails"></td>
                  <th>Product Categories</th>
                  <td><input type="text" class="form-control" name="productcategories"></td>
                </tr>
              </tbody>
            </table>
      
            <a href="#" class="btn btn-primary btn-block"><i class=""></i>Save</a>
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


