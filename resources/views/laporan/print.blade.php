<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #111;
            margin: 24px;
        }
        h1, h2, h3, p {
            margin: 0 0 8px 0;
        }
        .meta {
            margin-bottom: 12px;
        }
        .summary {
            margin: 12px 0 16px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .text-right {
            text-align: right;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 12px;">
        <button onclick="window.print()">Print / Save as PDF</button>
    </div>

    <h1>Laporan Keuangan</h1>
    <div class="meta">
        <p>Dicetak: {{ date('d-m-Y H:i') }}</p>
        <p>
            Filter:
            Tahun {{ $filters['tahun'] ?? '-' }},
            Bulan {{ $filters['bulan'] ?? '-' }},
            Tanggal {{ $filters['tanggal_mulai'] ?? '-' }} s/d {{ $filters['tanggal_selesai'] ?? '-' }}
        </p>
    </div>

    <div class="summary">
        <p>Total Transaksi: {{ $summary['total_transaksi'] }}</p>
        <p>Total Pendapatan: Rp. {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</p>
        <p>Total Kembalian: Rp. {{ number_format($summary['total_kembalian'], 0, ',', '.') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>No. Order</th>
                <th>Nama</th>
                <th>Jenis Paket</th>
                <th>Status</th>
                <th>Total</th>
                <th>Dibayar</th>
                <th>Kembalian</th>
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
                    <td class="text-right">Rp. {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="text-right">Rp. {{ number_format($order->dibayarkan ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Rp. {{ number_format($order->kembalian ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Data tidak tersedia</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
