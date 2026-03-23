@extends('layouts.app')

@section('title', 'Daftar Cuci Satuan')

@section('content')
    {{-- KONTAINER NOTIFIKASI --}}
    <div id="notification-container"></div>

    {{-- JAVASCRIPT UNTUK NOTIFIKASI --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                    display: flex; align-items: center; padding: 15px 20px; margin-bottom: 10px;
                    background-color: ${bgColor}; color: ${textColor}; border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1); position: relative;
                    transform: translateX(120%); transition: transform 0.4s ease-out; min-width: 300px;
                `;
                notification.querySelector('img').style.marginRight = '12px';
                notification.querySelector('span').style.flexGrow = '1';
                const closeBtn = notification.querySelector('.close-btn');
                closeBtn.style.cssText = `background: none; border: none; font-size: 24px; line-height: 20px; cursor: pointer; margin-left: 15px; opacity: 0.6; color: ${textColor};`;
                
                container.appendChild(notification);
                setTimeout(() => { notification.style.transform = 'translateX(0)'; }, 100);

                const closeNotification = () => {
                    notification.style.transform = 'translateX(120%)';
                    setTimeout(() => { if (notification.parentNode) notification.parentNode.removeChild(notification); }, 400);
                };
                closeBtn.addEventListener('click', closeNotification);
                setTimeout(closeNotification, 6000);
            }

            @if(session('success')) showNotification('{{ session('success') }}', 'success'); @endif
            @if(session('error')) showNotification('{{ session('error') }}', 'error'); @endif
        });
    </script>

    <div id="pkt_ck" class="main-content">
        <div class="container">
            <div class="baris">
                <div class="selamat-datang">
                    <div class="col-header">
                        <h2 class="judul-md">Paket {{ $filter_data }}</h2>
                    </div>
                    <div class="col-header txt-right">
                        <a href="{{ route('paket.create', $jenis_paket) }}" class="btn-lg bg-primary">+ Tambah Paket</a>					</div>	
                </div>
            </div>
            <div class="baris">
                <div class="col">
                    <div class="card">
                        <div class="card-title card-flex">
                            <div class="card-col"><h2>Daftar Paket Tersedia</h2></div>
                            <div class="card-col txt-right"><a href="{{ route('paket.index') }}" class="btn-xs bg-primary">Kembali</a></div>
                        </div>
                        <div class="card-body">
                            <div class="tabel-kontainer">
                                <table class="tabel-transaksi">
                                    <thead>
                                        <tr>
                                            <th class="sticky">No</th>
                                            <th class="sticky">Nama Paket</th>
                                            <th class="sticky">Waktu Kerja</th>
                                            <th class="sticky">Jumlah Min (Pcs)</th>
                                            <th class="sticky">Tarif</th>
                                            <th class="sticky">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($paket as $ck)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $ck->nama_paket }}</td>
                                                <td>{{ $ck->waktu_kerja }}</td>
                                                <td>{{ $ck->berat_minimal }}</td>
                                                <td>{{ $ck->harga }}</td>
                                                <td>
                                                    <a href="{{ route('paket.edit', [$jenis_paket, $ck->id]) }}" class="btn btn-edit">Edit</a>
                                                    <form action="{{ route('paket.destroy', [$jenis_paket, $ck->id]) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus paket {{ $ck->nama_paket }}?')">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
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
    </div>
@endsection