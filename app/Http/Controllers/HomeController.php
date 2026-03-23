<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Paket;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $jumlah_paket = Paket::count();
        $jumlah_order = Order::where('status', '!=', 'Selesai')->count();
        $jumlah_karyawan = User::where('level', 'karyawan')->count();

        $cuci_komplit = Order::where('status', '!=', 'Selesai')
                            ->whereHas('paket', fn($q) => $q->where('jenis_paket', 'Cuci Komplit'));
        $this->applyDashboardFilters($cuci_komplit);
        $cuci_komplit = $cuci_komplit->orderBy('created_at', 'asc')->get();

        $cuci_satuan = Order::where('status', '!=', 'Selesai')
                            ->whereHas('paket', fn($q) => $q->where('jenis_paket', 'Cuci Satuan'));
        $this->applyDashboardFilters($cuci_satuan);
        $cuci_satuan = $cuci_satuan->orderBy('created_at', 'asc')->get();

        $cuci_lipat = Order::where('status', '!=', 'Selesai')
                        ->whereHas('paket', fn($q) => $q->where('jenis_paket', 'Cuci Lipat'));
        $this->applyDashboardFilters($cuci_lipat);
        $cuci_lipat = $cuci_lipat->orderBy('created_at', 'asc')->get();
        
        return view('home', compact('jumlah_paket','jumlah_order', 'jumlah_karyawan', 'cuci_komplit', 'cuci_satuan', 'cuci_lipat'));
    }

    private function applyDashboardFilters($query): void
    {
        $request = request();

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($sub) use ($keyword) {
                $sub->where('or_number', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
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
}
