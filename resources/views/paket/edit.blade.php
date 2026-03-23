@extends('layouts.app')

@section('title', 'Edit Paket')

@section('content')
<div id="edit_ck" class="main-content">
      <div class="container">
         <div class="baris">
            <div class="col mt-2">
               <div class="card">
                  <div class="card-title card-flex">
                     <div class="card-col">
                        <h2>Ubah Paket</h2>	
                     </div>
                     <div class="card-col txt-right">
                        <a href="{{ route('paket.list', $jenis_paket) }}" class="btn-xs bg-primary">Kembali</a>
                     </div>
                  </div>

                  <div class="card-body">
                     <form action="{{ route('paket.update', [$jenis_paket, $ck->id]) }}" method="POST" class="form-input">
                     @csrf
                     @method('PUT')
                     <input type="hidden" name="jenis_paket" value="{{ $ck->jenis_paket }}">
                        <div class="form-grup">
                           <label for="nama_paket">Nama Paket</label>
                           <input type="text" name="nama_paket" placeholder="Nama paket" value="{{ $ck->nama_paket }}" autocomplete="off" id="nama_paket" required>
                        </div>

                        <div class="form-grup">
                           <label for="waktu_kerja">Waktu Kerja</label>
                           <input type="text" name="waktu_kerja" placeholder="Durasi Kerja" value="{{ $ck->waktu_kerja }}" autocomplete="off" id="waktu_kerja" required>
                        </div>

                        <div class="form-grup">
                           <label for="berat_minimal">Berat Min (Kg)</label>
                           <input type="text" name="berat_minimal" placeholder="Berat per-Kg" value="{{ $ck->berat_minimal }}" autocomplete="off" id="berat_minimal" required>
                        </div>

                        <div class="form-grup">
                           <label for="harga">Harga</label>
                           <input type="text" name="harga" placeholder="Harga Paket" value="{{ $ck->harga }}" autocomplete="off" id="harga" required>
                        </div>

                        <div class="form-grup ">
                           <button type="submit" class="mt-1" name="ubah">Update</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
@endsection