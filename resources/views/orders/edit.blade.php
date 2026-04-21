@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">✏️ Edit Order</h2>

    <form action="{{ route('orders.update', $order->id_pesanan) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ID Pesanan</label>
            <input type="text" value="{{ $order->id_pesanan }}" disabled
                class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" value="{{ $order->nama_pelanggan }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Menu</label>
            <select name="id_menu" required onchange="updateHarga(this)"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach($menus as $menu)
                <option value="{{ $menu->id_menu }}" data-harga="{{ $menu->harga_menu }}"
                    {{ $order->id_menu === $menu->id_menu ? 'selected' : '' }}>
                    {{ $menu->nama_menu }} - Rp {{ number_format($menu->harga_menu, 0, ',', '.') }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pesanan</label>
            <input type="number" name="total_pesanan" id="total_pesanan" value="{{ $order->total_pesanan }}" required min="1"
                onchange="hitungTotal()"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga</label>
            <input type="text" id="total_harga_display" disabled
                value="Rp {{ number_format($order->total_harga, 0, ',', '.') }}"
                class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-500">
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

<script>
    let hargaSatuan = {
        {
            $order - > harga_menu
        }
    };

    function updateHarga(select) {
        const option = select.options[select.selectedIndex];
        hargaSatuan = parseFloat(option.getAttribute('data-harga')) || 0;
        hitungTotal();
    }

    function hitungTotal() {
        const jumlah = parseInt(document.getElementById('total_pesanan').value) || 0;
        const total = hargaSatuan * jumlah;
        document.getElementById('total_harga_display').value = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endsection