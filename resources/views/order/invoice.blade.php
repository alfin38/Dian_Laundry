{{-- File view ini sengaja TIDAK menggunakan @extends agar menjadi halaman mandiri --}}
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Invoice {{ $order->or_number }}</title>
   <link rel="shortcut icon" href="{{ asset('_assets/img/logo/logo2.png') }}" type="image/x-icon">
   <link rel="stylesheet" href="{{ asset('_assets/css/invoice.css') }}">
</head>
<body>
   <div class="invoice">
      <div class="invoice-content">
         <div class="invoice-header">
            <div class="logo">
               <img src="{{ asset('_assets/img/logo/logo.png') }}" width="145" alt="Logo rumah laundry">
            </div>
            <div class="invoice-no_order">
               <span>Invoice number : {{ $order->or_number }}</span>
            </div>
         </div>

         <h4 style="text-align: center; color:#4d4d4d;">Bukti Transaksi</h4>

         <div class="invoice-body">

            {{-- Informasi Pelanggan --}}
            <table class="table-invoice">
               <tr>
                  <th>Nama pelanggan</th>
                  <td>{{ $order->pelanggan->name ?? 'Tidak diketahui' }}</td>
               </tr>
               <tr>
                  <th>Nomor telepon</th>
                  <td>{{ $order->pelanggan->no_telp ?? '-' }}</td>
               </tr>
               <tr>
                  <th>Alamat</th>
                  <td>{{ $order->pelanggan->alamat ?? '-' }}</td>
               </tr>
            </table>

            {{-- Informasi Tanggal --}}
            <table class="table-invoice">
               <tr>
                  <th>Tanggal order</th>
                  <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
               </tr>
               <tr>
                  <th>Diambil pada</th>
                  <td>
                     {{ $order->tgl_klr 
                         ? \Carbon\Carbon::parse($order->tgl_klr)->format('d-m-Y') 
                         : 'Belum ditentukan' }}
                  </td>
               </tr>
            </table>

            {{-- Detail Pembayaran --}}
            <table class="tb_byr">
               <tr>
                  <th class="tb_heading">Jenis paket</th>
                  <th class="tb_heading">Berat (Kg)</th>
                  <th class="tb_heading">Harga</th>
               </tr>

               <tr>
                  <td>{{ $order->paket->name ?? 'Paket Umum' }}</td>

                  {{-- Format Berat --}}
                  <td>
                     @php
                         $berat = $order->berat_order;
                     @endphp

                     {{ $berat == intval($berat) 
                        ? intval($berat) 
                        : rtrim(rtrim(number_format($berat, 2), '0'), '.') }} Kg
                  </td>

                  {{-- Harga x Berat --}}
                  <td>
                     Rp. {{ number_format($order->h_perkilo, 0, ',', '.') }} x
                     @php
                         $berat2 = $order->berat_order;
                     @endphp
                     {{ $berat2 == intval($berat2) 
                        ? intval($berat2) 
                        : rtrim(rtrim(number_format($berat2, 2), '0'), '.') }}
                  </td>
               </tr>

               {{-- Total --}}
               <tr>
                  <th colspan="2" class="ub">Total</th>
                  <td class="ub-col">Rp. {{ number_format($order->total, 0, ',', '.') }}</td>
               </tr>

               {{-- Bayar --}}
               <tr>
                  <th colspan="2" class="ub">Nominal Bayar</th>
                  <td class="ub-col">Rp. {{ number_format($order->dibayarkan, 0, ',', '.') }}</td>
               </tr>

               {{-- Kembalian --}}
               <tr>
                  <th colspan="2" class="ub">Uang kembali</th>
                  <td class="ub-col">Rp. {{ number_format($order->kembalian, 0, ',', '.') }}</td>
               </tr>
            </table>

            @if($order->keterangan)
            <div class="ket">
               <p><span>Keterangan : </span>{{ $order->keterangan }}</p>
            </div>
            @endif

            <div class="invoice-footer">
               <h3 class="foot_logo"><span>Dian</span> Laundry</h3>
               <p>Terima kasih telah menggunakan jasa kami.</p>
            </div>

         </div>
      </div>

      <div class="printbtn" id="btnPrint">
         <img src="{{ asset('_assets/img/printer.svg') }}" width="48" alt="print icon">
         <span>Cetak Invoice</span>
      </div>

      <a href="{{ route('home') }}" class="btn-back">Kembali</a>

   </div>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         let print = document.getElementById('btnPrint');
         print.addEventListener('click', function(){
            window.print();
         });
      });
   </script>
</body>
</html>
