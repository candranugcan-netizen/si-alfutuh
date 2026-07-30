<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Master\TahunAjaranController;
use App\Http\Controllers\Master\KelasController;
use App\Http\Controllers\Master\GuruController;
use App\Http\Controllers\Master\SiswaController;
use App\Http\Controllers\Keuangan\KategoriTransaksiController;
use App\Http\Controllers\Keuangan\TransaksiController;
use App\Http\Controllers\Master\KenaikanKelasController;
use App\Http\Controllers\Master\AlumniController;
use App\Http\Controllers\Master\AkademikController;
use App\Http\Controllers\Wali\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PpdbController;
use App\Http\Controllers\MateriParentingController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AbsensiGuruController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\PeminjamanBarangController;


// Jalur tamu
Route::get('/', [FrontController::class, 'index'])->name('home');
Route::post('/ppdb/daftar', [PpdbController::class, 'store'])->name('ppdb.store');
Route::get('/kabar-sekolah', [FrontController::class, 'semuaBerita'])->name('berita.semua');

Route::get('/migrate-database-rahasia', function() {
    try {
        // Tingkatkan batas waktu dan memori (untuk mengatasi timeout)
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '256M');

        // Eksekusi migrasi
        Artisan::call('migrate', ['--force' => true]);

        // Ambil pesan keberhasilan dari Artisan
        $output = Artisan::output();

        return '<h1>Migrasi Berhasil!</h1><pre>' . $output . '</pre>';

    } catch (\Exception $e) {
        // Jika error, tampilkan detail masalahnya
        return '<h1>Migrasi Gagal</h1><p>Pesan Error:</p><pre>' . $e->getMessage() . '</pre>';
    }
});
// Rute untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rute untuk user (sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::resource('master/tahun-ajaran', TahunAjaranController::class)->names('tahun-ajaran');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('master/kelas', KelasController::class)->names('kelas')->parameters(['kelas' => 'kelas']);

    Route::get('master/guru/export-excel', [GuruController::class, 'exportExcel'])->name('guru.export');
    Route::post('master/guru/import-excel', [GuruController::class, 'importExcel'])->name('guru.import');
    Route::resource('master/guru', GuruController::class)->names('guru');
    Route::get('master/siswa/export-excel', [SiswaController::class, 'exportExcel'])->name('siswa.export');
    Route::post('master/siswa/import-excel', [SiswaController::class, 'importExcel'])->name('siswa.import');
    Route::resource('master/siswa', SiswaController::class)->names('siswa')->parameters(['siswa' => 'siswa']);
    Route::resource('berita', BeritaController::class);

    Route::get('/ppdb', [PpdbController::class, 'index'])->name('ppdb.index');
    Route::post('/ppdb/terima/{id}', [PpdbController::class, 'terima'])->name('ppdb.terima');
    Route::post('/ppdb/tolak/{id}', [PpdbController::class, 'tolak'])->name('ppdb.tolak');

    Route::get('/admin/parenting', [MateriParentingController::class, 'indexAdmin'])->name('admin.parenting.index');
    Route::post('/admin/parenting/store', [MateriParentingController::class, 'store'])->name('admin.parenting.store');
    Route::delete('/admin/parenting/delete/{id}', [MateriParentingController::class, 'destroy'])->name('admin.parenting.destroy');

    Route::get('/parenting', [MateriParentingController::class, 'indexOrtu'])->name('ortu.parenting.index');
    Route::get('/parenting/{id}', [MateriParentingController::class, 'showOrtu'])->name('ortu.parenting.show');

    Route::get('/absensi_guru', [AbsensiGuruController::class, 'index'])->name('absensi_guru.index');
    Route::get('/absensi_guru/catat', [AbsensiGuruController::class, 'create'])->name('absensi_guru.create');
    Route::post('/absensi_guru/catat', [AbsensiGuruController::class, 'store'])->name('absensi_guru.store');
    Route::delete('/absensi_guru/{absensi}', [AbsensiGuruController::class, 'destroy'])->name('absensi_guru.destroy');
    Route::get('/absensi-guru/export/excel', [AbsensiGuruController::class, 'exportExcel'])->name('absensi_guru.exportExcel');
    Route::get('/absensi-guru/export/pdf', [AbsensiGuruController::class, 'exportPdf'])->name('absensi_guru.exportPdf');


    Route::prefix('inventaris')->name('inventaris.')->group(function () {

    Route::get('/cetak-qr', [InventarisController::class, 'cetakQr'])->name('cetakQr');
    Route::get('/export-excel', [InventarisController::class, 'exportExcel'])->name('exportExcel');
    Route::get('/export-pdf', [InventarisController::class, 'exportPdf'])->name('exportPdf');

    // Rute Resource otomatis untuk: index, create, store, show, edit, update, dan destroy
    Route::resource('pencatatan', InventarisController::class);
    Route::resource('kategori_barang', KategoriBarangController::class)->only(['index', 'store', 'destroy']);
    Route::resource('ruangan', RuanganController::class)->only(['index', 'store', 'destroy']);

    Route::post('peminjaman/{id}/kembalikan', [PeminjamanBarangController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::resource('peminjaman', PeminjamanBarangController::class)->except(['edit', 'update', 'show']);
    Route::get('peminjaman/export-pdf', [PeminjamanBarangController::class, 'exportPdf'])->name('peminjaman.exportPdf');
    Route::get('peminjaman/{id}/cetak-surat', [PeminjamanBarangController::class, 'cetakSurat'])->name('peminjaman.cetakSurat');
    Route::post('peminjaman/{id}/kembalikan', [PeminjamanBarangController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::resource('peminjaman', PeminjamanBarangController::class)->except(['edit', 'update', 'show']);
    });


    Route::prefix('keuangan')->name('keuangan.')->group(function () {
    // Route Kategori
        Route::resource('kategori', KategoriTransaksiController::class)->except(['create', 'edit', 'show']);

        // Route Jalur AJAX Ambil Data SPP Siswa (Letakkan di atas resource agar tidak bentrok)
        Route::get('transaksi/get-spp/{id}', [TransaksiController::class, 'getSppSiswa'])->name('transaksi.getSpp');

        // Route Transaksi Buku Kas
        Route::resource('transaksi', TransaksiController::class)->except(['edit', 'update', 'show']);
        Route::get('transaksi/export/excel', [TransaksiController::class, 'exportExcel'])->name('transaksi.exportExcel');
        Route::get('transaksi/export/pdf', [TransaksiController::class, 'exportPdf'])->name('transaksi.exportPdf');
    });

    Route::prefix('master')->name('master.')->group(function () {
    // Rute Kenaikan Kelas
        Route::get('/kenaikan-kelas', [KenaikanKelasController::class, 'index'])->name('kenaikan-kelas.index');
        Route::post('/kenaikan-kelas', [KenaikanKelasController::class, 'process'])->name('kenaikan-kelas.process');

        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
        Route::get('/absensi/export/excel', [\App\Http\Controllers\AbsensiController::class, 'exportExcel'])->name('absensi.export.excel');
        Route::get('/absensi/export/pdf', [\App\Http\Controllers\AbsensiController::class, 'exportPdf'])->name('absensi.export.pdf');
                // Tambahkan di dalam group route guru/admin Anda
        Route::get('/absensi/rekap-bulanan/excel', [\App\Http\Controllers\AbsensiController::class, 'exportBulananExcel'])->name('absensi.export_bulanan.excel');
        Route::get('/absensi/rekap-bulanan/pdf', [\App\Http\Controllers\AbsensiController::class, 'exportBulananPdf'])->name('absensi.export_bulanan.pdf');

        Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
        Route::get('/akademik', [AkademikController::class, 'index'])->name('akademik.index');
        Route::post('/akademik/alquran', [AkademikController::class, 'storeAlquran'])->name('akademik.store-alquran');
        Route::post('/akademik/aisma', [AkademikController::class, 'storeAisma'])->name('akademik.store-aisma');
        Route::post('/akademik/kenangan', [AkademikController::class, 'storeKenangan'])->name('akademik.store-kenangan');

        Route::get('/akademik/{siswa}', [AkademikController::class, 'show'])->name('akademik.show');
        Route::get('/akademik/{siswa}/cetak', [AkademikController::class, 'cetakPdf'])->name('akademik.cetak');
        Route::get('/akademik/kelas/{kelas}/cetak', [AkademikController::class, 'cetakRekapKelas'])->name('akademik.cetak-rekap');
        Route::delete('/akademik/alquran/{id}', [AkademikController::class, 'destroyAlquran'])->name('akademik.destroy-alquran');
        Route::delete('/akademik/aisma/{id}', [AkademikController::class, 'destroyAisma'])->name('akademik.destroy-aisma');
        Route::delete('/akademik/kenangan/{id}', [AkademikController::class, 'destroyKenangan'])->name('akademik.destroy-kenangan');


    });

    Route::prefix('wali')->name('wali.')->middleware(['auth'])->group(function () {
        Route::get('/index', [DashboardController::class, 'index'])->name('dashboard');
    });
});
