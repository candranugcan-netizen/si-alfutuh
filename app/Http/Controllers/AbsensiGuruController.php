<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\User;
use Illuminate\Http\Request;

use App\Exports\AbsensiGuruExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensiGuruController extends Controller
{
    /**
     * Menampilkan daftar absen berdasarkan tanggal
     */
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');

        $absensi = AbsensiGuru::with('user')
                              ->where('tanggal', $tanggal)
                              ->orderBy('waktu_masuk', 'asc')
                              ->get();

        return view('absensi_guru.index', compact('absensi', 'tanggal'));
    }

    /**
     * Menampilkan form input massal untuk admin
     */
    public function create(Request $request)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');

        // Cari ID guru yang SUDAH diabsen pada tanggal tersebut
        $sudahAbsen = AbsensiGuru::where('tanggal', $tanggal)->pluck('user_id')->toArray();

        // Tampilkan hanya guru yang BELUM diabsen pada tanggal tersebut
        // Asumsi: tabel users menggunakan kolom 'name' untuk nama
        $gurus = User::whereHas('guru') // 'guru' adalah nama fungsi relasi di Model User
                 ->whereNotIn('id', $sudahAbsen)
                 ->orderBy('name', 'asc')
                 ->get();

        return view('absensi_guru.create', compact('gurus', 'tanggal'));
    }

    /**
     * Menyimpan data absen massal ke database (Bulk Insert)
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
        ]);

        $tanggal = $request->tanggal;

        foreach ($request->absensi as $data) {
            // Pastikan data memiliki user_id dan status
            if (isset($data['user_id']) && isset($data['status'])) {
                // updateOrCreate digunakan agar tidak terjadi duplikat data
                AbsensiGuru::updateOrCreate(
                    [
                        'user_id' => $data['user_id'],
                        'tanggal' => $tanggal
                    ],
                    [
                        'status' => $data['status'],
                        'waktu_masuk' => $data['waktu_masuk'] ?? null,
                        'waktu_pulang' => $data['waktu_pulang'] ?? null,
                        'keterangan' => $data['keterangan'] ?? null,
                    ]
                );
            }
        }

        return redirect()->route('absensi_guru.index', ['tanggal' => $tanggal])
                         ->with('success', 'Data absensi massal berhasil disimpan!');
    }

    /**
     * Menghapus catatan absen (Jika admin salah klik)
     */
    public function destroy(AbsensiGuru $absensi)
    {
        $absensi->delete();
        return back()->with('success', 'Catatan absensi berhasil dihapus.');
    }

    /**
     * Export Rekap Absensi Bulanan ke Excel
     */
    public function exportExcel(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $namaFile = 'Rekap_Absensi_Guru_' . $bulan . '_' . $tahun . '.xlsx';
        return Excel::download(new AbsensiGuruExport($bulan, $tahun), $namaFile);
    }

    /**
     * Export Rekap Absensi Bulanan ke PDF
     */
    public function exportPdf(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $absensi = AbsensiGuru::with('user')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        // Kelompokkan dan hitung rekap per guru
        $rekap = [];
        foreach ($absensi as $absen) {
            $userId = $absen->user_id;
            if (!isset($rekap[$userId])) {
                $rekap[$userId] = [
                    'nama' => $absen->user->name,
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'alpa' => 0,
                ];
            }

            $status = strtolower($absen->status);
            if (isset($rekap[$userId][$status])) {
                $rekap[$userId][$status]++;
            }
        }

        $pdf = Pdf::loadView('absensi_guru.pdf', compact('rekap', 'bulan', 'tahun'));

        return $pdf->stream('Rekap_Absensi_Guru_' . $bulan . '_' . $tahun . '.pdf');
    }
}
