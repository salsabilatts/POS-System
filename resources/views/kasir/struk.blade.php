<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-size: 12px; }
        table { width: 100%; }
        th, td { text-align: left; padding: 4px; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <h4 align="center">Struk Pembayaran</h4>
    <p>Toko Berkah</p>
    <p>Tanggal: {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y H:i') }}</p>
    <p>Kasir: {{ $transaction->user->name ?? '-' }}</p>

    <table border="0" cellspacing="0">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th style="text-align:right">Harga</th>
                <!--<th>Subtotal</th> -->
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->details as $item)
                <tr>
                    <td>{{ $item->product->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td style="text-align:right">Rp{{ number_format($item->product->price) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

      <hr>
    <table>
            <tr>
                <td>Diskon</td>
                <td style="text-align:right">
                    @if($transaction->discount_type)
                       - Rp{{ number_format($transaction->discount_amount, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>Pajak (11%)</td>
                <td style="text-align:right">Rp{{ number_format($transaction->tax, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td>Total</td>
                <td style="text-align:right">Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Dibayar</td>
                <td style="text-align:right">Rp{{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembalian</td>
                <td style="text-align:right">Rp{{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    <!-- <p class="total">Total: Rp{{ number_format($transaction->total_amount) }}</p>
    <p class="total">Bayar: Rp{{ number_format($transaction->paid_amount) }}</p>
    <p class="total">Kembalian: Rp{{ number_format($transaction->change_amount) }}</p> -->

    <p align="center">~ Terima Kasih Telah Berbelanja di Toko Berkah~</p>
</body>
</html>
