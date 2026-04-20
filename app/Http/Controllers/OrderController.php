<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DeletionLog;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->get();
        $deletionLogs = DeletionLog::where('user_id', auth()->id())->latest()->get();
        return view('orders.index', compact('orders', 'deletionLogs'));
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'nomor_whatsapp' => 'required|string|max:20',
            'alamat'         => 'required|string',
            'detail_order'   => 'required|string',
            'total_harga'    => 'nullable|numeric',
        ]);

        Order::create([
            'user_id'        => auth()->id(),
            'nama_pelanggan' => $request->nama_pelanggan,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'alamat'         => $request->alamat,
            'detail_order'   => $request->detail_order,
            'total_harga'    => $request->total_harga,
            'jam_input'      => now()->format('H:i:s'),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Order berhasil ditambahkan!');
    }

    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'nomor_whatsapp' => 'required|string|max:20',
            'alamat'         => 'required|string',
            'detail_order'   => 'required|string',
            'total_harga'    => 'nullable|numeric',
            'status'         => 'required|in:pending,proses,selesai,batal',
        ]);

        $order->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'alamat'         => $request->alamat,
            'detail_order'   => $request->detail_order,
            'total_harga'    => $request->total_harga,
            'status'         => $request->status,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Order berhasil diupdate!');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $order->update(['status' => $request->status]);
        return redirect()->route('dashboard')
            ->with('success', 'Status order diupdate!');
    }

    public function destroy(Order $order)
    {
        DeletionLog::create([
            'user_id'        => auth()->id(),
            'nama_pelanggan' => $order->nama_pelanggan,
            'nomor_whatsapp' => $order->nomor_whatsapp,
            'detail_order'   => $order->detail_order,
            'status'         => $order->status,
            'dihapus_oleh'   => auth()->user()->name,
        ]);

        $order->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Order berhasil dihapus!');
    }

    public function exportExcel()
    {
        return Excel::download(new OrdersExport, 'laporan-order-' . now()->format('d-m-Y') . '.xlsx');
    }

    public function exportPdf()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->get();
        $pdf = Pdf::loadView('exports.orders-pdf', compact('orders'));
        return $pdf->download('laporan-order-' . now()->format('d-m-Y') . '.pdf');
    }
}
