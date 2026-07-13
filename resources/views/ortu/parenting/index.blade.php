@extends('layouts.app') @section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-1">Ruang Edukasi Orang Tua</h3>
    <p class="text-muted mb-4">Temukan berbagai tips, artikel, dan video menarik seputar dunia parenting anak usia dini.</p>

    <div class="row">
        @forelse($materi as $item)
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-3">
                <div class="card-body d-flex flex-column p-4">
                    <span class="badge bg-light text-primary align-self-start mb-2 text-capitalize">
                        <i class="bi {{ $item->tipe == 'pdf' ? 'bi-file-earmark-pdf-fill' : 'bi-play-btn-fill' }} me-1"></i> {{ $item->tipe }}
                    </span>
                    <h5 class="fw-bold text-dark mb-2">{{ $item->judul }}</h5>
                    <p class="text-muted small flex-grow-1">{{ Str::limit($item->deskripsi, 100) }}</p>
                    <a href="{{ route('ortu.parenting.show', $item->id) }}" class="btn btn-primary btn-sm w-100 rounded-pill mt-3 fw-bold">
                        Buka Materi
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5 text-muted">
            Belum ada materi parenting yang dibagikan oleh sekolah.
        </div>
        @endforelse
    </div>
</div>
@endsection
