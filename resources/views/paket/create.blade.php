@extends('layouts.app')

@section('title', 'Tambah Paket')

@section('content')
   <div id="tambah_paket_ck" class="main-content">
      <div class="container">
         <div class="baris">
            <div class="col mt-2">
               <div class="card">
                  <div class="card-title card-flex">
                     <div class="card-col">
                        <h2>Tambah Paket {{ $filter_data }}</h2>	
                     </div>
                     <div class="card-col txt-right">
                        <a href="{{ route('paket.list', $jenis_paket) }}" class="btn-xs bg-primary">Kembali</a>
                     </div>
                  </div>

                  <div class="card-body">
                     <form action="{{ route('paket.store', $jenis_paket) }}" method="POST" class="form-input">
                        @csrf
                        <input type="hidden" name="jenis_paket" placeholder="Nama paket" autocomplete="off" id="jenis_paket" value="{{ $filter_data }}" required>
                        <div class="form-grup">
                           <label for="nama_paket">Nama Paket</label>
                           <input type="text" name="nama_paket" placeholder="Nama paket" autocomplete="off" id="nama_paket" required>
                        </div>

                        <div class="form-grup">
                           <label for="waktu_kerja">Waktu Kerja</label>
                           <input type="text" name="waktu_kerja" placeholder="Durasi Kerja" autocomplete="off" id="waktu_kerja" required>
                        </div>
                        @if ($filter_data === 'Cuci Satuan')
                            <div class="form-grup">
                                <label for="berat_minimal">Jumlah Min (Pcs)</label>
                                <input type="number" name="berat_minimal" placeholder="Jumlah per-Pcs" autocomplete="off" id="berat_minimal" required>
                            </div>
                        @else  
                            <div class="form-grup">
                                <label for="berat_minimal">Berat Min (Kg)</label>
                                <input type="number" name="berat_minimal" placeholder="Berat per-Kg" autocomplete="off" id="berat_minimal" required>
                            </div>
                        @endif

                        <div class="form-grup">
                           <label for="harga">Harga</label>
                           <input type="number" name="harga" placeholder="Harga Paket" autocomplete="off" id="harga" required>
                        </div>

                        <div class="form-grup ">
                           <button type="submit" class="mt-1">Tambah</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
@endsection