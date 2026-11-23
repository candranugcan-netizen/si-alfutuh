<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Masuk Sistem Informasi - RA Islam Al Futuh</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --brand-green-deep: #0d4a2b;
            --brand-green-primary: #15803d;
            --brand-green-medium: #166534;
            --brand-green-subtle: #ecfdf5;
            --brand-green-border: #bbf7d0;
            --brand-gold-primary: #d97706;
            --brand-gold-deep: #b45309;
            --brand-gold-subtle: #fef3c7;
            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --border-light: #e2e8f0;
            --radius-pill: 9999px;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--text-body);
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
            color: var(--text-heading);
        }

        /* Flat Brand Buttons */
        .btn-brand-primary {
            background-color: var(--brand-green-primary);
            color: #ffffff;
            font-weight: 700;
            border: none;
            border-radius: var(--radius-pill);
            padding: 0.75rem 1.6rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
        }
        .btn-brand-primary:hover {
            background-color: var(--brand-green-deep);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
        }

        .btn-brand-outline {
            border: 1.5px solid var(--border-light);
            color: var(--text-heading);
            background: #ffffff;
            font-weight: 600;
            border-radius: var(--radius-pill);
            padding: 0.65rem 1.4rem;
            transition: all 0.2s ease;
        }
        .btn-brand-outline:hover {
            border-color: var(--brand-green-primary);
            color: var(--brand-green-primary);
            background: var(--brand-green-subtle);
        }

        /* Form Controls */
        .form-control {
            border: 1.5px solid #d1d5db;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            color: #1e293b;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: var(--brand-green-primary);
            box-shadow: 0 0 0 4px rgba(21, 128, 61, 0.12);
        }

        .form-label-custom {
            font-weight: 700;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .form-check-input:checked {
            background-color: var(--brand-green-primary);
            border-color: var(--brand-green-primary);
        }

        .hover-green:hover {
            color: var(--brand-green-primary) !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
