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
    public function index()
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


        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // Asumsi: Kita mencari kategori yang namanya mengandung kata 'SPP'
        $kategoriSpp = KategoriTransaksi::where('nama_kategori', 'like', '%SPP%')->first();

         $siswaSudahBayarSpp = 0;
        if ($kategoriSpp) {
            // Menghitung jumlah siswa unik yang sudah ada transaksi SPP bulan ini
            $siswaSudahBayarSpp = Transaksi::where('kategori_transaksi_id', $kategoriSpp->id)
                ->whereMonth('tanggal', $bulanIni) // Sesuaikan 'tanggal' jika nama kolom di DB Anda 'tanggal_bayar' dsb.
                ->whereYear('tanggal', $tahunIni)
                ->distinct('siswa_id')
                ->count('siswa_id');
        }

            // Siswa yang belum bayar adalah Total Siswa Aktif dikurangi yang sudah bayar
        $siswaBelumBayarSpp = $totalSiswa - $siswaSudahBayarSpp;

        return view('dashboard', compact(
            'totalSiswa', 'totalGuru', 'totalKelas', 'totalAlumni',
            'siswaLaki', 'siswaPerempuan',
            'labelKelas', 'dataSiswaPerKelas',
            'siswaSudahBayarSpp', 'siswaBelumBayarSpp'
        ));


    }
}
