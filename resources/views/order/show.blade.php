@extends('layouts.app')

@section('title', 'Detail Order')

@section('content')
    <style>
.invoice-container {
    font-family: Arial, sans-serif;
    color: #111827;
}
.invoice-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 12px;
    margin-bottom: 12px;
    gap: 16px;
}
.invoice-logo {
    font-size: 20px;
    font-weight: bold;
    color: #111827;
    letter-spacing: 0.5px;
}
.invoice-details {
    text-align: right;
    font-size: 12px;
    line-height: 1.4;
}
.invoice-title {
    font-size: 16px;
    font-weight: bold;
    margin: 12px 0 16px 0;
    text-align: center;
    color: #2f7d32;
}
.invoice-section {
    margin: 16px 0;
}
.invoice-section h3 {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #6b7280;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 6px;
    margin: 0 0 10px 0;
    text-align: left !important;
}
.invoice-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}
.invoice-table th, .invoice-table td {
    padding: 8px 10px;
    text-align: left;
    border: 1px solid #e5e7eb;
    vertical-align: top;
}
.invoice-table th {
    width: 180px;
    background-color: #f8fafc;
    color: #111827;
}
.invoice-total {
    text-align: right;
    font-size: 16px;
    font-weight: bold;
    margin-top: 12px;
}
.invoice-footer {
    margin-top: 16px;
    text-align: center;
    color: #6b7280;
    font-size: 12px;
}
.status-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    display: inline-block;
}
.status-on-progress {
    background-color: #007bff;
    color: white;
}
.status-terlambat {
    background-color: #dc3545;
    color: white;
}
.status-selesai {
    background-color: #28a745;
    color: white;
}
.status-belum-bayar {
    background-color: #ffba3b;
    color: #111;
}
</style>
<div id="detail_or_ck" class="main-content">
    <div class="container">
        <div class="baris">
            <div class="col mt-2">
                <div class="card-md">
                    @php
                        $dibayarkan = $order->dibayarkan ?? 0;
                        $isPaid = $order->total > 0 && $dibayarkan >= $order->total;
                        $paymentLabel = $isPaid ? 'Sudah Bayar' : 'Belum Bayar';
                    @endphp
                    <div class="card-title card-flex">
                        <div class="card-col">
                            Detail Order
                            @if($order->status === 'Selesai')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($order->status === 'Siap Diambil')
                                <span class="badge bg-success">Siap Diambil</span>
                            @elseif($order->status === 'On Progress')
                                <span class="badge bg-primary">On Progress</span>
                            @elseif($order->status === 'Terlambat')
                                <span class="badge bg-danger">Terlambat</span>
                            @else
                                <span class="badge bg-warning">Belum Bayar</span>
                            @endif
                        </div>
                        <div class="card-col txt-right">
                            
                            
						<h5 class="no-order"><small>No Order : </small>{{ $order->or_number }}</h5>
                        </div>
                    </div>

                    <div class="card-body">

                        <table class="tb-detail_customer">
                            <tr>
                                <th>Nama</th>
                                <td><input type="text" name="nama_pelanggan" disabled value="{{ $order->nama_pelanggan }}"></td>
                            </tr>
                            <tr>
                                <th>Nomor Telepon</th>
                                <td><input type="text" name="no_telp" disabled value="{{ $order->no_telp }}"></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td><textarea name="alamat_ck" disabled class="txt-area">{{ $order->alamat }}</textarea></td>
                            </tr>
                            <tr>
                                <th>Order Masuk</th>
                                <td><input type="text" name="tgl_masuk_ck" disabled value="{{ $order->tgl_masuk }}"></td>
                            </tr>
                            <tr>
                                <th>Diambil Pada</th>
                                <td><input type="text" name="tgl_keluar_ck" disabled value="{{ $order->tgl_keluar }}"></td>
                            </tr>
                            <tr>
                                <th>Durasi Kerja</th>
                                <td><input type="text" name="wkt_krj_ck" disabled value="{{ $durasiTeks }}"></td>
                            </tr>
                            <tr>
                                <th>Jenis Paket</th>
                                <td><input type="text" name="jenis_paket_ck" disabled value="{{ $order->paket->nama_paket }}"></td>
                            </tr>
                            <tr>
                                <th>Pembayaran</th>
                                <td><input type="text" disabled value="{{ $paymentLabel }}"></td>
                            </tr>
                        </table>

                        <div class="mt-1"></div>

                        <div class="jdl-or">
                            <h4>Order</h4>
                        </div>

                        <table class="tb-detail_order">
                            <tr>
                            @if($jenis_paket === 'cuci-satuan')
                                <th>Jumlah (Pcs)</th>
                                <th>Harga Satuan</th>
                            @else
                            	<th>Berat (Kg)</th>
                                <th>Harga Per-Kg</th>
                            @endif
                                <th>Total Bayar</th>
                            </tr>
                            <tr>
                            @if($jenis_paket === 'cuci-satuan')
                                @php
                                    $jumlahPcs = intval($order->berat_order);
                                @endphp
                                <td>
                                    <input type="text" disabled
                                        value="{{ $jumlahPcs }} Pcs">
                                </td>
                            @else
                                @php
                                    $berat = $order->berat_order;
                                    $beratRapi = $berat == intval($berat)
                                        ? intval($berat)
                                        : rtrim(rtrim(number_format($berat, 2), '0'), '.');
                                @endphp
                                <td>
                                    <input type="text" disabled
                                        value="{{ $beratRapi }} Kg">
                                </td>
                            @endif

                                <td><input type="text" name="harga_perkilo" disabled value="Rp. {{ number_format($order->paket->harga, 0, ',', '.') }}"></td>
                                <td><input type="text" name="tot_bayar" disabled value="Rp. {{ number_format($order->total, 0, ',', '.') }}"></td>
                            </tr>
                        </table>

                        <div class="details">
                            <h4 class="mb-01">Keterangan:</h4>
                            <p class="lead">
                                <textarea name="keterangan_ck" disabled class="txt-area">{{ $order->keterangan }}</textarea>
                            </p>
                        </div>

                            {{-- TOMBOL AKSI (DIPERBAIKI) --}}
                            <div class="form-footer_detail">
                                <div class="buttons">
                                    {{-- Tombol Konfirmasi: Hanya muncul jika status 'Belum Bayar' --}}
                                    @if(!$isPaid)
                                        {{-- Ganti dengan tombol ini --}}
                                        <button type="button" class="btn-sm bg-primary text-white action-btn" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                            Bayar Sekarang
                                        </button>
                                    @endif
                                    @if($order->status === 'Belum Bayar')
                                        <button type="button" class="btn-sm bg-primary text-white action-btn" id="showInvoiceBtn">
                                            INVOICE
                                        </button>
                                        <form action="{{ route('order.payLater', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-sm bg-transparent action-btn">Bayar Nanti</button>
                                        </form>
                                    @endif

                                    {{-- Tombol Siap Diambil: Muncul jika status 'On Progress' atau 'Terlambat' --}}
                                    @if (in_array($order->status, ['On Progress', 'Terlambat']))
                                        <form action="{{ route('order.ready', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-sm bg-success-light text-white action-btn">
                                                Siap Diambil
                                            </button>
                                        </form>
                                        <button type="button" class="btn-sm bg-primary text-white action-btn" id="showInvoiceBtn">
                                            INVOICE
                                        </button>
                                    @endif

                                    {{-- Tombol Pesanan Sudah Diambil: Hanya muncul jika sudah bayar --}}
                                    @if ($order->status === 'Siap Diambil')
                                        @if ($isPaid)
                                            <button type="button" class="btn-sm bg-success-light text-white action-btn" data-bs-toggle="modal" data-bs-target="#confirmCompleteModal">
                                                Pesanan Sudah Diambil
                                            </button>
                                        @endif
                                        <button type="button" class="btn-sm bg-primary text-white action-btn" id="showInvoiceBtn">
                                            INVOICE
                                        </button>
                                    @endif
{{-- ==================== MODAL INVOICE ==================== --}}
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Invoice Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="invoice-container">
                    <div class="invoice-header">
                        <div class="invoice-logo">
                            LAUNDRY SYSTEM
                        </div>
                        <div class="invoice-details">
                            <strong>INVOICE</strong><br>
                            No. {{ $order->or_number }}<br>
                            Tanggal: {{ date('d-m-Y') }}
                        </div>
                    </div>

                    <div class="invoice-title">
                        Bukti Orderan
                    </div>

                    <div class="invoice-section">
                        <h3>Informasi Pelanggan</h3>
                        <table class="invoice-table">
                            <tr>
                                <th width="150">Nama</th>
                                <td>{{ $order->nama_pelanggan }}</td>
                            </tr>
                            <tr>
                                <th>Nomor Telepon</th>
                                <td>{{ $order->no_telp }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $order->alamat }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="invoice-section">
                        <h3>Informasi Order</h3>
                        <table class="invoice-table">
                            <tr>
                                <th width="150">No. Order</th>
                                <td>{{ $order->or_number }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Masuk</th>
                                <td>{{ $order->tgl_masuk }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Keluar</th>
                                <td>{{ $order->tgl_keluar }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($order->status === 'On Progress')
                                        <span class="status-badge status-on-progress">On Progress</span>
                                    @elseif($order->status === 'Terlambat')
                                        <span class="status-badge status-terlambat">Terlambat</span>
                                    @elseif($order->status === 'Siap Diambil')
                                        <span class="status-badge status-selesai">Siap Diambil</span>
                                    @elseif($order->status === 'Selesai')
                                        <span class="status-badge status-selesai">Selesai</span>
                                    @else
                                        <span class="status-badge status-belum-bayar">Belum Bayar</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="invoice-section">
                        <h3>Detail Pembayaran</h3>
                        <table class="invoice-table">
                            <tr>
                                <th>Jenis Paket</th>
                                <td>{{ $order->paket->nama_paket }}</td>
                            </tr>
                            @if($order->paket->jenis_paket === 'Cuci Satuan')
                                <tr>
                                    <th>Jumlah (Pcs)</th>
                                    <td>{{ intval($order->berat_order) }} Pcs</td>
                                </tr>
                                <tr>
                                    <th>Harga Satuan</th>
                                    <td>Rp. {{ number_format($order->paket->harga, 0, ',', '.') }}</td>
                                </tr>
                            @else
                                @php
                                    $beratInv = $order->berat_order;
                                    $beratInvRapi = $beratInv == intval($beratInv)
                                        ? intval($beratInv)
                                        : rtrim(rtrim(number_format($beratInv, 2), '0'), '.');
                                @endphp

                                <tr>
                                    <th>Berat (Kg)</th>
                                    <td>{{ $beratInvRapi }} Kg</td>
                                </tr>
                                <tr>
                                    <th>Harga Per-Kg</th>
                                    <td>Rp. {{ number_format($order->paket->harga, 0, ',', '.') }}</td>
                                </tr>
                            @endif


                            <tr>
                                <th>Total Pembayaran</th>
                                <td>Rp. {{ number_format($order->total, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td>
                                    @if ($isPaid)
                                        <span style="color: green; font-weight: bold;">LUNAS</span>
                                    @else
                                        <span style="color: #ffba3b; font-weight: bold;">BELUM BAYAR</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="invoice-total">
                        Total: Rp. {{ number_format($order->total, 0, ',', '.') }}
                    </div>

                    <div class="invoice-footer">
                        <p>Terima kasih telah menggunakan jasa laundry kami.</p>
                        <p>Pesanan Anda sedang dalam proses dan akan selesai sesuai estimasi.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="printInvoiceBtn">Cetak Invoice</button>
                <a href="https://wa.me/{{ preg_replace('/^0/', '+62', $order->no_telp) }}?text={{ urlencode(
                    '🧺 *LAUNDRY SYSTEM - INVOICE PEMBAYARAN* 🧺' . "\n\n" .

                    '📋 DETAIL ORDER' . "\n" .
                    'No. Order: ' . $order->or_number . "\n" .
                    'Tanggal Masuk: ' . $order->tgl_masuk . "\n" .
                    'Estimasi Selesai: ' . $order->tgl_keluar . "\n" .
                    'Status: ' . $order->status . "\n\n" .

                    '👤 DATA PELANGGAN' . "\n" .
                    'Nama: ' . $order->nama_pelanggan . "\n" .
                    'No. Telepon: ' . $order->no_telp . "\n" .
                    'Alamat: ' . $order->alamat . "\n\n" .

                    '🧼 DETAIL LAYANAN' . "\n" .
                    'Jenis Paket: ' . $order->paket->nama_paket . "\n" .

                    (
                        $order->paket->jenis_paket === 'Cuci Satuan'
                        ? ('Jumlah: ' . intval($order->berat_order) . ' Pcs' . "\n" .
                        'Harga Satuan: Rp. ' . number_format($order->paket->harga, 0, ',', '.') . "\n")
                        : (
                            'Berat: ' .
                            (
                                ($order->berat_order == intval($order->berat_order))
                                    ? intval($order->berat_order)
                                    : rtrim(rtrim(number_format($order->berat_order, 2), '0'), '.')
                            )
                            . ' Kg' . "\n" .
                            'Harga per Kg: Rp. ' . number_format($order->paket->harga, 0, ',', '.') . "\n"
                        )
                    ) .

                    "\n💰 DETAIL PEMBAYARAN" . "\n" .
                    'Total Pembayaran: Rp. ' . number_format($order->total, 0, ',', '.') . "\n" .
                    'Status Pembayaran: ' . ($isPaid ? 'LUNAS' : 'BELUM BAYAR') . "\n\n" .

                    'Terima kasih telah menggunakan jasa laundry kami! 😊' . "\n" .
                    'Hubungi kami jika ada pertanyaan lebih lanjut.'
                ) }}"
                target="_blank"
                class="btn btn-success">
                    Kirim ke WhatsApp
                </a>

            </div>
        </div>
    </div>
</div>
                                    {{-- Tombol Terlambat: Hanya muncul jika status 'On Progress' --}}
                                    @if ($order->status === "On Progress")
                                        <button type="button" class="btn-sm bg-danger text-white action-btn" data-bs-toggle="modal" data-bs-target="#confirmDelayModal">
                                            Kirim Pesan Terlambat
                                        </button>
                                    @endif


                                </div>
                            </div>
           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODAL KONFIRMASI SELESAI ==================== --}}
<div class="modal fade" id="confirmCompleteModal" tabindex="-1" aria-labelledby="confirmCompleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmCompleteModalLabel">Konfirmasi Pesanan Diambil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('order.complete', $order->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin pesanan <strong>{{ $order->or_number }}</strong> sudah diambil?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Sudah Diambil</button>
                </div>
            </form>
        </div>
    </div>
</div>
    
{{-- ==================== MODAL PEMBAYARAN ==================== --}}
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Pilih Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="paymentMethod" class="form-label">Metode Pembayaran</label>
                    <select class="form-select" id="paymentMethod" name="payment_method">
                        <option value="cash">Tunai</option>
                        <option value="online">Online (Midtrans)</option>
                    </select>
                </div>
                
                <div id="cashPaymentSection" style="display: block;">
                    <form action="{{ route('payment.process.cash', $order->id) }}" method="POST" id="cashPaymentForm">
                        @csrf
                        <div class="mb-3">
                            <label for="cashTotal" class="form-label">Total Bayar</label>
                            <input type="text" class="form-control" id="cashTotal" value="Rp. {{ number_format($order->total, 0, ',', '.') }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="cashAmount" class="form-label">Uang Customer</label>
                            <input
                                type="text"
                                class="form-control"
                                id="cashAmount"
                                name="cash_amount"
                                value="{{ $order->total }}"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                data-total="{{ $order->total }}"
                            >
                            <div class="form-help">Masukkan nominal uang yang diterima dari customer.</div>
                        </div>
                        <div class="mb-3">
                            <label for="cashChange" class="form-label">Kembalian / Kurang</label>
                            <input type="text" class="form-control" id="cashChange" value="Rp. 0" readonly>
                        </div>
                    </form>
                </div>
                
                <div id="onlinePaymentSection" style="display: none;">
                    <form action="{{ route('payment.process.midtrans', $order->id) }}" method="POST" id="onlinePaymentForm">
                        @csrf
                        <p>Anda akan diarahkan ke halaman pembayaran Midtrans untuk menyelesaikan transaksi.</p>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmCashPaymentBtn">Bayar Tunai</button>
                <button type="button" class="btn btn-primary" id="triggerMidtransBtn" style="display: none;">Bayar Online</button>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODAL KONFIRMASI TERLAMBAT ==================== --}}
