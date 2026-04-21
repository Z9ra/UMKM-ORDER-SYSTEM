@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📝 Form Pemesanan</h2>

    @if($menus->isEmpty())
    <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded mb-4">
        ⚠️ Belum ada menu. <a href="{{ route('menus.create') }}" class="underline font-semibold">Tambah menu dulu</a>
    </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan nama pelanggan">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Order</label>
            <select name="tipe_order" id="tipe_order" onchange="toggleOnline(this.value)"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="onsite">🏪 Onsite / Offline</option>
                <option value="online">📱 Online</option>
            </select>
        </div>

        <div id="field_online" class="hidden space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                <input type="text" name="nomor_whatsapp"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="08xxxxxxxxxx">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman</label>
                <textarea name="alamat" rows="2"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan alamat lengkap"></textarea>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Menu</label>
            <div id="menu_list" class="space-y-3">
                <div class="menu-item flex gap-2 items-center">
                    <select name="menus[0][id_menu]" onchange="updateSubtotal(this)"
                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Menu --</option>
                        @foreach($menus as $menu)
                        <option value="{{ $menu->id_menu }}" data-harga="{{ $menu->harga_menu }}">
                            {{ $menu->nama_menu }} - Rp {{ number_format($menu->harga_menu, 0, ',', '.') }}
                        </option>
                        @endforeach
                    </select>
                    <input type="number" name="menus[0][jumlah]" value="1" min="1"
                        onchange="updateSubtotal(this)"
                        class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span class="subtotal text-sm text-gray-500 w-28">Rp 0</span>
                </div>
            </div>
            <button type="button" onclick="tambahMenu()"
                class="mt-3 text-blue-600 text-sm font-semibold hover:underline">
                + Tambah Menu Lain
            </button>
        </div>

        <div class="bg-gray-50 rounded-lg px-4 py-3 flex justify-between items-center">
            <span class="font-semibold text-gray-700">Total Harga:</span>
            <span id="grand_total" class="font-bold text-blue-600 text-lg">Rp 0</span>
        </div>

        <button type="submit"
            class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
            🚀 Kirim Order
        </button>
    </form>
</div>

<script>
    let menuCount = 1;
    const menusList = @json($menus);
    const menusData = {};
    menusList.forEach(m => {
        menusData[m.id_menu] = m;
    });

    function toggleOnline(val) {
        document.getElementById('field_online').classList.toggle('hidden', val !== 'online');
    }

    function getMenuOptions() {
        return menusList.map(m =>
            `<option value="${m.id_menu}" data-harga="${m.harga_menu}">${m.nama_menu} - Rp ${Number(m.harga_menu).toLocaleString('id-ID')}</option>`
        ).join('');
    }

    function tambahMenu() {
        const list = document.getElementById('menu_list');
        const div = document.createElement('div');
        div.className = 'menu-item flex gap-2 items-center';
        div.innerHTML = `
        <select name="menus[${menuCount}][id_menu]" onchange="updateSubtotal(this)"
            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih Menu --</option>
            ${getMenuOptions()}
        </select>
        <input type="number" name="menus[${menuCount}][jumlah]" value="1" min="1"
            onchange="updateSubtotal(this)"
            class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <span class="subtotal text-sm text-gray-500 w-28">Rp 0</span>
        <button type="button" onclick="this.parentElement.remove(); hitungGrandTotal()"
            class="text-red-500 text-sm hover:underline">Hapus</button>
    `;
        list.appendChild(div);
        menuCount++;
    }

    function updateSubtotal(el) {
        const row = el.closest('.menu-item');
        const select = row.querySelector('select');
        const jumlah = parseInt(row.querySelector('input').value) || 0;
        const option = select.options[select.selectedIndex];
        const harga = parseFloat(option?.getAttribute('data-harga')) || 0;
        row.querySelector('.subtotal').textContent = 'Rp ' + (harga * jumlah).toLocaleString('id-ID');
        hitungGrandTotal();
    }

    function hitungGrandTotal() {
        let total = 0;
        document.querySelectorAll('.menu-item').forEach(row => {
            const select = row.querySelector('select');
            const jumlah = parseInt(row.querySelector('input')?.value) || 0;
            const option = select.options[select.selectedIndex];
            const harga = parseFloat(option?.getAttribute('data-harga')) || 0;
            total += harga * jumlah;
        });
        document.getElementById('grand_total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endsection