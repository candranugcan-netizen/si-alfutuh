<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Http\Requests\Master\StoreGuruRequest;
use App\Http\Requests\Master\UpdateGuruRequest;
use App\Services\GuruService;
use Illuminate\Support\Facades\Gate;

use App\Exports\GuruExport;
use App\Imports\GuruImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    protected $guruService;

    public function __construct(GuruService $guruService)
    {
        $this->guruService = $guruService;
    }

    public function index()
    {
        Gate::authorize('viewAny', Guru::class);
        // Eager loading tabel users agar query database lebih ringan dan cepat
        $gurus = Guru::with('user')->latest()->get();
        return view('master.guru.index', compact('gurus'));
    }

    public function create()
    {
        Gate::authorize('create', Guru::class);
        return view('master.guru.create');
    }

    public function store(StoreGuruRequest $request)
    {
        Gate::authorize('create', Guru::class);
        $this->guruService->createGuru($request->validated());
        return redirect()->route('guru.index')->with('success', 'Data Guru & Akun Login berhasil dibuat.');
    }

    public function edit(Guru $guru)
    {
        Gate::authorize('update', $guru);
        return view('master.guru.edit', compact('guru'));
    }

    public function update(UpdateGuruRequest $request, Guru $guru)
    {
        Gate::authorize('update', $guru);
        $this->guruService->updateGuru($guru, $request->validated());
        return redirect()->route('guru.index')->with('success', 'Data Guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        Gate::authorize('delete', $guru);

        // Mengambil user terkait sebelum profile guru dihapus
        $user = $guru->user;
        $guru->delete();
        $user->delete(); // Hapus akun loginnya sekalian

        return redirect()->route('guru.index')->with('success', 'Data Guru & Akun terkait berhasil dihapus.');
    }

    public function exportExcel()
    {
        Gate::authorize('viewAny', Guru::class);
        return Excel::download(new GuruExport, 'data-guru-' . date('Y-m-d') . '.xlsx');
    }

    public function importExcel(Request $request)
    {
        Gate::authorize('create', Guru::class);
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        Excel::import(new GuruImport, $request->file('file_excel'));
        return redirect()->route('guru.index')->with('success', 'Data Guru berhasil di-import dari Excel.');
    }
}