<div class="modal fade" id="confirmDelayModal" tabindex="-1" aria-labelledby="confirmDelayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDelayModalLabel">Konfirmasi Kirim Pesan Terlambat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('order.delay', $order->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengirim pesan terlambat ke <strong>{{ $order->no_telp }}</strong>?</p>
                    <p class="text-muted">Halaman ini akan dialihkan ke WhatsApp setelah konfirmasi.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Kirim Pesan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script untuk Midtrans --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi semua modal Bootstrap
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        new bootstrap.Modal(modal);
    });
    
    const paymentMethodSelect = document.getElementById('paymentMethod');
    const cashPaymentSection = document.getElementById('cashPaymentSection');
    const onlinePaymentSection = document.getElementById('onlinePaymentSection');
    const confirmCashBtn = document.getElementById('confirmCashPaymentBtn');
    const triggerMidtransBtn = document.getElementById('triggerMidtransBtn');
    const cashPaymentForm = document.getElementById('cashPaymentForm');
    const onlinePaymentForm = document.getElementById('onlinePaymentForm');
    const cashAmountInput = document.getElementById('cashAmount');
    const cashChangeInput = document.getElementById('cashChange');
    const orderTotal = cashAmountInput ? parseFloat(cashAmountInput.dataset.total || '0') : 0;

    function formatRupiah(value) {
        return 'Rp. ' + Math.abs(value).toLocaleString('id-ID');
    }

    function updateCashChange() {
        if (!cashAmountInput || !cashChangeInput) {
            return;
        }

        const rawPaid = (cashAmountInput.value || '').replace(/[^0-9]/g, '');
        const paid = parseFloat(rawPaid || '0');
        const change = paid - orderTotal;
        if (change < 0) {
            cashChangeInput.value = 'Kurang ' + formatRupiah(change);
        } else {
            cashChangeInput.value = formatRupiah(change);
        }
    }

    // Fungsi untuk toggle tampilan berdasarkan metode pembayaran
    function togglePaymentMethod() {
        if (paymentMethodSelect.value === 'cash') {
            cashPaymentSection.style.display = 'block';
            onlinePaymentSection.style.display = 'none';
            confirmCashBtn.style.display = 'inline-block';
            triggerMidtransBtn.style.display = 'none';
            updateCashChange();
        } else {
            cashPaymentSection.style.display = 'none';
            onlinePaymentSection.style.display = 'block';
            confirmCashBtn.style.display = 'none';
            triggerMidtransBtn.style.display = 'inline-block';
        }
    }

    // Event listener saat metode pembayaran diubah
    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', togglePaymentMethod);
    }

    // Event listener untuk tombol pembayaran tunai
    if (confirmCashBtn) {
        confirmCashBtn.addEventListener('click', function() {
            cashPaymentForm.submit();
        });
    }

    if (cashAmountInput) {
        cashAmountInput.addEventListener('input', updateCashChange);
    }

    // Fungsi untuk pembayaran Midtrans
    function payWithMidtrans() {
        // Pastikan snap token tersedia
        const snapToken = '{{ $order->snap_token ?? "" }}';
        
        if (!snapToken) {
            alert('Token pembayaran tidak tersedia. Silakan refresh halaman.');
            return;
        }
        
        snap.pay(snapToken, {
            onSuccess: function(result) {
                console.log('success', result);
                // Refresh halaman untuk melihat status terbaru
                location.reload();
            },
            onPending: function(result) {
                console.log('pending', result);
                location.reload();
            },
            onError: function(result) {
                console.log('error', result);
                alert('Pembayaran gagal. Silakan coba lagi.');
            }
        });
    }

    // Event listener untuk tombol Midtrans
    if (triggerMidtransBtn) {
        triggerMidtransBtn.addEventListener('click', function() {
            onlinePaymentForm.submit();
        });
    }

    // Cek apakah ada session 'openMidtrans' dari controller
    @if(session('openMidtrans'))
        const snapToken = '{{ $order->snap_token ?? "" }}';
        if (snapToken) {
            // Jika token ada, buka langsung popup Midtrans
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log('Pembayaran Midtrans selesai:', result);
                    // Status sudah On Progress, tidak perlu ubah apa-apa lagi
                    // Mungkin tampilkan notifikasi sukses saja
                    alert('Terima kasih, pembayaran telah diproses.');
                },
                onPending: function(result) {
                    console.log('Pembayaran Midtrans pending:', result);
                },
                onError: function(result) {
                    console.log('Pembayaran Midtrans gagal:', result);
                    alert('Pembayaran gagal, namun order Anda tetap kami proses.');
                },
                onClose: function() {
                    console.log('Popup pembayaran ditutup.');
                }
            });
        } else {
            console.error('Snap token tidak ditemukan setelah proses.');
        }
    @endif
});

// Event listener untuk tombol Invoice
const showInvoiceBtn = document.getElementById('showInvoiceBtn');
if (showInvoiceBtn) {
    showInvoiceBtn.addEventListener('click', function() {
        const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
        invoiceModal.show();
    });
}

// Event listener untuk tombol Cetak Invoice
const printInvoiceBtn = document.getElementById('printInvoiceBtn');
if (printInvoiceBtn) {
    printInvoiceBtn.addEventListener('click', function() {
        const invoiceContent = document.querySelector('.invoice-container').innerHTML;
        const originalContent = document.body.innerHTML;
        
        document.body.innerHTML = invoiceContent;
        window.print();
        document.body.innerHTML = originalContent;
        
        // Re-inisialisasi modal setelah pencetakan
        const modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal) {
            new bootstrap.Modal(modal);
        });
        
        // Tampilkan kembali modal invoice
        const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
        invoiceModal.show();
    });
}

</script>

@endsection
