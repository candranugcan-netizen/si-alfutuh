<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangan = Ruangan::orderBy('nama_ruangan', 'asc')->get();
        return view('inventaris.ruangan.index', compact('ruangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:255|unique:ruangan,nama_ruangan',
            'keterangan' => 'nullable|string'
        ]);

        Ruangan::create($request->all());
        return back()->with('success', 'Data ruangan berhasil ditambahkan!');
    }

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();
        return back()->with('success', 'Data ruangan berhasil dihapus.');
    }
}
