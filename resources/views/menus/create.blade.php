@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">➕ Tambah Menu</h2>

    <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Menu</label>
            <input type="text" name="nama_menu" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Contoh: Ayam Penyet">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
            <input type="number" name="harga_menu" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="8000">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Detail Menu</label>
            <textarea name="detail_menu" rows="3"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Deskripsi menu..."></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Menu</label>
            <input type="file" name="gambar_menu" accept="image/*"
                class="w-full border border-gray-300 rounded-lg px-4 py-2">
        </div>

        <div class="flex gap-3">
            <button type="submit"
                class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                💾 Simpan Menu
            </button>
            <a href="{{ route('menus.index') }}"
                class="flex-1 text-center bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection