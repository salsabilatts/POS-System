@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>Dashboard Admin</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Produk</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalProduk }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Transaksi</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalTransaksi }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Total Stok</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalStok }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Pendapatan Hari Ini</div>
                <div class="card-body">
                    <h5 class="card-title">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
