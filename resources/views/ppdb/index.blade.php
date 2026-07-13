@extends('layouts.app') <!-- Sesuaikan dengan nama file layout admin Anda -->

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"><i class="bi-inbox-fill me-2 text-primary"></i>Kelola Data PPDB</h2>
    </div>

    <!-- Pesan Sukses/Error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col">No. Daftar</th>
                            <th scope="col">Nama Calon Siswa</th>
                            <th scope="col">Data Orang Tua</th>
                            <th scope="col" class="text-center">Dokumen</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftar as $index => $item)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                            <td><span class="badge">{{ $item->no_pendaftaran }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->nama_lengkap }}</div>
                                <div class="small text-muted">{{ $item->jenis_kelamin }} | {{ $item->tanggal_lahir }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $item->nama_ayah }}</div>
                                <div class="small text-muted"><i class="bi-whatsapp me-1 text-success"></i>{{ $item->no_hp_ortu }}</div>
                            </td>
                            <td class="text-center">
                                <!-- Tombol Lihat Dokumen -->
                                <a href="{{ asset('uploads/ppdb/' . $item->file_kk) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-1" title="Lihat KK">KK</a>
                                <a href="{{ asset('uploads/ppdb/' . $item->file_akta) }}" target="_blank" class="btn btn-sm btn-outline-info mb-1" title="Lihat Akta">Akta</a>
                                <a href="{{ asset('uploads/ppdb/' . $item->file_foto) }}" target="_blank" class="btn btn-sm btn-outline-success mb-1" title="Lihat Foto">Foto</a>
                            </td>
                            <td class="text-center">
                                @if($item->status == 'Pending')
                                    <span class="badge bg-warning text-white"><i class="bi-hourglass-split me-1"></i>Pending</span>
                                @elseif($item->status == 'Diterima')
                                    <span class="badge bg-success text-white"><i class="bi-check-circle me-1"></i>Diterima</span>
                                @else
                                    <span class="badge bg-danger text-white"><i class="bi-x-circle me-1"></i>Ditolak</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status == 'Pending')
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Form Terima -->
                                        <form action="{{ route('ppdb.terima', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success fw-bold" onclick="return confirm('Terima siswa ini? Data akan otomatis masuk ke master Siswa & User.')">
                                                <i class="bi-check-lg"></i> Terima
                                            </button>
                                        </form>

                                        <!-- Form Tolak -->
                                        <form action="{{ route('ppdb.tolak', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger fw-bold" onclick="return confirm('Yakin ingin menolak pendaftar ini?')">
                                                <i class="bi-x-lg"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small fst-italic">Selesai diproses</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi-inbox display-4 d-block mb-2"></i>
                                Belum ada data pendaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
