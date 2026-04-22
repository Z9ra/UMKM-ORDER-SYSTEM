@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Menu</h2>

    <form action="{{ route('menus.update', $menu->id_menu) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ID Menu</label>
            <input type="text" value="{{ $menu->id_menu }}" disabled
                class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Menu</label>
            <input type="text" name="nama_menu" value="{{ $menu->nama_menu }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <input type="text" name="kategori" value="{{ $menu->kategori }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
            <input type="number" name="harga_menu" value="{{ $menu->harga_menu }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Detail Menu</label>
            <textarea name="detail_menu" rows="3"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $menu->detail_menu }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Menu</label>
            @if($menu->gambar_menu)
            <img src="{{ asset('storage/' . $menu->gambar_menu) }}" class="w-24 h-24 object-cover rounded-lg mb-2">
            @endif
            <input type="file" name="gambar_menu" accept="image/*"
                class="w-full border border-gray-300 rounded-lg px-4 py-2">
            <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah gambar</p>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Simpan Perubahan
            </button>
            <a href="{{ route('menus.index') }}"
                class="flex-1 text-center bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection