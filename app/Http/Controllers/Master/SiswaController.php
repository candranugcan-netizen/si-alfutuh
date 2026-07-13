<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Http\Requests\Master\StoreSiswaRequest;
use App\Http\Requests\Master\UpdateSiswaRequest;
use App\Services\SiswaService;
use Illuminate\Support\Facades\Gate;

use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class SiswaController extends Controller
{
    protected $siswaService;

    public function __construct(SiswaService $siswaService)
    {
        $this->siswaService = $siswaService;
    }

    public function index(Request $request)
    {
        // Proteksi bawaan hak akses Gate Anda
        Gate::authorize('viewAny', Siswa::class);

        // Membuka query builder dengan Eager Loading relasi kelas dan orangTua
        $query = Siswa::with(['kelas', 'orangTua']);

        // [FITUR BARU] 1. Jalankan Filter Berdasarkan Status Siswa
        if ($request->filled('status')) {
            $query->where('status_siswa', $request->status);
        }

        // [FITUR BARU] 2. Jalankan Filter Berdasarkan ID Kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // [FITUR BARU] 3. Jalankan Pencarian Berdasarkan Nama atau NIS
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_siswa', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        // [FITUR BARU] 4. Gunakan Pagination (10 data per halaman) & kunci pencarian di URL
        $siswas = $query->latest()->paginate(10)->withQueryString();

        // Mengambil semua data kelas untuk ditampilkan di dropdown filter
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        // Kembalikan ke halaman view bawaan Anda dengan data lengkap
        return view('master.siswa.index', compact('siswas', 'kelas'));
    }

    public function create()
    {
        Gate::authorize('create', Siswa::class);
        $kelas = Kelas::all();
        return view('master.siswa.create', compact('kelas'));
    }

    public function store(StoreSiswaRequest $request)
    {
        Gate::authorize('create', Siswa::class);
        $this->siswaService->createSiswa($request->validated());
        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        Gate::authorize('update', $siswa);
        $kelas = Kelas::all();
        return view('master.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa)
    {
        Gate::authorize('update', $siswa);
        $this->siswaService->updateSiswa($siswa, $request->validated());
        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        Gate::authorize('delete', $siswa);
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil dihapus.');
    }

    public function exportExcel()
    {
        Gate::authorize('viewAny', Siswa::class);
        return Excel::download(new SiswaExport, 'data-siswa-' . date('Y-m-d') . '.xlsx');
    }

    public function importExcel(Request $request)
    {
        Gate::authorize('create', Siswa::class);
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        Excel::import(new SiswaImport, $request->file('file_excel'));
        return redirect()->route('siswa.index')->with('success', 'Data Siswa & Akun Orang Tua berhasil di-import.');
    }
}
