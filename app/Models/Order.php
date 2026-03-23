<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'or_number',
        'nama_pelanggan',
        'no_telp',
        'alamat',
        'id_paket',
        'berat_order',
        'tgl_masuk',
        'tgl_keluar',
        'total',
        'keterangan',
        'status',
        'dibayarkan',
        'kembalian',
        'snap_token', // Tambahkan ini
    ];


    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }
}
