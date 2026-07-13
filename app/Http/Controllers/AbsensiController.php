<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Support\Facades\Gate;

use App\Exports\AbsensiExport;
use App\Exports\AbsensiBulananExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensiController extends Controller
{
    // Menampilkan halaman input absensi untuk Guru
    public function index(Request $request)
    {
        $kelas = Kelas::all();
        $selectedKelas = $request->kelas_id;
        $tanggal = $request->tanggal ?? date('Y-m-d');
        $siswas = [];

        if ($selectedKelas) {
            // KOREKSI: Tambahkan filter agar siswa yang Lulus tidak tampil
            $siswas = Siswa::where('kelas_id', $selectedKelas)
                ->where('status_siswa', '!=', 'Lulus') // <--- Tambah baris ini
                ->with(['absensi' => function($query) use ($tanggal) {
                    $query->where('tanggal', $tanggal);
                }])->get();
        }

        return view('master.absensi.index', compact('kelas', 'selectedKelas', 'tanggal', 'siswas'));
    }

    // Menyimpan data absensi secara massal (Bulk Save)
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kehadiran' => 'required|array',
        ]);

        $tanggal = $request->tanggal;

        foreach ($request->kehadiran as $siswa_id => $status) {
            // KUNCI UTAMA: Gunakan updateOrCreate agar tidak terjadi duplikasi absen di tanggal yang sama
            Absensi::updateOrCreate(
                ['siswa_id' => $siswa_id, 'tanggal' => $tanggal],
                [
                    'status' => $status,
                    'keterangan' => $request->keterangan[$siswa_id] ?? null
                ]
            );
        }

        return back()->with('success', 'Data absensi tanggal ' . date('d/m/Y', strtotime($tanggal)) . ' berhasil disimpan!');
    }

    // ==========================================
    // FUNGSI BARU UNTUK EXPORT EXCEL DAN PDF
    // ==========================================

    public function exportExcel(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required',
            'tanggal' => 'required|date'
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);
        $namaFile = 'Absensi_' . str_replace(' ', '_', $kelas->nama_kelas) . '_' . $request->tanggal . '.xlsx';

        return Excel::download(new AbsensiExport($request->kelas_id, $request->tanggal), $namaFile);
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required',
            'tanggal' => 'required|date'
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);
        $tanggal = $request->tanggal;
        $siswas = Siswa::where('kelas_id', $request->kelas_id)
            ->with(['absensi' => function($query) use ($tanggal) {
                $query->where('tanggal', $tanggal);
            }])->get();

        // Me-load view khusus untuk PDF
        $pdf = Pdf::loadView('master.absensi.pdf', compact('kelas', 'tanggal', 'siswas'));

        $namaFile = 'Absensi_' . str_replace(' ', '_', $kelas->nama_kelas) . '_' . $request->tanggal . '.pdf';

        // return $pdf->stream($namaFile); // Gunakan ->stream() jika ingin preview dulu di browser
        return $pdf->download($namaFile); // Langsung Download
    }

    // FUNGSI BARU UNTUK REKAP BULANAN
    public function exportBulananExcel(Request $request)
    {
        $request->validate(['kelas_id_rekap' => 'required', 'bulan' => 'required', 'tahun' => 'required']);

        $kelas = Kelas::findOrFail($request->kelas_id_rekap);
        $namaFile = 'Rekap_Absensi_' . str_replace(' ', '_', $kelas->nama_kelas) . '_' . $request->bulan . '_' . $request->tahun . '.xlsx';

        return Excel::download(new AbsensiBulananExport($request->kelas_id_rekap, $request->bulan, $request->tahun), $namaFile);
    }

    public function exportBulananPdf(Request $request)
    {
        $request->validate(['kelas_id_rekap' => 'required', 'bulan' => 'required', 'tahun' => 'required']);

        $kelas = Kelas::findOrFail($request->kelas_id_rekap);
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // KOREKSI: Tambahkan filter agar siswa yang Lulus tidak masuk laporan PDF
        $siswas = Siswa::where('kelas_id', $request->kelas_id_rekap)
            ->where('status_siswa', '!=', 'Lulus') // <--- Tambah baris ini
            ->get()
            ->map(function($siswa) use ($bulan, $tahun) {
                $absensi = $siswa->absensi()->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
                $siswa->total_hadir = $absensi->where('status', 'Hadir')->count();
                $siswa->total_sakit = $absensi->where('status', 'Sakit')->count();
                $siswa->total_izin  = $absensi->where('status', 'Izin')->count();
                $siswa->total_alfa  = $absensi->where('status', 'Alfa')->count();
                return $siswa;
            });

        $pdf = Pdf::loadView('master.absensi.pdf_bulanan', compact('kelas', 'bulan', 'tahun', 'siswas'));
        $namaFile = 'Rekap_Absensi_' . str_replace(' ', '_', $kelas->nama_kelas) . '_' . $bulan . '_' . $tahun . '.pdf';

        return $pdf->download($namaFile);
    }
}
