@extends('layouts.app')

@section('title', 'Daftar Karyawan')

@section('content')
	<div id="karyawan" class="main-content">
		<div class="container">
			<div class="baris">
				<div class="selamat-datang">
					<div class="col-header">
						<h2 class="judul-md">Management Karyawan</h2>
					</div>

					<div class="col-header txt-right">
						<a href="{{ route('karyawan.create') }}" class="btn-lg bg-primary">+ Tambah Karyawan</a>
					</div>	
				</div>
			</div>

			<div class="baris">
				<div class="col">
					<div class="card">
						<div class="card-title card-flex">
							<div class="card-col">
								<h2>Daftar Karyawan</h2>	
							</div>
						</div>

						<div class="card-body">
							<div class="tabel-kontainer">
								<table class="tabel-transaksi">
									<thead>
										<tr>
											<th class="sticky">No</th>
											<th class="sticky">Nama Karyawan</th>
											<th class="sticky">Username</th>
											<th class="sticky">Email</th>
											<th class="sticky">Action</th>
										</tr>
									</thead>

									<tbody>
                                        @forelse ($karyawan as $k)
											<tr>
												<td>{{ $loop->iteration }}</td>
												<td>{{ $k->nama }}</td>
												<td>{{ $k->username }}</td>
												<td>{{ $k->email }}</td>
												<td>    
                                                    <a href="{{ route('karyawan.edit', $k->id) }}" class="btn btn-edit">Edit</a>
                                                    <form action="{{ route('karyawan.destroy', $k->id) }}" method="POST" style="display:inline-block;" data-confirm="Hapus karyawan ini?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-hapus">Hapus</button>
                                                    </form>
												</td>
											</tr>
                                        @empty 
                                            <tr>
                                                <td class="text-center" colspan="5">Tidak ada karyawan terdaftar</td>
                                            </tr>
                                        @endforelse
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    
@endsection
