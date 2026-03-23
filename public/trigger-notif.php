<?php
require __DIR__ . '/../vendor/autoload.php';

 $app = require_once __DIR__ . '/../bootstrap/app.php';

 $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
 $kernel->bootstrap();

// --- DATA NOTIFIKASI PALSU ---
// Anda bisa mengubah data ini sesuai kebutuhan
 $fakeNotif = [
    'order_id' => 'TEST-ORDER-' . time(),
    'status_code' => '200',
    'gross_amount' => '100000.00',
    'payment_type' => 'bank_transfer',
    'transaction_status' => 'settlement',
    'transaction_id' => 'fake-transaction-id-' . uniqid(),
    'settlement_time' => now()->format('Y-m-d H:i:s'),
];

// Buat signature key palsu agar valid
 $serverKey = config('midtrans.server_key');
 $fakeNotif['signature_key'] = hash(
    "sha512",
    $fakeNotif['order_id'] . $fakeNotif['status_code'] . $fakeNotif['gross_amount'] . $serverKey
);


// --- KIRIM NOTIFIKASI PALSU KE WEBHOOK ANDA ---
// Buat request palsu
 $request = Illuminate\Http\Request::create(
    '/payment/notification', // URL webhook Anda
    'POST',
    $fakeNotif
);

// Jalankan controller
 $controller = new App\Http\Controllers\MidtransWebhookController();
 $response = $controller->handle($request);

// Tampilkan hasilnya
echo "<h1>Notifikasi Palsu Terkirim!</h1>";
echo "<h2>Respons dari Webhook:</h2>";
echo "<pre>" . $response->getContent() . "</pre>";

echo "<h2>Data yang Dikirim:</h2>";
echo "<pre>" . json_encode($fakeNotif, JSON_PRETTY_PRINT) . "</pre>";

echo "<h2>Langkah Selanjutnya:</h2>";
echo "<p>Buka file <code>storage/logs/laravel.log</code> untuk melihat log notifikasi dan pesan untuk admin.</p>";