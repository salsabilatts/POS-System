@extends('layouts.kasir')

@section('content')
<div class="container">
    <h3>Laporan Nominal Harian</h3>
    <p>Tanggal: <strong>{{ $today }}</strong></p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    @if(!$closing)
        <form method="POST" action="{{ route('kasir.tutupLaporan') }}">
            @csrf
            <div class="mb-3">
                <label for="modal_awal" class="form-label">Modal Awal</label>
                <input type="number" name="modal_awal" id="modal_awal" class="form-control" required oninput="formatModalAwal()" />
                <div id="formattedModal" class="mt-1 text-muted">Modal Awal: Rp0</div>
            </div>
            
            <ul class="list-group mb-3">
                <li class="list-group-item">Pemasukan Cash: Rp{{ number_format($cashTotal, 0, ',', '.') }}</li>
                <li class="list-group-item">Pemasukan QRIS: Rp{{ number_format($qrisTotal, 0, ',', '.') }}</li>
                <li class="list-group-item"><strong>Total Hari Ini: Rp{{ number_format($cashTotal + $qrisTotal, 0, ',', '.') }}</strong></li>
            </ul>
            
            <button type="submit" class="btn btn-danger">Tutup Laporan Hari Ini</button>
        </form>
    @else
        <div class="alert alert-success">Laporan hari ini sudah ditutup.</div>
    @endif
</div>
@endsection

<script>
function formatModalAwal() {
    let input = document.getElementById('modal_awal').value;
    let formatted = parseInt(input || 0).toLocaleString('id-ID');
    document.getElementById('formattedModal').innerText = 'Modal Awal: Rp' + formatted;
}
</script>