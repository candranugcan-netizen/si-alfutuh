<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Http\Requests\Master\StoreTahunAjaranRequest;
use App\Http\Requests\Master\UpdateTahunAjaranRequest;
use App\Services\TahunAjaranService;
use Illuminate\Support\Facades\Gate;

class TahunAjaranController extends Controller
{
    protected $tahunAjaranService;

    // Masukkan Service melalui Constructor Injection
    public function __construct(TahunAjaranService $tahunAjaranService)
    {
        $this->tahunAjaranService = $tahunAjaranService;

        // Pastikan tidak ada kode lain di dalam sini
    }

    public function index()
    {
        $tahunAjarans = TahunAjaran::latest()->get();
        return view('master.tahun_ajaran.index', compact('tahunAjarans'));
    }

    public function create()
    {
        return view('master.tahun_ajaran.create');
    }

    public function store(StoreTahunAjaranRequest $request)
    {
        $this->tahunAjaranService->createTahunAjaran($request->validated());
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('master.tahun_ajaran.edit', compact('tahunAjaran'));
    }

    public function update(UpdateTahunAjaranRequest $request, TahunAjaran $tahunAjaran)
    {
        $this->tahunAjaranService->updateTahunAjaran($tahunAjaran, $request->validated());
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->delete();
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
}
