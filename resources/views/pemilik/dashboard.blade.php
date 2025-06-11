@extends('layouts.pemilik')

@section('content')
<div class="container mt-4">
    <h3>Dashboard Pemilik</h3>

    {{-- Filter Tahun --}}
<form method="GET" action="{{ route('pemilik.dashboard') }}" class="mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-auto">
            <label for="year" class="form-label">Pilih Tahun</label>
            <select name="year" id="year" class="form-select">
                <option value="">-- Pilih Tahun --</option>
                @foreach ($years as $year)
                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>

    @if (empty($selectedYear))
            <div class="alert alert-info">Silakan pilih tahun untuk menampilkan grafik pendapatan.</div>
        @elseif (array_sum($monthlyData) == 0)
            <div class="alert alert-warning">Tidak ada data pendapatan untuk tahun {{ $selectedYear }}.</div>
    @endif

    {{-- Grafik Pendapatan Bulanan --}}
    <canvas id="revenueChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [{
                label: 'Pendapatan Bulanan',
                data: {!! json_encode($monthlyData) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
