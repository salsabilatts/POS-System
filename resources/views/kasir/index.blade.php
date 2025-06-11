@extends('layouts.kasir')
@section('title', 'Halaman Kasir')

@section('content')
<div class="row">
    <!-- Kolom Produk -->
    <div class="col-md-7">
        <h4>Daftar Produk</h4>
        <div class="row row-cols-2 row-cols-md-3 g-3">
            @foreach($products as $product)
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->product_name }}</h5>
                        <p class="card-text">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        <form method="POST" action="{{ route('kasir.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                            <input type="number" name="quantity" value="1" min="1" class="form-control mb-2" required>
                            <button type="submit" class="btn btn-sm btn-primary w-100">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Kolom Keranjang -->
    <div class="col-md-5">
        <h4>Daftar Belanja</h4>
        <table class="table table-bordered">
            <thead class="table-secondary">
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @forelse($cart as $id => $item)
                @php $total += $item['subtotal']; @endphp
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('kasir.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $id }}">
                            <button class="btn btn-sm btn-danger">×</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Keranjang kosong</td>
                </tr>
                @endforelse
                @if(session('id_transaksi'))
                <div class="mt-3">
                    <a href="{{ route('kasir.struk', session('id_transaksi')) }}" class="btn btn-sm btn-primary" target="_blank">
                        Cetak Struk
                    </a>
                </div>
            @endif
            </tbody>
        </table>

        <!-- Total & Form Pembayaran -->
        @if($total > 0)
        <div class="mb-2">
            <strong>Total: Rp{{ number_format($total, 0, ',', '.') }}</strong>
        </div>
        <form action="{{ route('kasir.checkout') }}" method="POST">
            @csrf
            <!-- Pilih Metode Pembayaran -->
        <div class="mb-2">
            <label for="payment_method" class="form-label">Metode Pembayaran:</label>
            <select id="payment_method" name="payment_method" class="form-select" required onchange="togglePaymentMethod()">
                <option value="">-- Pilih --</option>
                <option value="cash">Cash</option>
                <option value="qris">QRIS</option>
            </select>
        </div>

        <!-- Form Cash -->
        <div id="cash_section" style="display: none;">
            <div class="mb-2">
                <label for="paid_amount" class="form-label">Bayar (Cash):</label>
                <input type="number" name="paid_amount" class="form-control">
            </div>
        </div>

        <!-- QRIS Section -->
        <div id="qris_section" style="display: none; text-align:center;">
            <p>Scan QR berikut untuk membayar:</p>
            <img src="{{ asset('images/qr-codeTA.png') }}" alt="QRIS" style="max-width:200px;">
            <div id="qris_timer" class="mt-2 text-muted"></div>
        </div>

        <!-- Tombol Submit -->
        <button id="submit_button" type="submit" class="btn btn-success w-100">Bayar & Simpan</button>
        </form>
        </form>
        @if(session('id_transaksi'))
            <div class="mt-3">
                <a href="{{ route('kasir.struk', session('id_transaksi')) }}" class="btn btn-sm btn-primary" target="_blank">
                    Cetak Struk
                </a>
            </div>
        @endif
    @endif
        @if(session('change'))
                <div class="alert alert-info">
                    Kembalian: Rp{{ number_format(session('change'), 0, ',', '.') }}
                </div>
         @endif
    </div>
</div>
@endsection

<script>
function togglePaymentMethod() {
    const method = document.getElementById('payment_method').value;
    const cashSection = document.getElementById('cash_section');
    const qrisSection = document.getElementById('qris_section');
    const submitButton = document.getElementById('submit_button');
    const timerDisplay = document.getElementById('qris_timer');

    cashSection.style.display = 'none';
    qrisSection.style.display = 'none';
    submitButton.disabled = false;
    timerDisplay.innerText = '';

    if (method === 'cash') {
        cashSection.style.display = 'block';
        submitButton.disabled = false;

    } else if (method === 'qris') {
        qrisSection.style.display = 'block';

        // Mulai timer 1 menit
        let seconds = 60;
        const countdown = setInterval(() => {
            timerDisplay.innerText = `Sisa waktu: ${seconds} detik`;
            seconds--;

            if (seconds < 0) {
                clearInterval(countdown);
                submitButton.disabled = true;
                timerDisplay.innerText = "Waktu habis. Silakan scan ulang QR.";
            }
        }, 1000);


    }
}
</script>


