@extends('layouts.auth')

@section('content')
<div class="container py-4 my-auto">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11 col-md-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden my-4" style="border: 1px solid var(--border-light) !important;">
                <div class="row g-0">
                    <!-- Panel Branding Kiri (Desktop) -->
                    <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between p-5 text-white" style="background-color: var(--brand-green-deep);">
                        <div>
                            <!-- Header Logo & Nama -->
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="bg-white rounded-circle p-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 58px; height: 58px;">
                                    <img src="{{ asset('img/logo.png') }}" alt="Logo RA Al Futuh" style="width: 44px; height: 44px; object-fit: contain;">
                                </div>
                                <div>
                                    <h4 class="font-heading fw-bold text-white mb-0 lh-1">RA ISLAM AL FUTUH</h4>
                                    <small class="text-warning text-uppercase fw-semibold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Pondasi Generasi Qur'ani</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <span class="badge py-2 px-3 fw-semibold text-uppercase" style="background-color: rgba(255, 255, 255, 0.12); color: #ecfdf5; letter-spacing: 0.05em; font-size: 0.75rem; border-radius: var(--radius-pill);">
                                    <i class="bi-shield-lock-fill text-warning me-1"></i> Portal Administrasi Terpadu
                                </span>
                            </div>

                            <p class="text-white-50 small mb-4 lh-base">
                                Akses tunggal untuk Pendidik, Staf Administrasi, Bendahara Komite, dan Wali Murid RA Islam Al Futuh.
                            </p>

                            <!-- Fitur Unggulan -->
                            <div class="d-flex flex-column gap-3 pt-2">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="bi-check-circle-fill text-warning fs-5 flex-shrink-0"></i>
                                    <div>
                                        <strong class="text-white small d-block">E-Rapor & Perkembangan Anak</strong>
                                        <span class="text-white-50" style="font-size: 0.78rem;">Pencatatan hafalan surah, hadits, doa harian, dan narasi tumbuh kembang.</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start gap-2">
                                    <i class="bi-check-circle-fill text-warning fs-5 flex-shrink-0"></i>
                                    <div>
                                        <strong class="text-white small d-block">Manajemen Keuangan & SPP</strong>
                                        <span class="text-white-50" style="font-size: 0.78rem;">Pencatatan kas masuk, pengeluaran komite, dan nota transaksi transparan.</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start gap-2">
                                    <i class="bi-check-circle-fill text-warning fs-5 flex-shrink-0"></i>
                                    <div>
                                        <strong class="text-white small d-block">Terintegrasi & Aman</strong>
                                        <span class="text-white-50" style="font-size: 0.78rem;">Data tersimpan aman pada basis data resmi terenkripsi.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Visi Kiri -->
                        <div class="pt-4 mt-4 border-top border-white border-opacity-10">
                            <small class="text-white-50 d-block mb-1" style="font-size: 0.72rem;">Visi Sekolah:</small>
                            <p class="text-white small mb-0 fw-semibold fst-italic">
                                "Mencetak Generasi Yang Islami, Mandiri, dan Berprestasi."
                            </p>
                        </div>
                    </div>

                    <!-- Form Login Kanan -->
                    <div class="col-lg-7 bg-white p-4 p-md-5 d-flex flex-column justify-content-between">
                        <div>
                            <!-- Tombol Kembali ke Beranda -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <a href="{{ url('/') }}" class="text-muted small text-decoration-none d-inline-flex align-items-center gap-1 hover-green">
                                    <i class="bi-arrow-left"></i>
                                    <span>Kembali ke Beranda</span>
                                </a>
                                <span class="badge bg-light text-secondary border small">Versi {{ date('Y') }}</span>
                            </div>

                            <!-- Header Mobile (Hanya Tampil di Layar Kecil) -->
                            <div class="d-lg-none text-center mb-4 pb-3 border-bottom">
                                <img src="{{ asset('img/logo.png') }}" alt="Logo RA Al Futuh" width="60" height="60" class="mb-2" style="object-fit: contain;">
                                <h4 class="font-heading fw-bold mb-0 text-dark">RA ISLAM AL FUTUH</h4>
                                <small class="text-muted">Sistem Informasi Manajemen Sekolah</small>
                            </div>

                            <div class="mb-4">
                                <h3 class="font-heading fw-bold text-dark mb-1">Masuk ke Sistem</h3>
                                <p class="text-muted small mb-0">Silakan masukkan alamat email dan kata sandi akun Anda.</p>
                            </div>

                            <!-- Alert Sesi Habis / Warning -->
                            @if (session('warning') || request()->query('expired'))
                                <div class="alert alert-warning border-0 d-flex align-items-center gap-2 py-3 px-3 rounded-3 mb-4" role="alert" style="background-color: #fefce8; border-left: 4px solid #d97706 !important; color: #854d0e;">
                                    <i class="bi-clock-history fs-5 flex-shrink-0 text-warning"></i>
                                    <div class="small fw-semibold">
                                        {{ session('warning') ?? 'Sesi Anda telah berakhir karena tidak ada aktivitas. Silakan masuk kembali.' }}
                                    </div>
                                </div>
                            @endif

                            <!-- Alert Sukses / Status -->
                            @if (session('status'))
                                <div class="alert alert-success border-0 d-flex align-items-center gap-2 py-3 px-3 rounded-3 mb-4" role="alert" style="background-color: #ecfdf5; border-left: 4px solid #15803d !important; color: #166534;">
                                    <i class="bi-check-circle-fill fs-5 flex-shrink-0 text-success"></i>
                                    <div class="small fw-semibold">
                                        {{ session('status') }}
                                    </div>
                                </div>
                            @endif

                            <!-- Alert Error Validasi -->
                            @if ($errors->any())
                                <div class="alert alert-danger border-0 d-flex align-items-center gap-2 py-3 px-3 rounded-3 mb-4" role="alert" style="background-color: #fef2f2; border-left: 4px solid #ef4444 !important; color: #991b1b;">
                                    <i class="bi-exclamation-triangle-fill fs-5 flex-shrink-0 text-danger"></i>
                                    <div class="small">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Form Autentikasi -->
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label-custom" for="email">
                                        <i class="bi-envelope text-success"></i> Alamat Email
                                    </label>
                                    <div class="input-group">
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="Masukkan Email Terdaftar" 
                                               required 
                                               autofocus>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-custom" for="password">
                                        <i class="bi-lock text-success"></i> Kata Sandi
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Masukkan kata sandi..." 
                                               required>
                                        <button class="btn btn-outline-secondary border-start-0 bg-white" 
                                                type="button" 
                                                id="togglePassword" 
                                                style="border-color: #d1d5db; border-top-right-radius: 0.75rem; border-bottom-right-radius: 0.75rem;"
                                                title="Lihat / Sembunyikan Kata Sandi">
                                            <i class="bi-eye text-muted" id="toggleIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label small text-muted" for="remember">
                                            Ingat Saya
                                        </label>
                                    </div>
                                    <a href="https://wa.me/6285864480331?text=Halo%20Admin%20RA%20Al%20Futuh,%20saya%20butuh%20bantuan%20login%20akun" 
                                       target="_blank" 
                                       class="text-decoration-none small text-success fw-semibold d-inline-flex align-items-center gap-1 hover-green">
                                        <i class="bi-whatsapp"></i> Butuh Bantuan?
                                    </a>
                                </div>

                                <button type="submit" class="btn btn-brand-primary w-100 py-3 fs-6 d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi-box-arrow-in-right fs-5"></i>
                                    <span>Masuk ke Dashboard</span>
                                </button>
                            </form>
                        </div>

                        <!-- Footer Kanan -->
                        <div class="mt-4 pt-4 border-top text-center text-muted small">
                            <div class="mb-2">
                                Calon wali murid belum terdaftar? 
                                <a href="{{ url('/#ppdb') }}" class="text-success fw-bold text-decoration-none hover-green">
                                    Formulir PPDB Online <i class="bi-arrow-right"></i>
                                </a>
                            </div>
                            <span class="text-secondary" style="font-size: 0.75rem;">
                                &copy; {{ date('Y') }} RA Islam Al Futuh Semin, Gunungkidul. Hak Cipta Dilindungi.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (togglePassword && passwordInput && toggleIcon) {
            togglePassword.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.classList.toggle('bi-eye', !isPassword);
                toggleIcon.classList.toggle('bi-eye-slash', isPassword);
            });
        }
    });
</script>
@endpush
