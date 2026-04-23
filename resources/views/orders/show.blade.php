@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title text-xl">Detail Order</h2>
                <a href="{{ route('dashboard') }}" class="btn btn-ghost btn-sm">Kembali</a>
            </div>

            {{-- Info Order --}}
            <div class="bg-base-200 rounded-xl p-4 mb-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-base-content/60">ID Pesanan</span>
                    <span class="font-bold">{{ $order->id_pesanan }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-base-content/60">Nama Pelanggan</span>
                    <span class="font-semibold">{{ $order->nama_pelanggan }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-base-content/60">Tipe Order</span>
                    <span class="badge {{ $order->tipe_order === 'onsite' ? 'badge-warning' : 'badge-info' }}">
                        {{ $order->tipe_order === 'onsite' ? 'Onsite' : 'Online' }}
                    </span>
                </div>

                @if($order->tipe_order === 'online')
                <div class="flex justify-between">
                    <span class="text-base-content/60">Nomor WhatsApp</span>
                    <span class="font-semibold">{{ $order->nomor_whatsapp ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-base-content/60">Alamat</span>
                    <span class="font-semibold text-right max-w-xs">{{ $order->alamat ?? '-' }}</span>
                </div>
                @else
                <div class="flex justify-between">
                    <span class="text-base-content/60">Keterangan</span>
                    <span class="text-warning font-semibold">Order dilakukan secara Onsite</span>
                </div>
                @endif

                <div class="flex justify-between">
                    <span class="text-base-content/60">Status</span>
                    <span class="badge badge-sm
                        {{ $order->status === 'pending' ? 'badge-warning' : '' }}
                        {{ $order->status === 'proses' ? 'badge-info' : '' }}
                        {{ $order->status === 'selesai' ? 'badge-success' : '' }}
                        {{ $order->status === 'batal' ? 'badge-error' : '' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-base-content/60">Jam Input</span>
                    <span class="font-semibold">{{ $order->jam_input }}</span>
                </div>
            </div>

            {{-- Detail Menu --}}
            <h3 class="font-bold text-base mb-2">Detail Menu</h3>
            <div class="overflow-x-auto">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th class="text-right">Harga</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->nama_menu }}</td>
                            <td class="text-right">Rp {{ number_format($item->harga_menu, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-right font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right font-bold">Total</td>
                            <td class="text-right font-bold text-primary">
                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection