<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\KategoriBarang;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Exports\InventarisExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class InventarisController extends Controller
{
    public function index()
    {
        // Mengambil data inventaris beserta relasi kategori dan ruangan
        $inventaris = Inventaris::with(['kategori', 'ruangan'])->latest()->get();
        return view('inventaris.pencatatan.index', compact('inventaris'));
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

        // Auto-Generate Kode Barang (Contoh: INV-2026-X8B9)
        $tahun = date('Y');
        $randomStr = strtoupper(Str::random(4));
        $kodeBarang = 'INV-' . $tahun . '-' . $randomStr;

        Inventaris::create([
            'kode_barang' => $kodeBarang,
            'kategori_barang_id' => $request->kategori_barang_id,
            'ruangan_id' => $request->ruangan_id,
            'nama_barang' => $request->nama_barang,
            'merk' => $request->merk,
            'jumlah_total' => $request->jumlah_total,
            'jumlah_tersedia' => $request->jumlah_total, // Default sama dengan total saat baru ditambah
            'kondisi' => $request->kondisi,
            'sumber_dana' => $request->sumber_dana,
            'tanggal_pengadaan' => $request->tanggal_pengadaan,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('inventaris.pencatatan.index')->with('success', 'Data Barang Baru Berhasil Ditambahkan!');
    }

    public function destroy(Inventaris $inventari) // Parameter default resource Laravel
    {
        $inventari->delete();
        return back()->with('success', 'Data barang berhasil dihapus.');
    }

    public function cetakQr()
    {
        // Mengambil semua data inventaris untuk dicetak labelnya
        $inventaris = Inventaris::orderBy('nama_barang', 'asc')->get();

        return view('inventaris.pencatatan.cetak_qr', compact('inventaris'));
    }

    public function exportExcel()
    {
        return Excel::download(new InventarisExport, 'Laporan_Inventaris_Sekolah.xlsx');
    }

    public function exportPdf()
    {
        // Ambil data dan urutkan berdasarkan kategori
        $inventaris = Inventaris::with(['kategori', 'ruangan'])
                        ->orderBy('kategori_barang_id', 'asc')
                        ->get();

        // setPaper('a4', 'landscape') agar tabel yang panjang muat di kertas
        $pdf = Pdf::loadView('inventaris.pencatatan.pdf', compact('inventaris'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Inventaris_Sekolah.pdf');
    }
}
