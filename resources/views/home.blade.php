@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- KONTAINER NOTIFIKASI --}}
<div id="notification-container"></div>

{{-- JAVASCRIPT UNTUK NOTIFIKASI --}}
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Fungsi tampilkan notifikasi
    function showNotification(message, type = 'success') {
        const container = document.getElementById('notification-container');
        
        const notification = document.createElement('div');
        notification.className = 'notification ' + type;

        let iconSrc, bgColor, textColor;
        if (type === 'success') {
            iconSrc = '{{ asset("_assets/img/berhasil.png") }}';
            bgColor = '#d4edda';
            textColor = '#155724';
        } else {
            iconSrc = '{{ asset("_assets/img/gagal.png") }}';
            bgColor = '#f8d7da';
            textColor = '#721c24';
        }

        notification.innerHTML = `
            <img src="${iconSrc}" alt="icon" height="40">
            <span>${message}</span>
            <button class="close-btn">&times;</button>
        `;

        notification.style.cssText = `
            display: flex;
            align-items: center;
            padding: 15px 20px;
            margin-bottom: 10px;
            background-color: ${bgColor};
            color: ${textColor};
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateX(120%);
            transition: transform 0.4s ease-out;
            min-width: 300px;
        `;

        const img = notification.querySelector('img');
        const span = notification.querySelector('span');
        const closeBtn = notification.querySelector('.close-btn');

        img.style.marginRight = '12px';
        span.style.flexGrow = '1';
        closeBtn.style.cssText = `
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            margin-left: 15px;
            opacity: 0.6;
            color: ${textColor};
        `;

        container.appendChild(notification);

        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        const closeNotification = () => {
            notification.style.transform = 'translateX(120%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 400);
        };

        closeBtn.addEventListener('click', closeNotification);
        setTimeout(closeNotification, 6000);
    }

    // Tampilkan notifikasi jika ada session sukses
    @if(session('success'))
        showNotification('{{ addslashes(session('success')) }}', 'success');
    @endif

    // Tampilkan notifikasi jika ada session error
    @if(session('error'))
        showNotification('{{ addslashes(session('error')) }}', 'error');
    @endif

});
</script>


    </script>
    
    <div id="main" class="main-content">
        <div class="container">
            <div class="baris">
                <div class="selamat-datang">
                    <div class="col-header">
                        <p class="judul-sm">Selamat Datang 👋🏻 <span>{{ Auth::user()->nama }}</span></p>
                        <h2 class="judul-md">Dashboard</h2>
                    </div>

                    <div class="col-header txt-right">
                        <a href="{{ route('order.index') }}" class="btn-lg bg-primary">+ Orderan</a>
                    </div>	
                </div>
            </div>

            @php
                $summary_items = [
                    [
                        'label' => 'Jumlah Karyawan',
                        'value' => (int) ($jumlah_karyawan ?? 0),
                        'color' => '#2f6ce5',
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Total Order Sedang Berjalan',
                        'value' => (int) ($jumlah_order ?? 0),
                        'color' => '#f59e0b',
                        'icon' => 'clock',
                    ],
                    [
                        'label' => 'Jumlah Paket Tersedia',
                        'value' => (int) ($jumlah_paket ?? 0),
                        'color' => '#22c55e',
                        'icon' => 'box',
                    ],
                ];
            @endphp

            <div class="baris">
                <div class="col">
                    <div class="card dashboard-summary">
                        <div class="card-title card-flex">
                            <div class="card-col">
                                <h2>Ringkasan Dashboard</h2>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div class="summary-wrap" role="img" aria-label="Ringkasan jumlah karyawan, total order sedang berjalan, dan jumlah paket tersedia">
                                <div class="summary-back"></div>
                                <div class="summary-grid">
                                    @foreach ($summary_items as $item)
                                        <div class="summary-card">
                                            <div class="summary-ring" style="--summary-color: {{ $item['color'] }};">
                                                <span class="summary-icon" aria-hidden="true">
                                                    @if ($item['icon'] === 'users')
                                                        <svg viewBox="0 0 24 24">
                                                            <path d="M16 11c1.7 0 3-1.3 3-3s-1.3-3-3-3-3 1.3-3 3 1.3 3 3 3Z"></path>
                                                            <path d="M8 11c1.7 0 3-1.3 3-3S9.7 5 8 5 5 6.3 5 8s1.3 3 3 3Z"></path>
                                                            <path d="M3 19c0-2.2 2.7-4 6-4"></path>
                                                            <path d="M13 15c3.3 0 6 1.8 6 4"></path>
                                                        </svg>
                                                    @elseif ($item['icon'] === 'clock')
                                                        <svg viewBox="0 0 24 24">
                                                            <circle cx="12" cy="12" r="7"></circle>
                                                            <path d="M12 8v4l3 2"></path>
                                                        </svg>
                                                    @else
                                                        <svg viewBox="0 0 24 24">
                                                            <path d="M6 8l6-3 6 3v8l-6 3-6-3V8Z"></path>
                                                            <path d="M6 8l6 3 6-3"></path>
                                                            <path d="M12 11v8"></path>
                                                        </svg>
                                                    @endif
                                                </span>
                                                <span class="summary-value">{{ $item['value'] }}</span>
                                            </div>
                                            <div class="summary-label">{{ $item['label'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="baris">
                <div class="col">
                    <div class="card">
                        <div class="card-title card-flex">
                            <div class="card-col">
                                <h2>Filter Order</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('home') }}" method="GET" class="form-input form-input--wide">
                                <div class="row-input">
                                    <div class="col-form m-1">
                                        <div class="form-grup">
                                            <label for="q">Pencarian</label>
                                            <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="No. Order / Nama Pelanggan">
                                        </div>
                                    </div>
                                    <div class="col-form m-1">
                                        <div class="form-grup">
                                            <label for="status">Status</label>
                                            <select name="status" id="status">
                                                <option value="">Semua</option>
                                                <option value="Belum Bayar" {{ request('status') === 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                                <option value="On Progress" {{ request('status') === 'On Progress' ? 'selected' : '' }}>On Progress</option>
                                                <option value="Terlambat" {{ request('status') === 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                                                <option value="Siap Diambil" {{ request('status') === 'Siap Diambil' ? 'selected' : '' }}>Siap Diambil</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-input">
                                    <div class="col-form m-1">
                                        <div class="form-grup">
                                            <label for="tanggal_mulai">Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                                        </div>
                                    </div>
                                    <div class="col-form m-1">
                                        <div class="form-grup">
                                            <label for="tanggal_selesai">Tanggal Selesai</label>
                                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ request('tanggal_selesai') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row-input">
                                    <div class="col-form m-1">
                                        <div class="form-grup">
                                            <label for="jenis_paket">Jenis Paket</label>
                                            <select name="jenis_paket" id="jenis_paket">
                                                <option value="">Semua</option>
                                                <option value="Cuci Komplit" {{ request('jenis_paket') === 'Cuci Komplit' ? 'selected' : '' }}>Cuci Komplit</option>
                                                <option value="Cuci Satuan" {{ request('jenis_paket') === 'Cuci Satuan' ? 'selected' : '' }}>Cuci Satuan</option>
                                                <option value="Cuci Lipat" {{ request('jenis_paket') === 'Cuci Lipat' ? 'selected' : '' }}>Cuci Lipat</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-form m-1"></div>
                                </div>
                                <div class="form-footer">
                                    <div class="buttons">
                                        <button type="submit" class="btn-sm bg-primary">Terapkan Filter</button>
                                        <a href="{{ route('home') }}" class="btn-sm bg-transparent">Reset</a>
                                    </div>
                                </div>
                                @if (request()->query())
                                    <div class="form-help">
                                        Filter aktif:
                                        @if (request('q')) <strong>Pencarian</strong>, @endif
                                        @if (request('status')) <strong>Status</strong>, @endif
                                        @if (request('tanggal_mulai') || request('tanggal_selesai')) <strong>Tanggal</strong>, @endif
                                        @if (request('jenis_paket')) <strong>Jenis Paket</strong> @endif
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Order Cuci Komplit -->
            @if (!request('jenis_paket') || request('jenis_paket') === 'Cuci Komplit')
                <div class="baris">
                    @include('home_partials.order_ck')
                </div>
            @endif

            <!-- Daftar Order Cuci Satuan -->
            @if (!request('jenis_paket') || request('jenis_paket') === 'Cuci Satuan')
                <div class="baris">
                    @include('home_partials.order_cs')
                </div>
            @endif

            <!-- Daftar Order Cuci Lipat -->
            @if (!request('jenis_paket') || request('jenis_paket') === 'Cuci Lipat')
                <div class="baris">
                    @include('home_partials.order_cl')
                </div>
            @endif

        </div>
    </div>
@endsection
