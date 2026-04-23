@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Pengaturan Jam Tutup Buku --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">⚙️ Pengaturan Tutup Buku</h2>

        @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
        @endif

        <form action="{{ route('tutupbuku.simpanPengaturan') }}" method="POST" class="space-y-4">
            @csrf
            <div class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Tutup Buku</label>
                    <input type="time" name="jam_tutup"
                        value="{{ $pengaturan->jam_tutup ?? '22:00' }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center gap-2 pb-2">
                    <input type="checkbox" name="auto_tutup" id="auto_tutup"
                        {{ $pengaturan?->auto_tutup ? 'checked' : '' }}
                        class="w-4 h-4">
                    <label for="auto_tutup" class="text-sm text-gray-700">Auto Tutup Otomatis</label>
                </div>
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </form>

        {{-- Tombol Tutup Buku Manual --}}
        <div class="mt-4 pt-4 border-t">
            <p class="text-sm text-gray-500 mb-3">Klik tombol di bawah untuk tutup buku sekarang dan reset dashboard.</p>
            <form action="{{ route('tutupbuku.proses') }}" method="POST"
                onsubmit="return confirm('Yakin ingin tutup buku sekarang? Semua order akan dipindah ke laporan harian dan dashboard akan direset.')">
                @csrf
                <button type="submit"
                    class="bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                    🔒 Tutup Buku Sekarang
                </button>
            </form>
        </div>
    </div>

    {{-- Riwayat Laporan Harian --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">📅 Riwayat Laporan Harian</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Jam Tutup</th>
                        <th class="px-4 py-3">Total Order</th>
                        <th class="px-4 py-3">Total Item</th>
                        <th class="px-4 py-3">Pendapatan</th>
                        <th class="px-4 py-3">Selesai</th>
                        <th class="px-4 py-3">Batal</th>
                        <th class="px-4 py-3">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($laporanHarian as $laporan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium">{{ $laporan->tanggal->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $laporan->jam_tutup }}</td>
                        <td class="px-4 py-3 text-center">{{ $laporan->total_order }}</td>
                        <td class="px-4 py-3 text-center">{{ $laporan->total_item }}</td>
                        <td class="px-4 py-3 font-semibold text-green-600">
                            Rp {{ number_format($laporan->total_pendapatan, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                {{ $laporan->order_selesai }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">
                                {{ $laporan->order_batal }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('tutupbuku.show', $laporan->id) }}"
                                class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600 transition">
                                👁️ Lihat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-400">Belum ada laporan harian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $laporanHarian->links() }}</div>
    </div>
</div>
@endsection