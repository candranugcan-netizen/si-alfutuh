<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Kabar & Informasi - RA Al Futuh</title>

    <!-- Bootstrap Icons & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Mulish:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Mulish', sans-serif; background-color: #f8f9fa; color: #495057; }
        h1, h2, h3, h4, h5, h6, .navbar-brand { font-family: 'Fredoka', sans-serif; }

        .btn-success-tk { background-color: #1cc88a; border-color: #1cc88a; color: white; }
        .btn-success-tk:hover { background-color: #17a673; border-color: #17a673; color: white; }
        .btn-outline-success-tk { border-color: #1cc88a; color: #1cc88a; }
        .btn-outline-success-tk:hover { background-color: #1cc88a; color: white; }

        .bg-pastel-green { background-color: #f0fdf4; }
        .berita-img { height: 220px; object-fit: cover; width: 100%; border-radius: 1.25rem 1.25rem 0 0; }
        .card-custom { border: none; border-radius: 1.5rem; transition: all 0.3s ease; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); }
        .card-custom:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(28, 200, 138, 0.12); }
    </style>
</head>
<body>

    <!-- Navigation Bar Ringkas -->
    <nav class="navbar navbar-light bg-white shadow-sm sticky-top">
        <div class="container px-5">
            <a class="navbar-brand fw-bold text-success fs-3" href="/"><i class="bi-arrow-left-circle me-2 text-warning"></i> Kembali ke Beranda</a>
        </div>
    </nav>

    <!-- Header Jumbotron Kecil -->
    <header class="bg-pastel-green py-5 mb-5 text-center">
        <div class="container px-5">
            <span class="badge bg-white text-success px-3 py-2 rounded-pill mb-2 fw-bold shadow-sm">Arsip Dokumentasi</span>
            <h1 class="display-4 fw-bold text-dark">Seluruh Kabar RA Al Futuh</h1>
            <p class="lead text-muted mb-0">Menampilkan ruang informasi dan cerita ceria kegiatan ananda di sekolah.</p>
        </div>
    </header>

    <!-- Main Content Grid Berita -->
    <main class="container px-5 pb-5">
        <div class="row g-4 justify-content-center">
            @forelse($allBerita as $berita)
                <div class="col-lg-4 col-md-6">
                    <div class="card card-custom h-100 bg-white">
                        @if($berita->gambar)
                            <img src="{{ asset('uploads/berita/' . $berita->gambar) }}" class="berita-img" alt="{{ $berita->judul }}">
                        @else
                            <img src="https://via.placeholder.com/600x400.png?text=Berita+Sekolah" class="berita-img" alt="Placeholder">
                        @endif
                        <div class="card-body p-4">
                            <div class="small text-muted mb-2"><i class="bi-calendar-event me-2 text-success"></i>{{ \Carbon\Carbon::parse($berita->created_at)->format('d F Y') }}</div>
                            <h5 class="card-title fw-bold text-dark">{{ $berita->judul }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit(strip_tags($berita->konten), 120) }}</p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 px-4 pb-4">
                            <button type="button" class="btn btn-outline-success-tk rounded-pill btn-sm w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#beritaModal{{ $berita->id }}">
                                Baca Selengkapnya
                            </button>
                        </div>
                    </div>
                </div>

                <!-- MODAL POP UP UTUTK DETAIL BERITA -->
                <div class="modal fade" id="beritaModal{{ $berita->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content text-start text-dark" style="border-radius: 1.5rem; border: none;">
                            <div class="modal-header border-0 bg-light p-4">
                                <h5 class="modal-title fw-bold text-success">{{ $berita->judul }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                @if($berita->gambar)
                                    <img src="{{ asset('uploads/berita/' . $berita->gambar) }}" class="w-100 mb-4 rounded-4 shadow-sm" style="max-height: 380px; object-fit: cover;" alt="{{ $berita->judul }}">
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
                    <p class="lead">Belum ada arsip berita yang ditemukan.</p>
                </div>
            @endforelse
        </div>

        <!-- ==================== AREA NAVIGASI BLOK PAGINATION LARAVEL ==================== -->
        <div class="d-flex justify-content-center mt-5 pt-4">
            {{ $allBerita->links() }}
        </div>
        <!-- =============================================================================== -->
    </main>

    <!-- Footer Ringkas -->
    <footer class="bg-dark text-center py-4 text-white-50 mt-5">
        <div class="container small">
            <div>&copy; RA Al Futuh {{ date('Y') }}. Arsip Berita Terbuka.</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
