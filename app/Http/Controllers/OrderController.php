<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Notification;

class OrderController extends Controller
{
    private function resolvePaidStatus(Order $order): string
    {
        if ($order->status === 'Siap Diambil') {
            return 'Siap Diambil';
        }

        if ($order->status === 'Selesai') {
            return 'Selesai';
        }

        return 'On Progress';
    }

    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('order.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($jenis_paket)
    {
        $slugMapping = [
            'cuci-komplit' => 'Cuci Komplit',
            'cuci-lipat'   => 'Cuci Lipat',
            'cuci-satuan'  => 'Cuci Satuan',
        ];

        // 4. Ambil slug dari data yang sudah divalidasi
        $jenis_paket_slug = $slugMapping[$jenis_paket] ?? 'Cuci Komplit'; // Gunakan default jika tidak ditemukan

        $paket = Paket::where('jenis_paket', $jenis_paket_slug)->get();

        return view('order.create', compact('jenis_paket_slug','paket', 'jenis_paket'));
    }

/**
 * Store a newly created resource in storage.
 */
public function store(Request $request, $jenis_paket)
{
    $rules = [
        'nama_pelanggan' => 'required|string|max:255',
        'no_telp' => 'required|string|max:255',
        'alamat' => 'required|string|max:255',
        'id_paket' => 'required|exists:pakets,id',
        'tgl_masuk' => 'required|date',
        'tgl_keluar' => 'required|date|after_or_equal:tgl_masuk',
        'keterangan' => 'nullable|string',
    ];

    if ($jenis_paket === 'cuci-satuan') {
        $rules['berat_order'] = 'required|integer|min:1';
    } else {
        $rules['berat_order'] = 'required|numeric|min:0.1';
    }

    $validatedData = $request->validate($rules);

    try {
        // Buat Nomor Order Unik
        $prefix = strtoupper(substr($jenis_paket, 0, 2)); // CK, DC, CS
        $randomString = strtoupper(Str::random(7));
        $or_number = $prefix . '-' . $randomString;

        // Hitung Total Harga
        $paket = Paket::findOrFail($validatedData['id_paket']);
        $total = $paket->harga * $validatedData['berat_order'];

        // Siapkan Data untuk Disimpan
        $dataToSave = $validatedData;
        $dataToSave['or_number'] = $or_number;
        $dataToSave['total'] = $total;
        $dataToSave['status'] = 'Belum Bayar'; // Status awal

        $order = Order::create($dataToSave);

        // Generate Midtrans Snap Token
        $midtransParams = [
            'transaction_details' => [
                'order_id' => $order->or_number,
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' => [
                'first_name' => $order->nama_pelanggan,
                'phone' => $order->no_telp,
                'billing_address' => [
                    'address' => $order->alamat,
                ],
            ],
            'item_details' => [
                [
                    'id' => $paket->id,
                    'price' => (int) $order->total,
                    'quantity' => 1,
                    'name' => $paket->nama_paket . ' (' . rtrim(rtrim(number_format($order->berat_order, 2), '0'), '.') . ' Kg)',
                ],
            ],
        ];


        try {
            Log::info('Mencoba membuat Snap Token untuk Order: ' . $order->or_number);
            $snapToken = \Midtrans\Snap::getSnapToken($midtransParams);
            
            if ($snapToken) {
                Log::info('SUKSES: Snap Token didapatkan.');
                $order->update(['snap_token' => $snapToken]);
            } else {
                Log::error('GAGAL: Snap Token kosong.');
            }

        } catch (\Exception $e) {
            Log::error('GAGAL: Exception umum.');
            Log::error('Error Message: ' . $e->getMessage());
        }

        // Redirect ke halaman daftar paket
        $slugMapping = [
            'Cuci Komplit' => 'cuci-komplit',
            'Cuci Lipat'   => 'cuci-lipat',
            'Cuci Satuan'  => 'cuci-satuan',
        ];
        $jenis_paket_slug = $slugMapping[$paket->jenis_paket] ?? 'cuci-komplit';

        // KODE BARU
        return redirect()
            ->route('home') // Ubah redirect ke route 'home'
            ->with('success', 'Order dengan nomor ' . $or_number . ' berhasil dibuat!');

    } catch (\Exception $e) {
        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
/**
 * API untuk mengecek apakah ada order 'On Progress' yang harus selesai di tanggal tertentu
 */
public function checkUnfinishedOrder(Request $request)
{
    $tgl_masuk = $request->query('tgl_masuk');
    
    if (!$tgl_masuk) {
        return response()->json(['exists' => false]);
    }

    // Cek apakah ada order yang statusnya 'On Progress' dan tgl_keluar-nya sama dengan tgl_masuk yang diinput
    $exists = Order::whereDate('tgl_keluar', $tgl_masuk)
                    ->where('status', 'On Progress')
                    ->exists();
    
    return response()->json(['exists' => $exists]);
}
/**
 * Display the specified resource.
 */
public function show($jenis_paket, string $id)
{
    $packageMapping = [
        'cuci-komplit' => 'Cuci Komplit',
        'cuci-lipat'   => 'Cuci Lipat',
        'cuci-satuan'  => 'Cuci Satuan',
    ];

    if (!array_key_exists($jenis_paket, $packageMapping)) {
        abort(404);
    }
    
    $order = Order::with('paket')->findOrFail($id);

    if ($order->paket->jenis_paket !== $packageMapping[$jenis_paket]) {
        abort(404);
    }

    // --- LOGIKA PERHITUNGAN DURASI ---
    $durasiTeks = 'Tidak Diketahui'; // Nilai default

    try {
        // Buat objek DateTime dari string tanggal
        $tglMasuk = new \DateTime($order->tgl_masuk);
        $tglKeluar = new \DateTime($order->tgl_keluar);
        
        // Hitung selisih antara dua tanggal
        $selisih = $tglMasuk->diff($tglKeluar);
        
        // Ambil jumlah hari
        $durasiHari = $selisih->days;
        
        // Format teks durasi agar lebih mudah dibaca
        if ($durasiHari == 0) {
            $durasiTeks = 'Same Day'; // atau 'Hari yang Sama'
        } elseif ($durasiHari == 1) {
            $durasiTeks = '1 Hari';
        } else {
            $durasiTeks = $durasiHari . ' Hari';
        }

    } catch (\Exception $e) {
        // Jika format tanggal tidak valid atau ada error lain,
        // variabel $durasiTeks akan tetap bernilai 'Tidak Diketahui'.
        // Anda bisa juga melakukan logging di sini jika perlu.
        // Log::error('Gagal menghitung durasi untuk Order ID ' . $id . ': ' . $e->getMessage());
    }
    // --- AKHIR LOGIKA ---

    // Kirim variabel $durasiTeks ke view
    return view('order.show', compact('order', 'jenis_paket', 'durasiTeks'));
}

    /**
     * Proses pembayaran order.
     */
    public function processPayment(Request $request, string $id)
    {
        $request->validate([
            'dibayarkan' => 'required|numeric|min:0',
        ]);

        $order = Order::findOrFail($id);

        if ($request->dibayarkan < $order->total) {
            return redirect()->back()->with('error', 'Nominal pembayaran kurang!');
        }

        $kembalian = $request->dibayarkan - $order->total;

        // Update status dan data pembayaran
        $order->update([
            'status'      => $this->resolvePaidStatus($order),
            'dibayarkan'  => $request->dibayarkan,
            'kembalian'   => $kembalian,
        ]);

        // **PERUBAHAN DI SINI**
        // Redirect ke halaman invoice yang baru kita buat
        return redirect()
            ->route('order.invoice', $order->id)
            ->with('success', 'Pembayaran berhasil! Order sedang diproses.');
    }

    /**
     * Tampilkan halaman invoice untuk pencetakan.
     * Halaman ini tidak menggunakan layout utama.
     */
    public function showInvoice(string $id)
    {
        // Mengambil data order yang sudah dibayar
        $order = Order::findOrFail($id);

        // Pastikan hanya bisa diakses jika statusnya sudah 'On Progress' atau 'Lunas'
        if (!in_array($order->status, ['On Progress', 'Lunas', 'Selesai'])) {
            // Jika belum dibayar, kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Invoice hanya dapat dilihat setelah pembayaran dilakukan.');
        }

        // Mengirim data $order ke view 'order.invoice'
        // View ini akan menjadi halaman mandiri tanpa extends
        return view('order.invoice', compact('order'));
    }

    public function payLater(string $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'Selesai') {
            return redirect()->back()->with('error', 'Order sudah selesai.');
        }

        if (($order->dibayarkan ?? 0) >= $order->total) {
            return redirect()->back()->with('success', 'Order sudah dibayar.');
        }

        $order->update([
            'status' => 'On Progress',
            'dibayarkan' => 0,
            'kembalian' => 0,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Pembayaran ditandai bayar nanti. Order tetap diproses.');
    }

    public function markAsReadyForPickup(string $id)
    {
        $order = Order::findOrFail($id);

        if (in_array($order->status, ['Selesai', 'Siap Diambil'])) {
            return redirect()->back()->with('success', 'Order sudah siap diambil.');
        }

        if (!in_array($order->status, ['On Progress', 'Terlambat'])) {
            return redirect()->back()->with('error', 'Status order tidak valid.');
        }

        $order->update(['status' => 'Siap Diambil']);

        // Format nomor telepon
        $phoneNumber = $order->no_telp;
        if (substr($phoneNumber, 0, 2) === '08') {
            $phoneNumber = '+628' . substr($phoneNumber, 2);
        }

        $message = urlencode(
            "Halo, Bapak/Ibu {$order->nama_pelanggan}. Pesanan laundry Anda dengan No. Order {$order->or_number} sudah selesai dan dapat diambil. Terima kasih telah menggunakan jasa kami."
        );

        $whatsappUrl = "https://wa.me/{$phoneNumber}?text={$message}";

        return redirect()->away($whatsappUrl);
    }

/**
 * Menandai order sebagai "Selesai" setelah benar-benar diambil.
 */
public function markAsCompleted(string $id)
{
    $order = Order::findOrFail($id);

    $dibayarkan = $order->dibayarkan ?? 0;
    $isPaid = $order->total > 0 && $dibayarkan >= $order->total;
    if (!$isPaid) {
        return redirect()->back()->with('error', 'Pembayaran belum lunas.');
    }

    if ($order->status !== 'Siap Diambil') {
        return redirect()->back()->with('error', 'Order belum siap diambil.');
    }

    $order->update(['status' => 'Selesai']);

    return redirect()->back()->with('success', 'Pesanan ditandai sudah diambil.');
}

    /**
     * Menandai order sebagai "Terlambat" dan buka WhatsApp.
     */
    public function markAsDelayed(string $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'Terlambat']);

        // Format nomor telepon
        $phoneNumber = $order->no_telp;
        if (substr($phoneNumber, 0, 2) === '08') {
            $phoneNumber = '+628' . substr($phoneNumber, 2);
        }

        // Buat pesan WhatsApp
        $message = urlencode(
            "Halo, Bapak/Ibu *{$order->nama_pelanggan}*. Pesanan laundry Anda dengan No. Order *{$order->or_number}* terindikasi terlambat dari estimasi tanggal pengambilan. Mohon maaf atas ketidaknyamanan pelayanannya. Terima kasih."
        );

        $whatsappUrl = "https://wa.me/{$phoneNumber}?text={$message}";

        return redirect()->away($whatsappUrl);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $order = Order::findOrFail($id);

            $order->delete();
            
            // 5. Redirect ke halaman daftar paket yang sesuai dengan pesan sukses
            return redirect()
                ->route('home')->with('success', 'Orderan berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()
                ->back() // Kembali ke halaman sebelumnya (form edit)
                ->withInput() // Mengembalikan input yang sudah diisi user
                ->with('error', 'Terjadi kesalahan saat mengupdate paket. Pesan: ' . $e->getMessage());
        }
    }

    public function riwayat(Request $request)
    {
        $baseQuery = Order::with('paket')
            ->where('status', '=', 'Selesai')
            ->whereRaw('COALESCE(dibayarkan, 0) >= total');

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $baseQuery->where(function ($query) use ($keyword) {
                $query->where('or_number', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $keyword . '%');
            });
        }

        $tanggal = $request->input('tanggal');
        if (!empty($tanggal)) {
            $baseQuery->whereDate('tgl_masuk', $tanggal);
        } else {
            if ($request->filled('tahun')) {
                $baseQuery->whereYear('tgl_masuk', $request->input('tahun'));
            }

            if ($request->filled('bulan')) {
                $baseQuery->whereMonth('tgl_masuk', $request->input('bulan'));
            }
        }

        $cuci_komplit = (clone $baseQuery)
            ->whereHas('paket', fn($q) => $q->where('jenis_paket', 'Cuci Komplit'))
            ->orderBy('created_at', 'asc')
            ->get();

        $cuci_satuan = (clone $baseQuery)
            ->whereHas('paket', fn($q) => $q->where('jenis_paket', 'Cuci Satuan'))
            ->orderBy('created_at', 'asc')
            ->get();

        $cuci_lipat = (clone $baseQuery)
            ->whereHas('paket', fn($q) => $q->where('jenis_paket', 'Cuci Lipat'))
            ->orderBy('created_at', 'asc')
            ->get();

        return view('riwayat.index', compact('cuci_komplit', 'cuci_satuan', 'cuci_lipat'));
    }

    private function applyLaporanFilters($query, Request $request): void
    {
        if ($request->filled('tahun')) {
            $query->whereYear('tgl_masuk', $request->input('tahun'));
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tgl_masuk', $request->input('bulan'));
        }

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        if (!empty($tanggalMulai) && !empty($tanggalSelesai)) {
            $query->whereBetween('tgl_masuk', [$tanggalMulai, $tanggalSelesai]);
        } elseif (!empty($tanggalMulai)) {
            $query->whereDate('tgl_masuk', '>=', $tanggalMulai);
        } elseif (!empty($tanggalSelesai)) {
            $query->whereDate('tgl_masuk', '<=', $tanggalSelesai);
        }
    }

    public function laporanKeuangan(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $query = Order::with('paket')
            ->where('status', '=', 'Selesai');
        $query->whereRaw('COALESCE(dibayarkan, 0) >= total');

        $this->applyLaporanFilters($query, $request);

        $orders = $query->orderBy('created_at', 'asc')->get();

        $summary = [
            'total_transaksi' => $orders->count(),
            'total_pendapatan' => $orders->sum('total'),
            'total_dibayarkan' => $orders->sum('dibayarkan'),
            'total_kembalian' => $orders->sum('kembalian'),
        ];

        return view('laporan.index', compact('orders', 'summary'));
    }

    public function laporanKeuanganPrint(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $query = Order::with('paket')
            ->where('status', '=', 'Selesai');
        $query->whereRaw('COALESCE(dibayarkan, 0) >= total');

        $this->applyLaporanFilters($query, $request);

        $orders = $query->orderBy('created_at', 'asc')->get();

        $summary = [
            'total_transaksi' => $orders->count(),
            'total_pendapatan' => $orders->sum('total'),
            'total_dibayarkan' => $orders->sum('dibayarkan'),
            'total_kembalian' => $orders->sum('kembalian'),
        ];

        $filters = $request->only(['tahun', 'bulan', 'tanggal_mulai', 'tanggal_selesai']);

        return view('laporan.print', compact('orders', 'summary', 'filters'));
    }

    /**
     * Handle Midtrans notification.
     */
    public function notificationHandler(Request $request)
    {
        Log::info('Midtrans notification received.', ['request' => $request->all()]);

        try {
            $notif = new Notification();
            
            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;
            
            Log::info("Processing notification for Order ID: {$orderId}, Status: {$transaction}");

            $order = Order::where('or_number', $orderId)->first();
            
            if (!$order) {
                Log::error("Order not found for Order ID: {$orderId}");
                return response('Order not found', 404);
            }
            
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $order->update(['status' => 'Menunggu Verifikasi']);
                    } else {
                        $order->update([
                            'status'      => $this->resolvePaidStatus($order),
                            'dibayarkan'  => $order->total,
                            'kembalian'   => 0,
                        ]);
                        Log::info("Order {$orderId} status updated to On Progress.");
                    }
                }
            } else if ($transaction == 'settlement') {
                // Ini adalah bagian terpenting untuk QRIS dan VA
                $order->update([
                    'status'      => $this->resolvePaidStatus($order),
                    'dibayarkan'  => $order->total,
                    'kembalian'   => 0,
                ]);
                Log::info("Order {$orderId} status updated to On Progress.");
                
            } else if ($transaction == 'pending') {
                $order->update(['status' => 'Menunggu Pembayaran']);
            } else if ($transaction == 'deny') {
                $order->update(['status' => 'Pembayaran Ditolak']);
            } else if ($transaction == 'expire') {
                $order->update(['status' => 'Kadaluarsa']);
            } else if ($transaction == 'cancel') {
                $order->update(['status' => 'Dibatalkan']);
            }
            
