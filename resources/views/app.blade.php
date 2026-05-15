<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
<link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/2489/2489756.png" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        :root {
            --bg-primary: #0a0f0a;
            --bg-secondary: #0d1a0d;
            --bg-card: #111a11;
            --bg-card-hover: #162016;
            --green-primary: #00c853;
            --green-secondary: #1a7a1a;
            --green-accent: #4caf50;
            --green-light: #69f0ae;
            --text-primary: #e8f5e9;
            --text-secondary: #a5d6a7;
            --text-muted: #558b2f;
            --border-color: #1b2e1b;
            --sidebar-width: 240px;
        }

        * { font-family: 'Figtree', sans-serif; }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-color);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
        }

        .sidebar-logo {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--green-primary);
            padding: 0.5rem 1rem 1.5rem;
            letter-spacing: 1px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1rem;
            border-radius: 10px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 0.25rem;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(0, 200, 83, 0.1);
            color: var(--green-primary);
        }

        .sidebar-link i { font-size: 1.1rem; }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        /* ── Main Content ── */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }

        /* ── Cards ── */
        .card-dark {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.2s;
        }

        .card-dark:hover {
            background: var(--bg-card-hover);
            border-color: var(--green-secondary);
        }

        /* ── Stat Cards ── */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.2s;
        }

        .stat-card:hover {
            border-color: var(--green-secondary);
            transform: translateY(-2px);
        }

        .stat-card .label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0.5rem 0;
        }

        /* ── Buttons ── */
        .btn-green {
            background: var(--green-primary);
            color: #000;
            border: none;
            border-radius: 10px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-green:hover {
            background: var(--green-light);
            color: #000;
            transform: translateY(-1px);
        }

        .btn-outline-green {
            background: transparent;
            color: var(--green-primary);
            border: 1px solid var(--green-primary);
            border-radius: 10px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-outline-green:hover {
            background: rgba(0, 200, 83, 0.1);
            color: var(--green-primary);
        }

        /* ── Inputs ── */
        .input-dark {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            padding: 0.65rem 1rem;
            width: 100%;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .input-dark:focus {
            outline: none;
            border-color: var(--green-primary);
            box-shadow: 0 0 0 3px rgba(0, 200, 83, 0.1);
        }

        .input-dark::placeholder { color: var(--text-muted); }

        .label-dark {
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
            display: block;
        }

        /* ── Table ── */
        .table-dark-custom { color: var(--text-primary); }

        .table-dark-custom th {
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            padding: 0.75rem 1rem;
            background: transparent;
        }

        .table-dark-custom td {
            border-bottom: 1px solid var(--border-color);
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            vertical-align: middle;
            background: transparent;
        }

        .table-dark-custom tr:hover td {
            background: rgba(0, 200, 83, 0.03);
        }

        /* ── Badges ── */
        .badge-income {
            background: rgba(0, 200, 83, 0.15);
            color: var(--green-primary);
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-expense {
            background: rgba(244, 67, 54, 0.15);
            color: #ef5350;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* ── Auth pages ── */
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-primary);
        }

        .auth-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
        }

        .auth-logo {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--green-primary);
            text-align: center;
            margin-bottom: 0.25rem;
        }

        .auth-subtitle {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-bottom: 2rem;
        }

        /* ── Progress ── */
        .progress-dark {
            background: var(--bg-primary);
            border-radius: 10px;
            height: 6px;
            overflow: hidden;
        }

        .progress-fill {
            background: var(--green-primary);
            height: 100%;
            border-radius: 10px;
            transition: width 0.5s ease;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--green-secondary); border-radius: 3px; }
    </style>

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    @inertia
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>