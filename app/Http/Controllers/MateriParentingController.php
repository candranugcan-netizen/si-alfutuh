<?php

namespace App\Http\Controllers;

use App\Models\MateriParenting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MateriParentingController extends Controller
{
    // ==========================================
    //          BAGIAN 1: SISI ADMIN
    // ==========================================

    public function indexAdmin()
    {
        $materi = MateriParenting::latest()->get();
        return view('admin.parenting.index', compact('materi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:pdf,video',
            // Kita pastikan mimes menerima pdf dengan penulisan tegas, max 20MB
            'file_pdf' => 'required_if:tipe,pdf|nullable|mimes:pdf,PDF|max:20480',
            'link_youtube' => 'required_if:tipe,video|nullable|url',
        ]);

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
        ];

        if ($request->tipe == 'pdf' && $request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');

            // Bersihkan nama file dari karakter aneh
            $namaAsli = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $namaFile = time() . '_parenting_' . \Illuminate\Support\Str::slug($namaAsli) . '.pdf';

            // GUNAKAN FOLDER UPLOADS (Sama seperti PPDB yang sudah terbukti berhasil)
            $pathTujuan = Storage::disk('public')->path('materi_parenting');

            if (!File::exists($pathTujuan)) {
                File::makeDirectory($pathTujuan, 0755, true);
            }

            $file->move($pathTujuan, $namaFile);
            $data['file_pdf'] = $namaFile;
            $data['link_youtube'] = null; // Pastikan link youtube kosong jika tipenya PDF
        }

        if ($request->tipe == 'video' && $request->link_youtube) {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $request->link_youtube, $match);
            $data['link_youtube'] = $match[1] ?? null;
            $data['file_pdf'] = null; // Pastikan file pdf kosong jika tipenya video
        }

        MateriParenting::create($data);

        return back()->with('success', 'Materi parenting berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $materi = MateriParenting::findOrFail($id);

        // Sesuaikan folder hapus ke uploads
        if ($materi->tipe == 'pdf' && $materi->file_pdf) {
            $pathFile = Storage::disk('public')->path('materi_parenting/' . $materi->file_pdf);
            if (File::exists($pathFile)) {
                File::delete($pathFile);
            }
        }

        $materi->delete();
        return back()->with('success', 'Materi parenting berhasil dihapus.');
    }


    // ==========================================
    //        BAGIAN 2: SISI ORANG TUA
    // ==========================================

    public function indexOrtu()
    {
        $materi = MateriParenting::latest()->get();
        return view('ortu.parenting.index', compact('materi'));
    }

    public function showOrtu($id)
    {
        $materi = MateriParenting::findOrFail($id);
        return view('ortu.parenting.show', compact('materi'));
    }
}
