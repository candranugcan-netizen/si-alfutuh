<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;

class FrontController extends Controller
{
    public function index()
    {
        // Ambil 3 berita terbaru yang berstatus Publish
        $beritaTerbaru = Berita::with('penulis')
                        ->where('status', 'Publish')
                        ->latest()
                        ->take(3)
                        ->get();

        return view('welcome', compact('beritaTerbaru'));
    }

    public function semuaBerita()
    {
        // Menggunakan paginate(9) agar jika ada ratusan berita, otomatis terbagi per 9 data per halaman
        $allBerita = Berita::latest()->paginate(9);

        return view('kabar-sekolah.index', compact('allBerita'));
    }
}
