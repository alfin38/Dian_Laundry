@extends('layouts.app')

@section('title', 'Riwayat')

@section('content')
   <div class="riwayat" class="main-content">
      <div class="container">
         <div class="baris">
            <div class="selamat-datang">
					<div class="col-header">
						<h2 class="judul-md">Daftar Riwayat Transaksi</h2>
					</div>	
				</div>
         </div>

         <div class="baris">
            <div class="col">
               <div class="card">
                  <div class="card-title">
                     <h2>Filter Riwayat</h2>
                  </div>
                  <div class="card-body">
                     <form action="{{ route('riwayat.index') }}" method="GET" class="form-input">
                        <div class="form-grup">
                           <label for="q">Cek Pesanan (No. Order / Nama)</label>
                           <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Contoh: CK-XXXX / Nama pelanggan">
                        </div>

                        <div class="form-grup">
                           <label for="tahun">Tahun</label>
                           <input type="number" name="tahun" id="tahun" min="2000" max="2100" value="{{ request('tahun') }}">
                        </div>

                        <div class="form-grup">
                           <label for="bulan">Bulan</label>
                           <select name="bulan" id="bulan">
                              <option value="">Semua</option>
                              <option value="1" {{ request('bulan') == '1' ? 'selected' : '' }}>Januari</option>
                              <option value="2" {{ request('bulan') == '2' ? 'selected' : '' }}>Februari</option>
                              <option value="3" {{ request('bulan') == '3' ? 'selected' : '' }}>Maret</option>
                              <option value="4" {{ request('bulan') == '4' ? 'selected' : '' }}>April</option>
                              <option value="5" {{ request('bulan') == '5' ? 'selected' : '' }}>Mei</option>
                              <option value="6" {{ request('bulan') == '6' ? 'selected' : '' }}>Juni</option>
                              <option value="7" {{ request('bulan') == '7' ? 'selected' : '' }}>Juli</option>
                              <option value="8" {{ request('bulan') == '8' ? 'selected' : '' }}>Agustus</option>
                              <option value="9" {{ request('bulan') == '9' ? 'selected' : '' }}>September</option>
                              <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                              <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                              <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                           </select>
                        </div>

                        <div class="form-grup">
                           <label for="tanggal">Tanggal Masuk</label>
                           <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}">
                        </div>

                        <div class="form-footer">
                           <div class="buttons">
                              <button type="submit" class="btn-sm bg-primary">Terapkan Filter</button>
                              <a href="{{ route('riwayat.index') }}" class="btn-sm bg-transparent">Reset</a>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>

         <div class="baris">
            <div class="col">
                @include('riwayat_partials.order_ck');
            </div>
         </div>

         <div class="baris">
            <div class="col">
                @include('riwayat_partials.order_cl');
            </div>
         </div>

         <div class="baris">
            <div class="col">
                @include('riwayat_partials.order_cs');
            </div>
         </div>
      </div>
   </div>
    
@endsection
