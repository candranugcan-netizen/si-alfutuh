@extends('layouts.auth')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5" style="border-radius: 1rem;">
            <div class="card-body p-0">
                <div class="row">
                    <!-- Panel Branding Kiri -->
                    <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-center bg-gradient-success text-white p-5" style="border-top-left-radius: 1rem; border-bottom-left-radius: 1rem;">
                        <i class="fas fa-school fa-5x mb-4 text-white-50"></i>
                        <h2 class="font-weight-bold text-center">RA Al Futuh</h2>
                        <p class="text-center mt-2" style="font-size: 1.1rem; opacity: 0.9;">
                            Sistem Informasi Manajemen<br>Akademik & Keuangan
                        </p>
                    </div>

                    <!-- Form Login Kanan -->
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <h1 class="h4 text-gray-900 mb-2">Selamat Datang</h1>
                                <p class="text-muted">Silakan masuk ke akun Anda</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger" style="border-radius: 0.5rem;">
                                    <ul class="mb-0 pl-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form class="user" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" name="email" value="{{ old('email') }}" placeholder="Masukkan Alamat Email..." required autofocus>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" name="password" placeholder="Password" required>
                                </div>

                                <!-- Tambahan Checkbox Remember Me -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" id="customCheck" name="remember">
                                        <label class="custom-control-label" for="customCheck">Ingat Saya</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block shadow-sm">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <span class="small text-muted">© {{ date('Y') }} RA Al Futuh. Hak Cipta Dilindungi.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
