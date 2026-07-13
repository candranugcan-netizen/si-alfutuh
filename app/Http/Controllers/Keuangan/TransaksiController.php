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
        $query = Transaksi::with(['kategori', 'siswa.kelas', 'sumberDana']);

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

        // FILTER BARU: Filter Berdasarkan Pos Kantong Dana (Kategori Pemasukan)
        if ($request->filled('pos_dana')) {
            $posDana = $request->pos_dana;
            $query->where(function($q) use ($posDana) {
                $q->where(function($p) use ($posDana) {
                    $p->where('jenis', 'Pemasukan')->where('kategori_transaksi_id', $posDana);
                })->orWhere(function($p) use ($posDana) {
                    $p->where('jenis', 'Pengeluaran')->where('sumber_dana_id', $posDana);
                });
            });
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

        // 3. HITUNG RINGKASAN BOX DINAMIS
        // Jika filter pos_dana aktif, nominal box dihitung khusus pos dana tersebut
        if ($request->filled('pos_dana')) {
            $posDana = $request->pos_dana;

            // Hitung uang masuk khusus pos tersebut
            $qPemasukan = Transaksi::where('jenis', 'Pemasukan')->where('kategori_transaksi_id', $posDana);
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $qPemasukan->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }
            $totalPemasukan = $qPemasukan->sum('nominal');

            // Hitung pengeluaran yang memotong pos tersebut
            $qPengeluaran = Transaksi::where('jenis', 'Pengeluaran')->where('sumber_dana_id', $posDana);
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $qPengeluaran->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }
            $totalPengeluaran = $qPengeluaran->sum('nominal');
        } else {
            // Kas Umum Gabungan Sekolah
            $totalPemasukan = (clone $query)->where('jenis', 'Pemasukan')->sum('nominal');
            $totalPengeluaran = (clone $query)->where('jenis', 'Pengeluaran')->sum('nominal');
        }

        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // 4. BATASI TAMPILAN DATA & KUNCI QUERY FILTER DI PAGINATION
        $transaksi = $query->latest('tanggal')
                           ->latest('id')
                           ->paginate(10)
                           ->appends($request->all());

        // Ambil data untuk dropdown filter biasa
        $kategori = KategoriTransaksi::all();

        // Ambil kategori khusus Pemasukan sebagai pilihan Pos Kantong Dana
        $kategoriPemasukan = KategoriTransaksi::where('jenis', 'Pemasukan')->orderBy('nama_kategori', 'asc')->get();

        return view('keuangan.transaksi.index', compact('transaksi', 'kategori', 'kategoriPemasukan', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir'));
    }

    public function create()
    {
        $kategori = KategoriTransaksi::all();
        // Ambil data kategori pemasukan untuk dropdown sumber dana
        $kategoriPemasukan = KategoriTransaksi::where('jenis', 'Pemasukan')->orderBy('nama_kategori', 'asc')->get();
        $siswa = Siswa::with('kelas')->orderBy('nama_siswa', 'asc')->get();

        $daftarBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return view('keuangan.transaksi.create', compact('kategori', 'kategoriPemasukan', 'siswa', 'daftarBulan'));
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
        'sumber_dana_id' => 'nullable|exists:kategori_transaksi,id',
    ]);

    // ================= VALIDASI SALDO KANTONG DANA =================
    if ($request->jenis == 'Pengeluaran') {
        if ($request->filled('sumber_dana_id')) {
            // 1. Jika memotong Kantong Dana Spesifik (Misal: SPP, Seragam)
            $posDana = $request->sumber_dana_id;

            $totalPemasukanPos = Transaksi::where('jenis', 'Pemasukan')->where('kategori_transaksi_id', $posDana)->sum('nominal');
            $totalPengeluaranPos = Transaksi::where('jenis', 'Pengeluaran')->where('sumber_dana_id', $posDana)->sum('nominal');
            $saldoPosSaatIni = $totalPemasukanPos - $totalPengeluaranPos;

            if ($request->nominal > $saldoPosSaatIni) {
                return back()->withInput()->withErrors([
                    'nominal' => 'Gagal! Saldo pada Pos Dana terpilih tidak mencukupi. Saldo saat ini hanya Rp ' . number_format($saldoPosSaatIni, 0, ',', '.')
                ]);
            }
        } else {
            // 2. Jika memotong Kas Umum / Bebas (sumber_dana_id kosong)
            // Kita hitung Kas Global Sekolah (Semua Pemasukan - Semua Pengeluaran)
            $totalPemasukanGlobal = Transaksi::where('jenis', 'Pemasukan')->sum('nominal');
            $totalPengeluaranGlobal = Transaksi::where('jenis', 'Pengeluaran')->sum('nominal');
            $saldoGlobalSaatIni = $totalPemasukanGlobal - $totalPengeluaranGlobal;

            if ($request->nominal > $saldoGlobalSaatIni) {
                return back()->withInput()->withErrors([
                    'nominal' => 'Gagal! Saldo Kas Umum Sekolah tidak mencukupi. Saldo Kas Umum saat ini Rp ' . number_format($saldoGlobalSaatIni, 0, ',', '.')
                ]);
            }
        }
    }
    // ===============================================================

    // Auto-generate Nomor Transaksi
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
        'user_id' => Auth::id(),
        'nominal' => $request->nominal,
        'keterangan' => $request->keterangan,
        'sumber_dana_id' => $request->jenis == 'Pengeluaran' ? $request->sumber_dana_id : null,
    ]);

    return redirect()->route('keuangan.transaksi.index')->with('success', 'Transaksi berhasil dicatat!');
}

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return back()->with('success', 'Transaksi berhasil dihapus!');
    }

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
        $query = Transaksi::with(['kategori', 'siswa.kelas', 'sumberDana']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('kategori_transaksi_id')) {
            $query->where('kategori_transaksi_id', $request->kategori_transaksi_id);
        }
        if ($request->filled('pos_dana')) {
            $posDana = $request->pos_dana;
            $query->where(function($q) use ($posDana) {
                $q->where(function($p) use ($posDana) {
                    $p->where('jenis', 'Pemasukan')->where('kategori_transaksi_id', $posDana);
                })->orWhere(function($p) use ($posDana) {
                    $p->where('jenis', 'Pengeluaran')->where('sumber_dana_id', $posDana);
                });
            });
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

        if ($request->filled('pos_dana')) {
            $posDana = $request->pos_dana;

            $qPemasukan = Transaksi::where('jenis', 'Pemasukan')->where('kategori_transaksi_id', $posDana);
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $qPemasukan->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }
            $totalPemasukan = $qPemasukan->sum('nominal');

            $qPengeluaran = Transaksi::where('jenis', 'Pengeluaran')->where('sumber_dana_id', $posDana);
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $qPengeluaran->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }
            $totalPengeluaran = $qPengeluaran->sum('nominal');
        } else {
            $totalPemasukan = (clone $query)->where('jenis', 'Pemasukan')->sum('nominal');
            $totalPengeluaran = (clone $query)->where('jenis', 'Pengeluaran')->sum('nominal');
        }
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        $pdf = Pdf::loadView('keuangan.transaksi.pdf', compact('transaksi', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir', 'request'));

        return $pdf->setPaper('a4', 'portrait')->stream('Laporan_Keuangan_Kas.pdf');
    }
}
