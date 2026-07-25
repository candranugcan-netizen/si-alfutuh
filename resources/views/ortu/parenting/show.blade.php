@extends('layouts.app') @section('content')

<style>
    /* Tombol Kembali */
    .btn-back {
        background-color: #ffffff;
        color: #2e7d32;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .btn-back:hover {
        background-color: #edf7ed;
        color: #1b5e20;
        border-color: #c8e6c9;
    }

    /* Pengunci Video 16:9 Mutlak */
    .video-wrapper-absolute {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%; /* Rasio 16:9 sejati */
        height: 0;
        overflow: hidden;
        border-radius: 12px 12px 0 0;
    }
    .video-wrapper-absolute iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    /* Pengunci Tinggi Flipbook */
    ._df_book {
        height: 650px !important;
        width: 100% !important;
        border-radius: 12px;
        background-color: #f8f9fa;
        box-shadow: inset 0 0 20px rgba(0,0,0,0.05);
    }
</style>
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('ortu.parenting.index') }}" class="btn btn-sm btn-back rounded-pill px-3 shadow-sm">
            <i class="bi-arrow-left me-2"></i>Kembali ke Daftar Materi
        </a>
    </div>

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">{{ $materi->judul }}</h2>
        @if($materi->deskripsi)
            <p class="text-muted fs-6">{{ $materi->deskripsi }}</p>
        @endif
    </div>

    <div class="row">
        <div class="col-12">

            @if($materi->tipe == 'video')
                <div style="max-width: 850px; margin: 0 auto; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border-radius: 12px; background: #000;">

                    <div class="video-wrapper-absolute">
                        <iframe
                            src="https://www.youtube.com/embed/{{ $materi->link_youtube }}?rel=0&modestbranding=1"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>

                    <div class="p-3 d-flex justify-content-between align-items-center" style="background: #1a1a1a; border-radius: 0 0 12px 12px;">
                        <small class="text-white-50"><i class="bi-info-circle me-2"></i>Klik tombol putar untuk menonton</small>
                        <span class="badge bg-danger"><i class="bi-youtube me-1"></i>YouTube</span>
                    </div>
                </div>

            @elseif($materi->tipe == 'pdf')
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-2 p-md-4 bg-light">

                        <div id="loading-text" class="text-center py-5 text-muted">
                            <div class="spinner-border text-success mb-2" role="status"></div>
                            <p>Menyiapkan Buku Digital...</p>
                        </div>

                        <div class="_df_book" source="{{ asset('storage/materi_parenting/' . $materi->file_pdf) }}" id="buku_digital"></div>

                    </div>
                    <div class="card-footer bg-white border-0 p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted">
                            <i class="bi-hand-index-thumb me-2 text-success"></i>Gunakan klik atau geser ujung halaman
                        </small>
                        <a href="{{ asset('storage/materi_parenting/' . $materi->file_pdf) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3">
                            <i class="bi-download me-2"></i>Unduh / Buka PDF
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@if($materi->tipe == 'pdf')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/deepakghimire/dflip-free@v1.0.28/css/dflip.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/deepakghimire/dflip-free@v1.0.28/css/themify-icons.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/gh/deepakghimire/dflip-free@v1.0.28/js/dflip.min.js"></script>

    <script>
        // Sembunyikan tulisan loading ketika buku mulai dirender
        setTimeout(function() {
            document.getElementById('loading-text').style.display = 'none';
        }, 1500);
    </script>
@endif
@endsection
