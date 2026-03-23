<div class="card">
   <div class="card-title card-flex">
      <div class="card-col">
         <h2>Daftar Transaksi - Cuci Komplit</h2>	
      </div>
   </div>
   
   <div class="card-body">
      <div class="tabel-kontainer">
         <table class="tabel-transaksi">
            <thead>
               <tr>
                  <th class="sticky">No</th>
                  <th class="sticky">No. Order</th>
                  <th class="sticky" width="10%">Nama</th>
                  <th class="sticky">Jenis Paket</th>
                  <th class="sticky">Jumlah(Kg)</th>
                  <th class="sticky">Total</th>
                  <th class="sticky">Uang Bayar</th>
                  <th class="sticky">Kembalian</th>
                  <th class="sticky">Status</th>
                  <th class="sticky" style="text-align: center">Action</th>
               </tr>
            </thead>

            <tbody>
               @forelse ($cuci_komplit as $ck)
                  <tr>
                     <td>{{ $loop->iteration }}</td>
                     <td>{{ $ck->or_number }}</td>
                     <td style="max-width: 150px; overflow:hidden;">{{ $ck->nama_pelanggan }}</td>
                     <td>{{ $ck->paket->nama_paket }}</td>
                     <td class="cell-center">
                        @php
                           $berat = $ck->berat_order;
                        @endphp

                        {{ $berat == intval($berat) 
                           ? intval($berat) 
                           : rtrim(rtrim(number_format($berat, 2), '0'), '.') }}
                     </td>

                     <td>Rp.&nbsp;{{ number_format($ck->total, 0, ',', '.') }}</td>
                     <td>Rp.&nbsp;{{ number_format($ck->dibayarkan, 0, ',', '.') }}</td>
                     <td>Rp.&nbsp;{{ number_format($ck->kembalian, 0, ',', '.') }}</td>
                     <td><span class="success">{{ $ck->status }}</span></td>
                     <td class="align-center">
                        <a href="{{ route('order.show', ['cuci-komplit', $ck->id]) }}" class="btn btn-detail">
                           <span class="btn-icon" aria-hidden="true">
                              <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                 <path d="M1.5 12s4-7 10.5-7 10.5 7 10.5 7-4 7-10.5 7S1.5 12 1.5 12z"></path>
                                 <circle cx="12" cy="12" r="3"></circle>
                              </svg>
                           </span>
                           Detail
                        </a>
                     </td>
                  </tr>
               @empty
                  <tr>
                     <td colspan="10" class="txt-center">Data tidak tersedia</td>
                  </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
</div>
