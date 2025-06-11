<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class DashboardPemilikController extends Controller
{
    public function index()
    {
        // Ambil total pendapatan per bulan
        $monthlyRevenue = Transaction::selectRaw('MONTH(transaction_date) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('pemilik.dashboard', compact('monthlyRevenue'));
    }
}
