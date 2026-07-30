<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanBarang;
use App\Models\Inventaris;
use App\Models\RiwayatInventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanBarangController extends Controller
{
    // Halaman daftar sirkulasi peminjaman
    public function index(Request $request)
    {
        $query = PeminjamanBarang::with('inventaris');

        // 1. Pencarian (Nama Peminjam, Peran, Nama Barang, atau Kode Barang)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_peminjam', 'like', "%{$search}%")
                  ->orWhere('peran_peminjam', 'like', "%{$search}%")
                  ->orWhereHas('inventaris', function($qInv) use ($search) {
                      $qInv->where('nama_barang', 'like', "%{$search}%")
                           ->orWhere('kode_barang', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Filter Status Peminjaman (Dipinjam / Dikembalikan)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Filter Tanggal Pinjam
        if ($request->filled('tanggal_pinjam')) {
            $query->whereDate('tanggal_pinjam', $request->tanggal_pinjam);
        }

        // 4. Eksekusi Paginasi (10 data per halaman) & Pertahankan Parameter Filter di URL
        $peminjaman = $query->latest()->paginate(10)->withQueryString();

        return view('inventaris.peminjaman.index', compact('peminjaman'));
    }

    // Form peminjaman barang
    public function create()
    {
        // Hanya tampilkan barang yang stok tersedianya > 0
        $inventaris = Inventaris::where('jumlah_tersedia', '>', 0)->orderBy('nama_barang', 'asc')->get();
        return view('inventaris.peminjaman.create', compact('inventaris'));
    }

    // Simpan transaksi peminjaman baru
    public function store(Request $request)
    {
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'nama_peminjam' => 'required|string|max:255',
            'peran_peminjam' => 'nullable|string|max:255',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'batas_waktu_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'keterangan' => 'nullable|string'
        ]);

        $item = Inventaris::findOrFail($request->inventaris_id);

        // Validasi ketersediaan stok fisik
        if ($request->jumlah_pinjam > $item->jumlah_tersedia) {
            return back()->withInput()->with('error', "Stok barang tidak mencukupi! Hanya tersedia {$item->jumlah_tersedia} unit.");
        }

        // 1. Simpan Transaksi Peminjaman
        PeminjamanBarang::create([
            'inventaris_id' => $item->id,
            'nama_peminjam' => $request->nama_peminjam,
            'peran_peminjam' => $request->peran_peminjam,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'batas_waktu_kembali' => $request->batas_waktu_kembali,
            'status' => 'Dipinjam',
            'keterangan' => $request->keterangan,
        ]);

        // 2. Kurangi Stok Tersedia
        $item->decrement('jumlah_tersedia', $request->jumlah_pinjam);

        // 3. Catat ke Log Riwayat Inventaris
        RiwayatInventaris::create([
            'inventaris_id' => $item->id,
            'jenis_perubahan' => 'Peminjaman',
            'keterangan' => "Dipinjam sebanyak {$request->jumlah_pinjam} unit oleh {$request->nama_peminjam} ({$request->peran_peminjam})",
            'petugas' => Auth::user()->name ?? 'Admin'
        ]);

        return redirect()->route('inventaris.peminjaman.index')->with('success', 'Transaksi peminjaman berhasil dicatat!');
    }

    // Proses pengembalian barang
    public function kembalikan($id)
    {
        $pinjam = PeminjamanBarang::findOrFail($id);

        if ($pinjam->status == 'Dikembalikan') {
            return back()->with('error', 'Barang ini sudah dikembalikan sebelumnya.');
        }

        $item = Inventaris::findOrFail($pinjam->inventaris_id);

        // 1. Update Status Peminjaman
        $pinjam->update([
            'status' => 'Dikembalikan',
            'tanggal_dikembalikan' => date('Y-m-d')
        ]);

        // 2. Kembalikan Stok Tersedia
        $item->increment('jumlah_tersedia', $pinjam->jumlah_pinjam);

        // 3. Catat ke Log Riwayat Inventaris
        RiwayatInventaris::create([
            'inventaris_id' => $item->id,
            'jenis_perubahan' => 'Pengembalian',
            'keterangan' => "Dikembalikan sebanyak {$pinjam->jumlah_pinjam} unit oleh {$pinjam->nama_peminjam}",
            'petugas' => Auth::user()->name ?? 'Admin'
        ]);

        return redirect()->route('inventaris.peminjaman.index')->with('success', 'Barang berhasil dikembalikan!');
    }

    public function destroy($id)
    {
        $pinjam = PeminjamanBarang::findOrFail($id);

        // Jika data dihapus saat status masih 'Dipinjam', kembalikan stoknya terlebih dahulu
        if ($pinjam->status == 'Dipinjam') {
            $item = Inventaris::find($pinjam->inventaris_id);
            if ($item) {
                $item->increment('jumlah_tersedia', $pinjam->jumlah_pinjam);
            }
        }

        $pinjam->delete();
        return back()->with('success', 'Catatan peminjaman berhasil dihapus.');
    }

    // METHOD BARU: Export PDF Seluruh Daftar Peminjaman
    public function exportPdf()
    {
        $peminjaman = PeminjamanBarang::with('inventaris')->latest()->get();

        $pdf = Pdf::loadView('inventaris.peminjaman.daftar_pdf', compact('peminjaman'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Peminjaman_Barang.pdf');
    }

    // METHOD BARU: Cetak Surat Bukti Peminjaman Per Transaksi
    public function cetakSurat($id)
    {
        $peminjaman = PeminjamanBarang::with('inventaris')->findOrFail($id);

        $pdf = Pdf::loadView('inventaris.peminjaman.surat_pdf', compact('peminjaman'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('Surat_Peminjaman_' . $peminjaman->nama_peminjam . '.pdf');
    }
}
