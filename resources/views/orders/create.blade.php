@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 p-4">

    {{-- Grid Menu per Kategori --}}
    @if($menus->isEmpty())
    <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded mb-4 max-w-lg mx-auto">
        Belum ada menu. <a href="{{ route('menus.create') }}" class="underline font-semibold">Tambah menu dulu</a>
    </div>
    @else
    @foreach($menus as $kategori => $items)
    <div class="mb-6">
        <div class="inline-block bg-gray-800 text-white px-4 py-1 rounded font-bold mb-3 text-sm">
            {{ $kategori }}
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            @foreach($items as $menu)
            <div class="bg-white rounded-xl shadow p-2 flex flex-col items-center text-center"
                data-id="{{ $menu->id_menu }}"
                data-nama="{{ $menu->nama_menu }}"
                data-harga="{{ $menu->harga_menu }}">

                {{-- Gambar --}}
                @if($menu->gambar_menu)
                <img src="{{ asset('storage/' . $menu->gambar_menu) }}"
                    class="w-full h-24 object-cover rounded-lg mb-2">
                @else
                <div class="w-full h-24 bg-gray-200 rounded-lg mb-2 flex items-center justify-center text-gray-400 text-xs">
                    No Image
                </div>
                @endif

                <div class="font-semibold text-xs text-gray-800 mb-1">{{ $menu->nama_menu }}</div>
                <div class="text-xs text-gray-500 mb-2 line-clamp-2">{{ $menu->detail_menu }}</div>
                <div class="text-xs font-bold text-gray-700 mb-2">
                    Rp. {{ number_format($menu->harga_menu, 0, ',', '.') }}
                </div>

                {{-- Counter --}}
                <div class="flex items-center gap-2">
                    <button type="button"
                        onclick="kurang('{{ $menu->id_menu }}')"
                        class="w-6 h-6 bg-gray-200 rounded text-sm font-bold hover:bg-gray-300">-</button>
                    <input type="number"
                        id="qty_{{ $menu->id_menu }}"
                        value="0" min="0"
                        onchange="updateCart('{{ $menu->id_menu }}')"
                        class="w-10 text-center border border-gray-300 rounded text-xs py-0.5">
                    <button type="button"
                        onclick="tambah('{{ $menu->id_menu }}')"
                        class="w-6 h-6 bg-gray-200 rounded text-sm font-bold hover:bg-gray-300">+</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
    @endif

    {{-- Tombol Selesai --}}
    <div class="fixed bottom-0 left-0 right-0 p-4">
        <button onclick="openCheckout()"
            class="w-full bg-gray-800 text-white py-4 rounded-xl text-xl font-bold hover:bg-gray-700 transition">
            Selesai
        </button>
    </div>

</div>

{{-- Modal Checkout --}}
<div id="modal_checkout" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">
        <h3 class="text-xl font-bold text-center text-gray-800 mb-4">Checking Pesanan</h3>

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf

            {{-- Hidden fields untuk menu yang dipilih --}}
            <div id="hidden_menus"></div>

            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Order</label>
                    <select name="tipe_order" id="tipe_order_modal" onchange="toggleOnlineModal(this.value)"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="onsite">Onsite / Offline</option>
                        <option value="online">Online</option>
                    </select>
                </div>

                <div id="field_online_modal" class="hidden space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                        <input type="text" name="nomor_whatsapp"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman</label>
                        <textarea name="alamat" rows="2"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Detail Order</label>
                    <textarea name="detail_order_display" id="detail_order_display" rows="3" readonly
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm bg-gray-50 text-gray-600"></textarea>
                </div>

                <div class="flex justify-between items-center py-2 border-t">
                    <span class="font-bold text-gray-700">Total Harga =</span>
                    <span id="total_display" class="font-bold text-blue-600 text-lg">Rp. 0</span>
                </div>
            </div>

            <div class="flex gap-3 mt-4">
                <button type="button" onclick="closeCheckout()"
                    class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 bg-gray-800 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const cart = {};
    const menuData = {};

    // Kumpulkan semua data menu
    document.querySelectorAll('[data-id]').forEach(el => {
        const id = el.getAttribute('data-id');
        menuData[id] = {
            nama: el.getAttribute('data-nama'),
            harga: parseFloat(el.getAttribute('data-harga')),
        };
    });

    function tambah(id) {
        const input = document.getElementById('qty_' + id);
        input.value = parseInt(input.value) + 1;
        updateCart(id);
    }

    function kurang(id) {
        const input = document.getElementById('qty_' + id);
        if (parseInt(input.value) > 0) {
            input.value = parseInt(input.value) - 1;
            updateCart(id);
        }
    }

    function updateCart(id) {
        const qty = parseInt(document.getElementById('qty_' + id).value) || 0;
        if (qty > 0) {
            cart[id] = qty;
        } else {
            delete cart[id];
        }
    }

    function openCheckout() {
        if (Object.keys(cart).length === 0) {
            alert('Pilih menu terlebih dahulu!');
            return;
        }

        // Generate hidden inputs & detail order
        const hiddenDiv = document.getElementById('hidden_menus');
        hiddenDiv.innerHTML = '';
        let detail = '';
        let total = 0;
        let index = 0;

        for (const [id, qty] of Object.entries(cart)) {
            const menu = menuData[id];
            const subtotal = menu.harga * qty;
            total += subtotal;
            detail += `${menu.nama} x${qty} = Rp ${subtotal.toLocaleString('id-ID')}\n`;

            hiddenDiv.innerHTML += `
            <input type="hidden" name="menus[${index}][id_menu]" value="${id}">
            <input type="hidden" name="menus[${index}][jumlah]" value="${qty}">
        `;
            index++;
        }

        document.getElementById('detail_order_display').value = detail.trim();
        document.getElementById('total_display').textContent = 'Rp. ' + total.toLocaleString('id-ID');
        document.getElementById('modal_checkout').classList.remove('hidden');
    }

    function closeCheckout() {
        document.getElementById('modal_checkout').classList.add('hidden');
    }

    function toggleOnlineModal(val) {
        document.getElementById('field_online_modal').classList.toggle('hidden', val !== 'online');
    }
</script>
@endsection