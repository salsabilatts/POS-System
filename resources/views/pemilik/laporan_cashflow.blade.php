@extends('layouts.pemilik')

@section('content')
<div class="container">
    <h3>Laporan Cashflow Harian</h3>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Kasir</th>
                <th>Modal Awal</th>
                <th>Cash</th>
                <th>QRIS</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($closings as $closing)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($closing->tanggal)->format('d M Y') }}</td>
                    <td>{{ $closing->kasir->name }}</td>
                    <td>Rp{{ number_format($closing->modal_awal ?? 0, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($closing->cash_total, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($closing->qris_total, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($closing->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $closings->links() }}
    </div>
</div>
@endsection
