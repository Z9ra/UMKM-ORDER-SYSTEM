@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Pengaturan --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h2 class="card-title">Pengaturan Tutup Buku</h2>

            <form action="{{ route('tutupbuku.simpanPengaturan') }}" method="POST">
                @csrf
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Jam Tutup Buku</span></label>
                        <input type="time" name="jam_tutup"
                            value="{{ $pengaturan->jam_tutup ?? '22:00' }}"
                            class="input input-bordered input-sm">
                    </div>
                    <div class="form-control">
                        <label class="label cursor-pointer gap-2">
                            <span class="label-text">Auto Tutup Otomatis</span>
                            <input type="checkbox" name="auto_tutup" class="toggle toggle-sm"
                                {{ $pengaturan?->auto_tutup ? 'checked' : '' }}>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-neutral btn-sm">Simpan</button>
                </div>
            </form>

            <div class="divider"></div>

            <p class="text-sm text-base-content/60">Klik tombol di bawah untuk tutup buku sekarang dan reset dashboard.</p>
            <form action="{{ route('tutupbuku.proses') }}" method="POST"
                onsubmit="return confirm('Yakin ingin tutup buku sekarang? Semua order akan dipindah ke laporan harian.')">
                @csrf
                <button type="submit" class="btn btn-error btn-sm">Tutup Buku Sekarang</button>
            </form>
        </div>
    </div>

    {{-- Riwayat Laporan Harian --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h2 class="card-title">Riwayat Laporan Harian</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Jam Tutup</th>
                            <th>Total Order</th>
                            <th>Total Item</th>
                            <th>Pendapatan</th>
                            <th>Selesai</th>
                            <th>Batal</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporanHarian as $laporan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $laporan->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $laporan->jam_tutup }}</td>
                            <td class="text-center">{{ $laporan->total_order }}</td>
                            <td class="text-center">{{ $laporan->total_item }}</td>
                            <td class="font-semibold text-success">Rp {{ number_format($laporan->total_pendapatan, 0, ',', '.') }}</td>
                            <td class="text-center"><span class="badge badge-success badge-sm">{{ $laporan->order_selesai }}</span></td>
                            <td class="text-center"><span class="badge badge-error badge-sm">{{ $laporan->order_batal }}</span></td>
                            <td>
                                <a href="{{ route('tutupbuku.show', $laporan->id) }}" class="btn btn-info btn-xs">Lihat</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-base-content/50 py-8">Belum ada laporan harian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $laporanHarian->links() }}</div>
        </div>
    </div>
</div>
@endsection