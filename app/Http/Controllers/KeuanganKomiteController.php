<?php

namespace App\Http\Controllers;

use App\Models\KeuanganKomite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KeuanganKomiteController extends Controller
{
    public function index()
    {
        $transaksi = KeuanganKomite::with('komite')
            ->latest('tanggal')
            ->paginate(10);

        // Perhitungan ringkasan kas
        $totalPemasukan = KeuanganKomite::where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $totalPengeluaran = KeuanganKomite::where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        return view('komite.keuangan.index', compact('transaksi', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:pemasukan,pengeluaran',
            'kategori' => 'required|string|max:50',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'bukti_nota' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $komite = Auth::user()->komite;

        if (!$komite) {
            return back()->with('error', 'Profil Komite belum terhubung dengan akun ini.');
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_nota')) {
            $buktiPath = $request->file('bukti_nota')->store('bukti_komite', 'public');
        }

        $komite->keuangan()->create([
            'tanggal' => $request->tanggal,
            'jenis_transaksi' => $request->jenis_transaksi,
            'kategori' => $request->kategori,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'bukti_nota' => $buktiPath,
        ]);

        return redirect()->route('keuangan-komite.index')->with('success', 'Catatan keuangan berhasil disimpan.');
    }

    public function destroy(KeuanganKomite $keuanganKomite)
    {
        if ($keuanganKomite->bukti_nota) {
            Storage::disk('public')->delete($keuanganKomite->bukti_nota);
        }

        $keuanganKomite->delete();

        return redirect()->route('keuangan-komite.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
