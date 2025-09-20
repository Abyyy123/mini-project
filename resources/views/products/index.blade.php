<!DOCTYPE html>
<html>
<head>
    <title>Master Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="container mt-4">

<h2 class="mb-4">Master Products</h2>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Button trigger modal -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
    + Tambah Produk
</button>

<!-- Search Box -->
<form method="GET" action="{{ route('products.index') }}" class="mb-3 d-flex">
    <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control me-2" placeholder="Cari produk...">
    <button type="submit" class="btn btn-outline-primary">Search</button>
</form>

<!-- Table -->
<table class="table table-bordered table-striped" id="productTable">
    <thead class="table-dark">
        <tr>
            <th>ID</th><th>Code</th><th>Name</th><th>Price</th>
            <th>Stock</th><th>Status</th><th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $p)
        <tr>
            <td>{{ $p->product_id }}</td>
            <td>{{ $p->product_code }}</td>
            <td>{{ $p->product_name }}</td>
            <td>{{ $p->price }}</td>
            <td>{{ $p->stock_quantity }}</td>
            <td>
                <span class="badge {{ $p->is_active ? 'bg-success' : 'bg-danger' }}">
                    {{ $p->is_active ? 'Active' : 'Inactive' }}
                </span>
            </td>
            <td>
                <a href="{{ route('products.destroy',$p->product_id) }}" 
                   class="btn btn-sm btn-danger">Soft Delete</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>


<!-- Modal Add -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="product_code" class="form-control mb-2" placeholder="Code" required>
                <input type="text" name="product_name" class="form-control mb-2" placeholder="Name" required>
                <input type="number" step="0.01" name="price" class="form-control mb-2" placeholder="Price" required>
                <input type="number" name="stock_quantity" class="form-control mb-2" placeholder="Stock" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){
    $("#searchBox").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#productTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>

</body>
</html>
