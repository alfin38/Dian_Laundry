<div class="col">
   <div class="card">
      <div class="card-title card-flex">
         <div class="card-col">
            <h2>Order Cuci Satuan</h2>	
         </div>
      </div>

      <div class="card-body">
         <div class="tabel-kontainer">
            <table class="tabel-transaksi">
               <thead>
                  <tr>
                     <th class="sticky">No</th>
                     <th class="sticky">No.Order</th>
                     <th class="sticky">Tgl Order</th>
                     <th class="sticky">Nama Pelanggan</th>
                     <th class="sticky">Jenis Paket</th>
                     <th class="sticky">Waktu Kerja</th>
                     <th class="sticky">Jumlah (Pcs)</th>
                     <th class="sticky">Status</th>
                     <th class="sticky">Pembayaran</th>
                     <th class="sticky">Action</th>
                  </tr>
               </thead>

               <tbody>
                      @foreach ($cuci_satuan as $cs)
                        <tr>
                           <td>{{ $loop->iteration }}</td>
                           <td>{{ $cs->or_number }}</td>
                           <td>{{ $cs->tgl_masuk }}</td>
                           <td>{{ $cs->nama_pelanggan }}</td>
                           <td>{{ $cs->paket->jenis_paket }}</td>
                           <td>
                              {{ \Carbon\Carbon::parse($cs->tgl_masuk)
                                 ->diffInDays(\Carbon\Carbon::parse($cs->tgl_keluar)) }} Hari
                           </td>

                           <td>
                              @php
                                 $berat = $cs->berat_order;
                              @endphp

                              {{ $berat == intval($berat) 
                                 ? intval($berat) 
                                 : rtrim(rtrim(number_format($berat, 2), '0'), '.') }}
                           </td>

                           @php
                                 $dibayarkan = $cs->dibayarkan ?? 0;
                                 $isPaid = $cs->total > 0 && $dibayarkan >= $cs->total;
                                 $statusLabel = $cs->status === 'Terlambat'
                                    ? 'Terlambat'
                                    : ($cs->status === 'Siap Diambil' ? 'Siap Diambil' : 'On Progress');
                                 $statusClass = $statusLabel === 'Terlambat'
                                    ? 'badge-status--danger'
                                    : ($statusLabel === 'Siap Diambil' ? 'badge-status--success' : 'badge-status--info');
                           @endphp
                           <td class="cell-center"><span class="badge-status {{ $statusClass }}">{{ $statusLabel }}</span></td>
                           <td class="cell-center">
                              <span class="badge-status {{ $isPaid ? 'badge-status--success' : 'badge-status--warning' }}">
                                 {{ $isPaid ? 'Sudah Bayar' : 'Belum Bayar' }}
                              </span>
                           </td>
                           <td>
                              <a href="{{ route('order.show', ['cuci-satuan', $cs->id]) }}" class="btn btn-edit">
                                 <span class="btn-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                       <path d="M1.5 12s4-7 10.5-7 10.5 7 10.5 7-4 7-10.5 7S1.5 12 1.5 12z"></path>
                                       <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                 </span>
                                 Detail
                              </a>
                              @auth
                              @if (Auth::user()->isAdmin())
                              <form action="{{ route('order.destroy', $cs->id) }}" method="POST" style="display:inline-block;" data-confirm="Hapus order ini?">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="btn btn-hapus">
                                    <span class="btn-icon" aria-hidden="true">
                                       <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                          <path d="M3 6h18"></path>
                                          <path d="M8 6V4h8v2"></path>
                                          <path d="M19 6l-1 14H6L5 6"></path>
                                       </svg>
                                    </span>
                                    Hapus
                                 </button>
                              </form>
    							@endif
    							@endauth
                           </td>
                        </tr>
                      @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
