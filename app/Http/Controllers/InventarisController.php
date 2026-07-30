<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\KategoriBarang;
use App\Models\Ruangan;
use App\Models\RiwayatInventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Exports\InventarisExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class InventarisController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi Query dasar dengan Eager Loading
        $query = Inventaris::with(['kategori', 'ruangan']);

        // 2. Filter Kata Kunci (Nama Barang, Kode Barang, atau Merek)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%");
            });
        }

        // 3. Filter Kategori Barang
        if ($request->filled('kategori_id')) {
            $query->where('kategori_barang_id', $request->kategori_id);
        }

        // 4. Filter Ruangan / Lokasi
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // 5. Filter Kondisi Barang
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // 6. Eksekusi Paginasi (10 data per halaman) & Pertahankan Parameter Filter di URL
        $inventaris = $query->latest()->paginate(10)->withQueryString();

        // 7. Ambil Data Kategori & Ruangan untuk isi Dropdown Filter
        $kategori = KategoriBarang::orderBy('nama_kategori', 'asc')->get();
        $ruangan = Ruangan::orderBy('nama_ruangan', 'asc')->get();

        return view('inventaris.pencatatan.index', compact('inventaris', 'kategori', 'ruangan'));
    }

    public function create()
    {
        $kategori = KategoriBarang::orderBy('nama_kategori', 'asc')->get();
        $ruangan = Ruangan::orderBy('nama_ruangan', 'asc')->get();

        return view('inventaris.pencatatan.create', compact('kategori', 'ruangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_barang_id' => 'required|exists:kategori_barang,id',
            'ruangan_id' => 'nullable|exists:ruangan,id',
            'nama_barang' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'jumlah_total' => 'required|integer|min:1',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'sumber_dana' => 'nullable|string|max:255',
            'tanggal_pengadaan' => 'nullable|date',
            'keterangan' => 'nullable|string'
        ]);

        $tahun = date('Y');
        $randomStr = strtoupper(Str::random(4));
        $kodeBarang = 'INV-' . $tahun . '-' . $randomStr;

        $item = Inventaris::create([
            'kode_barang' => $kodeBarang,
            'kategori_barang_id' => $request->kategori_barang_id,
            'ruangan_id' => $request->ruangan_id,
            'nama_barang' => $request->nama_barang,
            'merk' => $request->merk,
            'jumlah_total' => $request->jumlah_total,
            'jumlah_tersedia' => $request->jumlah_total,
            'kondisi' => $request->kondisi,
            'sumber_dana' => $request->sumber_dana,
            'tanggal_pengadaan' => $request->tanggal_pengadaan,
            'keterangan' => $request->keterangan,
        ]);

        // Catat Log Pengadaan Pertama
        RiwayatInventaris::create([
            'inventaris_id' => $item->id,
            'jenis_perubahan' => 'Pendataan Awal',
            'keterangan' => "Barang pertama kali didaftarkan sebanyak {$item->jumlah_total} unit dengan kondisi {$item->kondisi}.",
            'petugas' => Auth::user()->name ?? 'Admin'
        ]);

        return redirect()->route('inventaris.pencatatan.index')->with('success', 'Data Barang Baru Berhasil Ditambahkan!');
    }

    // --- METHOD DENGAN FITUR EDIT & RIWAYAT (BARU) ---

    public function show($id)
    {
        $item = Inventaris::with(['kategori', 'ruangan', 'riwayatPerubahan', 'riwayatPeminjaman'])->findOrFail($id);
        return view('inventaris.pencatatan.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Inventaris::findOrFail($id);
        $kategori = KategoriBarang::orderBy('nama_kategori', 'asc')->get();
        $ruangan = Ruangan::orderBy('nama_ruangan', 'asc')->get();

        return view('inventaris.pencatatan.edit', compact('item', 'kategori', 'ruangan'));
    }

    public function update(Request $request, $id)
    {
        $item = Inventaris::findOrFail($id);

        $request->validate([
            'kategori_barang_id' => 'required|exists:kategori_barang,id',
            'ruangan_id' => 'nullable|exists:ruangan,id',
            'nama_barang' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'jumlah_total' => 'required|integer|min:1',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'sumber_dana' => 'nullable|string|max:255',
            'tanggal_pengadaan' => 'nullable|date',
            'keterangan' => 'nullable|string'
        ]);

        $logKeterangan = [];

        // 1. Deteksi Mutasi Ruangan
        if ($item->ruangan_id != $request->ruangan_id) {
            $ruanganLama = Ruangan::find($item->ruangan_id)->nama_ruangan ?? 'Belum Ditempatkan';
            $ruanganBaru = Ruangan::find($request->ruangan_id)->nama_ruangan ?? 'Belum Ditempatkan';
            $logKeterangan[] = "Dipindahkan dari [{$ruanganLama}] ke [{$ruanganBaru}]";
        }

        // 2. Deteksi Perubahan Kondisi
        if ($item->kondisi != $request->kondisi) {
            $logKeterangan[] = "Kondisi berubah dari '{$item->kondisi}' menjadi '{$request->kondisi}'";
        }

        // 3. Deteksi Perubahan Jumlah Total
        if ($item->jumlah_total != $request->jumlah_total) {
            $selisih = $request->jumlah_total - $item->jumlah_total;
            $item->jumlah_tersedia += $selisih;
            if ($item->jumlah_tersedia < 0) $item->jumlah_tersedia = 0;
            $logKeterangan[] = "Jumlah total disesuaikan dari {$item->jumlah_total} menjadi {$request->jumlah_total}";
        }

        // Simpan Data Baru
        $item->update([
            'kategori_barang_id' => $request->kategori_barang_id,
            'ruangan_id' => $request->ruangan_id,
            'nama_barang' => $request->nama_barang,
            'merk' => $request->merk,
            'jumlah_total' => $request->jumlah_total,
            'jumlah_tersedia' => $item->jumlah_tersedia,
            'kondisi' => $request->kondisi,
            'sumber_dana' => $request->sumber_dana,
            'tanggal_pengadaan' => $request->tanggal_pengadaan,
            'keterangan' => $request->keterangan,
        ]);

        // Catat ke Log jika ada perubahan
        if (count($logKeterangan) > 0) {
            RiwayatInventaris::create([
                'inventaris_id' => $item->id,
                'jenis_perubahan' => 'Pembaruan Data / Mutasi',
                'keterangan' => implode('; ', $logKeterangan),
                'petugas' => Auth::user()->name ?? 'Admin'
            ]);
        }

        return redirect()->route('inventaris.pencatatan.index')->with('success', 'Data inventaris berhasil diperbarui!');
    }

    // --- END METHOD BARU ---

    public function destroy(Inventaris $inventari)
    {
        $inventari->delete();
        return back()->with('success', 'Data barang berhasil dihapus.');
    }

    public function cetakQr()
    {
        $inventaris = Inventaris::orderBy('nama_barang', 'asc')->get();
        return view('inventaris.pencatatan.cetak_qr', compact('inventaris'));
    }

    public function exportExcel()
    {
        return Excel::download(new InventarisExport, 'Laporan_Inventaris_Sekolah.xlsx');
    }

    public function exportPdf()
    {
        $inventaris = Inventaris::with(['kategori', 'ruangan'])
                        ->orderBy('kategori_barang_id', 'asc')
                        ->get();

        $pdf = Pdf::loadView('inventaris.pencatatan.pdf', compact('inventaris'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Inventaris_Sekolah.pdf');
    }
}
