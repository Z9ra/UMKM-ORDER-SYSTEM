@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📝 Form Pemesanan</h2>

    <form action="{{ route('orders.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan nama lengkap">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
            <input type="text" name="nomor_whatsapp" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="08xxxxxxxxxx">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman</label>
            <textarea name="alamat" required rows="3"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan alamat lengkap"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Detail Order</label>
            <textarea name="detail_order" required rows="3"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Contoh: Nasi Goreng x2, Es Teh x2"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga (Rp)</label>
            <input type="number" name="total_harga"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Opsional">
        </div>

        <button type="submit"
            class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
            🚀 Kirim Order
        </button>
    </form>
</div>
@endsection