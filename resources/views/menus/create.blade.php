@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-base-200 -m-8 p-4">

    @if($menus->isEmpty())
    <div class="alert alert-warning max-w-lg mx-auto mb-4">
        <span>Belum ada menu. <a href="{{ route('menus.create') }}" class="underline font-semibold">Tambah menu dulu</a></span>
    </div>
    @endif

    {{-- Grid Menu per Kategori --}}
    @foreach($menus as $kategori => $items)
    <div class="mb-6">
        <div class="inline-block bg-neutral text-neutral-content px-4 py-1 rounded font-bold mb-3 text-sm">
            {{ $kategori }}
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            @foreach($items as $menu)
            <div class="card bg-base-100 shadow-sm hover:shadow-md transition"
                data-id="{{ $menu->id_menu }}"
                data-nama="{{ $menu->nama_menu }}"
                data-harga="{{ $menu->harga_menu }}">
                <div class="card-body p-3 items-center text-center">
                    @if($menu->gambar_menu)
                    <img src="{{ asset('storage/' . $menu->gambar_menu) }}"
                        class="w-full h-24 object-cover rounded-lg mb-2">
                    @else
                    <div class="w-full h-24 bg-base-200 rounded-lg mb-2 flex items-center justify-center text-base-content/40 text-xs">
                        No Image
                    </div>
                    @endif

                    <p class="font-semibold text-xs">{{ $menu->nama_menu }}</p>
                    <p class="text-xs text-base-content/60 line-clamp-2">{{ $menu->detail_menu }}</p>
                    <p class="text-xs font-bold mt-1">Rp {{ number_format($menu->harga_menu, 0, ',', '.') }}</p>

                    {{-- Counter --}}
                    <div class="join mt-2">
                        <button type="button" onclick="kurang('{{ $menu->id_menu }}')"
                            class="btn btn-xs join-item">-</button>
                        <input type="number" id="qty_{{ $menu->id_menu }}" value="0" min="0"
                            onchange="updateCart('{{ $menu->id_menu }}')"
                            class="input input-xs input-bordered join-item w-12 text-center">
                        <button type="button" onclick="tambah('{{ $menu->id_menu }}')"
                            class="btn btn-xs join-item">+</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Tombol Selesai --}}
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-base-100 shadow-lg">
        <button onclick="openCheckout()" class="btn btn-neutral w-full text-lg">
            Selesai
        </button>
    </div>
    <div class="h-20"></div>
</div>

{{-- Modal Checkout --}}
<dialog id="modal_checkout" class="modal">
    <div class="modal-box w-full max-w-md">
        <h3 class="font-bold text-lg mb-4 text-center">Checking Pesanan</h3>

        <form action="{{ route('orders.store') }}" method="POST" class="space-y-3">
            @csrf
            <div id="hidden_menus"></div>

            <div class="form-control">
                <label class="label"><span class="label-text">Nama Pelanggan</span></label>
                <input type="text" name="nama_pelanggan" required
                    class="input input-bordered input-sm" placeholder="Masukkan nama pelanggan">
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">Tipe Order</span></label>
                <select name="tipe_order" id="tipe_order_modal" onchange="toggleOnlineModal(this.value)"
                    class="select select-bordered select-sm">
                    <option value="onsite">Onsite / Offline</option>
                    <option value="online">Online</option>
                </select>
            </div>

            <div id="field_online_modal" class="hidden space-y-3">
                <div class="form-control">
                    <label class="label"><span class="label-text">Nomor WhatsApp</span></label>
                    <input type="text" name="nomor_whatsapp"
                        class="input input-bordered input-sm" placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Alamat Pengiriman</span></label>
                    <textarea name="alamat" rows="2" class="textarea textarea-bordered textarea-sm"></textarea>
                </div>
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">Detail Order</span></label>
                <textarea id="detail_order_display" rows="3" readonly
                    class="textarea textarea-bordered textarea-sm bg-base-200"></textarea>
            </div>

            <div class="flex justify-between items-center py-2 border-t border-base-300">
                <span class="font-bold">Total Harga</span>
                <span id="total_display" class="font-bold text-primary text-lg">Rp 0</span>
            </div>

            <div class="modal-action mt-2">
                <button type="button" onclick="closeCheckout()" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn btn-neutral">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button onclick="closeCheckout()">close</button>
    </form>
</dialog>

<script>
    const cart = {};
    const menuData = {};

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
        if (qty > 0) cart[id] = qty;
        else delete cart[id];
    }

    function openCheckout() {
        if (Object.keys(cart).length === 0) {
            alert('Pilih menu terlebih dahulu!');
            return;
        }

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
        document.getElementById('total_display').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('modal_checkout').showModal();
    }

    function closeCheckout() {
        document.getElementById('modal_checkout').close();
    }

    function toggleOnlineModal(val) {
        document.getElementById('field_online_modal').classList.toggle('hidden', val !== 'online');
    }
</script>
@endsection