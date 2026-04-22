<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use App\Models\OrderItem;
use App\Models\DeletionLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items')->where('user_id', auth()->id())->latest()->get();
        $deletionLogs = DeletionLog::where('user_id', auth()->id())->latest()->get();
        return view('orders.index', compact('orders', 'deletionLogs'));
    }

    public function create()
    {
        $menus = Menu::where('user_id', auth()->id())
            ->orderBy('kategori')
            ->orderBy('nama_menu')
            ->get()
            ->groupBy('kategori');
        return view('orders.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'tipe_order'     => 'required|in:online,onsite',
            'nomor_whatsapp' => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
            'menus'          => 'required|array|min:1',
            'menus.*.id_menu' => 'required|string',
            'menus.*.jumlah' => 'required|integer|min:1',
        ]);

        // Generate ID Pesanan
        $lastOrder = Order::where('user_id', auth()->id())
            ->orderByRaw('CAST(SUBSTRING(id_pesanan, 3) AS UNSIGNED) DESC')
            ->first();
        $lastNumber = $lastOrder ? intval(substr($lastOrder->id_pesanan, 2)) : 0;
        $newId = 'OD' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        // Hitung total
        $totalPesanan = 0;
        $totalHarga = 0;
        foreach ($request->menus as $item) {
            $menu = Menu::find($item['id_menu']);
            $totalPesanan += $item['jumlah'];
            $totalHarga += $menu->harga_menu * $item['jumlah'];
        }

        // Buat order
        $order = Order::create([
            'id_pesanan'     => $newId,
            'user_id'        => auth()->id(),
            'nama_pelanggan' => $request->nama_pelanggan,
            'nomor_whatsapp' => $request->tipe_order === 'online' ? $request->nomor_whatsapp : null,
            'alamat'         => $request->tipe_order === 'online' ? $request->alamat : null,
            'tipe_order'     => $request->tipe_order,
            'total_pesanan'  => $totalPesanan,
            'total_harga'    => $totalHarga,
            'jam_input'      => now()->format('H:i:s'),
        ]);

        // Simpan items
        foreach ($request->menus as $item) {
            $menu = Menu::find($item['id_menu']);
            OrderItem::create([
                'id_pesanan' => $newId,
                'id_menu'    => $menu->id_menu,
                'nama_menu'  => $menu->nama_menu,
                'harga_menu' => $menu->harga_menu,
                'jumlah'     => $item['jumlah'],
                'subtotal'   => $menu->harga_menu * $item['jumlah'],
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Order berhasil ditambahkan!');
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $menus = Menu::where('user_id', auth()->id())->get();
        $order->load('items');
        return view('orders.edit', compact('order', 'menus'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'tipe_order'     => 'required|in:online,onsite',
            'nomor_whatsapp' => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
            'status'         => 'required|in:pending,proses,selesai,batal',
            'menus'          => 'required|array|min:1',
            'menus.*.id_menu' => 'required|string',
            'menus.*.jumlah' => 'required|integer|min:1',
        ]);

        // Hitung total baru
        $totalPesanan = 0;
        $totalHarga = 0;
        foreach ($request->menus as $item) {
            $menu = Menu::find($item['id_menu']);
            $totalPesanan += $item['jumlah'];
            $totalHarga += $menu->harga_menu * $item['jumlah'];
        }

        $order->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'nomor_whatsapp' => $request->tipe_order === 'online' ? $request->nomor_whatsapp : null,
            'alamat'         => $request->tipe_order === 'online' ? $request->alamat : null,
            'tipe_order'     => $request->tipe_order,
            'total_pesanan'  => $totalPesanan,
            'total_harga'    => $totalHarga,
            'status'         => $request->status,
        ]);

        // Update items
        $order->items()->delete();
        foreach ($request->menus as $item) {
            $menu = Menu::find($item['id_menu']);
            OrderItem::create([
                'id_pesanan' => $order->id_pesanan,
                'id_menu'    => $menu->id_menu,
                'nama_menu'  => $menu->nama_menu,
                'harga_menu' => $menu->harga_menu,
                'jumlah'     => $item['jumlah'],
                'subtotal'   => $menu->harga_menu * $item['jumlah'],
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Order berhasil diupdate!');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $order->update(['status' => $request->status]);
        return redirect()->route('dashboard')->with('success', 'Status diupdate!');
    }

    public function destroy(Order $order)
    {
        DeletionLog::create([
            'user_id'        => auth()->id(),
            'id_pesanan'     => $order->id_pesanan,
            'nama_pelanggan' => $order->nama_pelanggan,
            'nama_menu'      => $order->items->pluck('nama_menu')->join(', '),
            'total_pesanan'  => $order->total_pesanan,
            'total_harga'    => $order->total_harga,
            'status'         => $order->status,
            'dihapus_oleh'   => auth()->user()->name,
        ]);

        $order->delete();
        return redirect()->route('dashboard')->with('success', 'Order berhasil dihapus!');
    }

    public function exportExcel()
    {
        return Excel::download(new OrdersExport, 'laporan-order-' . now()->format('d-m-Y') . '.xlsx');
    }

    public function exportPdf()
    {
        $orders = Order::with('items')->where('user_id', auth()->id())->latest()->get();
        $pdf = Pdf::loadView('exports.orders-pdf', compact('orders'));
        return $pdf->download('laporan-order-' . now()->format('d-m-Y') . '.pdf');
    }
}
