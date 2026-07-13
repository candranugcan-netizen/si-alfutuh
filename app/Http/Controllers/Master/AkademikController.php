<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreAlquranRequest;
use App\Http\Requests\Master\StoreAismaRequest;
use App\Http\Requests\Master\StoreKenanganRequest;
use App\Services\AkademikService;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\PerkembanganAlquran;
use App\Models\PerkembanganAisma;
use App\Models\AktivitasKenangan;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AkademikController extends Controller
{
    protected $akademikService;

    public function __construct(AkademikService $akademikService)
    {
        $this->akademikService = $akademikService;
    }

    // Halaman Utama Input Nilai & Perkembangan bagi Guru
    public function index(Request $request)
    {
        $kelasId = $request->kelas_id;
        $kelasList = Kelas::all();
        $tahunAjaranList = TahunAjaran::all();

        $siswaList = [];
        if ($kelasId) {
            $siswaList = Siswa::where('kelas_id', $kelasId)
                              ->where('status_siswa', 'Aktif')
                              ->orderBy('nama_siswa', 'asc')
                              ->get();
        }

        return view('master.akademik.index', compact('kelasList', 'tahunAjaranList', 'siswaList', 'kelasId'));
    }

    public function storeAlquran(StoreAlquranRequest $request)
    {
        $this->akademikService->simpanAlquran($request->validated());
        return redirect()->back()->with('success', 'Catatan perkembangan Al-Qur/Iqro berhasil disimpan.');
    }

    public function storeAisma(StoreAismaRequest $request)
    {
        $this->akademikService->simpanAisma($request->validated());
        return redirect()->back()->with('success', 'Catatan perkembangan AISMA berhasil disimpan.');
    }

    public function storeKenangan(StoreKenanganRequest $request)
    {
        $this->akademikService->simpanKenangan($request->validated());
        return redirect()->back()->with('success', 'Foto aktivitas & narasi kenangan berhasil diterbitkan ke Orang Tua.');
    }

    // Menampilkan halaman riwayat khusus 1 siswa
    public function show(Siswa $siswa)
    {
        // Memuat data siswa beserta relasi riwayatnya
        $siswa->load([
            'perkembanganAlquran.guru',
            'perkembanganAisma.guru',
            'aktivitasKenangan.tahunAjaran'
        ]);

        return view('master.akademik.show', compact('siswa'));
    }

    // Fungsi Hapus Al-Quran
    public function destroyAlquran($id)
    {
        PerkembanganAlquran::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Riwayat Al-Qur\'an berhasil dihapus.');
    }

    // Fungsi Hapus AISMA
    public function destroyAisma($id)
    {
        PerkembanganAisma::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Riwayat AISMA berhasil dihapus.');
    }

    // Fungsi Hapus Foto Kenangan
    public function destroyKenangan($id)
    {
        $kenangan = AktivitasKenangan::findOrFail($id);

        // Hapus file fisik foto dari storage public agar tidak memenuhi memori server
        if (Storage::disk('public')->exists('aktivitas_anak/' . $kenangan->foto_aktivitas)) {
            Storage::disk('public')->delete('aktivitas_anak/' . $kenangan->foto_aktivitas);
        }

        $kenangan->delete();
        return redirect()->back()->with('success', 'Foto kenangan beserta narasinya berhasil dihapus.');
    }

    // Fungsi untuk men-generate dan mengunduh PDF Laporan
    public function cetakPdf(Siswa $siswa)
    {
        // Memuat data siswa dan relasinya
        $siswa->load([
            'kelas',
            'perkembanganAlquran.guru',
            'perkembanganAisma.guru',
            'aktivitasKenangan.tahunAjaran'
        ]);

        // Mengatur opsi PDF (kertas A4, posisi Portrait)
        $pdf = Pdf::loadView('master.akademik.cetak', compact('siswa'))->setPaper('a4', 'portrait');

        // Mengunduh file dengan nama otomatis
        $namaFile = 'Laporan_Perkembangan_' . str_replace(' ', '_', $siswa->nama_siswa) . '.pdf';

        // Gunakan ->stream() jika ingin melihat di browser dulu, atau ->download() jika ingin langsung terunduh
        return $pdf->stream($namaFile);
    }

    // Fungsi untuk men-generate PDF Rekapitulasi 1 Kelas
    public function cetakRekapKelas($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);

        // Ambil semua siswa aktif di kelas tersebut beserta data perkembangan terakhirnya
        $siswaList = Siswa::with([
            'perkembanganAlquran' => function($q) {
                $q->latest('tanggal'); // Urutkan dari yang terbaru
            },
            'perkembanganAisma' => function($q) {
                $q->latest('tanggal');
            }
        ])
        ->where('kelas_id', $kelas->id)
        ->where('status_siswa', 'Aktif')
        ->orderBy('nama_siswa', 'asc')
        ->get();

        // Mengatur opsi PDF (kertas A4, posisi Landscape / Mendatar)
        $pdf = Pdf::loadView('master.akademik.cetak_rekap', compact('kelas', 'siswaList'))
                  ->setPaper('a4', 'landscape');

        $namaFile = 'Rekap_Perkembangan_Kelas_' . str_replace(' ', '_', $kelas->nama_kelas) . '.pdf';

        return $pdf->stream($namaFile);
    }
}
