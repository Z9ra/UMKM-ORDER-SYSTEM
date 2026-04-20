@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">✏️ Edit Order</h2>

    <form action="{{ route('orders.update', $order) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" value="{{ $order->nama_pelanggan }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
            <input type="text" name="nomor_whatsapp" value="{{ $order->nomor_whatsapp }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman</label>
            <textarea name="alamat" required rows="3"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $order->alamat }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Detail Order</label>
            <textarea name="detail_order" required rows="3"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $order->detail_order }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga (Rp)</label>
            <input type="number" name="total_harga" value="{{ $order->total_harga }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="proses" {{ $order->status === 'proses' ? 'selected' : '' }}>Proses</option>
                <option value="selesai" {{ $order->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="batal" {{ $order->status === 'batal' ? 'selected' : '' }}>Batal</option>
            </select>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                💾 Simpan Perubahan
            </button>
            <a href="{{ route('dashboard') }}"
                class="flex-1 text-center bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection