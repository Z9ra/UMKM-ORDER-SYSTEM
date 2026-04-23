@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">📋 Detail Laporan Harian</h2>
        <a href="{{ route('tutupbuku.index') }}" class="text-sm text-gray-500 hover:underline">← Kembali</a>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 rounded-lg p-3 text-center">
            <div class="text-xl font-bold text-blue-600">{{ $laporanHarian->total_order }}</div>
            <div class="text-xs text-gray-500">Total Order</div>
        </div>
        <div class="bg-green-50 rounded-lg p-3 text-center">
            <div class="text-xl font-bold text-green-600">{{ $laporanHarian->order_selesai }}</div>
            <div class="text-xs text-gray-500">Selesai</div>
        </div>
        <div class="bg-red-50 rounded-lg p-3 text-center">
            <div class="text-xl font-bold text-red-600">{{ $laporanHarian->order_batal }}</div>
            <div class="text-xs text-gray-500">Batal</div>
        </div>
        <div class="bg-yellow-50 rounded-lg p-3 text-center">
            <div class="text-xl font-bold text-yellow-600">
                Rp {{ number_format($laporanHarian->total_pendapatan, 0, ',', '.') }}
            </div>
            <div class="text-xs text-gray-500">Total Pendapatan</div>
        </div>
    </div>

    <div class="text-sm text-gray-500 mb-4">
        Tanggal: <strong>{{ $laporanHarian->tanggal->format('d/m/Y') }}</strong> |
        Jam Tutup: <strong>{{ $laporanHarian->jam_tutup }}</strong>
    </div>

    {{-- Detail Orders --}}
    <h3 class="font-bold text-gray-700 mb-3">📝 Rincian Order</h3>
    <div class="space-y-4">
        @foreach($laporanHarian->detail_orders as $i => $order)
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <span class="font-semibold text-gray-800">{{ $order['id_pesanan'] }}</span>
                    <span class="ml-2 text-sm text-gray-500">{{ $order['nama_pelanggan'] }}</span>
                </div>
                <div class="flex gap-2">
                    <span class="text-xs px-2 py-1 rounded-full
                        {{ $order['tipe_order'] === 'onsite' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $order['tipe_order'] === 'onsite' ? '🏪 Onsite' : '📱 Online' }}
                    </span>
                    <span class="text-xs px-2 py-1 rounded-full
                        {{ $order['status'] === 'selesai' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $order['status'] === 'batal' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $order['status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $order['status'] === 'proses' ? 'bg-blue-100 text-blue-700' : '' }}">
                        {{ ucfirst($order['status']) }}
                    </span>
                </div>
            </div>
            <table class="w-full text-xs mt-2">
                <thead class="text-gray-500">
                    <tr>
                        <th class="text-left py-1">Menu</th>
                        <th class="text-center py-1">Jumlah</th>
                        <th class="text-right py-1">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order['items'] as $item)
                    <tr class="border-t border-gray-100">
                        <td class="py-1">{{ $item['nama_menu'] }}</td>
                        <td class="text-center py-1">{{ $item['jumlah'] }}</td>
                        <td class="text-right py-1">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-gray-300">
                        <td colspan="2" class="text-right py-1 font-semibold">Total</td>
                        <td class="text-right py-1 font-bold text-blue-600">
                            Rp {{ number_format($order['total_harga'], 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endforeach
    </div>

    {{-- Log Penghapusan --}}
    @if(!empty($laporanHarian->detail_deletion_logs))
    <div class="mt-6">
        <h3 class="font-bold text-gray-700 mb-3">🗑️ Order yang Dihapus</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-red-50 text-red-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">ID Pesanan</th>
                        <th class="px-4 py-2">Nama Pelanggan</th>
                        <th class="px-4 py-2">Menu</th>
                        <th class="px-4 py-2">Total Pesanan</th>
                        <th class="px-4 py-2">Total Harga</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Dihapus Oleh</th>
                        <th class="px-4 py-2">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($laporanHarian->detail_deletion_logs as $i => $log)
                    <tr class="hover:bg-red-50">
                        <td class="px-4 py-2">{{ $i + 1 }}</td>
                        <td class="px-4 py-2 font-medium">{{ $log['id_pesanan'] }}</td>
                        <td class="px-4 py-2">{{ $log['nama_pelanggan'] }}</td>
                        <td class="px-4 py-2">{{ $log['nama_menu'] }}</td>
                        <td class="px-4 py-2 text-center">{{ $log['total_pesanan'] }}</td>
                        <td class="px-4 py-2">Rp {{ number_format($log['total_harga'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2">{{ ucfirst($log['status']) }}</td>
                        <td class="px-4 py-2 font-medium">{{ $log['dihapus_oleh'] }}</td>
                        <td class="px-4 py-2">{{ $log['waktu'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection