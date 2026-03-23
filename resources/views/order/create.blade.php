@extends('layouts.app')

@section('title', 'Order Baru')

@section('content')
   <div id="order_ck" class="main-content">
      <div class="container">
         <div class="baris">
            <div class="col mt-2">
               <div class="card">
                  <div class="card-title card-flex">
                     <div class="card-col">
                        <h2>{{ $jenis_paket_slug }}</h2>
                     </div>

                     <div class="card-col txt-right">
                        <a href="{{ route('order.index') }}" class="btn-xs bg-primary">Kembali</a>
                     </div>
                  </div>

                  <div class="card-body">
                     {{-- TAMBAHKAN ID PADA FORM UNTUK DISELEKSI OLEH JAVASCRIPT --}}
                     <form action="{{ route('order.store', $jenis_paket) }}" method="POST" id="orderForm">
                        @csrf
                        <div class="row-input">
                           <div class="col-form m-1">
                              <div class="form-grup">
                                 <label for="nama_pelanggan">Nama Pelanggan</label>
                                 <input type="text" name="nama_pelanggan" placeholder="Nama lengkap" autocomplete="off" id="nama_pelanggan">
                              </div>

                              <div class="form-grup">
                                 <label for="no_telp">Nomor Telepon</label>
                                 <input type="text" name="no_telp" placeholder="Nomor Telepon" autocomplete="off" id="no_telp">
                              </div>

                              <div class="form-grup">
                                 <label for="alamat">Alamat</label>
                                 <input type="text" name="alamat" placeholder="Alamat Pelanggan" autocomplete="off" id="alamat">
                              </div>
                           </div>

                           <div class="col-form m-1">
                              <div class="form-grup">
                                 <label for="pilih_paket">Pilih Paket</label>
                                 <select name="id_paket" id="id_paket">
                                    <option value="">-- Pilih Paket --</option>
                                        @foreach ($paket as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_paket }}</option>
                                        @endforeach
                                    </select>
                              </div>
                              @if ($jenis_paket === 'cuci-satuan')
                                 <div class="form-grup">
                                    <label for="berat_order">Jumlah Pesanan (Pcs)</label>
                                    <input type="number" name="berat_order" placeholder="Jumlah Pesanan (Pcs)" autocomplete="off" id="berat_order" min="1" step="1">
                                 </div>
                              @else
                                 <div class="form-grup">
                                    <label for="berat_order">Berat Pesanan (Kg)</label>
                                    <input type="number" name="berat_order" placeholder="Berat Pesanan (Kg)" autocomplete="off" id="berat_order" step="any">
                                 </div>
                              @endif

                              <div class="form-grup">
                                 <label for="tgl_masuk">Tanggal Order Masuk</label>
                                 <input type="date" name="tgl_masuk" autocomplete="off" id="tgl_masuk">
                              </div>

                              <div class="form-grup">
                                 <label for="tgl_keluar">Tanggal Order Keluar</label>
                                 <input type="date" name="tgl_keluar" autocomplete="off" id="tgl_keluar">
                              </div>

                              <div class="form-grup">
                                 <label for="keterangan">Keterangan</label>
                                 <input type="text" name="keterangan" autocomplete="off" id="keterangan" placeholder="(Optional)">
                              </div>
                           </div>
                        </div>
                        
                        <div class="form-footer">
                           <div class="buttons">
                              <button type="submit" name="order_ck" class="btn-sm bg-primary" id="submitOrderBtn">Pesan</button>
                              <button type="reset" class="btn-sm bg-transparent">Batal</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
@endsection

{{-- GANTI SELURUH BAGIAN SCRIPT DENGAN INI --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tglMasukInput = document.getElementById('tgl_masuk');
    const orderForm = document.getElementById('orderForm');
    const submitBtn = document.getElementById('submitOrderBtn');
    let hasUnfinishedOrder = false; // Flag untuk menyimpan hasil pengecekan order yang belum selesai

    // Fungsi untuk mengecek order yang belum selesai di tanggal tertentu
    async function checkUnfinishedOrder() {
        const tglMasuk = tglMasukInput.value;
        if (!tglMasuk) {
            hasUnfinishedOrder = false;
            return;
        }

        try {
            // Panggil endpoint API baru yang kita buat
            const response = await fetch(`/api/check-unfinished-order?tgl_masuk=${tglMasuk}`);
            const data = await response.json();
            hasUnfinishedOrder = data.exists;
        } catch (error) {
            console.error('Error checking unfinished order:', error);
            hasUnfinishedOrder = false; // Asumsi aman jika error
        }
    }

    // Event listener saat form akan disubmit
    orderForm.addEventListener('submit', function(event) {
        // Hentikan submit dulu
        event.preventDefault();

        // Jalankan pengecekan untuk memastikan data paling akurat
        checkUnfinishedOrder().then(() => {
            if (hasUnfinishedOrder) {
                // Format tanggal agar mudah dibaca
                const formattedDate = new Date(tglMasukInput.value).toLocaleDateString('id-ID', {
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                });
                
                // Pesan konfirmasi sesuai permintaan Anda
                const confirmMessage = `Terdapat Order yang belum selesai dan harus selesai pada ${formattedDate}. Apakah Anda yakin ingin tetap menyimpan order?`;
                
                if (confirm(confirmMessage)) {
                    // Jika user setuju, submit form
                    event.target.submit();
                } else {
                    // Jika tidak, tidak ada apa-apa yang terjadi
                    console.log('Pembuatan order dibatalkan oleh pengguna.');
                }
            } else {
                // Jika tidak ada order yang belum selesai, submit form langsung
                event.target.submit();
            }
        });
    });
});
</script>
