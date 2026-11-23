<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="1;url={{ route('login') }}?expired=1">
    <title>Sesi Telah Berakhir - RA Islam Al Futuh</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .btn-brand-primary {
            background-color: #15803d;
            color: #ffffff;
            font-weight: 700;
            border-radius: 9999px;
            padding: 0.75rem 1.75rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }
        .btn-brand-primary:hover {
            background-color: #0d4a2b;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mx-auto text-center" style="max-width: 480px; border: 1px solid #e2e8f0 !important; background: #ffffff;">
        <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: #fefce8; color: #d97706; font-size: 2.2rem;">
            <i class="bi-clock-history"></i>
        </div>
        <h3 class="fw-bold text-dark mb-2" style="font-family: 'Outfit', sans-serif;">Sesi Telah Berakhir</h3>
        <p class="text-muted small mb-4">Sesi login Anda telah habis karena tidak ada aktivitas. Anda dialihkan secara otomatis ke halaman login...</p>
        <div class="spinner-border text-success mb-4" role="status">
            <span class="visually-hidden">Mengalihkan...</span>
        </div>
        <div>
            <a href="{{ route('login') }}?expired=1" class="btn-brand-primary">
                <i class="bi-box-arrow-in-right"></i>
                <span>Kembali ke Halaman Login</span>
            </a>
        </div>
    </div>

    <script>
        setTimeout(function() {
            window.location.href = "{{ route('login') }}?expired=1";
        }, 1000);
    </script>
</body>
</html>
