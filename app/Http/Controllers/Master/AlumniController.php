<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\RiwayatKelas;
use Illuminate\Support\Facades\Gate;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        // Opsional: Aktifkan jika Anda menggunakan Policy milik Siswa untuk keamanan
        // Gate::authorize('viewAny', Siswa::class);

        $tahunLulusFilter = $request->tahun_lulus;

        // Ambil data siswa yang berstatus Lulus beserta relasi kelas dan riwayatnya
        $query = Siswa::with(['kelas', 'riwayatKelas.tahunAjaran'])
                      ->where('status_siswa', 'Lulus');

        // Jika admin melakukan filter berdasarkan tahun kelulusan
        if ($tahunLulusFilter) {
            $query->whereHas('riwayatKelas', function ($q) use ($tahunLulusFilter) {
                $q->where('keterangan', 'Lulus')
                  ->whereYear('created_at', $tahunLulusFilter);
            });
        }

        $alumniList = $query->orderBy('nama_siswa', 'asc')->get();

        // Ambil daftar tahun kelulusan yang unik dari tabel riwayat_kelas untuk dropdown filter
        $daftarTahunLulus = RiwayatKelas::where('keterangan', 'Lulus')
            ->selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('master.alumni.index', compact('alumniList', 'daftarTahunLulus', 'tahunLulusFilter'));
    }
}
