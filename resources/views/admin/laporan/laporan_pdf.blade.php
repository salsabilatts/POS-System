<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h3>Laporan Penjualan</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
                @foreach($t->details as $detail)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($t->transaction_date)->format('d-m-Y') }}</td>
                        <td>{{ $detail->product->product_name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="4">Tidak ada transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
