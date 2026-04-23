@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h2 class="card-title text-xl mb-4">Edit Menu</h2>

            <form action="{{ route('menus.update', $menu->id_menu) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')

                <div class="form-control">
                    <label class="label"><span class="label-text">ID Menu</span></label>
                    <input type="text" value="{{ $menu->id_menu }}" disabled class="input input-bordered input-sm bg-base-200">
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text">Nama Menu</span></label>
                    <input type="text" name="nama_menu" value="{{ $menu->nama_menu }}" required class="input input-bordered input-sm">
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text">Kategori</span></label>
                    <input type="text" name="kategori" value="{{ $menu->kategori }}" required class="input input-bordered input-sm">
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text">Harga (Rp)</span></label>
                    <input type="number" name="harga_menu" value="{{ $menu->harga_menu }}" required class="input input-bordered input-sm">
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text">Detail Menu</span></label>
                    <textarea name="detail_menu" rows="3" class="textarea textarea-bordered textarea-sm">{{ $menu->detail_menu }}</textarea>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text">Gambar Menu</span></label>
                    @if($menu->gambar_menu)
                    <div class="avatar mb-2">
                        <div class="w-20 rounded-lg">
                            <img src="{{ asset('storage/' . $menu->gambar_menu) }}">
                        </div>
                    </div>
                    @endif
                    <input type="file" name="gambar_menu" accept="image/*" class="file-input file-input-bordered file-input-sm w-full">
                    <label class="label"><span class="label-text-alt text-base-content/50">Kosongkan jika tidak ingin mengubah gambar</span></label>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="submit" class="btn btn-neutral flex-1">Simpan Perubahan</button>
                    <a href="{{ route('menus.index') }}" class="btn btn-ghost flex-1">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection