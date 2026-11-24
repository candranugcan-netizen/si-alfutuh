<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>e-Rapor Lengkap - {{ $erapor->siswa->nama_siswa }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.4cm 1.5cm 1.4cm 1.5cm;
        }
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 11px;
            color: #111;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .page-break {
            page-break-after: always;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-weight-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        /* Cover */
        .cover-box {
            text-align: center;
            padding-top: 50px;
        }
        .cover-logo {
            width: 185px;
            height: auto;
            margin-bottom: 25px;
        }
        .cover-title {
            font-size: 18px;
            font-weight: bold;
            line-height: 1.5;
            margin-bottom: 40px;
        }
        .cover-school-info {
            margin: 0 auto 50px auto;
            width: 80%;
            text-align: left;
            font-size: 12px;
            border-collapse: collapse;
        }
        .cover-school-info td {
            padding: 5px 8px;
        }
        .cover-student-box {
            border: 2px solid #2d6a4f;
            background-color: #f8f9fa;
            padding: 15px 25px;
            display: inline-block;
            margin-top: 30px;
            border-radius: 6px;
        }
        .cover-student-title {
            font-size: 12px;
            color: #555;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .cover-student-name {
            font-size: 16px;
            font-weight: bold;
            color: #1b4332;
        }

        /* Visi Misi & Landasan */
        .header-section {
            text-align: center;
            border-bottom: 2px solid #1b4332;
            padding-bottom: 6px;
            margin-bottom: 16px;
        }
        .header-section h3 {
            margin: 0;
            font-size: 14px;
            color: #1b4332;
        }
        .content-block {
            margin-bottom: 14px;
        }
        .content-block h4 {
            margin: 0 0 6px 0;
            font-size: 12px;
            color: #1b4332;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }
        .arabic-text {
            font-size: 13px;
            line-height: 1.8;
            text-align: right;
            direction: rtl;
            margin: 6px 0;
            color: #0b3d20;
        }
        .translation-text {
            font-style: italic;
            font-size: 10px;
            color: #333;
            margin-bottom: 10px;
            padding: 0 8px;
        }

        /* Tabel Data Siswa */
        .bio-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 30px;
        }
        .bio-table td {
            padding: 6px 8px;
            vertical-align: top;
        }

        /* Tabel Penilaian Indikator */
        .header-identitas {
            width: 100%;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 11px;
        }
        .header-identitas td {
            padding: 2px 4px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #333;
            padding: 4px 6px;
        }
        table.data-table th {
            background-color: #e8f5e9;
            color: #1b4332;
            text-align: center;
            font-size: 10px;
        }
        table.data-table td.center {
            text-align: center;
        }
        .category-row {
            background-color: #f1f8e9;
            font-weight: bold;
            color: #1b4332;
        }
        .sub-category-row {
            background-color: #fafafa;
            font-weight: bold;
            color: #333;
            padding-left: 12px !important;
        }

        /* Kotak Elemen Capaian Perkembangan Foto */
        .element-box {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #333;
            margin-bottom: 14px;
        }
        .element-title-cell {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            font-size: 11px;
            border-bottom: 1px solid #333;
        }
        .element-body-table {
            width: 100%;
            border-collapse: collapse;
        }
        .element-text-col {
            padding: 8px 10px;
            vertical-align: top;
            text-align: justify;
            font-size: 10.5px;
            line-height: 1.45;
        }
        .element-photo-col {
            width: 150px;
            padding: 8px;
            vertical-align: top;
            text-align: center;
            border-left: 1px dashed #ccc;
        }
        .element-photo-col img {
            width: 135px;
            height: auto;
            max-height: 140px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #bbb;
            margin-bottom: 5px;
        }

        /* Tanda Tangan */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .ttd-table td {
            vertical-align: top;
            text-align: center;
            padding: 4px;
        }
        .ttd-space {
            height: 55px;
        }

        .no-print {
            background: #e8f5e9;
            border: 1px solid #a5d6a7;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 0; }
        }
    </style>
</head>
<body>

    @if(request()->has('preview'))
        <div class="no-print">
            <div>
                <strong>Mode Preview Cetak Browser:</strong> Bundel Lengkap e-Rapor & Laporan Perkembangan.
            </div>
            <div>
                <button onclick="window.print()" style="padding: 6px 14px; background: #2e7d32; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                    🖨️ Cetak Dokumen Ini
                </button>
                <a href="{{ route('erapor.cetakLengkap', $erapor->id) }}" style="margin-left: 10px; padding: 6px 14px; background: #c62828; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold;">
                    📄 Unduh File PDF
                </a>
            </div>
        </div>
    @endif

    <!-- ================= HALAMAN 1: COVER ================= -->
    <div class="cover-box">
        @if(!empty($logoBase64))
            <img src="{{ $logoBase64 }}" class="cover-logo" alt="Logo RA Al Futuh">
        @endif

        <div class="cover-title">
            LAPORAN PERKEMBANGAN PESERTA DIDIK<br>
            SEKOLAH RA ISLAM AL FUTUH
        </div>

        <table class="cover-school-info">
            <tr>
                <td width="28%" class="font-weight-bold">Nama Sekolah</td>
                <td width="3%">:</td>
                <td width="69%">RA ISLAM AL FUTUH</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Alamat</td>
                <td>:</td>
                <td>Klepu, RT 4 RW 4 Rejosari</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Kecamatan</td>
                <td>:</td>
                <td>Semin</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Kabupaten / Kota</td>
                <td>:</td>
                <td>Gunungkidul</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Provinsi</td>
                <td>:</td>
                <td>Daerah Istimewa Yogyakarta</td>
            </tr>
        </table>

        <div class="cover-student-box">
            <div class="cover-student-title">Nama Peserta Didik</div>
            <div class="cover-student-name">{{ strtoupper($erapor->siswa->nama_siswa) }}</div>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- ================= HALAMAN 2: VISI MISI, DALIL & PETUNJUK ================= -->
    <div class="content-block">
        <h4>VISI</h4>
        <p style="margin: 4px 0 10px 0;">
            Menjadi taman kanak - kanak yang unggul dalam mencetak anak didik yang cerdas, berkepribadian islami dan mandiri.
        </p>

        <h4>MISI</h4>
        <ol style="margin: 4px 0 14px 0; padding-left: 20px;">
            <li>Menyelenggarakan pendidikan yang berkualitas dengan nilai islami berdasarkan AL Qur’an dan Sunnah.</li>
            <li>Membentuk generasi yang cinta Allah dan Rasul - Nya serta Al Qur’an Al Karim.</li>
            <li>Membentuk generasi yang mandiri, berakhlakul karimah dalam kehidupan sehari - hari.</li>
        </ol>
    </div>

    <div class="content-block" style="text-align: center; margin: 15px 0 20px 0;">
        <div class="translation-text" style="font-size: 11px; line-height: 1.6; margin-bottom: 14px;">
            "Berdirilah kamu, maka berdirilah, niscaya Allah akan mengangkat (derajat) orang - orang yang beriman diantara kamu dan orang - orang yang diberi ilmu beberapa derajat. Dan Allah maha mengetahui terhadap apa yang kamu kerjakan."<br>
            <strong style="color: #1b4332;">(QS. Al Mujadalah : 11)</strong>
        </div>

        <div style="font-size: 10.5px; margin-bottom: 4px; font-weight: bold; color: #1b4332;">
            Hadits dari Abu Hurairah Radhiyallahu 'anhu, Rasûlullâh Shallallâhu ‘alaihi wa sallam bersabda:
        </div>
        <div class="translation-text" style="font-size: 11px; line-height: 1.6;">
            "Apabila seseorang sudah meninggal maka seluruh amalannya terputus kecuali dari tiga perkara (yaitu) dari sedekah jariyah, ilmu yang dimanfaatkan dan anak shalih yang mendo’akannya."<br>
            <strong style="color: #1b4332;">(HR. Muslim)</strong>
        </div>
    </div>

    <div class="content-block" style="border: 1px solid #ccc; padding: 10px 14px; background-color: #fafafa; border-radius: 4px;">
        <h4 style="border-bottom: none; margin-bottom: 4px;">PETUNJUK PENGGUNAAN</h4>
        <ul style="margin: 0; padding-left: 18px; font-size: 10px;">
            <li>Buku Laporan Perkembangan peserta didik ini dipergunakan selama peserta didik mengikuti pembelajaran di TK.</li>
            <li>Buku Laporan Perkembangan peserta didik ini diperuntukkan khusus untuk peserta didik TK dan digunakan tiap semester.</li>
            <li>Apabila peserta didik pindah sekolah maka buku laporan perkembangan peserta didik ini dibawa oleh peserta didik yang bersangkutan untuk dipergunakan di sekolah yang baru dengan meninggalkan arsip di sekolah asal.</li>
            <li>Diisi oleh guru dengan memberi tanda check list (√) dan narasi.</li>
            <li>Program yang belum diberikan diberi tanda strip (-) pada kolomnya.</li>
            <li>Penilaian dilakukan secara kualitatif selama peserta didik mengikuti pendidikan di TK berdasarkan observasi, portofolio, dll.</li>
            <li>Lembar catatan perkembangan peserta didik pada halaman akhir diisi dengan catatan penting berkaitan dengan peserta didik.</li>
        </ul>
        <div style="margin-top: 8px; font-size: 10px; font-weight: bold; color: #1b4332;">
            PADA PROGRAM PERKEMBANGAN PESERTA DIDIK:
            <span style="font-weight: normal; color: #111;">BB = Belum Berkembang | MB = Mulai Berkembang | BSH = Berkembang Sesuai Harapan</span>
        </div>
        <div style="margin-top: 3px; font-size: 10px; font-weight: bold; color: #1b4332;">
            PADA PROGRAM KEGIATAN EKSTRAKURIKULER DAN UKS:
            <span style="font-weight: normal; color: #111;">B = Baik | C = Cukup | K = Kurang</span>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- ================= HALAMAN 3: DATA PESERTA DIDIK ================= -->
    <div class="header-section">
        <h3>DATA PESERTA DIDIK</h3>
    </div>

    <table class="bio-table">
        <tr>
            <td width="30%">Nama Lengkap</td>
            <td width="3%">:</td>
            <td width="67%" class="font-weight-bold">{{ strtoupper($erapor->siswa->nama_siswa) }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $erapor->siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td>Tempat dan Tanggal Lahir</td>
            <td>:</td>
            <td>
                {{ $erapor->siswa->tempat_lahir ?? 'Gunungkidul' }},
                {{ $erapor->siswa->tanggal_lahir ? \Carbon\Carbon::parse($erapor->siswa->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td>Agama</td>
            <td>:</td>
            <td>{{ $erapor->agama ?? 'Islam' }}</td>
        </tr>
        <tr>
            <td>Anak ke</td>
            <td>:</td>
            <td>{{ $erapor->anak_ke ?? '1' }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ (!empty($erapor->alamat) && $erapor->alamat !== 'Klepu RT 4 RW 4 Rejosari Semin Gunungkidul') ? $erapor->alamat : ($erapor->siswa->alamat ?: ($erapor->alamat ?: 'Klepu RT 4 RW 4 Rejosari Semin Gunungkidul')) }}</td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 15px; font-weight: bold; color: #1b4332;">Nama Orang Tua:</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">a. Ayah</td>
            <td>:</td>
            <td>{{ $erapor->nama_ayah ?? ($erapor->siswa->nama_ayah ?? '-') }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">b. Ibu</td>
            <td>:</td>
            <td>{{ $erapor->nama_ibu ?? ($erapor->siswa->nama_ibu ?? '-') }}</td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 10px; font-weight: bold; color: #1b4332;">Agama Orang Tua:</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">a. Ayah</td>
            <td>:</td>
            <td>{{ $erapor->agama_ayah ?? 'Islam' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">b. Ibu</td>
            <td>:</td>
            <td>{{ $erapor->agama_ibu ?? 'Islam' }}</td>
        </tr>
    </table>

    <table class="ttd-table" style="margin-top: 60px;">
        <tr>
            <td width="50%"></td>
            <td width="50%">
                {{ $erapor->tempat_cetak ?? 'Semin' }}, {{ $erapor->tanggal_rapor ? \Carbon\Carbon::parse($erapor->tanggal_rapor)->translatedFormat('d F Y') : '15 Juni 2025' }}<br>
                Kepala RA<br>
                <div class="ttd-space"></div>
                <strong>{{ $erapor->nama_kepala_sekolah ?? 'Pradita Ovi Agustina, S.Pd' }}</strong><br>
                NIP. {{ $erapor->nip_kepala_sekolah ?? '-' }}
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- ================= HALAMAN 4: TABEL INDIKATOR PERKEMBANGAN ================= -->
    <table class="header-identitas">
        <tr>
            <td width="18%">Nama Peserta Didik</td>
            <td width="2%">:</td>
            <td width="40%">{{ strtoupper($erapor->siswa->nama_siswa) }}</td>
            <td width="15%">Semester</td>
            <td width="2%">:</td>
            <td width="23%">{{ $erapor->semester }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td>{{ $erapor->siswa->kelas->nama_kelas ?? '-' }}</td>
            <td>Tahun Pelajaran</td>
            <td>:</td>
            <td>{{ $erapor->tahunAjaran->nama ?? '2024 / 2025' }}</td>
        </tr>
    </table>

    @php
        $selectedNilai = $erapor->nilai->filter(function ($item) {
            return $item->is_selected == 1 || $item->is_selected === true;
        });
        $groupedNilai = $selectedNilai->groupBy(['kategori_nama', function ($item) {
            return $item->sub_kategori ?? 'Umum';
        }]);
    @endphp

    <table class="data-table">
        <thead>
            <tr>
                <th width="8%" rowspan="2">NO / KODE</th>
                <th rowspan="2">INDIKATOR PERKEMBANGAN</th>
                <th colspan="3" width="22%">PENILAIAN SEMESTER {{ $erapor->semester }}</th>
            </tr>
            <tr>
                <th width="7%">BB</th>
                <th width="7%">MB</th>
                <th width="8%">BSH</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedNilai as $kategoriNama => $subKats)
                <tr class="category-row">
                    <td colspan="5">{{ $kategoriNama }}</td>
                </tr>

                @foreach($subKats as $subNama => $items)
                    @if($subNama !== 'Umum')
                        <tr class="sub-category-row">
                            <td colspan="5">{{ $subNama }}</td>
                        </tr>
                    @endif

                    @foreach($items as $item)
                        <tr>
                            <td class="center font-weight-bold">{{ $item->kode }}</td>
                            <td>{{ $item->indikator }}</td>
                            <td class="center">{{ $item->nilai == 'BB' ? '√' : ($item->nilai == '-' ? '-' : '') }}</td>
                            <td class="center">{{ $item->nilai == 'MB' ? '√' : ($item->nilai == '-' ? '-' : '') }}</td>
                            <td class="center">{{ $item->nilai == 'BSH' ? '√' : ($item->nilai == '-' ? '-' : '') }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- ================= HALAMAN 5+: LAPORAN PERKEMBANGAN DENGAN FOTO BUKTI ================= -->
    <div class="header-section">
        <h3>LAPORAN PERKEMBANGAN DENGAN LAMPIRAN FOTO BUKTI</h3>
    </div>

    <!-- 1. Nilai Agama Islam -->
    <div class="element-box">
        <div class="element-title-cell">Nilai Agama Islam dan Budi Pekerti</div>
        <table class="element-body-table">
            <tr>
                <td class="element-text-col">
                    <span style="font-size: 12px; color: #0b3d20; font-weight: bold;">ٱلْحَمْدُ لِلَّٰهِ رَبِّ ٱلْعَالَمِينَ</span>
                    {!! nl2br(e($erapor->narasi_agama_display)) !!}
                </td>
                <td class="element-photo-col">
                    @if(!empty($fotoMap['foto_agama_1']))
                        <img src="{{ $fotoMap['foto_agama_1'] }}" alt="Bukti Agama 1">
                    @endif
                    @if(!empty($fotoMap['foto_agama_2']))
                        <img src="{{ $fotoMap['foto_agama_2'] }}" alt="Bukti Agama 2">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- 2. Jati Diri -->
    <div class="element-box">
        <div class="element-title-cell">Jati Diri</div>
        <table class="element-body-table">
            <tr>
                <td class="element-text-col">
                    {!! nl2br(e($erapor->narasi_jati_diri ?? 'Ananda menunjukkan perkembangan yang baik dalam nilai karakter Cinta Diri Sendiri dan Sesama. Ananda terbiasa bertanggung jawab menyelesaikan kegiatan sampai tuntas dan merapikan mainan ke tempat semula. Ananda memiliki kepedulian yang tinggi dan suka membantu teman-temannya di kelas.')) !!}
                </td>
                <td class="element-photo-col">
                    @if(!empty($fotoMap['foto_jati_diri_1']))
                        <img src="{{ $fotoMap['foto_jati_diri_1'] }}" alt="Bukti Jati Diri 1">
                    @endif
                    @if(!empty($fotoMap['foto_jati_diri_2']))
                        <img src="{{ $fotoMap['foto_jati_diri_2'] }}" alt="Bukti Jati Diri 2">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- 3. Literasi STEAM -->
    <div class="element-box">
        <div class="element-title-cell">Dasar-dasar Literasi, Matematika, Sains, Teknologi, Rekayasa, Seni</div>
        <table class="element-body-table">
            <tr>
                <td class="element-text-col">
                    {!! nl2br(e($erapor->narasi_literasi_steam ?? 'Alhamdulillah Ananda sudah menunjukkan perkembangan yang sangat baik dalam elemen Dasar-dasar Literasi, Matematika, Sains, Teknologi, Rekayasa, dan Seni. Ananda mampu mengenali bentuk, menyusun huruf A-Z, dan membuat karya dengan media loose parts secara kreatif (misal: membuat figura taman bunga). Semangat belajar ananda tampak kuat saat observasi lingkungan.')) !!}
                </td>
                <td class="element-photo-col">
                    @if(!empty($fotoMap['foto_literasi_steam_1']))
                        <img src="{{ $fotoMap['foto_literasi_steam_1'] }}" alt="Bukti Literasi 1">
                    @endif
                    @if(!empty($fotoMap['foto_literasi_steam_2']))
                        <img src="{{ $fotoMap['foto_literasi_steam_2'] }}" alt="Bukti Literasi 2">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- 4. Kokurikuler Projek P5-PPRA -->
    <div class="element-box">
        <div class="element-title-cell">Kokurikuler (Projek Penguatan Profil Pelajar Pancasila & Rahmatan Lil Alamin)</div>
        <table class="element-body-table">
            <tr>
                <td class="element-text-col">
                    @if($erapor->tema_projek)
                        <div style="font-weight: bold; margin-bottom: 5px; color: #1b4332;">
                            Tema: {{ $erapor->tema_projek }}
                        </div>
                    @endif
                    {!! nl2br(e($erapor->narasi_projek ?? 'Dalam kegiatan kokurikuler melalui Pembelajaran Berbasis Projek dengan tema "Aku Cinta Indonesia" dan "Gigiku Sehat", ananda menunjukkan keaktifan, fokus, dan kerja sama yang sangat membanggakan dalam menyelesaikan setiap tahapan projek bersama bunda dan teman-temannya.')) !!}
                </td>
                <td class="element-photo-col">
                    @if(!empty($fotoMap['foto_projek_1']))
                        <img src="{{ $fotoMap['foto_projek_1'] }}" alt="Bukti Projek 1">
                    @endif
                    @if(!empty($fotoMap['foto_projek_2']))
                        <img src="{{ $fotoMap['foto_projek_2'] }}" alt="Bukti Projek 2">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- ================= HALAMAN AKHIR: KESEHATAN FISIK, ABSENSI, CATATAN & TTD ================= -->
    <div class="header-section">
        <h3>KESEHATAN FISIK</h3>
    </div>

    @php
        $aspekFisikList = [
            '1' => ['label' => 'Status gizi', 'val' => $erapor->status_gizi ?? 'Baik'],
            '2' => ['label' => 'Kepala / rambut', 'val' => $erapor->kondisi_rambut ?? 'Baik'],
            '3' => ['label' => 'Mata / penglihatan', 'val' => $erapor->kondisi_mata ?? 'Baik'],
            '4' => ['label' => 'Pendengaran / telinga', 'val' => $erapor->kondisi_telinga ?? 'Baik'],
            '5' => ['label' => 'Hidung', 'val' => $erapor->kondisi_hidung ?? 'Baik'],
            '6' => ['label' => 'Mulut', 'val' => $erapor->kondisi_mulut ?? 'Baik'],
            '7' => ['label' => 'Kulit', 'val' => $erapor->kondisi_kulit ?? 'Baik'],
            '8' => ['label' => 'Kebersihan badan', 'val' => $erapor->kebersihan_badan ?? 'Baik'],
            '9' => ['label' => 'Kebersihan pakaian', 'val' => $erapor->kebersihan_pakaian ?? 'Baik'],
            '10' => ['label' => 'Berat badan', 'val' => $erapor->berat_badan ?? '-'],
            '11' => ['label' => 'Tinggi badan', 'val' => $erapor->tinggi_badan ?? '-'],
        ];
    @endphp

    <table class="data-table">
        <thead>
            <tr>
                <th width="8%" rowspan="2">No</th>
                <th rowspan="2">PEMERIKSAAN KESEHATAN</th>
                <th colspan="3" width="30%">PENILAIAN SEMESTER {{ $erapor->semester }}</th>
            </tr>
            <tr>
                <th width="10%">Kurang</th>
                <th width="10%">Cukup</th>
                <th width="10%">Baik</th>
            </tr>
        </thead>
        <tbody>
            @foreach($aspekFisikList as $noFisik => $dataFisik)
                @if(in_array($noFisik, ['10', '11']))
                    <tr>
                        <td class="center">{{ $noFisik }}</td>
                        <td>{{ $dataFisik['label'] }}</td>
                        <td colspan="3" class="center font-weight-bold">{{ $dataFisik['val'] }}</td>
                    </tr>
                @else
                    <tr>
                        <td class="center">{{ $noFisik }}</td>
                        <td>{{ $dataFisik['label'] }}</td>
                        <td class="center">{{ $dataFisik['val'] == 'Kurang' ? '√' : '' }}</td>
                        <td class="center">{{ $dataFisik['val'] == 'Cukup' ? '√' : '' }}</td>
                        <td class="center">{{ $dataFisik['val'] == 'Baik' ? '√' : '' }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="header-section" style="margin-top: 15px;">
        <h3>ABSENSI</h3>
    </div>
    <table class="data-table" style="width: 60%; margin: 0 auto 15px auto;">
        <thead>
            <tr>
                <th>KETERANGAN</th>
                <th width="40%">SEMESTER {{ $erapor->semester }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sakit</td>
                <td class="center font-weight-bold">{{ $erapor->kehadiran_sakit ?? 0 }} hari</td>
            </tr>
            <tr>
                <td>Izin</td>
                <td class="center font-weight-bold">{{ $erapor->kehadiran_izin ?? 0 }} hari</td>
            </tr>
            <tr>
                <td>Tanpa keterangan</td>
                <td class="center font-weight-bold">{{ $erapor->kehadiran_alfa ?? 0 }} hari</td>
            </tr>
        </tbody>
    </table>

    <div class="content-block" style="border: 1px solid #333; padding: 10px; margin-top: 15px; min-height: 50px;">
        <div class="font-weight-bold" style="margin-bottom: 5px;">CATATAN:</div>
        <div>{{ $erapor->catatan_guru ?? 'Ananda berkembang sangat baik sesuai tahap usianya. Pertahankan semangat belajar dan akhlaq mulianya.' }}</div>
    </div>

    <!-- TANDA TANGAN 3 PIHAK LENGKAP -->
    <table class="ttd-table" style="margin-top: 25px;">
        <tr>
            <td width="33%">
                Mengetahui,<br>
                Orang Tua / Wali<br>
                <div class="ttd-space"></div>
                <strong>..........................................</strong>
            </td>
            <td width="34%">
                Mengetahui,<br>
                Kepala RA Al Futuh<br>
                <div class="ttd-space"></div>
                <strong>{{ $erapor->nama_kepala_sekolah ?? 'Pradita Ovi Agustina, S.Pd' }}</strong><br>
                NIP. {{ $erapor->nip_kepala_sekolah ?? '-' }}
            </td>
            <td width="33%">
                {{ $erapor->tempat_cetak ?? 'Semin' }}, {{ $erapor->tanggal_rapor ? \Carbon\Carbon::parse($erapor->tanggal_rapor)->translatedFormat('d F Y') : '16 Juni 2025' }}<br>
                Guru Kelas<br>
                <div class="ttd-space"></div>
                <strong>{{ $erapor->nama_guru_kelas ?? 'Ati Aturahmi' }}</strong><br>
                NIP. {{ $erapor->nip_guru_kelas ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>
