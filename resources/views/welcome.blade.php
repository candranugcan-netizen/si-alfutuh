<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>RA Al Futuh - Cerdas & Berakhlak Mulia</title>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Mulish:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- MENGGUNAKAN BOOTSTRAP 5 RESMI -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Kustomisasi Warna Elegan & Menyenangkan Khas Anak-Anak -->
    <style>
        body { font-family: 'Mulish', sans-serif; background-color: #ffffff; color: #495057; }
        h1, h2, h3, h4, h5, h6, .navbar-brand { font-family: 'Fredoka', sans-serif; } /* Font Fredoka memberi kesan ramah & playful */

        .bg-gradient-primary-to-secondary {
            background: linear-gradient(135deg, #1cc88a 0%, #20c997 100%) !important;
        }
        .text-gradient {
            background: linear-gradient(45deg, #1cc88a, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Tema Warna Ceria Berkelas */
        .btn-success-tk { background-color: #1cc88a; border-color: #1cc88a; color: white; }
        .btn-success-tk:hover { background-color: #17a673; border-color: #17a673; color: white; }
        .btn-outline-success-tk { border-color: #1cc88a; color: #1cc88a; }
        .btn-outline-success-tk:hover { background-color: #1cc88a; color: white; }

        .bg-pastel-yellow { background-color: #fffbeb; }
        .bg-pastel-blue { background-color: #f0f9ff; }
        .bg-pastel-green { background-color: #f0fdf4; }

        /* Pengaturan Header / Masthead */
        .masthead { padding-top: 8rem; padding-bottom: 6rem; background: radial-gradient(circle at 90% 10%, #f0fdf4 0%, #ffffff 60%); }

        /* Kartu Berita & Fitur Elegan */
        .berita-img { height: 220px; object-fit: cover; width: 100%; border-radius: 1.25rem 1.25rem 0 0; }
        .card-custom { border: none; border-radius: 1.5rem; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04); }
        .card-custom:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(28, 200, 138, 0.12); }

        /* Bentuk Lingkaran Hiasan Belakang */
        .blob-decorator {
            position: absolute; top: -10%; left: -10%; width: 120%; height: 120%; z-index: 1; opacity: 0.7;
        }

        /* Desain Kustom Gelombang Pembatas */
        .wave-container { position: relative; width: 100%; overflow: hidden; line-height: 0; }
        .wave-container svg { position: relative; display: block; width: calc(100% + 1.3px); height: 50px; }

        /* Gaya Accordion FAQ */
        .accordion-item { border: none; margin-bottom: 1rem; border-radius: 1rem !important; box-shadow: 0 4px 12px rgba(0,0,0,0.02); overflow: hidden; }
        .accordion-button:not(.collapsed) { background-color: #f0fdf4; color: #1cc88a; box-shadow: none; }
        .accordion-button { font-weight: 600; border-radius: 1rem !important; }
        .accordion-button::after { background-size: 1rem; }
    </style>
</head>
<body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm" id="mainNav" style="background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
        <div class="container px-5">
            <a class="navbar-brand fw-bold text-success fs-3" href="#page-top"><i class="bi-egg-bounce me-2 text-warning"></i>RA Al Futuh</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu <i class="bi-list"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto me-4 my-3 my-lg-0 fw-bold">
                    <li class="nav-item"><a class="nav-link me-lg-3 text-secondary" href="#keunggulan">Keunggulan</a></li>
                    <li class="nav-item"><a class="nav-link me-lg-3 text-secondary" href="#berita">Kabar Sekolah</a></li>
                    <li class="nav-item"><a class="nav-link me-lg-3 text-secondary" href="#faq">Tanya Jawab</a></li>
                    <li class="nav-item"><a class="nav-link me-lg-3 text-secondary" href="#ppdb">Pendaftaran (PPDB)</a></li>
                </ul>

                @auth
                    <!-- Tombol jika sudah login -->
                    <a class="btn btn-success-tk rounded-pill px-4 mb-2 mb-lg-0 shadow-sm" href="{{ route('dashboard') }}">
                        <span class="d-flex align-items-center">
                            <i class="bi-speedometer2 me-2"></i>
                            <span class="small">Dashboard</span>
                        </span>
                    </a>
                @else
                    <!-- Tombol Login Portal -->
                    <a class="btn btn-success-tk rounded-pill px-4 mb-2 mb-lg-0 shadow-sm" href="{{ route('login') }}">
                        <span class="d-flex align-items-center">
                            <i class="bi-box-arrow-in-right me-2"></i>
                            <span class="small">Login Portal</span>
                        </span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Masthead Header -->
    <header class="masthead">
        <div class="container px-5">
            <div class="row gx-5 align-items-center">
                <div class="col-lg-6">
                    <div class="mb-5 mb-lg-0 text-center text-lg-start">
                        <span class="badge bg-pastel-green text-success px-3 py-2 rounded-pill mb-3 fw-bold"><i class="bi-stars me-2 text-warning"></i> Penerimaan Siswa Baru Dibuka!</span>
                        <h1 class="display-3 lh-sm mb-3 fw-bold">Pendidikan <span class="text-gradient">Usia Dini</span> Berbasis Islami.</h1>
                        <p class="lead fw-normal text-muted mb-5">Sistem Informasi Akademik & Portal Terpadu RA Al Futuh. Membangun generasi cerdas, ceria, kreatif, dan berakhlak mulia sejak usia dini.</p>
                        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center justify-content-lg-start">
                            <a class="btn btn-success-tk py-3 px-4 rounded-pill me-sm-3 mb-3 mb-sm-0 shadow-lg fw-bold" href="#ppdb"><i class="bi-person-plus me-2"></i>Daftar Online (PPDB)</a>
                            <a class="btn btn-outline-dark py-3 px-4 rounded-pill fw-bold" href="{{ route('login') }}"><i class="bi-emoji-smile me-2 text-warning"></i>Portal Wali Murid</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center position-relative">
                    <div class="position-relative d-inline-block">
                        <!-- Frame Foto Lingkaran Playful -->
                        <img class="img-fluid rounded-circle shadow-lg position-relative" style="max-width: 80%; border: 15px solid #ffffff; z-index: 2;" src="https://ui-avatars.com/api/?name=RA+Al+Futuh&background=1cc88a&color=fff&size=400&rounded=true" alt="Ilustrasi RA Al Futuh" />
                        <svg class="position-absolute blob-decorator" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="46" fill="#f0fdf4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- [FITUR BARU] Section Gelombang Atas -->
    <div class="wave-container bg-light">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#ffffff"></path>
        </svg>
    </div>

    <!-- [FITUR BARU] Section Keunggulan RA Al Futuh -->
    <section class="bg-light pb-5" id="keunggulan">
        <div class="container px-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-2 text-dark">Mengapa Memilih <span class="text-success">RA Al Futuh?</span></h2>
                <p class="lead text-muted">Kami menghadirkan lingkungan belajar yang ideal untuk tumbuh kembang ananda tercinta.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card card-custom h-100 p-4 text-center">
                        <div class="rounded-circle bg-pastel-green text-success mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi-heart-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Nilai Islami</h5>
                        <p class="text-muted small mb-0">Pembiasaan sholat dhuha, hafalan doa harian, dan pengenalan huruf hijaiyah dengan cara menyenangkan.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card card-custom h-100 p-4 text-center">
                        <div class="rounded-circle bg-pastel-blue text-info mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi-controller fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Bermain & Belajar</h5>
                        <p class="text-muted small mb-0">Kurikulum berbasis stimulasi motorik dan kreativitas lewat aktivitas bermain kelompok yang interaktif.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card card-custom h-100 p-4 text-center">
                        <div class="rounded-circle bg-pastel-yellow text-warning mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi-people-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Guru Berpengalaman</h5>
                        <p class="text-muted small mb-0">Dididik oleh ustadzah yang sabar, telaten, menyayangi anak-anak, dan kompeten di bidang PAUD.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card card-custom h-100 p-4 text-center">
                        <div class="rounded-circle bg-pastel-green text-primary mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background-color: #f5f3ff !important; color: #8b5cf6 !important;">
                            <i class="bi-laptop fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Portal Digital Pantauan</h5>
                        <p class="text-muted small mb-0">Kemudahan bagi Ayah & Bunda untuk memantau administrasi keuangan dan raport perkembangan lewat smartphone.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Berita & Informasi Section -->
    <section class="bg-white pt-5 pb-5" id="berita">
        <div class="container px-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-2 text-dark"><i class="bi-journal-text text-warning me-2"></i>Kabar Terbaru Sekolah</h2>
                <p class="lead text-muted">Ikuti terus keseruan dokumentasi kegiatan belajar dan pengumuman resmi kami.</p>
            </div>

            <div class="row gx-5 justify-content-center">
                @forelse($beritaTerbaru as $berita)
                    <div class="col-lg-4 col-md-6 mb-5">
                        <div class="card card-custom h-100">
                            @if($berita->gambar)
                                <img src="{{ asset('storage/berita/' . $berita->gambar) }}" class="berita-img" alt="{{ $berita->judul }}">
                            @else
                                <img src="https://via.placeholder.com/600x400.png?text=Berita+Sekolah" class="berita-img" alt="Placeholder">
                            @endif
                            <div class="card-body p-4">
                                <div class="small text-muted mb-2"><i class="bi-calendar-event me-2 text-success"></i>{{ \Carbon\Carbon::parse($berita->created_at)->format('d F Y') }}</div>
                                <h5 class="card-title fw-bold text-dark">{{ $berita->judul }}</h5>
                                <p class="card-text text-muted small">{{ Str::limit(strip_tags($berita->konten), 90) }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-top-0 px-4 pb-4">
                                <button type="button" class="btn btn-outline-success-tk rounded-pill btn-sm w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#beritaModal{{ $berita->id }}">
                                    Baca Selengkapnya
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL DETAIL BERITA -->
                    <div class="modal fade" id="beritaModal{{ $berita->id }}" tabindex="-1" aria-labelledby="beritaModalLabel{{ $berita->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content text-start text-dark" style="border-radius: 1.5rem; border: none;">
                                <div class="modal-header border-0 bg-light p-4">
                                    <h5 class="modal-title fw-bold text-success" id="beritaModalLabel{{ $berita->id }}">{{ $berita->judul }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    @if($berita->gambar)
                                        <img src="{{ asset('storage/berita/' . $berita->gambar) }}" class="w-100 mb-4 rounded-4 shadow-sm" style="max-height: 380px; object-fit: cover;" alt="{{ $berita->judul }}">
                                    @endif
                                    <div class="small text-muted mb-3 pb-2 border-bottom">
                                        <i class="bi-calendar-event me-2"></i>Diterbitkan pada: {{ \Carbon\Carbon::parse($berita->created_at)->format('d F Y') }}
                                    </div>
                                    <div class="lh-base text-secondary" style="white-space: pre-line;">
                                        {!! $berita->konten !!}
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0">
                                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Tutup Berita</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="bi-journal-x display-4 mb-3 d-block text-secondary opacity-50"></i>
                        <p class="lead">Belum ada berita atau pengumuman yang diterbitkan.</p>
                    </div>
                @endforelse
            </div> <!-- Penutup <div class="row"> berita -->

            <!-- ==================== [FITUR BARU] TOMBOL MENUJU HALAMAN BERITA ==================== -->
            @if($beritaTerbaru->count() >= 3)
                <div class="text-center mt-5">
                    <a href="{{ route('berita.semua') }}" class="btn btn-success-tk rounded-pill px-5 py-3 fw-bold shadow-lg">
                        Lihat Semua Berita <i class="bi-arrow-right ms-2"></i>
                    </a>
                </div>
            @endif
            <!-- =================================================================================== -->

        </div>
    </section>

    <!-- [FITUR BARU] Section FAQ (Pertanyaan Umum) -->
    <section class="bg-light pt-5 pb-5" id="faq">
        <div class="container px-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-2 text-dark"><i class="bi-question-circle text-info me-2"></i>Pertanyaan Yang Sering Diajukan (FAQ)</h2>
                <p class="lead text-muted">Informasi ringkas seputar kendala dan pertanyaan umum dari Ayah & Bunda.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="accordionFAQ">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                    Berapa batasan usia minimal anak untuk mendaftar di RA Al Futuh?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-muted">
                                    Untuk kelompok bermain (KB/Playgroup) minimal berusia 3 tahun, sedangkan untuk kelompok TK-A minimal berusia 4 tahun dan TK-B minimal berusia 5 tahun pada bulan Juli tahun berjalan.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                    Bagaimana cara orang tua melihat catatan pembayaran SPP anak?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-muted">
                                    Ayah & Bunda bisa masuk ke menu <strong>Login Portal</strong> di atas menggunakan akun email yang didaftarkan saat PPDB. Di dalam dasbor, menu riwayat Buku Kas dan tagihan bulanan tercatat transparan secara realtime.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                    Apakah pendaftaran online ini wajib mengunggah berkas fisik asli?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body text-muted">
                                    Cukup unggah foto berkas yang jelas atau hasil scan dokumen pelengkap (Pas foto anak, Kartu Keluarga, dan Akta Kelahiran) dalam format JPG atau PDF melalui formulir di bawah ini. Dokumen fisik asli nanti dikumpulkan saat hari pertama masuk sekolah.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Gelombang Bawah Sebelum PPDB -->
    <div class="wave-container bg-gradient-primary-to-secondary">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#f8f9fa"></path>
        </svg>
    </div>

    <!-- CTA / PPDB Section -->
    <section class="bg-gradient-primary-to-secondary pb-5 text-white" id="ppdb">
        <div class="container px-5">
            <div class="text-center text-white mb-5">
                <h2 class="display-4 fw-bold mb-3">Formulir Pendaftaran PPDB</h2>
                <p class="lead opacity-75">Isi data calon siswa di bawah ini dengan lengkap dan benar untuk mendapatkan kuota kelas belajar.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success bg-white text-success fw-bold border-0 shadow-sm mb-4 rounded-3">
                    <i class="bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif
           @if($errors->any())
                <div class="alert alert-danger bg-white text-danger fw-bold border-0 shadow-sm mb-4 rounded-3">
                    <i class="bi-exclamation-triangle-fill me-2"></i> Terdapat kesalahan pengisian data:
                    <ul class="mb-0 mt-2 text-start fw-normal small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-lg" style="border-radius: 2rem;">
                <div class="card-body p-5 text-dark">
                    <form action="{{ route('ppdb.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h5 class="fw-bold text-success border-bottom pb-2 mb-4"><i class="bi-person-badge me-2 text-warning"></i>Data Calon Siswa</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Nama Lengkap Anak</label>
                                <input type="text" name="nama_lengkap" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Nama Panggilan</label>
                                <input type="text" name="nama_panggilan" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">NIK / No. Akta Kelahiran</label>
                                <input type="text" name="nik_atau_akta" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select rounded-3" required>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control rounded-3" required>
                            </div>
                        </div>

                        <h5 class="fw-bold text-success border-bottom pb-2 mb-4 mt-5"><i class="bi-people me-2 text-warning"></i>Data Orang Tua / Wali</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Nama Ayah</label>
                                <input type="text" name="nama_ayah" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Nama Ibu</label>
                                <input type="text" name="nama_ibu" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Pekerjaan Utama Orang Tua</label>
                                <input type="text" name="pekerjaan_orang_tua" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Email (Gunakan Email Aktif!)</label>
                                <input type="email" name="email_ortu" class="form-control rounded-3" placeholder="Untuk login wali murid nanti" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">No. HP (WhatsApp)</label>
                                <input type="text" name="no_hp_ortu" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label text-muted small fw-bold">Alamat Lengkap</label>
                                <textarea name="alamat_lengkap" class="form-control rounded-3" rows="3" required></textarea>
                            </div>
                        </div>

                        <h5 class="fw-bold text-success border-bottom pb-2 mb-4 mt-5"><i class="bi-folder me-2 text-warning"></i>Unggah Dokumen</h5>
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted small fw-bold">Pas Foto Anak (JPG/PNG)</label>
                                <input type="file" name="file_foto" class="form-control" accept=".jpg,.jpeg,.png" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted small fw-bold">Scan/Foto KK (PDF/JPG)</label>
                                <input type="file" name="file_kk" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted small fw-bold">Scan/Foto Akta (PDF/JPG)</label>
                                <input type="file" name="file_akta" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-success-tk btn-lg rounded-pill fw-bold py-3 shadow-lg">
                                <i class="bi-send-fill me-2"></i> Kirim Pendaftaran Online
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-center py-5 text-white-50">
        <div class="container px-5">
            <div class="small">
                <div class="mb-2 text-white">&copy; RA Al Futuh {{ date('Y') }}. All Rights Reserved.</div>
                <a href="#!" class="text-white-50 text-decoration-none">Kebijakan Privasi</a>
                <span class="mx-1">&middot;</span>
                <a href="#!" class="text-white-50 text-decoration-none">Syarat & Ketentuan</a>
                <span class="mx-1">&middot;</span>
                <a href="#!" class="text-white-50 text-decoration-none">Kontak Kami</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Kustom Sederhana untuk Efek Navbar -->
    <script>
        window.addEventListener('scroll', function() {
            var navbar = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                navbar.classList.add('shadow');
            } else {
                navbar.classList.remove('shadow');
            }
        });
    </script>
</body>
</html>
