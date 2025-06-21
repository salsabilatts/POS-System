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
<form action="{{ route('kasir.checkout') }}" method="POST">
    @csrf
    <input type="hidden" id="subtotal" value="{{ $total }}">

    <div class="form-group">
        <label for="discount_type">Jenis Diskon</label>
        <select name="discount_type" id="discount_type" class="form-control">
            <option value="">-- Pilih --</option>
            <option value="percent">Persen (%)</option>
            <option value="nominal">Nominal (Rp)</option>
        </select>
    </div>

    <div class="form-group">
        <label for="discount_amount">Nilai Diskon</label>
        <input type="number" name="discount_amount" id="discount_amount" class="form-control" value="0">
    </div>

    {{-- Tampilkan preview diskon, pajak, total --}}
    <p>Diskon: Rp<span id="preview_diskon">0</span></p>
    <p>Pajak (11%): Rp<span id="preview_tax">0</span></p>
    <p><strong>Total: Rp<span id="total_display">{{ number_format($total, 0, ',', '.') }}</span></strong></p>
    <input type="hidden" name="total_amount" id="total_amount" value="{{ $total }}">

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
            <input type="number" name="paid_amount" class="form-control" id="paid_amount">
        </div>
    </div>
    <p class="mt-2 text-success fw-bold">Kembalian: Rp<span id="preview_change">0</span></p>

    <!-- QRIS Section -->
    <div id="qris_section" style="display: none; text-align:center;">
        <p>Scan QR berikut untuk membayar:</p>
        <img src="{{ asset('images/qr-codeTA.png') }}" alt="QRIS" style="max-width:200px;">
        <div id="qris_timer" class="mt-2 text-muted"></div>
    </div>

    <!-- Tombol Submit -->
    <button id="submit_button" type="submit" class="btn btn-success w-100">Bayar & Simpan</button>
</form>

        @if(session('id_transaksi'))
            <div class="mt-3">
                <a href="{{ route('kasir.struk', session('id_transaksi')) }}" class="btn btn-sm btn-primary" target="_blank">
                    Cetak Struk
                </a>
            </div>
        @endif
        @if(session('change'))
                <div class="alert alert-info">
                    Kembalian: Rp{{ number_format(session('change'), 0, ',', '.') }}
                </div>
         @endif
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    function calculateTotal() {
        const subtotal = parseFloat($('#subtotal').val()) || 0;
        const discountType = $('#discount_type').val();
        const discountInput = parseFloat($('#discount_amount').val()) || 0;

        let discount = 0;
        if (discountType === 'percent') {
            discount = subtotal * (discountInput / 100);
        } else if (discountType === 'nominal') {
            discount = discountInput;
        }

        const tax = (subtotal - discount) * 0.11;
        const total = subtotal - discount + tax;

        $('#preview_diskon').text(discount.toLocaleString('id-ID'));
        $('#preview_tax').text(tax.toLocaleString('id-ID'));
        $('#total_display').text(total.toLocaleString('id-ID'));
        $('#total_amount').val(Math.round(total));
    }

    $('#discount_type, #discount_amount').on('input change', function () {
        calculateTotal();
    });

    calculateTotal(); // hitung awal

    function updateChange() {
    const subtotal = parseFloat($('#subtotal').val()) || 0;
    const discountType = $('#discount_type').val();
    const discountInput = parseFloat($('#discount_amount').val()) || 0;

    let discount = 0;
    if (discountType === 'percent') {
        discount = subtotal * (discountInput / 100);
    } else if (discountType === 'nominal') {
        discount = discountInput;
    }

    const tax = (subtotal - discount) * 0.11;
    const total = subtotal - discount + tax;

    const paid = parseFloat($('#paid_amount').val()) || 0;
    const change = paid - total;

    $('#preview_change').text(change > 0 ? change.toLocaleString('id-ID') : 0);
    }

    $('#discount_type, #discount_amount, #paid_amount').on('input change', function () {
        calculateTotal();
        updateChange();
    });
    updateChange();


});
</script>

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


