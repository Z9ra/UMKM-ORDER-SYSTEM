@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📊 Dashboard Order</h2>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @php
        $statuses = ['pending' => '🟡', 'proses' => '🔵', 'selesai' => '🟢', 'batal' => '🔴'];
        @endphp
        @foreach($statuses as $status => $icon)
        <div class="bg-gray-50 rounded-lg p-4 text-center">
            <div class="text-2xl">{{ $icon }}</div>
            <div class="text-xl font-bold">{{ $orders->where('status', $status)->count() }}</div>
            <div class="text-sm text-gray-500 capitalize">{{ $status }}</div>
        </div>
        @endforeach
    </div>

    {{-- Tabel Order --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">WhatsApp</th>
                    <th class="px-4 py-3">Alamat</th>
                    <th class="px-4 py-3">Detail Order</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-medium">{{ $order->nama_pelanggan }}</td>
                    <td class="px-4 py-3">{{ $order->nomor_whatsapp }}</td>
                    <td class="px-4 py-3">{{ $order->alamat }}</td>
                    <td class="px-4 py-3">{{ $order->detail_order }}</td>
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
                    <td class="px-4 py-3">
                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
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
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">Belum ada order masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection