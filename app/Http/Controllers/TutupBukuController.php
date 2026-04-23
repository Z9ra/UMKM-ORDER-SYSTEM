<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\LaporanHarian;
use App\Models\PengaturanTutupBuku;
use Illuminate\Http\Request;

class TutupBukuController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanTutupBuku::where('user_id', auth()->id())->first();
        $laporanHarian = LaporanHarian::where('user_id', auth()->id())
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_tutup', 'desc')
            ->paginate(10);
        return view('tutup_buku.index', compact('pengaturan', 'laporanHarian'));
    }

    public function simpanPengaturan(Request $request)
    {
        $request->validate([
            'jam_tutup'  => 'required',
            'auto_tutup' => 'boolean',
        ]);

        PengaturanTutupBuku::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'jam_tutup'  => $request->jam_tutup,
                'auto_tutup' => $request->has('auto_tutup'),
            ]
        );

        return back()->with('success', 'Pengaturan jam tutup buku disimpan!');
    }

    public function tutupBuku()
    {
        $orders = Order::with('items')
            ->where('user_id', auth()->id())
            ->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'Tidak ada order untuk ditutup!');
        }

        // Ambil deletion logs sebelum dihapus
        $deletionLogs = \App\Models\DeletionLog::where('user_id', auth()->id())->get();

        // Simpan ke laporan harian
        $detailOrders = $orders->map(function ($order) {
            return [
                'id_pesanan'     => $order->id_pesanan,
                'nama_pelanggan' => $order->nama_pelanggan,
                'tipe_order'     => $order->tipe_order,
                'items'          => $order->items->map(fn($i) => [
                    'nama_menu'  => $i->nama_menu,
                    'jumlah'     => $i->jumlah,
                    'subtotal'   => $i->subtotal,
                ])->toArray(),
                'total_harga'    => $order->total_harga,
                'status'         => $order->status,
                'jam_input'      => $order->jam_input,
            ];
        })->toArray();

        // Simpan deletion logs ke dalam laporan
        $detailDeletionLogs = $deletionLogs->map(function ($log) {
            return [
                'id_pesanan'     => $log->id_pesanan,
                'nama_pelanggan' => $log->nama_pelanggan,
                'nama_menu'      => $log->nama_menu,
                'total_pesanan'  => $log->total_pesanan,
                'total_harga'    => $log->total_harga,
                'status'         => $log->status,
                'dihapus_oleh'   => $log->dihapus_oleh,
                'waktu'          => $log->created_at->format('d/m/Y H:i'),
            ];
        })->toArray();

        LaporanHarian::create([
            'user_id'          => auth()->id(),
            'tanggal'          => now()->toDateString(),
            'jam_tutup'        => now()->format('H:i:s'),
            'total_order'      => $orders->count(),
            'total_item'       => $orders->sum('total_pesanan'),
            'total_pendapatan' => $orders->sum('total_harga'),
            'order_pending'    => $orders->where('status', 'pending')->count(),
            'order_proses'     => $orders->where('status', 'proses')->count(),
            'order_selesai'    => $orders->where('status', 'selesai')->count(),
            'order_batal'      => $orders->where('status', 'batal')->count(),
            'detail_orders'    => $detailOrders,
            'detail_deletion_logs' => $detailDeletionLogs,
        ]);

        // Hapus log penghapusan & orders
        \App\Models\DeletionLog::where('user_id', auth()->id())->delete();
        Order::where('user_id', auth()->id())->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Tutup buku berhasil! Dashboard sudah direset.');
    }

    public function show(LaporanHarian $laporanHarian)
    {
        return view('tutup_buku.show', compact('laporanHarian'));
    }
}
