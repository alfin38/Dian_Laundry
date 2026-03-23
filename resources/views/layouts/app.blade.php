<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dian Laundry | @yield('title', 'Dian Laundry')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Open+Sans:400,600,700|Montserrat:600,700" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('_assets/css/style.css') }}?v={{ filemtime(public_path('_assets/css/style.css')) }}">
    <style>
    /* CENTERKAN kolom: Waktu Kerja (6), Berat/Jumlah (7), Status (8), Pembayaran (9) */
    .tabel-transaksi th:nth-child(6),
    .tabel-transaksi td:nth-child(6),
    .tabel-transaksi th:nth-child(7),
    .tabel-transaksi td:nth-child(7),
    .tabel-transaksi th:nth-child(8),
    .tabel-transaksi td:nth-child(8),
    .tabel-transaksi th:nth-child(9),
    .tabel-transaksi td:nth-child(9) {
        text-align: center;
    }
    </style>

    <link rel="shortcut icon" href="<?=url('_assets/img/logo/logo2.png')?>" type="image/x-icon">
</head>
<body>
    <div id="app">
        <header>
            <nav>
                <div class="logo">
                    <a href="{{ route('home') }}" class="d-flex">
                        <img src="{{ asset('_assets/img/logo3.png') }}" alt="Dian Laundry Logo" class="mr-3"> <h3>Dian Laundry</h3>
                    </a>
                </div>
                <ul class="nav-menu">
                    <li>
                        <span id="">{{ Auth::user()->nama }}</span>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <div id="nav-mini">
                <a href="{{ route('riwayat.index') }}" class="link-nav {{ request()->routeIs('riwayat.index') ? 'active' : '' }}">Riwayat Transaksi</a>
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('laporan.index') }}" class="link-nav {{ request()->routeIs('laporan.index') ? 'active' : '' }}">Laporan Keuangan</a>
                    @endif
                @endauth
                                    
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('karyawan.index') }}" class="link-nav {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">Karyawan</a>
                    @endif
                @endauth

                <a href="{{ route('paket.index') }}" class="link-nav {{ request()->routeIs('paket.*') ? 'active' : '' }}">Daftar Paket</a>
            </div>
        </header>

        <main class="py-4">
            @yield('content')
        </main>

        <footer>
            <p>&copy; <span id="tahun"></span> Dian Laundry.</p>
            <script>
            // mengambil tanggal hari ini
            var now = new Date();
            var tahun = now.getFullYear();
            // menampilkan tahun di dalam elemen HTML
            document.getElementById("tahun").innerHTML = tahun;
            </script>
        </footer>

        <script src="<?=url('_assets/js/rumah_laundry.js')?>"></script>
        
        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form[data-confirm]').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    const message = form.getAttribute('data-confirm') || 'Yakin?';
                    if (!confirm(message)) {
                        event.preventDefault();
                    }
                });
            });
        });
        </script>
    </div>
</body>
</html>
