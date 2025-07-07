@extends('layouts.admin')

@section('title', 'Daftar Produk')

@section('content')
<div class="container">
    <h1 class="mt-4">Daftar Produk</h1>

    <!-- Search Input -->
    <div class="row mb-3">
        <div class="col-md-6">
            <input 
                type="text" 
                id="searchInput" 
                class="form-control" 
                placeholder="Cari produk...">
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.products.create') }}" class="btn btn-success">Tambah Produk</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabel Produk -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle" id="productTable">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Unit</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                <tr>
                    <td>{{ $product->id_product }}</td>
                    <td>{{ $product->product_name }}</td>
                     <td>{{ $product->satuan }}</td>
                    <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id_product) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('admin.products.destroy', $product->id_product) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada produk ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $products->links() }}
    </div>
</div>

<!-- Script Filter -->
<script>
    document.getElementById("searchInput").addEventListener("keyup", function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll("#productTable tbody tr");

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? "" : "none";
        });
    });
</script>
@endsection
