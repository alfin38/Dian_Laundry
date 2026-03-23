@extends('layouts.app')

@section('title', 'Order Baru')

@section('content')
    <div class="main-content" id="id_order">
      <div class="container">
         <div class="baris">
            <div class="col mt-2">
               <div class="card">
                  <div class="card-title card-flex">
                     <div class="card-col">
                        <h2>Tambah Order Baru</h2>	
                     </div>
                     
                     <div class="card-col txt-right">
                        <a href="{{ route('home') }}" class="btn-xs bg-primary">Kembali</a>
                     </div>
                  </div>

                  <div class="card-body mt-2">
                     <div class="col">
                        <div class="order-sub-judul txt-center">
                           <h3 class="paket-heading">Pilih Paket</h3>
                        </div>

                        <div class="container-paket paket-grid">
                           <div class="col-paket">
                              <a href="{{ route('order.create', 'cuci-komplit') }}" class="paket paket--blue">
                                 <img src="{{ asset('_assets/img/paket-komplit.svg') }}" alt="Cuci Komplit" class="paket-illustration">
                                 <h4>Cuci Komplit</h4>
                              </a>
                           </div>

                           <div class="col-paket">
                              <a href="{{ route('order.create', 'cuci-lipat') }}" class="paket paket--peach">
                                 <img src="{{ asset('_assets/img/paket-lipat.svg') }}" alt="Cuci Lipat" class="paket-illustration">
                                 <h4>Cuci Lipat</h4>
                              </a>
                           </div>

                           <div class="col-paket">
                              <a href="{{ route('order.create', 'cuci-satuan') }}" class="paket paket--mint">
                                 <img src="{{ asset('_assets/img/paket-satuan.svg') }}" alt="Cuci Satuan" class="paket-illustration">
                                 <h4>Cuci Satuan</h4>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

@endsection
