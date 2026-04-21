@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-8">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">📊 Dashboard Order</h2>
        <div class="flex gap-3">
            <a href="{{ route('orders.exportExcel') }}"
                class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition">
                📥 Export Excel
            </a>
            <a href="{{ route('orders.exportPdf') }}"
                class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                📄 Export PDF
            </a>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
    @endif

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach(['pending' => '🟡', 'proses' => '🔵', 'selesai' => '🟢', 'batal' => '🔴'] as $status => $icon)
        <div class="bg-gray-50 rounded-lg p-4 text-center">
            <div class="text-2xl">{{ $icon }}</div>
            <div class="text-xl font-bold">{{ $orders->where('status', $status)->count() }}</div>
            <div class="text-sm text-gray-500 capitalize">{{ ucfirst($status) }}</div>
        </div>
        @endforeach
    </div>

    {{-- Tabel Order --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">ID Pesanan</th>
                    <th class="px-4 py-3">Nama Pelanggan</th>
                    <th class="px-4 py-3">Tipe</th>
                    <th class="px-4 py-3">WhatsApp</th>
                    <th class="px-4 py-3">Alamat</th>
                    <th class="px-4 py-3">Menu</th>
                    <th class="px-4 py-3">Total Pesanan</th>
                    <th class="px-4 py-3">Total Harga</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Jam Input</th>
                    <th class="px-4 py-3">Ubah Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-medium">{{ $order->id_pesanan }}</td>
                    <td class="px-4 py-3">{{ $order->nama_pelanggan }}</td>
                    <td class="px-4 py-3">
                        @if($order->tipe_order === 'onsite')
                        <span class="text-orange-600 font-semibold text-xs">🏪 Onsite</span>
                        @else
                        <span class="text-blue-600 font-semibold text-xs">📱 Online</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        {{ $order->nomor_whatsapp ?? '-' }}
                    </td>
                    <td class="px-4 py-3">
                        {{ $order->alamat ?? '-' }}
                    </td>
                    <td class="px-4 py-3">
                        @foreach($order->items as $item)
                        <div class="text-xs">{{ $item->nama_menu }} x{{ $item->jumlah }}</div>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 text-center">{{ $order->total_pesanan }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->status === 'proses' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status === 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->status === 'batal' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ $order->jam_input }}</td>
                    <td class="px-4 py-3">
                        <form action="{{ route('orders.updateStatus', $order->id_pesanan) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                class="border border-gray-300 rounded px-2 py-1 text-xs">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="proses" {{ $order->status === 'proses' ? 'selected' : '' }}>Proses</option>
                                <option value="selesai" {{ $order->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="batal" {{ $order->status === 'batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-col gap-1">
                            <a href="{{ route('orders.show', $order->id_pesanan) }}"
                                class="w-16 text-center bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition">
                                👁️ Detail
                            </a>
                            <a href="{{ route('orders.edit', $order->id_pesanan) }}"
                                class="w-16 text-center bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600 transition">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('orders.destroy', $order->id_pesanan) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus order ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-16 text-center bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 transition">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="13" class="px-4 py-8 text-center text-gray-400">Belum ada order masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Log Penghapusan --}}
    <div class="mt-10">
        <h3 class="text-xl font-bold text-gray-800 mb-4">🗑️ Log Penghapusan Order</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-red-50 text-red-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">ID Pesanan</th>
                        <th class="px-4 py-3">Nama Pelanggan</th>
                        <th class="px-4 py-3">Menu</th>
                        <th class="px-4 py-3">Total Pesanan</th>
                        <th class="px-4 py-3">Total Harga</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Dihapus Oleh</th>
                        <th class="px-4 py-3">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($deletionLogs as $log)
                    <tr class="hover:bg-red-50">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium">{{ $log->id_pesanan }}</td>
                        <td class="px-4 py-3">{{ $log->nama_pelanggan }}</td>
                        <td class="px-4 py-3">{{ $log->nama_menu }}</td>
                        <td class="px-4 py-3 text-center">{{ $log->total_pesanan }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($log->total_harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ ucfirst($log->status) }}</td>
                        <td class="px-4 py-3 font-medium">{{ $log->dihapus_oleh }}</td>
                        <td class="px-4 py-3">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-400">Belum ada log penghapusan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection