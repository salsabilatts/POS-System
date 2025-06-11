<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h4 class="mb-3">Laporan Penjualan</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $t)
                @foreach ($t->details as $detail)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($t->transaction_date)->format('d-m-Y') }}</td>
                        <td>{{ $detail->product->product_name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @empty
                <tr><td colspan="4">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
    <button class="btn btn-primary no-print" onclick="window.print()">Print</button>
</div>
</body>
</html>
