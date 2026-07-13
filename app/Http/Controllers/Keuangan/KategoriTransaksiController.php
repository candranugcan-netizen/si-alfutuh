<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\KategoriTransaksi;
use Illuminate\Http\Request;

class KategoriTransaksiController extends Controller
{
    public function index()
    {
        $kategori = KategoriTransaksi::orderBy('jenis', 'asc')->latest()->get();
        return view('keuangan.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'jenis' => 'required|in:Pemasukan,Pengeluaran',
        ]);

        KategoriTransaksi::create($request->all());
        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, KategoriTransaksi $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'jenis' => 'required|in:Pemasukan,Pengeluaran',
        ]);

        $kategori->update($request->all());
        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(KategoriTransaksi $kategori)
    {
        // Cegah penghapusan jika sudah dipakai di tabel transaksi
        if ($kategori->transaksi()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena sudah memiliki data transaksi!');
        }

        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
