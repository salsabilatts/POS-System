<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProduk = Product::count();
        $totalTransaksi = Transaction::count();
        $totalStok = Product::sum('stock');
        $pendapatanHariIni = Transaction::whereDate('transaction_date', now())->sum('total_amount');

        return view('admin.dashboard', compact('totalProduk', 'totalTransaksi', 'totalStok', 'pendapatanHariIni'));
    }

}
