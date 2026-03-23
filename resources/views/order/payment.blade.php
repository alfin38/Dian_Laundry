@extends('layouts.app')

@section('title', 'Pembayaran Order')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Pembayaran Order: {{ $order->or_number }}</h3>
                </div>
                <div class="card-body text-center">
                    <h4>Total: Rp. {{ number_format($order->total, 0, ',', '.') }}</h4>
                    <hr>
                    <p>Silakan pilih metode pembayaran:</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="payment-option">
                                <h5>Transfer Bank</h5>
                                <p>BCA Virtual Account</p>
                                <p><strong>{{ env('MIDTRANS_MERCHANT_ID') }}</strong></p>
                                <img src="{{ asset('_assets/img/bca-logo.png') }}" alt="BCA" width="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="payment-option">
                                <h5>QR Code</h5>
                                <p>Scan QR code berikut:</p>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $order->or_number }}" alt="QR Code">
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <p class="text-muted">Pembayaran akan otomatis terverifikasi setelah Anda melakukan transfer atau scan QR code.</p>
                    <p class="text-muted">Status pembayaran akan diperbarui secara otomatis.</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('order.show', ['jenis_paket' => $order->paket->jenis_paket, 'id' => $order->id]) }}" class="btn btn-secondary">Kembali ke Detail Order</a>
                        <button type="button" class="btn btn-primary" onclick="location.reload()">Cek Status Pembayaran</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection