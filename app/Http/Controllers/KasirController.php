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
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;




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

    // Jika produk sudah ada di keranjang, tambahkan jumlahnya
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

    $total = array_sum(array_column($cart, 'subtotal'));
    $paid = $request->paid_amount;
    $change = $paid - $total;

    $method = $request->payment_method;

    if ($method === 'cash') {
        $paid = $request->paid_amount;
        $change = $paid - $total;

        if ($change < 0) {
            return redirect()->back()->with('error', 'Uang tidak cukup');
        }
    } else {
        // QRIS: kita asumsikan user bayar sesuai total
        $paid = $total;
        $change = 0;
    }

    if ($change < 0) {
        return redirect()->back()->with('error', 'Uang tidak cukup');
    }

    DB::beginTransaction();
    $paymentMethod = $request->payment_method; // misal 'cash' atau 'qris'

    try {
        $transaction = Transaction::create([
            'transaction_date' => now(),
            'total_amount' => $total,
            'paid_amount' => $paid,
            'change_amount' => $change,
            'payment_method' => $paymentMethod,
            'id_user' => Auth::id() 
        ]);

        foreach ($cart as $productId => $item) {
            DetailTransaction::create([
                'id_transaction' => $transaction->id_transaction,
                'id_product' => $productId,
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
            ]);

            // Update stock
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
        // Ambil transaksi lengkap beserta detail produk dan user kasir
        $transaction = Transaction::with('details.product', 'user')->findOrFail($id);

        // Generate PDF dari view
        $pdf = Pdf::loadView('kasir.struk', compact('transaction'))
                ->setPaper('A6'); // Ukuran struk mini

        // Tampilkan di browser
        return $pdf->stream('struk-transaksi-' . $transaction->id . '.pdf');
    }
     public function riwayatTransaksi(Request $request)
        {
            // Mulai query builder
            $query = Transaction::with('details.product')
                ->where('id_user', auth()->id())
                ->orderByDesc('transaction_date');

            // Filter berdasarkan tanggal jika ada input
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
            }

            // Ambil data dengan pagination
            $transactions = $query->paginate(10); // <= PENTING

            return view('kasir.riwayat', compact('transactions'));
        }

}
