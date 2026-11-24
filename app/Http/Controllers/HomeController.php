<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Transaksi;
use App\Models\KategoriTransaksi;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Data Kartu Ringkasan (Summary Cards)
        $totalSiswa = Siswa::where('status_siswa', 'Aktif')->count();
        $totalGuru = Guru::count();
        $totalKelas = Kelas::count();
        $totalAlumni = Siswa::where('status_siswa', 'Lulus')->count();

        // 2. Data Grafik Pie: Siswa Berdasarkan Jenis Kelamin
        $siswaLaki = Siswa::where('status_siswa', 'Aktif')->where('jenis_kelamin', 'L')->count();
        $siswaPerempuan = Siswa::where('status_siswa', 'Aktif')->where('jenis_kelamin', 'P')->count();

        // 3. Data Grafik Bar: Jumlah Siswa Per Kelas
        $kelas = Kelas::withCount(['siswa' => function($query) {
            $query->where('status_siswa', 'Aktif');
        }])->get();

        $labelKelas = $kelas->pluck('nama_kelas');
        $dataSiswaPerKelas = $kelas->pluck('siswa_count');

        // 4. Data SPP: Default bulan berjalan saat ini, dapat difilter per bulan/tahun
        $daftarBulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $bulanDipilih = (int) $request->input('bulan', Carbon::now()->month);
        $tahunDipilih = (int) $request->input('tahun', Carbon::now()->year);

        $namaBulan = $daftarBulanIndo[$bulanDipilih] ?? Carbon::now()->translatedFormat('F');
        $periodeBulanIni = $namaBulan . ' ' . $tahunDipilih;

        // Kategori SPP
        $kategoriSpp = KategoriTransaksi::where('nama_kategori', 'like', '%SPP%')->first();

        $siswaSudahBayarSpp = 0;
        if ($kategoriSpp) {
            // HANYA menghitung siswa aktif yang telah melunasi SPP untuk periode bulan ini (untuk_bulan = "Bulan Tahun")
            // Anak yang bayar di muka untuk bulan depan otomatis terhitung saat bulan depan tiba (atau saat bulan depan difilter)
            $siswaSudahBayarSpp = Transaksi::where('kategori_transaksi_id', $kategoriSpp->id)
                ->where('untuk_bulan', $periodeBulanIni)
                ->whereHas('siswa', function($q) {
                    $q->where('status_siswa', 'Aktif');
                })
                ->distinct('siswa_id')
                ->count('siswa_id');
        }

        // Siswa yang belum bayar adalah Total Siswa Aktif dikurangi yang sudah bayar
        $siswaBelumBayarSpp = max(0, $totalSiswa - $siswaSudahBayarSpp);

        $daftarTahunPilihan = range(Carbon::now()->year - 1, Carbon::now()->year + 3);

        return view('dashboard', compact(
            'totalSiswa', 'totalGuru', 'totalKelas', 'totalAlumni',
            'siswaLaki', 'siswaPerempuan',
            'labelKelas', 'dataSiswaPerKelas',
            'siswaSudahBayarSpp', 'siswaBelumBayarSpp',
            'periodeBulanIni', 'bulanDipilih', 'tahunDipilih',
            'daftarBulanIndo', 'daftarTahunPilihan'
        ));
    }
}
