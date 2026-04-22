<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::where('user_id', auth()->id())->latest()->get();
        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        return view('menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu'   => 'required|string|max:255',
            'kategori'    => 'required|string|max:100',
            'harga_menu'  => 'required|numeric',
            'detail_menu' => 'nullable|string',
            'gambar_menu' => 'nullable|image|max:2048',
        ]);

        $lastMenu = Menu::where('user_id', auth()->id())
            ->orderByRaw('CAST(SUBSTRING(id_menu, 4) AS UNSIGNED) DESC')
            ->first();
        $lastNumber = $lastMenu ? intval(substr($lastMenu->id_menu, 3)) : 0;
        $newId = 'PKG' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        $gambarPath = null;
        if ($request->hasFile('gambar_menu')) {
            $gambarPath = $request->file('gambar_menu')->store('menus', 'public');
        }

        Menu::create([
            'id_menu'    => $newId,
            'user_id'    => auth()->id(),
            'nama_menu'  => $request->nama_menu,
            'kategori'   => $request->kategori,
            'harga_menu' => $request->harga_menu,
            'detail_menu' => $request->detail_menu,
            'gambar_menu' => $gambarPath,
        ]);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        return view('menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'nama_menu'   => 'required|string|max:255',
            'kategori'    => 'required|string|max:100',
            'harga_menu'  => 'required|numeric',
            'detail_menu' => 'nullable|string',
            'gambar_menu' => 'nullable|image|max:2048',
        ]);

        $gambarPath = $menu->gambar_menu;
        if ($request->hasFile('gambar_menu')) {
            if ($gambarPath) Storage::disk('public')->delete($gambarPath);
            $gambarPath = $request->file('gambar_menu')->store('menus', 'public');
        }

        $menu->update([
            'nama_menu'   => $request->nama_menu,
            'kategori'    => $request->kategori,
            'harga_menu'  => $request->harga_menu,
            'detail_menu' => $request->detail_menu,
            'gambar_menu' => $gambarPath,
        ]);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diupdate!');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->gambar_menu) {
            Storage::disk('public')->delete($menu->gambar_menu);
        }
        $menu->delete();
        return redirect()->route('menus.index')
            ->with('success', 'Menu berhasil dihapus!');
    }
}