            return response('Notification processed successfully', 200);
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response('Error processing notification', 500);
        }
    }

    /**
     * Proses awal pembayaran Midtrans, ubah status dulu.
     */
    public function processMidtransPayment(string $id)
    {
        $order = Order::with('paket')->findOrFail($id);

        // Ubah status dan data pembayaran
        $order->update([
            'status'      => $this->resolvePaidStatus($order),
            'dibayarkan'  => $order->total, // Samakan dengan total
            'kembalian'   => 0,
        ]);

        // --- PERBAIKAN DI SINI ---
        // Ubah jenis_paket (contoh: "Cuci Komplit") kembali ke slug untuk URL (contoh: "cuci-komplit")
        $slugMapping = [
            'Cuci Komplit' => 'cuci-komplit',
            'Cuci Lipat'   => 'cuci-lipat',
            'Cuci Satuan'  => 'cuci-satuan',
        ];
        $jenis_paket_slug = $slugMapping[$order->paket->jenis_paket] ?? 'cuci-komplit';

        // Redirect kembali ke halaman detail dengan parameter untuk membuka Midtrans
        return redirect()
            ->route('order.show', ['jenis_paket' => $jenis_paket_slug, 'id' => $order->id])
            ->with('success', 'Order sedang diproses. Silakan lakukan pembayaran.')
            ->with('openMidtrans', true); // Kirim flag untuk membuka modal
    }

    // app/Http/Controllers/OrderController.php

