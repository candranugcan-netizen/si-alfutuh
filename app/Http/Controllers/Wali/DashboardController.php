<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrangTua;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Cari data orang tua berdasarkan user_id yang sedang login
        $orangTua = OrangTua::where('user_id', $user->id)->first();

        if (!$orangTua) {
            abort(403, 'Akses ditolak. Anda tidak terdaftar sebagai Wali Murid.');
        }

        // Filter bulan & tahun untuk Absensi (Default: Bulan Ini)
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // Ambil semua anak dari orang tua ini beserta seluruh riwayatnya, TERMASUK ABSENSI
        $siswaList = Siswa::with([
            'kelas',
            'perkembanganAlquran' => function($q) {
                $q->latest('tanggal')->take(5);
            },
            'perkembanganAisma' => function($q) {
                $q->latest('tanggal')->take(5);
            },
            'erapor' => function($q) {
                $q->with('tahunAjaran')->latest();
            },
            'transaksi.kategoriTransaksi',

            'absensi' => function($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun)
                  ->orderBy('tanggal', 'desc');
            }
        ])
        ->where('orang_tua_id', $orangTua->id)
        ->get();

        return view('wali.index', compact('orangTua', 'siswaList', 'bulan', 'tahun'));
    }
}
