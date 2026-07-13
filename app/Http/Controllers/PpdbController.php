<?php

namespace App\Http\Controllers;

use App\Models\PpdbPendaftaran;
use App\Models\Siswa;
use App\Models\User;
use App\Models\OrangTua;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Str;

// --- IMPOR LIBRARY KOMPRESI VERSI 3 ---
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PpdbController extends Controller
{
    // --- 1. BAGIAN UNTUK ORANG TUA (LANDING PAGE) ---

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'nik_atau_akta' => 'required|unique:ppdb_pendaftaran',
            'email_ortu' => 'required|email',
            'file_kk' => 'required|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB
            'file_akta' => 'required|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_foto' => 'required|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        // FUNGSI KOMPRESI PINTAR UNTUK INTERVENTION IMAGE V3
        $prosesFile = function ($file, $prefix) {
            $ekstensi = strtolower($file->getClientOriginalExtension());
            $namaAsli = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            // Jika gambar (png/jpg), kita akan paksa simpan sebagai .jpg agar kompresi maksimal
            $ekstensiFinal = in_array($ekstensi, ['jpg', 'jpeg', 'png']) ? 'jpg' : $ekstensi;
            $namaFile = time() . '_' . $prefix . '_' . Str::slug($namaAsli) . '.' . $ekstensiFinal;

            $pathTujuan = public_path('uploads/ppdb');
            if (!File::exists($pathTujuan)) {
                File::makeDirectory($pathTujuan, 0755, true);
            }

            if (in_array($ekstensi, ['jpg', 'jpeg', 'png'])) {
                // Inisialisasi ImageManager versi 3
                $manager = new ImageManager(new Driver());

                // Baca file yang diunggah
                $img = $manager->read($file->getRealPath());

                // Perkecil ukuran maksimal lebar 1200px (scaleDown mencegah gambar kecil malah dibesarkan)
                $img->scaleDown(width: 1200);

                // Simpan sebagai format JPEG dengan kualitas 60%
                $img->toJpeg(60)->save($pathTujuan . '/' . $namaFile);
            } else {
                // Jika PDF, pindahkan langsung tanpa kompresi
                $file->move($pathTujuan, $namaFile);
            }

            return $namaFile;
        };

        // EKSEKUSI PROSES
        $fileKK = $prosesFile($request->file('file_kk'), 'kk');
        $fileAkta = $prosesFile($request->file('file_akta'), 'akta');
        $fileFoto = $prosesFile($request->file('file_foto'), 'foto');

        // Generate No Pendaftaran
        $lastId = PpdbPendaftaran::max('id') ?? 0;
        $noPendaftaran = 'PPDB-' . date('Y') . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        // Simpan Data
        PpdbPendaftaran::create(array_merge($request->except(['_token', 'file_kk', 'file_akta', 'file_foto']), [
            'no_pendaftaran' => $noPendaftaran,
            'file_kk' => $fileKK,
            'file_akta' => $fileAkta,
            'file_foto' => $fileFoto,
            'status' => 'Pending'
        ]));

        return redirect('/#ppdb')->with('success', 'Pendaftaran Berhasil! Nomor Pendaftaran Anda: ' . $noPendaftaran);
    }


    // --- 2. BAGIAN UNTUK ADMIN (DASHBOARD) ---

    public function index()
    {
        $pendaftar = PpdbPendaftaran::latest()->get();
        return view('ppdb.index', compact('pendaftar'));
    }

    public function terima($id)
    {
        $pendaftaran = PpdbPendaftaran::findOrFail($id);

        if ($pendaftaran->status != 'Pending') {
            return back()->with('error', 'Data ini sudah diproses sebelumnya.');
        }

        // A. CEK & BUAT AKUN USERS (Berdasarkan Email)
        $user = User::where('email', $pendaftaran->email_ortu)->first();
        if (!$user) {
            $user = User::create([
                'name' => $pendaftaran->nama_ayah, // Ambil nama Ayah sebagai representasi
                'email' => $pendaftaran->email_ortu,
                'password' => Hash::make('raalfutuh' . date('Y')), // Password default: raalfutuh2026
            ]);

            // Assign Role jika Spatie terpasang
            $user->assignRole('Orang Tua');
        }

        // B. CEK & BUAT PROFIL ORANG TUA
        // Menyesuaikan relasi db_ra_alfutuh (Tabel orang_tua)
        $profilOrtu = OrangTua::where('user_id', $user->id)->first();
        if (!$profilOrtu) {
            $profilOrtu = OrangTua::create([
                'user_id' => $user->id,
                'nama_wali' => $pendaftaran->nama_ayah,
                'no_hp' => $pendaftaran->no_hp_ortu,
            ]);
        }

        // C. PINDAHKAN FILE KE FOLDER RESMI DOKUMEN SISWA
        $berkas = ['file_foto', 'file_akta', 'file_kk'];
        $folderTujuan = public_path('storage/dokumen_siswa');

        if (!File::exists($folderTujuan)) {
            File::makeDirectory($folderTujuan, 0755, true);
        }

        foreach ($berkas as $item) {
            $namaFile = $pendaftaran->$item;
            $pathLama = public_path('uploads/ppdb/' . $namaFile);
            $pathBaru = $folderTujuan . '/' . $namaFile;

            if (File::exists($pathLama)) {
                File::move($pathLama, $pathBaru);
            }
        }

        // Konversi Jenis Kelamin ke Enum 'L' atau 'P'
        $jenisKelamin = ($pendaftaran->jenis_kelamin == 'Laki-laki') ? 'L' : 'P';

        // Ambil Kelas ID pertama sebagai kelas sementara (Karena DB tidak membolehkan Null)
        $kelasDefault = Kelas::first();
        $kelasId = $kelasDefault ? $kelasDefault->id : 1;

        // D. MASUKKAN DATA KE TABEL SISWA (Presisi 100% dengan database)
        Siswa::create([
            'kelas_id' => $kelasId,
            'orang_tua_id' => $profilOrtu->id,
            'nominal_spp' => 90000.00, // Standar SPP seperti di database
            'status_siswa' => 'Aktif',
            'tahun_masuk' => date('Y'),

            // File Dokumen
            'foto_anak' => $pendaftaran->file_foto,
            'akta_kelahiran' => $pendaftaran->file_akta,
            'kartu_keluarga' => $pendaftaran->file_kk,

            // Data Pribadi Siswa
            'nis' => null, // NIS kosong, diisi Admin nanti
            'nama_siswa' => $pendaftaran->nama_lengkap,
            'jenis_kelamin' => $jenisKelamin,
            'tempat_lahir' => $pendaftaran->tempat_lahir,
            'tanggal_lahir' => $pendaftaran->tanggal_lahir,
        ]);

        // E. UBAH STATUS PENDAFTAR
        $pendaftaran->update(['status' => 'Diterima']);

        return back()->with('success', 'Siswa Diterima! Data otomatis masuk ke tabel Siswa & Orang Tua.');
    }

    public function tolak($id)
    {
        $pendaftaran = PpdbPendaftaran::findOrFail($id);
        $pendaftaran->update(['status' => 'Ditolak']);
        return back()->with('success', 'Pendaftaran siswa telah ditolak.');
    }
}
