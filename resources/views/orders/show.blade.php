@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Detail Order</h2>
        <a href="{{ route('dashboard') }}"
            class="text-sm text-gray-500 hover:underline">← Kembali</a>
    </div>

    {{-- Info Order --}}
    <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-2 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-500">ID Pesanan</span>
            <span class="font-semibold">{{ $order->id_pesanan }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Nama Pelanggan</span>
            <span class="font-semibold">{{ $order->nama_pelanggan }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Tipe Order</span>
            <span class="font-semibold">
                @if($order->tipe_order === 'onsite')
                Onsite / Offline
                @else
                Online
                @endif
            </span>
        </div>

        @if($order->tipe_order === 'online')
        <div class="flex justify-between">
            <span class="text-gray-500">Nomor WhatsApp</span>
            <span class="font-semibold">{{ $order->nomor_whatsapp ?? '-' }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Alamat</span>
            <span class="font-semibold text-right max-w-xs">{{ $order->alamat ?? '-' }}</span>
        </div>
        @else
        <div class="flex justify-between">
            <span class="text-gray-500">Keterangan</span>
            <span class="font-semibold text-orange-600">Order dilakukan secara Onsite/Offline</span>
        </div>
        @endif

        <div class="flex justify-between">
            <span class="text-gray-500">Status</span>
            <span class="px-2 py-1 rounded-full text-xs font-semibold
                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $order->status === 'proses' ? 'bg-blue-100 text-blue-800' : '' }}
                {{ $order->status === 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                {{ $order->status === 'batal' ? 'bg-red-100 text-red-800' : '' }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Jam Input</span>
            <span class="font-semibold">{{ $order->jam_input }}</span>
        </div>
    </div>

    {{-- Detail Menu --}}
    <h3 class="font-bold text-gray-700 mb-3">🍽️ Detail Menu</h3>
    <table class="w-full text-sm mb-4">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-2 text-left">Menu</th>
                <th class="px-4 py-2 text-right">Harga</th>
                <th class="px-4 py-2 text-center">Jumlah</th>
                <th class="px-4 py-2 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($order->items as $item)
            <tr>
                <td class="px-4 py-2">{{ $item->nama_menu }}</td>
                <td class="px-4 py-2 text-right">Rp {{ number_format($item->harga_menu, 0, ',', '.') }}</td>
                <td class="px-4 py-2 text-center">{{ $item->jumlah }}</td>
                <td class="px-4 py-2 text-right font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-gray-50">
            <tr>
                <td colspan="3" class="px-4 py-2 text-right font-bold">Total</td>
                <td class="px-4 py-2 text-right font-bold text-blue-600">
                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection