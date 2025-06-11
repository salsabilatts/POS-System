@extends('layouts.kasir')

@section('content')
    <h3>Riwayat Transaksi</h3>

    {{-- Filter tanggal --}}
    <form method="GET" action="{{ route('kasir.riwayat') }}" class="row mb-3">
        <div class="col-md-3">
            <label>Mulai Tanggal:</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label>Sampai Tanggal:</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    @if($transactions->isEmpty())
        <div class="alert alert-info">Belum ada transaksi.</div>
    @else
        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Bayar</th>
                    <th>Kembalian</th>
                    <th>Produk</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $trx)
                    <tr>
                        <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y, H:i') }}</td>
                        <td>Rp{{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($trx->payment_method) }}</td>
                        <td>Rp{{ number_format($trx->paid_amount, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($trx->change_amount, 0, ',', '.') }}</td>
                        <td>
                            <ul class="mb-0">
                                @foreach($trx->details as $detail)
                                    <li>{{ $detail->product->product_name }} x {{ $detail->quantity }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination links --}}
      <div class="d-flex justify-content-center">
            {{ $transactions->links() }}
        </div>
    @endif
@endsection