/**
 * API untuk mengecek apakah ada order di tanggal keluar tertentu
 */
public function checkOrderDate(Request $request)
{
    $tgl_keluar = $request->query('tgl_keluar');
    
    if (!$tgl_keluar) {
        return response()->json(['exists' => false]);
    }

    $exists = Order::whereDate('tgl_keluar', $tgl_keluar)
                    ->where('status', '!=', 'Selesai')
                    ->exists();
    
    return response()->json(['exists' => $exists]);
}
    
    /**
 * Proses pembayaran tunai.
 */
public function processCashPayment(Request $request, string $id)
{
    $order = Order::findOrFail($id);

    $request->validate([
        'cash_amount' => 'required|numeric|min:0',
    ]);

    $cashAmount = (float) $request->cash_amount;
    if ($cashAmount < $order->total) {
        return redirect()->back()->with('error', 'Nominal pembayaran kurang!');
    }

    $kembalian = $cashAmount - $order->total;

    // Update status dan data pembayaran
    $order->update([
        'status'      => $this->resolvePaidStatus($order),
        'dibayarkan'  => $cashAmount,
        'kembalian'   => $kembalian,
    ]);

    // Ubah jenis_paket (contoh: "Cuci Komplit") kembali ke slug untuk URL (contoh: "cuci-komplit")
    $slugMapping = [
        'Cuci Komplit' => 'cuci-komplit',
        'Cuci Lipat'   => 'cuci-lipat',
        'Cuci Satuan'  => 'cuci-satuan',
    ];
    $jenis_paket_slug = $slugMapping[$order->paket->jenis_paket] ?? 'cuci-komplit';

    // Redirect kembali ke halaman detail
    return redirect()
        ->route('order.show', ['jenis_paket' => $jenis_paket_slug, 'id' => $order->id])
        ->with('success', 'Pembayaran tunai berhasil! Order sedang diproses.');
}
}
