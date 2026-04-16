<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Tampilkan semua order (dashboard)
    public function index()
    {
        $orders = Order::latest()->get();
        return view('orders.index', compact('orders'));
    }

    // Tampilkan form order (landing page)
    public function create()
    {
        return view('orders.create');
    }

    // Simpan order baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'nomor_whatsapp' => 'required|string|max:20',
            'alamat'         => 'required|string',
            'detail_order'   => 'required|string',
            'total_harga'    => 'nullable|numeric',
        ]);

        Order::create($request->all());

        return redirect()->route('orders.index')
            ->with('success', 'Order berhasil ditambahkan!');
    }

    // Update status order
    public function updateStatus(Request $request, Order $order)
    {
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Status order diupdate!');
    }
}
