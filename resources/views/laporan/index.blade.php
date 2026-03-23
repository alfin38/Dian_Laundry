@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="main-content">
    <div class="container">
        <div class="baris">
            <div class="selamat-datang">
                <div class="col-header">
                    <h2 class="judul-md">Laporan Keuangan</h2>
                </div>
            </div>
        </div>

        <div class="baris">
            <div class="col">
                <div class="card">
                    <div class="card-title">
                        <h2>Filter Laporan</h2>
                        <p class="laporan-note">Filter dihitung berdasarkan tanggal masuk.</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('laporan.index') }}" method="GET" class="form-input">
                            <div class="row-input">
                                <div class="col-form m-1">
                                    <div class="form-grup">
                                        <label for="tahun">Tahun</label>
                                        <input type="number" name="tahun" id="tahun" min="2000" max="2100" value="{{ request('tahun') }}">
                                    </div>
                                </div>
                                <div class="col-form m-1">
                                    <div class="form-grup">
                                        <label for="bulan">Bulan</label>
                                        <select name="bulan" id="bulan">
                                            <option value="">Semua</option>
                                            <option value="1" {{ request('bulan') == '1' ? 'selected' : '' }}>Januari</option>
                                            <option value="2" {{ request('bulan') == '2' ? 'selected' : '' }}>Februari</option>
                                            <option value="3" {{ request('bulan') == '3' ? 'selected' : '' }}>Maret</option>
                                            <option value="4" {{ request('bulan') == '4' ? 'selected' : '' }}>April</option>
                                            <option value="5" {{ request('bulan') == '5' ? 'selected' : '' }}>Mei</option>
                                            <option value="6" {{ request('bulan') == '6' ? 'selected' : '' }}>Juni</option>
                                            <option value="7" {{ request('bulan') == '7' ? 'selected' : '' }}>Juli</option>
                                            <option value="8" {{ request('bulan') == '8' ? 'selected' : '' }}>Agustus</option>
                                            <option value="9" {{ request('bulan') == '9' ? 'selected' : '' }}>September</option>
                                            <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                                            <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                                            <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
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

                            <div class="form-footer laporan-footer">
                                <div class="buttons">
                                    <button type="submit" class="btn-sm bg-primary">Terapkan Filter</button>
                                    <a href="{{ route('laporan.print') }}?{{ http_build_query(request()->query()) }}" class="btn-sm btn-hapus" target="_blank">Export PDF</a>
                                    <a href="{{ route('laporan.index') }}" class="btn-sm bg-transparent">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

            <div class="baris laporan-summary">
            <div class="col col-4">
                <div class="card summary-card summary-card--blue">
                    <div class="card-body">
                        <div class="card-panel">
                            <div class="panel-header">
                                <p>Total Transaksi</p>
                                <h2>{{ $summary['total_transaksi'] }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-4">
                <div class="card summary-card summary-card--teal">
                    <div class="card-body">
                        <div class="card-panel">
                            <div class="panel-header">
                                <p>Total Pendapatan</p>
                                <h2>Rp. {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-4">
                <div class="card summary-card summary-card--amber">
                    <div class="card-body">
                        <div class="card-panel">
                            <div class="panel-header">
                                <p>Total Kembalian</p>
                                <h2>Rp. {{ number_format($summary['total_kembalian'], 0, ',', '.') }}</h2>
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
                            <h2>Detail Transaksi</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tabel-kontainer">
                            <table class="tabel-transaksi">
                                <thead>
                                    <tr>
                                        <th class="sticky">No</th>
                                        <th class="sticky">Tanggal</th>
                                        <th class="sticky">No. Order</th>
                                        <th class="sticky">Nama</th>
                                        <th class="sticky">Jenis Paket</th>
                                        <th class="sticky">Status</th>
                                        <th class="sticky">Total</th>
                                        <th class="sticky">Dibayar</th>
                                        <th class="sticky">Kembalian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $order->tgl_masuk }}</td>
                                            <td>{{ $order->or_number }}</td>
                                            <td>{{ $order->nama_pelanggan }}</td>
                                            <td>{{ $order->paket->jenis_paket ?? '-' }}</td>
                                            <td>{{ $order->status }}</td>
                                            <td>Rp. {{ number_format($order->total, 0, ',', '.') }}</td>
                                            <td>Rp. {{ number_format($order->dibayarkan ?? 0, 0, ',', '.') }}</td>
                                            <td>Rp. {{ number_format($order->kembalian ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="txt-center">Data tidak tersedia</td>
                                        </tr>
                                    @endforelse
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
