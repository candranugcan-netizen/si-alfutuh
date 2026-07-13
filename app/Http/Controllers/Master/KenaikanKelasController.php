<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Http\Requests\Master\ProsesKenaikanKelasRequest;
use App\Services\KenaikanKelasService;

class KenaikanKelasController extends Controller
{
    protected $kenaikanKelasService;

    public function __construct(KenaikanKelasService $kenaikanKelasService)
    {
        $this->kenaikanKelasService = $kenaikanKelasService;
    }

    public function index(Request $request)
    {
        $kelasId = $request->kelas_id;
        $kelasList = Kelas::all();
        $tahunAjaranList = TahunAjaran::all();

        $siswaList = [];
        // Jika admin sudah memilih filter kelas, ambil siswa di kelas tersebut yang berstatus Aktif
        if ($kelasId) {
            $siswaList = Siswa::where('kelas_id', $kelasId)
                              ->where('status_siswa', 'Aktif')
                              ->orderBy('nama_siswa', 'asc')
                              ->get();
        }

        return view('master.kenaikan_kelas.index', compact('kelasList', 'tahunAjaranList', 'siswaList', 'kelasId'));
    }

    public function process(ProsesKenaikanKelasRequest $request)
    {
        // Logika bisnis utama sudah ditangani oleh Service dengan aman
        $this->kenaikanKelasService->prosesMutasiMassal($request->validated());

        return redirect()->back()->with('success', 'Proses mutasi kelas berhasil dieksekusi secara massal!');
    }
}
