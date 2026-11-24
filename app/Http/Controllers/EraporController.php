<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Erapor;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Services\EraporService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EraporController extends Controller
{
    protected $eraporService;

    public function __construct(EraporService $eraporService)
    {
        $this->eraporService = $eraporService;
    }

    /**
     * Halaman Utama Pengelolaan e-Rapor
     */
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();
        $tahunAjaranList = TahunAjaran::orderBy('nama', 'desc')->get();

        $tahunAktif = TahunAjaran::where('is_active', true)->first() ?? $tahunAjaranList->first();

        $kelasId = $request->kelas_id ?? ($kelasList->first()->id ?? null);
        $tahunAjaranId = $request->tahun_ajaran_id ?? ($tahunAktif->id ?? null);
        $semester = $request->semester ?? ($tahunAktif && in_array(strtoupper($tahunAktif->semester), ['1', 'GANJIL', 'I']) ? 'I' : 'II');

        $siswaList = [];
        if ($kelasId) {
            $siswaList = Siswa::with([
                'kelas',
                'erapor' => function ($q) use ($tahunAjaranId, $semester) {
                    $q->where('tahun_ajaran_id', $tahunAjaranId)
                      ->where('semester', $semester);
                }
            ])
            ->where('kelas_id', $kelasId)
            ->where('status_siswa', 'Aktif')
            ->orderBy('nama_siswa', 'asc')
            ->get();
        }

        return view('erapor.index', compact(
            'kelasList',
            'tahunAjaranList',
            'kelasId',
            'tahunAjaranId',
            'semester',
            'siswaList'
        ));
    }

    /**
     * Form Input dan Edit e-Rapor Siswa
     */
    public function edit(Request $request, $siswaId)
    {
        $tahunAjaranId = $request->tahun_ajaran_id;
        $semester = $request->semester;

        $erapor = $this->eraporService->getOrInitErapor($siswaId, $tahunAjaranId, $semester);

        // Kelompokkan nilai indikator per kategori & subkategori untuk tampilan tab form
        $nilaiGrouped = $erapor->nilai->groupBy(['kategori_nama', function ($item) {
            return $item->sub_kategori ?? 'Umum';
        }]);

        $tahunAjaranList = TahunAjaran::orderBy('nama', 'desc')->get();
        $guruList = Guru::orderBy('nama_guru', 'asc')->get();

        return view('erapor.edit', compact('erapor', 'nilaiGrouped', 'tahunAjaranList', 'guruList'));
    }

    /**
     * Simpan Perubahan e-Rapor
     */
    public function update(Request $request, Erapor $erapor)
    {
        try {
            $validatedData = $request->validate([
                'fase' => 'nullable|string|max:50',
                'tanggal_rapor' => 'nullable|date',
                'tempat_cetak' => 'nullable|string|max:100',
                'nama_kepala_sekolah' => 'nullable|string|max:150',
                'nip_kepala_sekolah' => 'nullable|string|max:50',
                'nama_guru_kelas' => 'nullable|string|max:150',
                'nip_guru_kelas' => 'nullable|string|max:50',

                // Biodata siswa di rapor
                'agama' => 'nullable|string|max:30',
                'anak_ke' => 'nullable|string|max:20',
                'alamat' => 'nullable|string',
                'nama_ayah' => 'nullable|string|max:100',
                'agama_ayah' => 'nullable|string|max:30',
                'nama_ibu' => 'nullable|string|max:100',
                'agama_ibu' => 'nullable|string|max:30',

                // Fisik & Kesehatan
                'status_gizi' => 'nullable|string',
                'kondisi_rambut' => 'nullable|string',
                'kondisi_mata' => 'nullable|string',
                'kondisi_telinga' => 'nullable|string',
                'kondisi_hidung' => 'nullable|string',
                'kondisi_mulut' => 'nullable|string',
                'kondisi_kulit' => 'nullable|string',
                'kebersihan_badan' => 'nullable|string',
                'kebersihan_pakaian' => 'nullable|string',
                'berat_badan' => 'nullable|string',
                'tinggi_badan' => 'nullable|string',
                'lingkar_kepala' => 'nullable|string',

                // Kehadiran & Catatan
                'kehadiran_sakit' => 'nullable|integer|min:0',
                'kehadiran_izin' => 'nullable|integer|min:0',
                'kehadiran_alfa' => 'nullable|integer|min:0',
                'catatan_guru' => 'nullable|string',
                'umpan_balik_ortu' => 'nullable|string',

                // Laporan Perkembangan Foto Bukti
                'narasi_agama' => 'nullable|string',
                'foto_agama_1' => 'nullable|image|max:8192',
                'foto_agama_2' => 'nullable|image|max:8192',

                'narasi_jati_diri' => 'nullable|string',
                'foto_jati_diri_1' => 'nullable|image|max:8192',
                'foto_jati_diri_2' => 'nullable|image|max:8192',

                'narasi_literasi_steam' => 'nullable|string',
                'foto_literasi_steam_1' => 'nullable|image|max:8192',
                'foto_literasi_steam_2' => 'nullable|image|max:8192',

                'tema_projek' => 'nullable|string|max:255',
                'narasi_projek' => 'nullable|string',
                'foto_projek_1' => 'nullable|image|max:8192',
                'foto_projek_2' => 'nullable|image|max:8192',

                'status' => 'required|in:Draft,Final',
                'nilai' => 'nullable|array',
                'selected_indikator' => 'nullable|array',
                'indikator_selection_present' => 'nullable',
                'active_tab' => 'nullable|string',
            ]);

            // Fallback nama kepala sekolah & guru kelas jika kosong
            $validatedData['nama_kepala_sekolah'] = !empty($validatedData['nama_kepala_sekolah']) 
                ? $validatedData['nama_kepala_sekolah'] 
                : ($erapor->nama_kepala_sekolah ?: 'Pradita Ovi Agustina, S.Pd');
                
            $validatedData['nama_guru_kelas'] = !empty($validatedData['nama_guru_kelas']) 
                ? $validatedData['nama_guru_kelas'] 
                : ($erapor->nama_guru_kelas ?: 'Guru Kelas');

            // Fallback alamat: jika kosong di form e-rapor, ambil dari data master siswa
            if (empty($validatedData['alamat']) && !empty($erapor->siswa->alamat)) {
                $validatedData['alamat'] = $erapor->siswa->alamat;
            }

            $this->eraporService->updateErapor($erapor, $validatedData, $request->allFiles());

            $activeTab = $request->input('active_tab', 'tab-identitas');

            return redirect()->route('erapor.edit', [
                'siswaId' => $erapor->siswa_id,
                'tahun_ajaran_id' => $erapor->tahun_ajaran_id,
                'semester' => $erapor->semester,
                'tab' => $activeTab
            ])->with('success', 'Berhasil! Seluruh perubahan data e-Rapor dan seleksi indikator telah tersimpan.');
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return redirect()->back()
                ->withErrors($ve->errors())
                ->withInput()
                ->with('error', 'Gagal menyimpan: Ada isian data yang belum valid. Silakan periksa kembali formulir.');
        } catch (\Exception $e) {
            \Log::error('Error saat menyimpan e-Rapor: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan perubahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Data e-Rapor
     */
    public function destroy(Erapor $erapor)
    {
        // Hapus file-file foto jika ada
        $fotoFields = [
            'foto_agama_1', 'foto_agama_2',
            'foto_jati_diri_1', 'foto_jati_diri_2',
            'foto_literasi_steam_1', 'foto_literasi_steam_2',
            'foto_projek_1', 'foto_projek_2',
        ];

        foreach ($fotoFields as $field) {
            if ($erapor->$field && Storage::disk('public')->exists('erapor_foto/' . $erapor->$field)) {
                Storage::disk('public')->delete('erapor_foto/' . $erapor->$field);
            }
        }

        $siswaId = $erapor->siswa_id;
        $erapor->delete();

        return redirect()->route('erapor.index')->with('success', 'Data e-Rapor berhasil dihapus.');
    }

    /**
     * Helper untuk konversi gambar lokal ke base64 agar aman di DomPDF
     */
    public static function imageToBase64($path): ?string
    {
        if (empty($path)) return null;

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            return 'data:image/' . ($type === 'svg' ? 'svg+xml' : $type) . ';base64,' . base64_encode($data);
        }

        return null;
    }

    /**
     * Cetak e-Rapor Indikator Sesuai Lampiran 3 (Cover, Visi Misi, Data Siswa, Tabel Indikator, Kesehatan Fisik, Absensi, Ttd)
     */
    public function cetakIndikator(Erapor $erapor, Request $request)
    {
        $erapor->load([
            'siswa.kelas', 
            'tahunAjaran', 
            'nilai' => function ($q) {
                $q->where('is_selected', true)->orderBy('urutan');
            }
        ]);

        $logoPath = public_path('img/logo_ra.png');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('img/logo.png');
        }
        $logoBase64 = self::imageToBase64($logoPath);

        // Jika request memiliki parameter ?preview=1, tampilkan langsung HTML (Print-friendly view)
        if ($request->has('preview')) {
            return view('erapor.cetak_indikator', compact('erapor', 'logoBase64'));
        }

        $pdf = Pdf::loadView('erapor.cetak_indikator', compact('erapor', 'logoBase64'))
                  ->setPaper('a4', 'portrait')
                  ->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);

        $namaFile = 'eRapor_Indikator_' . str_replace(' ', '_', $erapor->siswa->nama_siswa) . '_Smt_' . $erapor->semester . '.pdf';
        return $pdf->stream($namaFile);
    }

    /**
     * Cetak Laporan Perkembangan dengan Lampiran Foto Bukti Sesuai Lampiran 4 (Format Kurikulum Merdeka RA/PAUD)
     */
    public function cetakLaporanPerkembangan(Erapor $erapor, Request $request)
    {
        $erapor->load(['siswa.kelas', 'tahunAjaran']);

        $logoPath = public_path('img/logo_ra.png');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('img/logo.png');
        }
        $logoBase64 = self::imageToBase64($logoPath);

        // Persiapkan foto bukti perkembangan dalam bentuk base64 jika ada
        $fotoMap = [
            'foto_agama_1' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_agama_1)),
            'foto_agama_2' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_agama_2)),
            'foto_jati_diri_1' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_jati_diri_1)),
            'foto_jati_diri_2' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_jati_diri_2)),
            'foto_literasi_steam_1' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_literasi_steam_1)),
            'foto_literasi_steam_2' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_literasi_steam_2)),
            'foto_projek_1' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_projek_1)),
            'foto_projek_2' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_projek_2)),
        ];

        if ($request->has('preview')) {
            return view('erapor.cetak_perkembangan', compact('erapor', 'logoBase64', 'fotoMap'));
        }

        $pdf = Pdf::loadView('erapor.cetak_perkembangan', compact('erapor', 'logoBase64', 'fotoMap'))
                  ->setPaper('a4', 'portrait')
                  ->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);

        $namaFile = 'Laporan_Perkembangan_Foto_' . str_replace(' ', '_', $erapor->siswa->nama_siswa) . '_Smt_' . $erapor->semester . '.pdf';
        return $pdf->stream($namaFile);
    }

    /**
     * Cetak Bundel Lengkap: Cover + Visi Misi + Data Siswa + Tabel Indikator + Laporan Perkembangan & Foto Bukti + Tanda Tangan
     */
    public function cetakLengkap(Erapor $erapor, Request $request)
    {
        $erapor->load([
            'siswa.kelas', 
            'tahunAjaran', 
            'nilai' => function ($q) {
                $q->where('is_selected', true)->orderBy('urutan');
            }
        ]);

        $logoPath = public_path('img/logo_ra.png');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('img/logo.png');
        }
        $logoBase64 = self::imageToBase64($logoPath);

        $fotoMap = [
            'foto_agama_1' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_agama_1)),
            'foto_agama_2' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_agama_2)),
            'foto_jati_diri_1' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_jati_diri_1)),
            'foto_jati_diri_2' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_jati_diri_2)),
            'foto_literasi_steam_1' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_literasi_steam_1)),
            'foto_literasi_steam_2' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_literasi_steam_2)),
            'foto_projek_1' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_projek_1)),
            'foto_projek_2' => self::imageToBase64(public_path('storage/erapor_foto/' . $erapor->foto_projek_2)),
        ];

        if ($request->has('preview')) {
            return view('erapor.cetak_lengkap', compact('erapor', 'logoBase64', 'fotoMap'));
        }

        $pdf = Pdf::loadView('erapor.cetak_lengkap', compact('erapor', 'logoBase64', 'fotoMap'))
                  ->setPaper('a4', 'portrait')
                  ->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);

        $namaFile = 'eRapor_Lengkap_' . str_replace(' ', '_', $erapor->siswa->nama_siswa) . '_Smt_' . $erapor->semester . '.pdf';
        return $pdf->stream($namaFile);
    }

    /**
     * Tambah Indikator Baru ke e-Rapor Siswa
     */
    public function tambahIndikator(Request $request, Erapor $erapor)
    {
        $validated = $request->validate([
            'kategori_kode' => 'required|string|max:20',
            'sub_kategori' => 'nullable|string|max:100',
            'kode' => 'required|string|max:30',
            'indikator' => 'required|string|max:500',
            'nilai' => 'required|in:-,BB,MB,BSH',
        ]);

        $mapKategori = [
            'A' => 'NILAI AGAMA DAN MORAL (NAM)',
            'B' => 'FISIK MOTORIK',
            'C' => 'KOGNITIF',
            'D' => 'BAHASA',
            'E' => 'SOSIAL EMOSIONAL',
            'F' => 'SENI',
            'G' => 'MUATAN LOKAL / CIRI KHAS',
        ];

        $kategoriNama = $mapKategori[$validated['kategori_kode']] ?? 'LAINNYA';
        // Sub-kategori hanya berlaku untuk kategori A (Agama), selain itu dikosongkan
        $subKategori = ($validated['kategori_kode'] === 'A' && !empty($validated['sub_kategori'])) ? $validated['sub_kategori'] : null;
        $maxUrutan = \App\Models\EraporNilai::where('erapor_id', $erapor->id)->max('urutan') ?? 0;

        \App\Models\EraporNilai::create([
            'erapor_id' => $erapor->id,
            'kategori_kode' => $validated['kategori_kode'],
            'kategori_nama' => $kategoriNama,
            'sub_kategori' => $subKategori,
            'kode' => $validated['kode'],
            'indikator' => $validated['indikator'],
            'nilai' => $validated['nilai'],
            'is_selected' => true,
            'urutan' => $maxUrutan + 1,
        ]);

        return redirect()->back()->with('success', 'Indikator baru berhasil ditambahkan.');
    }

    /**
     * Hapus Butir Indikator dari e-Rapor
     */
    public function hapusIndikator($nilaiId)
    {
        $nilai = \App\Models\EraporNilai::findOrFail($nilaiId);
        $nilai->delete();

        return redirect()->back()->with('success', 'Butir indikator berhasil dihapus.');
    }

    /**
     * Reset Indikator ke Standar Kelas Siswa (Kelas A / Kelas B)
     */
    public function resetIndikator(Erapor $erapor)
    {
        $this->eraporService->ensureIndikatorDefaults($erapor, true);

        return redirect()->back()->with('success', 'Daftar indikator berhasil dimuat ulang sesuai kurikulum kelas siswa.');
    }
}
