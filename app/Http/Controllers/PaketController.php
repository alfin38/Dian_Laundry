<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('paket.index');
    }

    public function list_paket($jenis_paket)
    {
        // 1. Buat pemetaan (whitelist) dari URL slug ke nama di database
        $packageMapping = [
            'cuci-komplit' => 'Cuci Komplit',
            'cuci-lipat'   => 'Cuci Lipat',
            'cuci-satuan'  => 'Cuci Satuan',
        ];

        // 2. Cek apakah $jenis_paket valid dan ambil nama untuk filter
        if (!array_key_exists($jenis_paket, $packageMapping)) {
            abort(404);
        }
        $filter_data = $packageMapping[$jenis_paket];

        // 3. Jalankan query
        $paket = Paket::where('jenis_paket', $filter_data)->get();

        // 4. Tentukan nama view dan kirim data
        $halaman = "paket.{$jenis_paket}";
        return view($halaman, compact('paket', 'filter_data', 'jenis_paket'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($jenis_paket)
    {
        $packageMapping = [
            'cuci-komplit' => 'Cuci Komplit',
            'cuci-lipat'   => 'Cuci Lipat',
            'cuci-satuan'  => 'Cuci Satuan',
        ];

        // 2. Cek apakah $jenis_paket valid dan ambil nama untuk filter
        if (!array_key_exists($jenis_paket, $packageMapping)) {
            abort(404);
        }

        $filter_data = $packageMapping[$jenis_paket];

        return view('paket.create', compact('filter_data', 'jenis_paket'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi data input
        $validatedData = $request->validate([
            'jenis_paket' => 'required|string|max:255',
            'nama_paket' => 'required|string|max:255',
            'waktu_kerja' => 'required|string|max:255',
            'berat_minimal' => 'required|numeric|max:10',
            'harga' => 'required|numeric',
        ]);

        try {
            // 2. Simpan data
            Paket::create($validatedData);

            // 3. Buat pemetaan dari nama di database ke slug URL
            $slugMapping = [
                'Cuci Komplit' => 'cuci-komplit',
                'Cuci Lipat'   => 'cuci-lipat',
                'Cuci Satuan'  => 'cuci-satuan',
            ];

            // 4. Ambil slug dari data yang sudah divalidasi
            $jenis_paket_slug = $slugMapping[$validatedData['jenis_paket']] ?? 'cuci-komplit'; // Gunakan default jika tidak ditemukan

            // 5. Redirect ke halaman daftar paket yang sesuai dengan pesan sukses
            return redirect()
                ->route('paket.list', ['jenis_paket' => $jenis_paket_slug])
                ->with('success', 'Paket "' . $validatedData['nama_paket'] . '" berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Jika terjadi error, kembalikan ke form dengan pesan error
            $jenis_paket_slug = $slugMapping[$validatedData['jenis_paket']] ?? 'cuci-komplit'; // Gunakan default jika tidak ditemukan

            return redirect()
                ->route('paket.list', ['jenis_paket' => $jenis_paket_slug])
                ->with('error', 'Terjadi kesalahan saat menyimpan paket. Pesan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($jenis_paket, string $id) // Urutan parameter di sini harus sama dengan route
    {
        // 1. Validasi jenis_paket
        $packageMapping = [
            'cuci-komplit' => 'Cuci Komplit',
            'cuci-lipat'   => 'Cuci Lipat',
            'cuci-satuan'  => 'Cuci Satuan',
        ];

        if (!array_key_exists($jenis_paket, $packageMapping)) {
            abort(404);
        }

        // 2. Cari data paket berdasarkan ID
        // Gunakan findOrFail() saja, jangan tambahkan ->first()
        $ck = Paket::findOrFail($id);

        // 3. (Opsional) Tambahkan validasi untuk memastikan paket yang diedit sesuai jenisnya
        // Ini mencegah user mengedit ID paket 'Cuci Lipat' dari halaman 'Cuci Komplit'
        if ($ck->jenis_paket !== $packageMapping[$jenis_paket]) {
            abort(404); // atau bisa redirect dengan pesan error
        }

        // 4. Kirim data ke view
        return view('paket.edit', compact('ck', 'jenis_paket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $jenis_paket, string $id)
    {
        // 1. Validasi data input (sudah benar)
        $validatedData = $request->validate([
            'jenis_paket' => 'required|string|max:255',
            'nama_paket' => 'required|string|max:255',
            'waktu_kerja' => 'required|string|max:255',
            'berat_minimal' => 'required|numeric|max:10',
            'harga' => 'required|numeric',
        ]);

        try {
            // 2. Cari paket berdasarkan ID dan update datanya
            // findOrFail akan otomatis menampilkan 404 jika ID tidak ditemukan
            $paket = Paket::findOrFail($id);
            $paket->update($validatedData);

            // 3. Buat pemetaan dari nama di database ke slug URL
            $slugMapping = [
                'Cuci Komplit' => 'cuci-komplit',
                'Cuci Lipat'   => 'cuci-lipat',
                'Cuci Satuan'  => 'cuci-satuan',
            ];

            // 4. Ambil slug dari data yang sudah divalidasi
            $jenis_paket_slug = $slugMapping[$validatedData['jenis_paket']] ?? 'cuci-komplit';

            // 5. Redirect ke halaman daftar paket yang sesuai dengan pesan sukses
            return redirect()
                ->route('paket.list', ['jenis_paket' => $jenis_paket_slug])
                ->with('success', 'Paket "' . $validatedData['nama_paket'] . '" berhasil diupdate!');

        } catch (\Exception $e) {
            // Jika terjadi error (misalnya validasi gagal atau error DB), redirect kembali
            // Lebih baik redirect ke halaman edit dengan input lama dan pesan error
            return redirect()
                ->back() // Kembali ke halaman sebelumnya (form edit)
                ->withInput() // Mengembalikan input yang sudah diisi user
                ->with('error', 'Terjadi kesalahan saat mengupdate paket. Pesan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($jenis_paket, string $id)
    {
        try {
            $paket = Paket::findOrFail($id);

            $paket->delete();
            
            $jenis_paket_slug = $jenis_paket;

            // 5. Redirect ke halaman daftar paket yang sesuai dengan pesan sukses
            return redirect()
                ->route('paket.list', ['jenis_paket' => $jenis_paket_slug])
                ->with('success', 'Paket "' . $paket['nama_paket'] . '" berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()
                ->back() // Kembali ke halaman sebelumnya (form edit)
                ->withInput() // Mengembalikan input yang sudah diisi user
                ->with('error', 'Terjadi kesalahan saat mengupdate paket. Pesan: ' . $e->getMessage());
        }
    }
}
