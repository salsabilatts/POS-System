<?php

namespace App\Http\Controllers;

use App\Models\DailyClosing;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class DailyClosingController extends Controller
{
    // Laporan harian kasir
public function indexKasir()
{
    $idKasir = Auth::id();

    // Waktu hari ini
    $todayStart = Carbon::now('Asia/Jakarta')->startOfDay();
    $todayEnd = Carbon::now('Asia/Jakarta')->endOfDay();
    $today = Carbon::now('Asia/Jakarta')->toDateString();

    // Ambil data closing jika sudah ada
    $closing = DailyClosing::where('id_kasir', $idKasir)
        ->where('tanggal', $today)
        ->first();

    // Ambil transaksi hari ini
    $transactions = Transaction::where('id_user', $idKasir)
        ->whereBetween('transaction_date', [$todayStart, $todayEnd])
        ->get();

    // Hitung total pemasukan
    $cashTotal = $transactions->where('payment_method', 'cash')->sum('total_amount');
    $qrisTotal = $transactions->where('payment_method', 'qris')->sum('total_amount');
    $total = $cashTotal + $qrisTotal;

    return view('kasir.laporan_harian', [
    'closing' => $closing,
    'cashTotal' => $cashTotal,
    'qrisTotal' => $qrisTotal,
    'total' => $total,
    'today' => $todayStart->toDateString() // ini string tanggal 'YYYY-MM-DD'
]);
}   

    // Aksi tutup laporan harian kasir
    public function closeToday(Request $request)
{
    $idKasir = Auth::id();
    $today = now('Asia/Jakarta')->toDateString(); 
    $startOfDay = now('Asia/Jakarta')->startOfDay();
    $endOfDay = now('Asia/Jakarta')->endOfDay();
    $modalAwal = $request->input('modal_awal');

    // Cek apakah sudah tutup laporan
    if (DailyClosing::where('id_kasir', $idKasir)->where('tanggal', $today)->exists()) {
        return redirect()->back()->with('info', 'Laporan sudah ditutup.');
    }

    // Ambil transaksi hari ini
    $transactions = Transaction::where('id_user', $idKasir)
        ->whereBetween('transaction_date', [$startOfDay, $endOfDay])
        ->get();

    if ($transactions->isEmpty()) {
        return back()->with('warning', 'Belum ada transaksi hari ini.');
    }

    $cashTotal = $transactions->where('payment_method', 'cash')->sum('total_amount');
    $qrisTotal = $transactions->where('payment_method', 'qris')->sum('total_amount');
    $total = $cashTotal + $qrisTotal;

    try {
        DailyClosing::create([
            'id_kasir' => $idKasir,
            'tanggal' => $today,
            'modal_awal' => $modalAwal,
            'cash_total' => $cashTotal,
            'qris_total' => $qrisTotal,
            'total' => $total,
            'auto_closed' => false
        ]);

        return redirect()->back()->with('success', 'Laporan harian berhasil ditutup.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menyimpan laporan: ' . $e->getMessage());
    }
}

    // Pemilik lihat semua laporan
    public function indexPemilik(Request $request)
    {
        $query = DailyClosing::with('kasir')->orderByDesc('tanggal');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $closings = $query->paginate(10);
        return view('pemilik.laporan_cashflow', compact('closings'));
    }
}
