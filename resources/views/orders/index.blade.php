@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">📊 Dashboard Order</h2>
    <div class="flex gap-2">
        <a href="{{ route('orders.exportExcel') }}" class="btn btn-success btn-sm">📥 Export Excel</a>
        <a href="{{ route('orders.exportPdf') }}" class="btn btn-error btn-sm">📄 Export PDF</a>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="stat bg-base-100 rounded-xl shadow">
        <div class="stat-figure text-warning text-3xl">🟡</div>
        <div class="stat-title">Pending</div>
        <div class="stat-value text-warning">{{ $orders->where('status', 'pending')->count() }}</div>
    </div>
    <div class="stat bg-base-100 rounded-xl shadow">
        <div class="stat-figure text-info text-3xl">🔵</div>
        <div class="stat-title">Proses</div>
        <div class="stat-value text-info">{{ $orders->where('status', 'proses')->count() }}</div>
    </div>
    <div class="stat bg-base-100 rounded-xl shadow">
        <div class="stat-figure text-success text-3xl">🟢</div>
        <div class="stat-title">Selesai</div>
        <div class="stat-value text-success">{{ $orders->where('status', 'selesai')->count() }}</div>
    </div>
    <div class="stat bg-base-100 rounded-xl shadow">
        <div class="stat-figure text-error text-3xl">🔴</div>
        <div class="stat-title">Batal</div>
        <div class="stat-value text-error">{{ $orders->where('status', 'batal')->count() }}</div>
    </div>
</div>

{{-- Tabel Order --}}
<div class="card bg-base-100 shadow mb-6">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID Pesanan</th>
                        <th>Nama Pelanggan</th>
                        <th>Tipe</th>
                        <th>WhatsApp</th>
                        <th>Alamat</th>
                        <th>Menu</th>
                        <th>Total Pesanan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Jam Input</th>
                        <th>Ubah Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="font-bold">{{ $order->id_pesanan }}</td>
                        <td>{{ $order->nama_pelanggan }}</td>
                        <td>
                            @if($order->tipe_order === 'onsite')
                            <span class="badge badge-warning badge-sm">🏪 Onsite</span>
                            @else
                            <span class="badge badge-info badge-sm">📱 Online</span>
                            @endif
                        </td>
                        <td>{{ $order->nomor_whatsapp ?? '-' }}</td>
                        <td>{{ $order->alamat ?? '-' }}</td>
                        <td>
                            @foreach($order->items as $item)
                            <div class="text-xs">{{ $item->nama_menu }} x{{ $item->jumlah }}</div>
                            @endforeach
                        </td>
                        <td class="text-center">{{ $order->total_pesanan }}</td>
                        <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge badge-sm
                                {{ $order->status === 'pending' ? 'badge-warning' : '' }}
                                {{ $order->status === 'proses' ? 'badge-info' : '' }}
                                {{ $order->status === 'selesai' ? 'badge-success' : '' }}
                                {{ $order->status === 'batal' ? 'badge-error' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->jam_input }}</td>
                        <td>
                            <form action="{{ route('orders.updateStatus', $order->id_pesanan) }}" method="POST">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="select select-bordered select-xs">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="proses" {{ $order->status === 'proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="selesai" {{ $order->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="batal" {{ $order->status === 'batal' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <div class="flex flex-col gap-1">
                                <a href="{{ route('orders.show', $order->id_pesanan) }}" class="btn btn-info btn-xs">👁️ Detail</a>
                                <a href="{{ route('orders.edit', $order->id_pesanan) }}" class="btn btn-warning btn-xs">✏️ Edit</a>
                                <form action="{{ route('orders.destroy', $order->id_pesanan) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus order ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-error btn-xs w-full">🗑️ Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" class="text-center text-base-content/50 py-8">Belum ada order masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Log Penghapusan --}}
<div class="card bg-base-100 shadow">
    <div class="card-body">
        <h3 class="card-title text-error">🗑️ Log Penghapusan Order</h3>
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead class="text-error">
                    <tr>
                        <th>#</th>
                        <th>ID Pesanan</th>
                        <th>Nama Pelanggan</th>
                        <th>Menu</th>
                        <th>Total Pesanan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Dihapus Oleh</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deletionLogs as $log)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="font-bold">{{ $log->id_pesanan }}</td>
                        <td>{{ $log->nama_pelanggan }}</td>
                        <td>{{ $log->nama_menu }}</td>
                        <td class="text-center">{{ $log->total_pesanan }}</td>
                        <td>Rp {{ number_format($log->total_harga, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($log->status) }}</td>
                        <td class="font-semibold">{{ $log->dihapus_oleh }}</td>
                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-base-content/50 py-8">Belum ada log penghapusan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection