<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\KategoriTransaksi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Exports\Keuangan\TransaksiExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi Query Dasar beserta Relasinya
        $query = Transaksi::with(['kategori', 'siswa.kelas']);

        // 2. LOGIKA FILTER DINAMIS
        // Filter Berdasarkan Rentang Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // Filter Berdasarkan Jenis (Pemasukan / Pengeluaran)
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter Berdasarkan Kategori Transaksi
        if ($request->filled('kategori_transaksi_id')) {
            $query->where('kategori_transaksi_id', $request->kategori_transaksi_id);
        }

        // Filter Berdasarkan Kata Kunci (No Transaksi, Keterangan, atau Nama Siswa)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('no_transaksi', 'like', "%{$keyword}%")
                  ->orWhere('keterangan', 'like', "%{$keyword}%")
                  ->orWhereHas('siswa', function($sq) use ($keyword) {
                      $sq->where('nama_siswa', 'like', "%{$keyword}%");
                  });
            });
        }

        // 3. HITUNG RINGKASAN BOX (Menggunakan teknik 'clone' agar nominal box ikut berubah sesuai filter)
        $totalPemasukan = (clone $query)->where('jenis', 'Pemasukan')->sum('nominal');
        $totalPengeluaran = (clone $query)->where('jenis', 'Pengeluaran')->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // 4. BATASI TAMPILAN DATA & KUNCI QUERY FILTER DI PAGINATION
        // Kita urutkan berdasarkan tanggal terbaru, lalu id terbaru, batasi 10 data per halaman
        $transaksi = $query->latest('tanggal')
                           ->latest('id')
                           ->paginate(10)
                           ->appends($request->all()); // <-- Kunci filter agar tidak hilang saat ganti halaman

        // Ambil data kategori untuk pilihan di dropdown filter
        $kategori = KategoriTransaksi::all();

        return view('keuangan.transaksi.index', compact('transaksi', 'kategori', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir'));
    }

    public function create()
    {
        $kategori = KategoriTransaksi::all();
        $siswa = Siswa::with('kelas')->orderBy('nama_siswa', 'asc')->get();

        // Daftar bulan untuk pilihan pembayaran SPP
        $daftarBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return view('keuangan.transaksi.create', compact('kategori', 'siswa', 'daftarBulan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:Pemasukan,Pengeluaran',
            'kategori_transaksi_id' => 'required|exists:kategori_transaksi,id',
            'nominal' => 'required|numeric|min:0',
            'siswa_id' => 'nullable|exists:siswa,id',
            'untuk_bulan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        // Auto-generate Nomor Transaksi: TRX-20260708-XXXXX
        $tanggalFormat = date('Ymd', strtotime($request->tanggal));
        $randomString = strtoupper(bin2hex(random_bytes(3)));
        $noTransaksi = 'TRX-' . $tanggalFormat . '-' . $randomString;

        Transaksi::create([
            'no_transaksi' => $noTransaksi,
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'kategori_transaksi_id' => $request->kategori_transaksi_id,
            'siswa_id' => $request->jenis == 'Pemasukan' ? $request->siswa_id : null,
            'untuk_bulan' => $request->jenis == 'Pemasukan' ? $request->untuk_bulan : null,
            'user_id' => Auth::id(), // Bendahara yang sedang login
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('keuangan.transaksi.index')->with('success', 'Transaksi berhasil dicatat!');
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return back()->with('success', 'Transaksi berhasil dihapus!');
    }

    // Fungsi Tambahan untuk Fitur Otomatis Ambil SPP Siswa lewat AJAX
    public function getSppSiswa($id)
    {
        $siswa = Siswa::find($id);
        if ($siswa) {
            return response()->json(['nominal_spp' => $siswa->nominal_spp]);
        }
        return response()->json(['nominal_spp' => 0], 404);
    }

    public function exportExcel(Request $request)
{
    $namaFile = 'Laporan_Kas_' . date('Ymd_His') . '.xlsx';
    return Excel::download(new TransaksiExport($request), $namaFile);
}

public function exportPdf(Request $request)
{
    $query = Transaksi::with(['kategori', 'siswa.kelas']);

    // Terapkan filter yang sama
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
    }
    if ($request->filled('jenis')) {
        $query->where('jenis', $request->jenis);
    }
    if ($request->filled('kategori_transaksi_id')) {
        $query->where('kategori_transaksi_id', $request->kategori_transaksi_id);
    }
    if ($request->filled('keyword')) {
        $keyword = $request->keyword;
        $query->where(function($q) use ($keyword) {
            $q->where('no_transaksi', 'like', "%{$keyword}%")
              ->orWhere('keterangan', 'like', "%{$keyword}%")
              ->orWhereHas('siswa', function($sq) use ($keyword) {
                  $sq->where('nama_siswa', 'like', "%{$keyword}%");
              });
        });
    }

    $transaksi = $query->latest('tanggal')->latest('id')->get();

    $totalPemasukan = (clone $query)->where('jenis', 'Pemasukan')->sum('nominal');
    $totalPengeluaran = (clone $query)->where('jenis', 'Pengeluaran')->sum('nominal');
    $saldoAkhir = $totalPemasukan - $totalPengeluaran;

    // UBAH BAGIAN INI (Ganti PDF menjadi Pdf)
    $pdf = Pdf::loadView('keuangan.transaksi.pdf', compact('transaksi', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir', 'request'));

    return $pdf->setPaper('a4', 'portrait')->stream('Laporan_Keuangan_Kas.pdf');
}
}
