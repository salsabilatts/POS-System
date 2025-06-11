<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex" style="min-height: 100vh">
    <!-- Sidebar -->
    <div class="bg-dark text-white p-3" style="width: 250px;">
        <h4 class="text-white mb-4">Admin Panel</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('products.index') }}" class="nav-link text-white">Produk</a>
            </li>
           <li class="nav-item">
                <a href="{{ route('laporan.index') }}" class="nav-link text-white">Laporan Penjualan</a>
            </li>
            <li class="nav-item mt-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-outline-light w-100" type="submit">Logout</button>
            </form>
        </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-fill p-4">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
