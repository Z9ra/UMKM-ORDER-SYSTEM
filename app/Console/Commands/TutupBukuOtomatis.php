<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\LaporanHarian;
use App\Models\PengaturanTutupBuku;
use Illuminate\Console\Command;

class TutupBukuOtomatis extends Command
{
    protected $signature = 'tutupbuku:otomatis';
    protected $description = 'Tutup buku otomatis berdasarkan jam yang diset';

    public function handle()
    {
        $pengaturanList = PengaturanTutupBuku::where('auto_tutup', true)->get();

        foreach ($pengaturanList as $pengaturan) {
            $jamSekarang = now()->format('H:i');
            $jamTutup = substr($pengaturan->jam_tutup, 0, 5);

            if ($jamSekarang === $jamTutup) {
                $orders = Order::with('items')
                    ->where('user_id', $pengaturan->user_id)
                    ->get();

                if ($orders->isEmpty()) continue;

                $detailOrders = $orders->map(function ($order) {
                    return [
                        'id_pesanan'     => $order->id_pesanan,
                        'nama_pelanggan' => $order->nama_pelanggan,
                        'tipe_order'     => $order->tipe_order,
                        'items'          => $order->items->map(fn($i) => [
                            'nama_menu' => $i->nama_menu,
                            'jumlah'    => $i->jumlah,
                            'subtotal'  => $i->subtotal,
                        ])->toArray(),
                        'total_harga' => $order->total_harga,
                        'status'      => $order->status,
                        'jam_input'   => $order->jam_input,
                    ];
                })->toArray();

                LaporanHarian::create([
                    'user_id'          => $pengaturan->user_id,
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
                ]);

                Order::where('user_id', $pengaturan->user_id)->delete();

                $this->info("Tutup buku berhasil untuk user: {$pengaturan->user_id}");
            }
        }
    }
}
