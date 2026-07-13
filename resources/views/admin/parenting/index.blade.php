@extends('layouts.app')

@section('content')
<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Tambah Materi Parenting</h5>
                    <form action="{{ route('admin.parenting.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Judul Materi</label>
                            <input type="text" name="judul" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Deskripsi Singkat</label>
                            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Tipe Materi</label>
                            <select name="tipe" id="tipeMateri" class="form-select" required>
                                <option value="pdf">Dokumen (PDF)</option>
                                <option value="video">Video (YouTube Link)</option>
                            </select>
                        </div>

                        <div class="mb-3" id="inputPdf">
                            <label class="form-label small fw-bold">File PDF (Maks 10MB)</label>
                            <input type="file" name="file_pdf" class="form-control" accept=".pdf">
                        </div>

                        <div class="mb-3 d-none" id="inputVideo">
                            <label class="form-label small fw-bold">Link Video YouTube</label>
                            <input type="url" name="link_youtube" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold mt-2">Simpan Materi</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Daftar Materi</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Tipe</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materi as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $item->judul }}</div>
                                        <small class="text-muted">{{ Str::limit($item->deskripsi, 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->tipe == 'pdf' ? 'bg-danger text-white' : 'bg-danger text-white' }} bg-opacity-100 text-capitalize">
                                            {{ $item->tipe }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.parenting.destroy', $item->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus materi ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('tipeMateri').addEventListener('change', function() {
        if(this.value === 'pdf') {
            document.getElementById('inputPdf').classList.remove('d-none');
            document.getElementById('inputVideo').classList.add('d-none');
        } else {
            document.getElementById('inputPdf').classList.add('d-none');
            document.getElementById('inputVideo').classList.remove('d-none');
        }
    });
</script>
@endsection
