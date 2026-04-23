@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Detail Laporan Harian</h2>
                <a href="{{ route('tutupbuku.index') }}" class="btn btn-ghost btn-sm">Kembali</a>
            </div>

            {{-- Summary --}}
            <div class="stats stats-horizontal shadow w-full mb-4">
                <div class="stat">
                    <div class="stat-title">Total Order</div>
                    <div class="stat-value text-primary text-2xl">{{ $laporanHarian->total_order }}</div>
                </div>
                <div class="stat">
                    <div class="stat-title">Selesai</div>
                    <div class="stat-value text-success text-2xl">{{ $laporanHarian->order_selesai }}</div>
                </div>
                <div class="stat">
                    <div class="stat-title">Batal</div>
                    <div class="stat-value text-error text-2xl">{{ $laporanHarian->order_batal }}</div>
                </div>
                <div class="stat">
                    <div class="stat-title">Pendapatan</div>
                    <div class="stat-value text-warning text-xl">Rp {{ number_format($laporanHarian->total_pendapatan, 0, ',', '.') }}</div>
                </div>
            </div>

            <p class="text-sm text-base-content/60 mb-4">
                Tanggal: <strong>{{ $laporanHarian->tanggal->format('d/m/Y') }}</strong> |
                Jam Tutup: <strong>{{ $laporanHarian->jam_tutup }}</strong>
            </p>

            {{-- Rincian Order --}}
            <h3 class="font-bold mb-3">Rincian Order</h3>
            <div class="space-y-3">
                @foreach($laporanHarian->detail_orders as $order)
                <div class="collapse collapse-arrow bg-base-200">
                    <input type="checkbox">
                    <div class="collapse-title text-sm font-semibold flex gap-2 items-center">
                        <span>{{ $order['id_pesanan'] }}</span>
                        <span>-</span>
                        <span>{{ $order['nama_pelanggan'] }}</span>
                        <span class="badge badge-sm {{ $order['tipe_order'] === 'onsite' ? 'badge-warning' : 'badge-info' }}">
                            {{ $order['tipe_order'] === 'onsite' ? 'Onsite' : 'Online' }}
                        </span>
                        <span class="badge badge-sm
                            {{ $order['status'] === 'selesai' ? 'badge-success' : '' }}
                            {{ $order['status'] === 'batal' ? 'badge-error' : '' }}
                            {{ $order['status'] === 'pending' ? 'badge-warning' : '' }}
                            {{ $order['status'] === 'proses' ? 'badge-info' : '' }}">
                            {{ ucfirst($order['status']) }}
                        </span>
                    </div>
                    <div class="collapse-content">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order['items'] as $item)
                                <tr>
                                    <td>{{ $item['nama_menu'] }}</td>
                                    <td class="text-center">{{ $item['jumlah'] }}</td>
                                    <td class="text-right">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-right font-bold">Total</td>
                                    <td class="text-right font-bold text-primary">Rp {{ number_format($order['total_harga'], 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Log Penghapusan --}}
            @if(!empty($laporanHarian->detail_deletion_logs))
            <div class="divider"></div>
            <h3 class="font-bold text-error mb-3">Order yang Dihapus</h3>
            <div class="overflow-x-auto">
                <table class="table table-sm table-zebra">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID Pesanan</th>
                            <th>Nama Pelanggan</th>
                            <th>Menu</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Dihapus Oleh</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporanHarian->detail_deletion_logs as $i => $log)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="font-bold">{{ $log['id_pesanan'] }}</td>
                            <td>{{ $log['nama_pelanggan'] }}</td>
                            <td>{{ $log['nama_menu'] }}</td>
                            <td>Rp {{ number_format($log['total_harga'], 0, ',', '.') }}</td>
                            <td>{{ ucfirst($log['status']) }}</td>
                            <td class="font-semibold">{{ $log['dihapus_oleh'] }}</td>
                            <td>{{ $log['waktu'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection