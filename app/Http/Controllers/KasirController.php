<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\DetailTransaction;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DailyClosing;

class KasirController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $cart = Session::get('cart', []);
        return view('kasir.index', compact('products', 'cart'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$product->id_product])) {
            $cart[$product->id_product]['quantity'] += $request->quantity;
            $cart[$product->id_product]['subtotal'] = $cart[$product->id_product]['quantity'] * $product->price;
        } else {
            $cart[$product->id_product] = [
                'name' => $product->product_name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'subtotal' => $product->price * $request->quantity,
            ];
        }

        Session::put('cart', $cart);

        return redirect()->route('kasir.index')->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function removeFromCart(Request $request)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            Session::put('cart', $cart);
        }

        return redirect()->route('kasir.index')->with('success', 'Produk dihapus dari keranjang');
    }

    public function checkout(Request $request)
    {
        $cart = Session::get('cart', []);
        if (count($cart) === 0) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }

        $subtotal = array_sum(array_column($cart, 'subtotal'));
        $discountType = $request->discount_type;
        $discountValue = (int) $request->discount_amount;
        $discount = $discountType === 'percent' ? ($subtotal * $discountValue) / 100 : $discountValue;
        $tax = ($subtotal - $discount) * 0.11;
        $total = $subtotal - $discount + $tax;
        $method = $request->payment_method;

        $paid = $method === 'cash' ? $request->paid_amount : $total;
        $change = $method === 'cash' ? $paid - $total : 0;

        if ($change < 0) {
            return redirect()->back()->with('error', 'Uang tidak cukup');
        }

        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'transaction_date' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'total_amount' => $total,
                'paid_amount' => $paid,
                'change_amount' => $change,
                'payment_method' => $method,
                'id_user' => Auth::id(),
                'discount_type' => $discountType,
                'discount_amount' => $discount,
                'tax' => $tax,
            ]);

            foreach ($cart as $productId => $item) {
                DetailTransaction::create([
                    'id_transaction' => $transaction->id_transaction,
                    'id_product' => $productId,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                $product = Product::find($productId);
                if ($product) {
                    $product->stock -= $item['quantity'];
                    $product->save();
                }
            }

            DB::commit();
            Session::forget('cart');
            session()->flash('id_transaksi', $transaction->id_transaction);
            return redirect()->route('kasir.index')->with('success', 'Transaksi berhasil')->with('change', $change);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function cetakStruk($id)
    {
        $transaction = Transaction::with('details.product', 'user')->findOrFail($id);
        $pdf = Pdf::loadView('kasir.struk', compact('transaction'))->setPaper('A6');
        return $pdf->stream('struk-transaksi-' . $transaction->id . '.pdf');
    }

    public function riwayatTransaksi(Request $request)
    {
        $query = Transaction::with('details.product')
            ->where('id_user', auth()->id())
            ->orderByDesc('transaction_date');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->paginate(10);
        return view('kasir.riwayat', compact('transactions'));
    }

    public function laporanHarian()
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $idKasir = auth()->id();

        $transactions = Transaction::where('id_user', $idKasir)
            ->whereDate('transaction_date', $today)
            ->get();

        $totalCash = $transactions->where('payment_method', 'cash')->sum('total_amount');
        $totalQris = $transactions->where('payment_method', 'qris')->sum('total_amount');
        $total = $totalCash + $totalQris;

        $alreadyClosed = DailyClosing::where('id_kasir', $idKasir)
            ->where('tanggal', $today)
            ->exists();

        return view('kasir.laporan_harian', compact('transactions', 'totalCash', 'totalQris', 'total', 'alreadyClosed'));
    }

    public function tutupLaporan()
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $idKasir = auth()->id();

        $transactions = Transaction::where('id_user', $idKasir)
            ->whereDate('transaction_date', $today)
            ->get();

        if ($transactions->isEmpty()) {
            return back()->with('warning', 'Belum ada transaksi hari ini.');
        }

        $totalCash = $transactions->where('payment_method', 'cash')->sum('total_amount');
        $totalQris = $transactions->where('payment_method', 'qris')->sum('total_amount');

        $check = DailyClosing::where('id_kasir', $idKasir)->where('tanggal', $today)->first();
        if ($check) {
            return back()->with('info', 'Laporan sudah ditutup sebelumnya.');
        }

        try {
            DailyClosing::create([
                'id_kasir' => $idKasir,
                'tanggal' => $today,
                'cash_total' => $totalCash,
                'qris_total' => $totalQris,
                'total' => $totalCash + $totalQris,
                'auto_closed' => false,
            ]);
            return redirect()->route('kasir.laporan.harian')->with('success', 'Laporan hari ini berhasil ditutup.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan laporan: ' . $e->getMessage());
        }
    }
}
