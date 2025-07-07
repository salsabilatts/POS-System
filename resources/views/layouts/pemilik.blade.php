<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pemilik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-dark text-white p-3" style="min-height: 100vh; width: 220px;">
        <h4 class="text-center mb-4">Pemilik</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="{{ route('pemilik.dashboard') }}" class="nav-link text-white">Dashboard</a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('pemilik.laporan') }}" class="nav-link text-white">Riwayat Penjualan</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pemilik.cashflow') }}" class="nav-link text-white {{ request()->is('pemilik/laporan-cashflow') ? 'active' : '' }}">
                    Laporan Cashflow
                </a>
            </li>
            <li class="nav-item mt-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-outline-light w-100" type="submit">Logout</button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div class="flex-grow-1 p-4">
        @yield('content')
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
