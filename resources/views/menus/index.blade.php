@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">🍽️ Daftar Menu</h2>
        <a href="{{ route('menus.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
            + Tambah Menu
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">ID Menu</th>
                    <th class="px-4 py-3">Gambar</th>
                    <th class="px-4 py-3">Nama Menu</th>
                    <th class="px-4 py-3">Detail</th>
                    <th class="px-4 py-3">Harga</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($menus as $menu)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $menu->id_menu }}</td>
                    <td class="px-4 py-3">
                        @if($menu->gambar_menu)
                        <img src="{{ asset('storage/' . $menu->gambar_menu) }}"
                            class="w-16 h-16 object-cover rounded-lg">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-xs">
                            No Image
                        </div>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $menu->nama_menu }}</td>
                    <td class="px-4 py-3">{{ $menu->detail_menu ?? '-' }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($menu->harga_menu, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('menus.edit', $menu->id_menu) }}"
                                class="w-16 text-center bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600 transition">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('menus.destroy', $menu->id_menu) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus menu ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-16 text-center bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 transition">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada menu. Tambahkan menu terlebih dahulu.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection