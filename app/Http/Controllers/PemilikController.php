<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use PDF;


class PemilikController extends Controller
{
    // Menampilkan halaman dashboard pemilik
    public function dashboard(Request $request)
{
    
    $years = Transaction::selectRaw('YEAR(transaction_date) as year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year')
        ->toArray();
        
    $selectedYear = $request->input('year'); // Tahun hanya jika user pilih

    $monthlyData = [];
    

    if ($selectedYear  && in_array($selectedYear, $years)) {
        $monthlyDataRaw = Transaction::selectRaw('MONTH(transaction_date) as month, SUM(total_amount) as revenue')
            ->whereYear('transaction_date', $selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month');

        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $monthlyDataRaw[$i] ?? 0;
        }
    }

    return view('pemilik.dashboard', compact('years', 'selectedYear', 'monthlyData'));
}
    
    // Menampilkan laporan penjualan pemilik
    public function laporan(Request $request)
    {
        $query = Transaction::with('user', 'details.product');

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('transaction_date', $request->bulan)
                  ->whereYear('transaction_date', $request->tahun);
        }

        $transactions = $query->latest()->paginate(10);

        return view('pemilik.laporan', compact('transactions'));
    }

        public function cetakLaporan(Request $request)
    {
        $query = Transaction::with('user', 'details.product');

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('transaction_date', $request->bulan)
                ->whereYear('transaction_date', $request->tahun);
        }

        $transactions = $query->get(); // TANPA pagination

        return view('pemilik.laporan-cetak', compact('transactions'));
    }

    public function cetakPDF(Request $request)
    {
        $query = Transaction::with('details.product');

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('transaction_date', $request->bulan)
                ->whereYear('transaction_date', $request->tahun);
        }

        $transactions = $query->get();

        $pdf = PDF::loadView('pemilik.laporan_pdf', compact('transactions'));

        return $pdf->download('laporan-penjualan.pdf');
    }
}
