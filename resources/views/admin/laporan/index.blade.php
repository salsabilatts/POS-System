@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Laporan Penjualan</h2>
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="row g-3">
            <!-- Filter Harian -->
            <div class="col-md-3">
                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
            </div>
            <div class="col-md-3">
                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
            </div>

            <!-- Filter Bulanan -->
            <div class="col-md-2">
                <label for="bulan" class="form-label">Bulan</label>
                <select name="bulan" id="bulan" class="form-control">
                    <option value="">--Pilih--</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2">
                <label for="tahun" class="form-label">Tahun</label>
                <select name="tahun" id="tahun" class="form-control">
                    <option value="">--Pilih--</option>
                    @for ($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>

            <a href="{{ route(Route::is('pemilik.*') ? 'pemilik.laporan.cetak' : 'admin.laporan.cetak', request()->only('bulan', 'tahun')) }}" 
            target="_blank" 
            class="btn btn-success mb-3">
                Cetak Laporan
            </a>
            <a href="{{ route('admin.laporan.pdf', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="btn btn-sm btn-primary mb-3" target="_blank">
                Cetak PDF
            </a>

        </form>
    </div>
</div>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Total Transaksi</th>
                <th>Total Produk</th>
                <th>Total Pendapatan</th>
                <th>Produk Terjual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
            <tr>
                <td>{{ $trx->transaction_date }}</td>
                <td>1</td> <!-- Karena ini per transaksi -->
                <td>{{ $trx->details->sum('quantity') }}</td>
                <td>Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                <td>
                    <ul>
                        @foreach($trx->details as $detail)
                            <li>{{ $detail->product->product_name ?? 'Produk tidak ditemukan' }} ({{ $detail->quantity }})</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
            {{-- Pagination links --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
</div>
@endsection
