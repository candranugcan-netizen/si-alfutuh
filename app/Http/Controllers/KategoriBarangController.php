<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $kategori = KategoriBarang::orderBy('nama_kategori', 'asc')->get();
        return view('inventaris.kategori_barang.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_barang,nama_kategori',
            'keterangan' => 'nullable|string'
        ]);

        KategoriBarang::create($request->all());
        return back()->with('success', 'Kategori barang berhasil ditambahkan!');
    }

    public function destroy(KategoriBarang $kategori_barang)
    {
        $kategori_barang->delete();
        return back()->with('success', 'Kategori barang berhasil dihapus.');
    }
}
