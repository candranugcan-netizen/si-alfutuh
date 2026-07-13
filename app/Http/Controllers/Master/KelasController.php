<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Http\Requests\Master\StoreKelasRequest;
use App\Http\Requests\Master\UpdateKelasRequest;
use App\Services\KelasService;
use Illuminate\Support\Facades\Gate;

class KelasController extends Controller
{
    protected $kelasService;

    public function __construct(KelasService $kelasService)
    {
        $this->kelasService = $kelasService;
    }

    public function index()
    {
        Gate::authorize('viewAny', Kelas::class);
        $kelas = Kelas::latest()->get();
        return view('master.kelas.index', compact('kelas'));
    }

    public function create()
    {
        Gate::authorize('create', Kelas::class);
        return view('master.kelas.create');
    }

    public function store(StoreKelasRequest $request)
    {
        Gate::authorize('create', Kelas::class);
        $this->kelasService->createKelas($request->validated());
        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        Gate::authorize('update', $kelas);
        return view('master.kelas.edit', compact('kelas'));
    }

    public function update(UpdateKelasRequest $request, Kelas $kelas)
    {
        Gate::authorize('update', $kelas);
        $this->kelasService->updateKelas($kelas, $request->validated());
        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        Gate::authorize('delete', $kelas);
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil dihapus.');
    }
}
