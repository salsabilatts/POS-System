<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Transaction;
use PDF;
class LaporanController extends Controller
{
    public function dashboardAdmin()
    {
        $totalProduk = Product::count();
        $totalTransaksi = Transaction::count();
        $totalStok = Product::sum('stock');
        $pendapatanHariIni = Transaction::whereDate('transaction_date', now())->sum('total_amount');

        return view('admin.dashboard', compact('totalProduk', 'totalTransaksi', 'totalStok', 'pendapatanHariIni'));
    }

    public function index(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('transaction_date', [
                $request->tanggal_awal,
                $request->tanggal_akhir,
            ]);
        }

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('transaction_date', $request->bulan)
                  ->whereYear('transaction_date', $request->tahun);
        }

        $transactions = $query->latest()->paginate(10);
        return view('admin.laporan.index', compact('transactions'));
    }
    public function cetakLaporan(Request $request)
{
    $query = Transaction::with('user', 'details.product');

    if ($request->filled('bulan') && $request->filled('tahun')) {
        $query->whereMonth('transaction_date', $request->bulan)
              ->whereYear('transaction_date', $request->tahun);
    }

    $transactions = $query->get(); // TANPA pagination

    return view('admin.laporan.laporan-cetak', compact('transactions'));
}

 // Tambahkan di atas controller

public function cetakPDF(Request $request)
{
    $query = Transaction::with('details.product');

    if ($request->filled('bulan') && $request->filled('tahun')) {
        $query->whereMonth('transaction_date', $request->bulan)
              ->whereYear('transaction_date', $request->tahun);
    }

    $transactions = $query->get();

    $pdf = PDF::loadView('admin.laporan.laporan_pdf', compact('transactions'));

    return $pdf->download('laporan-penjualan.pdf');
}

}

